<?php
// Function to deal with thunbnail things...
require_once(__DIR__."/vendor/autoload.php");
require_once('includes/data.php');
require_once('includes/localizePage.php');

switch(true){
	case isset($_GET['url']):
		$item = $POOL->getItem($_GET['url']);
		if($data = $item->get()){
			echo $data['raw_html'];
		} else {
			echo "<h1>ERROR: No Data Found.</h1>";
		}
		break;
	case isset($_GET['thumb']):
		echo "thunb!";
		break;
}