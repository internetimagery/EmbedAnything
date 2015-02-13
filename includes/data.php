<?php

// Optional parameters
define('SOUNDCLOUD_KEY',null); // Soundcloud key
define('FACEBOOK_KEY',null); // Facebook key
define('EMBEDLY_KEY',null); // Embedly key

// Cache file
define('CACHE_DIR',__DIR__.'/cache'); // Cache directory
define('CACHE_TIME', 1 * 60 * 60 * 24 * 7); // Cache expiry time. Items older than this will be regenerated.

// Create our Cache pool
$driver = new Stash\Driver\FileSystem();
$driver->setOptions(array(
	"path"		=>	CACHE_DIR
	));
$POOL = new Stash\Pool($driver);


// Embed the URL
function EA_Embed($url){
	global $POOL;
	// Maintain the Cache
	EA_CacheMaintenance();
	// Retrieve data from the cache if it exists otherwise create it.
	$item = $POOL->getItem(md5($url));
	$data = $item->get(Stash\Invalidation::OLD);
	if($item->isMiss()){
		$item->lock();
		$data = EA_Request($url);
		$item->set($data);
	}
	return $data;
}

// Maintain the cache. Flush any extraneous items ten times the duration of a regular cache expiry.
function EA_CacheMaintenance(){
	global $POOL;
	$item = $POOL->getItem('CacheFlush');
	$last_flush = $item->get(); // Get stored date
	// If we have previously stored the value.
	if($last_flush){
		// Check if we have passed 10 times the expire time. Then flush the cache.
		if((($last_flush + (CACHE_TIME*10)) - time()) < 0){ $POOL->flush(); }
	// If the value hasn't been stored yet.
	} else {
		$item->lock();
		$item->set(time());
	}
}

// Clean out the Cache. Maintenance function.
function EA_EmptyCache(){
	global $POOL;
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

// Get embed information from URL
function EA_Request($url){

// Set some basic options to get the best information.
$config = array(
    'adapter' => array(
        'config' => array(
        	'minImageHeight'	=> 300,
        	'minImageWidth'		=> 300,
            'getBiggerImage' 	=> true,
            'getBiggerIcon' 	=> true,
            'facebookKey' 		=> FACEBOOK_KEY,
            'soundcloudKey' 	=> SOUNDCLOUD_KEY,
        )
    ),
    'providers' => array(
    	'oembed' => array(
    		'embedlyKey' => EMBEDLY_KEY
    	)
    )
);
$info = Embed\Embed::create($url, $config);
$data = array();
if($info){
	$data['title'] = $info->title; //The page title
	$data['descrption'] = $info->description; //The page description
	$data['url'] = $info->url; //The canonical url
	$data['type'] = $info->type; //The page type (link, video, image, rich)

	$data['images'] = $info->images; //List of all images found in the page
	$data['image'] = $info->image; //The image choosen as main image
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
	$data['providerIcon'] = $info->providerIcon; //The icon choosen as main icon

	$data['content'] = EA_Readability($info->request->getContent(), $url); // The content as read by Readability
	return $data;
}
return false;
}