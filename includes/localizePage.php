<?php
//require_once(__DIR__.'/phpUri.php');
function EA_LocalizePage($url, $html, $debug=false){
	// enable user error handling
	libxml_use_internal_errors(true);
	$page = new DOMDocument();
	$page->loadHTML($html);
	if($page){
		print_r( $debug ? libxml_get_errors() : '' );
		libxml_clear_errors();
		// Check we have a HEAD section
		$head = $page->getElementsByTagName("head");
		if($head->length){
			// Check if we already have a base tag. If we do, we don't need to do anything...
			$head = $head->item(0);
			if(!$head->getElementsByTagName("base")->length){
				// Add a base tag
				$base = $page->createElement('base');
				$base->setAttribute("href",$url);
				$head->insertBefore( $base, $head->firstChild );
			}
			$imgs = $page->getElementsByTagName("img");
			// Convert image urls to absolute urls
			if($imgs->length){
				$url = substr($url, -1) == '/' ? substr($url, 0, -1) : $url;
				foreach($imgs as $img){
					$img_url = $img->getAttribute("src");
				//	$img->setAttribute("src", phpUri::parse($url)->join($img_url));
				}
			}
			// Nullify scripts
			$trash = array();
			$scripts = $page->getElementsByTagName("script");
			if($scripts->length){
				foreach($scripts as $script){
					$trash[] = $script;
				}
				foreach($trash as $del){
					$del->parentNode->removeChild($del);
				}
			}
			// Wipe out links. This is not supposed to be a proxy!
			$links = $page->getElementsByTagName("a");
			if($links->length){
				foreach($links as $link){
					$link->setAttribute("href", "#");
				}
			}
			return $page->saveHTML();
		}
	}
	return false;
}
