<?php
// Easily Embed anything into the page.


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
//$wgHooks['ParserAfterTidy'][] = 'NoEmbedDecode'; # put it onto the page

// Set up hooks
function EA_Setup( Parser $parser ) {
	$parser->setHook( 'embed', 'EA_Tag' );
	return true;
}

// Import HTML with tags
function EA_Tag( $input, array $args, Parser $parser, PPFrame $frame ) {
	$url = EA_CheckURL($input)? $input :( isset($args['url']) && EA_CheckURL($args['urls'])? $args['urls'] :'' );
	if($url){
		echo "SUCCESS: ".$url;
	}
	$html = '';
	return '';
return array(
	$html,
	'noparse' => true,
	'isHTML' => true
	);
}

function EA_CheckURL($url){
	return count(parse_url($url)) > 1? $url : false;
}