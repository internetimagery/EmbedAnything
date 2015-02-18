<?php

// Optional parameters
define('EA_SOUNDCLOUD_KEY',null); // Soundcloud key
define('EA_FACEBOOK_KEY',null); // Facebook key
define('EA_EMBEDLY_KEY',null); // Embedly key

// Default Images and Icons if missing
function EA_default_images(){
	global $wgExtensionAssetsPath;
	$img['img'] = "$wgExtensionAssetsPath/EmbedAnything/includes/missing_image.png";
	$img['ico'] = "$wgExtensionAssetsPath/EmbedAnything/includes/missing_icon.png";
	return $img;
}

// Cache file
define('EA_CACHE_DIR',__DIR__.'/cache'); // Cache directory
define('EA_CACHE_TIME', 1 * 60 * 60 * 24 * 7 * 2); // Cache expiry time. Items older than this will be regenerated.

// Create our Cache pool
$driver = new Stash\Driver\FileSystem();
$driver->setOptions(array(
	"path"		=>	EA_CACHE_DIR
	));
$POOL = new Stash\Pool($driver);

function EA_formKey($key){
	$key = substr($key, -1) == '/'? substr($key,0,-1) : $key;
	return md5($key);
}

// Embed the URL
function EA_Embed($url){
	global $POOL;
//	$url = substr($url, -1) == '/'? substr($url, 0, -1) : $url;
	// Maintain the Cache
	EA_CacheMaintenance();
	// Retrieve data from the cache if it exists otherwise create it.
	$item = $POOL->getItem(EA_formKey($url));
	$data = $item->get(Stash\Invalidation::OLD);
	if($item->isMiss()){
		$item->lock();
		$data = EA_Request($url);
		$item->set($data, EA_CACHE_TIME);
	}
	return $data;
}

// Maintain the cache. Flush any extraneous items five times the duration of a regular cache expiry.
function EA_CacheMaintenance(){
	global $POOL;
	$item = $POOL->getItem('CacheFlush');
	$last_flush = $item->get(); // Get stored date
	// If we have previously stored the value.
	if($last_flush){
		// Check if we have passed 5 times the expire time. Then flush the cache.
		if((($last_flush + (EA_CACHE_TIME*5)) - time()) < 0){ EA_EmptyCache(); }
	// If the value hasn't been stored yet.
	} else {
		$item->lock();
		$item->set(time());
	}
}

// Clean out the Cache. Maintenance function.
function EA_EmptyCache(){
	global $POOL;
	$img_path = realpath(__DIR__.'/images');
	// Clean out image cache
	if(is_readable($img_path)){
//		unlink($img_path);
	}
	$POOL->flush();
}

// Basic HTML cleaning for output... if you want to do that... yaknow
function EA_PurifyHtml($html){
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	return $purifier->purify($html);
}

// Grab visible content from the page. ie: if a blog, grab the blog text.
function EA_Readability($html, $url){
	// If we've got Tidy, let's clean up input.
	if (function_exists('tidy_parse_string')) {
	    $tidy = tidy_parse_string($html, array(), 'UTF8');
	    $tidy->cleanRepair();
	    $html = $tidy->value;
	}
	// Create Readability
	$readability = new Readability($html, $url);
	if($readability->init()){
		return EA_PurifyHtml($readability->getContent()->innerHTML);}
	return '';
}

function EA_FormImage($url, $class, $extra=''){
	return "<img src='$url' class='$class' $extra />";
}

// Get embed information from URL
function EA_Request($url){
	// Set some basic options to get the best information.
	$config = array(
	    'adapter' => array(
	        'config' => array(
	        	'minImageHeight'	=> 250,
	        	'minImageWidth'		=> 250,
	            'getBiggerImage' 	=> true,
	            'getBiggerIcon' 	=> true,
	            'facebookKey' 		=> EA_FACEBOOK_KEY,
	            'soundcloudKey' 	=> EA_SOUNDCLOUD_KEY,
	        )
	    ),
	    'providers' => array(
	    	'oembed' => array(
	    	    'parameters' => array(),
	    		'embedlyKey' => EA_EMBEDLY_KEY
	    	)
	    )
	);
	$info = Embed\Embed::create($url, $config);
	$data = array();
	if($info){
		$default = EA_default_images();
		$data['title'] = $info->title; //The page title
		$data['description'] = $info->description; //The page description
		$data['url'] = $info->url; //The canonical url
		$data['type'] = $info->type; //The page type (link, video, image, rich)

		$data['images'] = $info->images; //List of all images found in the page
		$data['image'] = $info->image?EA_FormImage($info->image,'main-image'):''; //The image choosen
		$data['defaultImage'] = EA_FormImage($default['img'], 'main-image'); // Image to use if nothing else
		$data['imageWidth'] = $info->imageWidth; //The width of the main image
		$data['imageHeight'] = $info->imageHeight; //The height of the main image

		$data['code'] = $info->code; //The code to embed the image, video, etc
		$data['width'] = $info->width; //The width of the embed code
		$data['height'] = $info->height; //The height of the embed code
		$data['aspectRatio'] = $info->aspectRatio; //The aspect ratio (width/height)

		$data['authorName'] = $info->authorName; //The (video/article/image/whatever) author
		$data['authorUrl'] = $info->authorUrl; //The author url

		$data['providerName'] = $info->providerName; //The provider name of the page (youtube, twitter, instagram, etc)
		$data['providerUrl'] = $info->providerUrl; //The provider url
		$data['providerIcons'] = $info->providerIcons; //All provider icons found in the page
		$data['providerIcon'] =
			EA_FormImage(
				($info->providerIcon?$info->providerIcon:$default['ico']),
				'icon',
				'height="20px" width="20px"'
				); //The icon choosen as main icon

		$data['content'] = EA_Readability($info->request->getContent(), $url); // The content as read by Readability
		$data['raw_data_do_not_use_in_template'] = array(); // Data for working.
		$data['raw_data_do_not_use_in_template']['html'] = EA_LocalizePage($info->url, $info->request->getContent()); // Raw HTML prepped for thumbnail

		global $wgExtensionAssetsPath;
		$thumb_url = $wgExtensionAssetsPath.'/EmbedAnything/thumbnail.php?data='.urlencode($url);
		$data['thumb'] = EA_FormImage($thumb_url, 'web-thumb', 'onerror="EA_LoadThumb(this);"');
// Code to generate thumbnail.
		return $data;
	}
	return false;
}