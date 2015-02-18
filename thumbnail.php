<?php
// Function to deal with thunbnail things...
require_once(__DIR__."/vendor/autoload.php");
require_once('includes/data.php');

switch(true){
	// Return Data
	case isset($_GET['data']):
		$item = $POOL->getItem($_GET['data']);
		if($data = $item->get()){
			echo $data['raw_data_do_not_use_in_template'];
		} else {
			echo "<h1>ERROR: No Data Found.</h1>";
		}
		break;
	// Set new Data
	case isset($_POST['data']):
		echo "thunb!";
		break;
}