<?php
// Function to deal with thunbnail things...
require_once(__DIR__."/vendor/autoload.php");
require_once('includes/data.php');
require_once('includes/localizePage.php');

switch(true){
	case isset($_GET['url']):
		echo "url!";
		break;
	case isset($_GET['thumb']):
		echo "thunb!";
		break;
}