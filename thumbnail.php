<?php
// Function to deal with thunbnail things...
require_once(__DIR__."/vendor/autoload.php");
require_once(__DIR__.'/includes/data.php');

if(isset($_GET['data'])){
	$item = $POOL->getItem($_GET['data']);
	// Ensure we have information
	if($data = $item->get()){
		// Are we sending data or requesting it?
		if(isset($_POST['data'])){
			if($img = $_POST['data']?urldecode($_POST['data']):''){
				// TODO add check to ensure upload is really an image.
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = base64_decode($img);

				$item->lock();
				$data['raw_data_do_not_use_in_template']['thumb'] = $img;
				$item->set($data, EA_CACHE_TIME);
				echo "Thumb Uploaded.";
			}
		} else {
			if($output = $data['raw_data_do_not_use_in_template']){
				if(isset($_GET['html'])){
					header('Content-Type: text/html');
					echo $output['html'];
				} else {
					header('Content-Type: image/png');
					echo $output['thumb'];
				}
			}
		}
	} else {
		echo "<h1>ERROR: No Data Found.</h1>";
	}
}