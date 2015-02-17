<?php
// Easily Embed anything into the page.
require_once(__DIR__."/vendor/autoload.php");

// Embed functionality.
require_once(__DIR__.'/includes/data.php');
require_once(__DIR__."/includes/formHTML.php");
require_once(__DIR__."/includes/localizePage.php");
require_once(__DIR__."/includes/thumbTemplate.php");


$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'EmbedAnything',
	'description' => 'Embed anything onto a page easily.',
	'version' => 1.0,
	'author' => 'Jason Dixon',
	'url' => 'https://github.com/internetimagery/EmbedAnything',
);

// Register i18n.
$wgExtensionMessagesFiles['EmbedAnything'] = __DIR__ . '/EmbedAnything.i18n.php';

// Register hooks.
$wgHooks['ParserFirstCallInit'][] = 'EA_Setup'; #grab text from parser
$wgHooks['BeforePageDisplay'][] = 'EA_load_javascript';

// Load extra Javascript
$wgResourceModules['ext.embedanything'] = array(
	'scripts'	=> array(
		'includes/html2canvas.js',
		'includes/screengrab.js'
	),
	'localBasePath'	=> dirname( __FILE__ ),
	'remoteExtPath'	=> basename( dirname( __FILE__ ) ),
	'position'		=> 'top'
);

// Load up our Javascript.
function EA_load_javascript( &$out, $skin = false){
	$out->addModules( 'ext.embedanything' );
	return true;
}

// Set up hook
function EA_Setup( Parser $parser ) {
	$parser->setHook( 'embed', 'EA_Tag' );
	return true;
}

// Tags. Use <embed>url</embed> or {{#tag:embed|url}}
function EA_Tag( $input, array $args, Parser $parser, PPFrame $frame ) {
	$url_check = "/^\[?\s*(https?:\/\/[^\s\]\|<>]+).*$/";
	preg_match($url_check, $input, $formed_input);
	$url = isset($formed_input[1])? $formed_input[1] : '';
	if(isset($args['url'])){
		preg_match($url_check, $input, $formed_url);
		$url = isset($formed_url[1])? $formed_url[1] : '';
	}
	if($url){
		return array( EA_GetTemplate($url, $args), 'noparse' => true, 'isHTML' => true );
	}
	return "'''WARNING:''' Not a valid URL.";
}
