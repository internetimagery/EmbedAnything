<?php
// Easily Embed anything into the page.
require_once(__DIR__."/vendor/vendor/autoload.php");

// Embed functionality.
require_once(__DIR__.'/includes/data.php');
require_once(__DIR__."/includes/formHTML.php");

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

// Set up hooks
function EA_Setup( Parser $parser ) {
	$parser->setHook( 'embed', 'EA_Tag' );
	return true;
}

// Import HTML with tags
function EA_Tag( $input, array $args, Parser $parser, PPFrame $frame ) {
	$url = '';
	if(isset($args['url'])){ $url = count(parse_url($args['url']))>1?$args['url']:''; unset($args['url']); }
	$url = count(parse_url($input)) > 1? $input : $url;
	$html = 'WARNING: Not a valid URL.';
	if($url){
		$html = EA_GetTemplate($url, $args);
	}
	return array( $html, 'noparse' => true, 'isHTML' => true );
}
