<?php

// Optional parameters
define('SOUNDCLOUD',null); // Soundcloud key
define('FACEBOOK',null); // Facebook key
define('EMBEDLY',null); // Embedly key

// Cache file
define('CACHE',__DIR__.'/cache'); // Cache directory

$driver = new Stash\Driver\FileSystem();
$driver->setOptions(array(
	"path"		=>	CACHE
	));
$POOL = new Stash\Pool($driver);


// Embed the URL
function EA_Embed($url){

// Create Cache pool




	EA_Request($url);
}

function EA_EmptyCache(){
	global $POOL;
	$POOL->flush();
}

// Get embed information from URL
function EA_Request($url){

// Set some basic options to get the best information.
$config = array(
    'adapter' => array(
        'config' => array(
            'getBiggerImage' => true,
            'getBiggerIcon' => true,
            'facebookKey' => FACEBOOK,
            'soundcloudKey' => SOUNDCLOUD,
        )
    ),
    'providers' => array(
    	'oembed' => array(
    		'embedlyKey' => EMBEDLY
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
	return $data;
}
return false;
}