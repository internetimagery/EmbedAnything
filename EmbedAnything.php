<?php
// Customize your secret key here!
$NoEmbedKey1 = "Here is a secret key!";
$NoEmbedKey2 = "This too is a key. Just in case...";


// Parser to allow easy embedding of media thanks to noembed.com (great site by the way!)
// Usage:  {{#embed: url here }}
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not here...' );
}


$wgExtensionCredits['parserhook'][] = array(
   'path' => __FILE__,
   'name' => 'NoEmbed',
   'description' => 'A generic embedding function. Utilizing noembed.com',
   'version' => 1,
   'author' => 'Jason Dixon',
   'url' => 'http://www.mediawiki.org/wiki/Extenson:noembed',

);

// Register i18n.
$wgExtensionMessagesFiles['NoEmbed'] = __DIR__ . '/NoEmbed.i18n.php';

// Register hooks.
$wgHooks['ParserFirstCallInit'][] = 'NoEmbedSetup'; #grab text from parser
$wgHooks['ParserAfterTidy'][] = 'NoEmbedDecode'; # put it onto the page

// Scramble keys.
$NoEmbedKey1 = md5($NoEmbedKey1);
$NoEmbedKey2 = md5($NoEmbedKey2);

// Register Parser.
function NoEmbedSetup( &$parser ) {
   $parser->setFunctionHook( 'embed', 'NoEmbedURL' );
   return true;
}

// Encode the html.
function NoEmbedEncode( $html ){
	global $NoEmbedKey1, $NoEmbedKey2;
	$encode = base64_encode($html);
	return "$NoEmbedKey1$encode$NoEmbedKey2";
}

// Decode the html.
function NoEmbedDecode( &$parser, &$text ){
	global $NoEmbedKey1, $NoEmbedKey2;
	$text = preg_replace( "/$NoEmbedKey1([0-9a-zA-Z\\+].*?)$NoEmbedKey2/esm", 'base64_decode("$1")', $text );
	return true;
}

// Get embed information.
function NoEmbedURL( $parser, $url ) {
	$encodeMarkers = array();
	$noEmbed = "http://noembed.com/embed?url="; #Url for noembed service

	$result = json_decode(file_get_contents("$noEmbed$url"),TRUE);
	if($result && isset($result['html'])){
		return NoEmbedEncode($result['html']);
	} else {
		return "
<div class='noembed-embed '>
	<div class='noembed-wrapper'>
		<div class='noembed-embed-inner noembed-link'>
			<img src='".__DIR__.'/noimage-placeholder.png'."' />
		</div>
		<table class='noembed-meta-info'>
			<tr>
				<td class='favicon'></td>
				<td>Link</td>
				<td align='right'>
					<a href='$url'>$url</a>
				</td>
			</tr>
		</table>
	</div>
</div>";
	}
}

// Check compatability (todo: get this working)
function NoEmbedCheck( $url ){
	$reg = json_decode(file_get_contents("http://noembed.com/providers"),true);
	if($reg){
		foreach( $reg as $patterns){
			foreach( $patterns['patterns'] as $pattern){
				print $pattern;
				if(preg_match("/$pattern/i", $url)){
					return true;
				}
			}
		}
	}
	return false;
}
