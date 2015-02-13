<?php
// Grab embed data and form some nice HTML with it

// Default image when nothing can be found.
define('DEFAULT_IMG' , null);
define('DEFAULT_ICON' , null);

// Generate a simple box without descriptions or whatnot.
function EA_SimpleBox($url){
	$data = EA_Embed($url);
	// Check we have something
	// Sort data out. Primary = main image / video whatever. Url = url. Icon = icon etc.
	echo '<pre>'.$data['image']."\n";
	print_r($data['images']);
	if($data){
		$html['title']	= $data['title']? $data['title'] : ''; // Title of the media
		$html['link']	= $data['url']; // URL
		$html['icon'] = $data['providerIcon']? EA_IMG($data['providerIcon']) : EA_IMG(DEFAULT_ICON);
		echo $html['icon'];
		switch($data['type']){
			case 'audio':
				echo 'audio';
				break;
			case 'image':
			case 'photo':
				$html['primary'] = $data['image']? EA_IMG($data['image']) : EA_IMG(DEFAULT_IMG);
				break;
			case 'video':
			case 'rich':
				$html['primary'] = $data['code']? $data['code'] :
					($data['images']? EA_IMG($data['images'][0]) : EA_IMG(DEFAULT_IMG) );
				break;
			case 'link':
				$html['primary'] = $data['image']? EA_IMG($data['image']) : EA_IMG(DEFAULT_IMG);
				break;
			default:
				echo "couldn't figure it out...";
				echo $data['type'];
		}
	// Or else if we don't have anything...
	} else {
		echo "ERROR: Please check your URL works correctly: $url";
	}
}

function EA_IMG($url){
	return "<img src='$url' />";
}


$html = '
<div class="flex-video">
<iframe width="420" height="315" src="//www.youtube.com/embed/aiBt44rrslw" frameborder="0" allowfullscreen></iframe>
</div>';