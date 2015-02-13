<?php
// Grab embed data and form some nice HTML with it

// Generate a simple box without descriptions or whatnot.
function EA_SimpleBox($url){
	$data = EA_Embed($url);
	// Check we have something
	// Sort data out. Primary = main image / video whatever. Url = url. Icon = icon etc.
	if($data){
		$html['title']	= $data['title']; // Title of the media
		$html['link']	= $data['url']; // URL
		$html['icon'] = $data['providerIcon'];
		switch($data['type']){
			case 'audio':
				echo 'audio';
				break;
			case 'image':
			case 'photo':
				$html['primary'] = EA_IMG($data['image']);
				break;
			case 'video':
			case 'rich':
				$html['primary'] = $data['code']? $data['code'] : EA_IMG($data['image']);
				break;
			case 'link':
				$html['primary'] = $data['image']? EA_IMG($data['image']) : $data['description'];
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

// Put URL in image tags
function EA_IMG($url){
	return "<img src='$url' />";
}
