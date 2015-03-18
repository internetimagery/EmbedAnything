<?php // Class for Special Page (proxy)
require_once(__DIR__."/vendor/autoload.php");
require_once(__DIR__.'/includes/data.php');
require_once(__DIR__.'/includes/localizePage.php');

class SpecialEmbedAnything extends SpecialPage {
	function __construct() {
		parent::__construct( "EmbedAnything" );
	}

	# Decide what to do.
	function execute( $par ) {
		global $wgOut;
		$title = $wgOut->getTitle();
		$request = ucfirst(basename($title->mUrlform));
		$output = $this->getOutput();
###		$wgOut->disable();
		switch ($request) {
		    // Main page
###		    // Loading Gif
###		    case 'Loading':
###		    	header('Content-Type: image/gif');
###		    	print file_get_contents(__DIR__."/includes/loading.gif");
###		    	break;
###		    // Load Proxy
###		    case 'Proxy':
###		    	require_once(__DIR__."/includes/html2canvasproxy.php");
###		    	break;
###		    case 'Flush':
###		    	EA_EmptyCache();
###		    	print "Cache flushed. :D";
###		    	break;
###		    // Loading Image
###		    case 'Image':
###		    	if ($data = $this->quickData()) {
###		    		if(
###		    		isset($data['raw_data_do_not_use_in_template']) &&
###		    		($output = $data['raw_data_do_not_use_in_template']) &&
###		    		isset($output['thumb'])
###		    			){
###		        		header('Content-Type: image/png');
###		        		echo $output['thumb'];
###		        		break;
###		    		}
###		    	}
###		    	print "This isn't an image!";
###		    	break;
###		    // Loading Page
###		    case 'Html':
###		    	if ($data = $this->quickData()) {
###		    		if($output = $data['raw_data_do_not_use_in_template']){
###		    			header("Access-Control-Allow-Origin: *");
###		        		header('Content-Type: text/html');
###		        		echo $output['html'];
###		    		}
###		    	}
###		    	break;
###		    // Uploading Thumbnail
###		    case 'Insert':
###		    	if(
###		    	isset($_POST['data'])	&&
###		    	isset($_POST['url'])	&&
###		    	($img = $_POST['data']?urldecode($_POST['data']):'') &&
###		    	($url = $_POST['url'])	&&
###		    	($data = $this->quickData())){
###		    		// TODO add check to ensure upload is really an image.
###		    		// Prep image
###		    		$img = str_replace('data:image/png;base64,', '', $img);
###		    		$img = base64_decode($img);
###
###		    		// Save image to cache
###		    		global $POOL;
###		        	$item = $POOL->getItem(EA_formKey($url));
###		        	$item->lock();
###		        	$data['raw_data_do_not_use_in_template']['thumb'] = $img;
###		        	$data['raw_data_do_not_use_in_template']['ip'] = $_SERVER['REMOTE_ADDR'];
###		        	$item->set($data, EA_CACHE_TIME);
###		        	echo "Thumb Uploaded.";
###		        }
###		        break;
###		    case 'test':
###		    	break;
			case 'Test':
				ob_start();
				print_r($_POST);
				$out = ob_get_clean();
				$wgOut->addHTML("<pre>$out</pre>");
				$output->addWikiText( "POST DATA ABOVE?" );
				break;
			case 'Submit':
				include_once(__DIR__."/includes/pagedata.php");
				if(isset($_POST['URL']) && $_POST['URL']){
					#test URL
					if( $request = $this->Resolve($_POST['URL']) ){
						#Do we allow the url?
						if(!$err = $this->Blacklist($request)){
							$pieces = parse_url($request);
							$home = $this->Resolve( $pieces['scheme']."://".$pieces['host'] ); #resolve homepage
							if($data = EA_Request($request)){
								# Choose. Homepage or Regular Url?
								if( $request == $home){
									print("Homepage");
								} else {
									print("Normal Url");
								}
							} else {
								$output->addHTML( EAP_Warning("
									We're having trouble getting data from your link.<br>
									You might need to add it manually.") );
							}
						} else {
							$output->addHTML( EAP_Warning($err));
						}
					} else {
						$output->addHTML( EAP_Warning("
							The Link is invalid. Can you load it in your browser?<br/>
							Is it a normal page?") );
					}
					#
					#$em_url = $this->URLAdd($request);
					#$em_result = $this->URLTest($em_url); #Test url works
					#if( !$err = $this->TestStatus($em_result['http_code']) ){
					#
					#
					#
					#
					#	$output->addWikiText( "redirecting?" );
					#	$url = Title::newFromText( "Special:EmbedAnything" )->getLocalUrl("");
					#	#$wgOut->redirect(Title::newFromText( "Special:EmbedAnything" )->getLocalUrl(""));
					#	$wgOut->setPageTitle("Content Found");
					#	$output->addHTML( EAP_Redirect($url, array("one"=>"one","two"=>"two","three"=>"three")) );
					#	break;

					#$output->addHTML( $err ? EAP_Warning($err) : "");
				} else {
		    		$output->addHTML( EAP_Warning() );
		    	}
		    # Default Page
		    case 'EmbedAnything':
		    default:
		    	include_once(__DIR__."/includes/pagedata.php");
		    	$wgOut->setPageTitle("Submit your link");
		    	$wgOut->addHTML(EAP_Submit( $this->getTitle()->getFullURL()."/Submit" ));
		    	$output->addWikiText( "" );
			}
		}
	##############################################
	# RESOLVE URL
	##############################################
	function Resolve($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$a = curl_exec($ch);
		if(
			(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) &&
			(strpos(curl_getinfo($ch, CURLINFO_CONTENT_TYPE), "text/html") !== false)
			){
				return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		}
		return false;
	}
	##############################################
	# CHECK BLACKLIST URL STUFF
	##############################################
	function Blacklist($url){
		$parts = parse_url($url);
		# Check for search queries
		if (isset($parts['query'])){
			if(substr_count($parts['query'], 'search')){
				return '
				Your link contains a search query. Please link to the page itself and not a search result. :)';
			}
		}
		# websites we don't want showing up
		$blacklist = array(
			"animationelevation.com"
			);
		foreach( $blacklist as $check ){
			if ($check == $parts['host'] ){
				return
				'The URL you tried is blocked. Sorry. :(';
			}
		}
		return false;
	}
	##############################################
	# Remove invalid characters that would mess with mediawiki
	##############################################
	function Sanitize($text){
		if($text){
    		$disallow = array('{','}','|','#','[',']','<','>','/','~~~');
    		$text = str_replace($disallow, '', $text); // Get rid of any bad values that interfere with template
    		return htmlentities($text, ENT_QUOTES);} // Remove quotes and other items from text
    	return;
	}

###	function quickData(){
###		if (isset($_GET['data']) && $_GET['data']) {
###			$url = urldecode($_GET['data']);
###			return EA_Embed($url);
###		}
###		return false;
###	}
###	function getInformation( $url ){
###		return "information";
###	}
###	function setInformation( $url, $data){
###		return "set";
###	}
}
