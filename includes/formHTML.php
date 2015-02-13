<?php
// Get a template to display the embed.

// Set the default template. The file name without php
define('EA_DEFAULT_TEMPLATE', 'SimpleBox');
define('EA_TEMPLATE_DIR', __DIR__.'/template');

// Register our Templates
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/template'));
$EA_TEMPLATES = array();
while($it->valid()){
    if (!$it->isDot()){
    	if(substr($it->getSubPathName(), -4) == '.php'){
	    	$EA_TEMPLATES[] = substr($it->getSubPathName(), 0, -4); }
    }
    $it->next();
}

// Grab the template provided.
function EA_GetTemplate($url, $options){
	global $EA_TEMPLATES;
	if($EA_TEMPLATES){
		$data = EA_Embed($url);
		// Data types: Audio, Link, Video, Photo, Image, Rich
		if($data){
			if(isset($options['style'])){ $options['template'] = $options['style']; unset($options['style']);}
			$options['template'] = isset($options['template'])? $options['template'] : EA_DEFAULT_TEMPLATE;
			$theme = in_array($options['template'],$EA_TEMPLATES)?$options['template']:$EA_TEMPLATES[0];
			ob_start();
			include(EA_TEMPLATE_DIR."/$theme.php");
			return ob_get_clean();
		// Or else if we don't have anything...
		} return "<a href='$url'>$url</a>";
	} return "WARNING: No templates can be found!";
}