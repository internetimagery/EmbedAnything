<?php // Class for Special Page (proxy)
require_once(__DIR__."/vendor/autoload.php");
require_once(__DIR__.'/includes/data.php');
require_once(__DIR__.'/includes/localizePage.php');

class SpecialEmbedAnything extends SpecialPage {
	function __construct() {
		parent::__construct( "EmbedAnything" );
	}

	function execute( $par ) {
		global $wgOut;
		$wgOut->disable();
		$title = $wgOut->getTitle();
		$request = basename($title->mUrlform);
		if ($request) {
			$wgOut->disable();
			switch ($request) {
				// Main page
				case 'EmbedAnything':
					print "This is embedanything!!";
					break;
				// Loading Gif
				case 'loading':
					header('Content-Type: image/gif');
					print file_get_contents(__DIR__."/includes/loading.gif");
					break;
				// Load Proxy
				case 'proxy':
					require_once(__DIR__."/includes/html2canvasproxy.php");
					break;
				case 'flush':
					EA_EmptyCache();
					print "Cache flushed. :D";
					break;
				// Loading Image
				case 'image':
					if ($data = $this->quickData()) {
						if($output = $data['raw_data_do_not_use_in_template']){
			        		header('Content-Type: image/png');
			        		echo $output['thumb'];
			        		break;
						}
					}
					print "This isn't an image!";
					break;
				// Loading Page
				case 'html':
					if ($data = $this->quickData()) {
						if($output = $data['raw_data_do_not_use_in_template']){
							header("Access-Control-Allow-Origin: *");
			        		header('Content-Type: text/html');
			        		echo $output['html'];
						}
					}
					break;
				// Uploading Thumbnail
				case 'insert':
					if(
						isset($_POST['data'])	&&
						isset($_POST['url'])	&&
					 	($img = $_POST['data']?urldecode($_POST['data']):'') &&
					 	($url = $_POST['url'])){
						global $POOL;
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
					// Adding data here!
					break;
				default:
					print "The address is invalid.";
					break;
			}
		} else {
			$output = $this->getOutput();
			$output->addWikiText( "DEFAULT TEXT ON THE PAGE. INCLUDE INSTRUCTIONS FOR EMBED STUFF" );
		}
	}
	function quickData(){
		if (isset($_GET['data']) && $_GET['data']) {
			$url = urldecode($_GET['data']);
			return EA_Embed($url);
		}
		return false;
	}
	function getInformation( $url ){
		return "information";
	}
	function setInformation( $url, $data){
		return "set";
	}
}
