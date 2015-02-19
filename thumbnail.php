<?php
// Function to deal with thunbnail things...
require_once(__DIR__."/vendor/autoload.php");
require_once(__DIR__.'/includes/data.php');
require_once(__DIR__.'/includes/localizePage.php');

if(isset($_GET['data']) && $_GET['data']){
	$url = urldecode($_GET['data']);
	$data = EA_Embed($url);
	// Ensure we have information
	if($data){
		// Are we sending data or requesting it?
		if(isset($_POST['data'])){
			if($img = $_POST['data']?urldecode($_POST['data']):''){
				// TODO add check to ensure upload is really an image.
				// Prep image
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = base64_decode($img);

				// Save image to cache
				$item = $POOL->getItem(EA_formKey($url));
				$item->lock();
				$data['raw_data_do_not_use_in_template']['thumb'] = $img;
				$data['raw_data_do_not_use_in_template']['ip'] = $_SERVER['REMOTE_ADDR'];
				$item->set($data, EA_CACHE_TIME);
				echo "Thumb Uploaded.";
			}
		} else {
			if($output = $data['raw_data_do_not_use_in_template']){
				if(isset($_GET['html'])){
					header("Access-Control-Allow-Origin: *");
					header('Content-Type: text/html');
					echo $output['html'];
				} elseif(isset($output['thumb']) && $output['thumb']) {
					header('Content-Type: image/png');
					echo $output['thumb'];
				}
			}
		}
	} else {
		echo "<h1>ERROR: No Data Found.</h1>";
	}
}