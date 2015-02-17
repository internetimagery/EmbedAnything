<?php
// Template of sorts for thumbnail generation.
function EA_genThumbnail($url){
	$url = EA_formKey($url);
	$frame = uniqid('frame');
	$thumb = uniqid('thumb');
	ob_start();
?>
<div style="overflow:hidden;position:relative;">
	<iframe
		name="<?php echo $frame; ?>"
		onload="<?php echo "EA_thumbnail(window.$frame, '$thumb');"; ?>"
		src="http://localhost/wik/extensions/EmbedAnything/thumbnail.php?url=<?php echo $url; ?>"
		style="
			position: absolute;
			top: 0px;
			left: 4000px;
			width: 1200px;
			height: 800px;
			"
		>
	</iframe>
</div>
<div id="<?php echo $thumb; ?>"></div><?php
	$output = ob_get_clean();
	return $output;
}