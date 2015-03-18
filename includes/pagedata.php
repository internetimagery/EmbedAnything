<?php
##############################################
# Form to submit
##############################################
function EAP_Submit($url){
	ob_start();
?>
<div class="row">
	<div class="small-12 large-8 columns small-centered">
		<div class="section_light">
<p><i class="fa fa-clipboard"></i> <strong>Paste the link to the reference in the box below</strong> to begin adding a Reference (a link to another websites article / video etc). In the form that is to follow, please check over each section. Make changes if you need to and fill in the remaining fields.<br/>
Don't forget to be liberal with categories.</p>
<p><a href="<?php echo BASE_URL; ?>/wiki/Manual_reference"><i class="fa fa-info-circle"></i>
 If you have problems, you can put in the information manually.</a></p>
		</div>
	</div>
</div><br>
<div class="row">
	<div class="small-12 large-10 columns small-centered">
		<form name="Link" class="middle" method="post" action="<?php echo $url ?>">
		<div class="row collapse">
			<div class="small-2 columns">
				<span class="prefix"><i class="fa fa-files-o"></i> <i class="fa fa-hand-o-right"></i>
</i>
</span>
			</div>
			<div class="small-8 large-8 columns">
				<input name="URL" class="input" type="url" placeholder="PASTE URL HERE" value="" />
			</div>
			<div class="small-2 columns">
				<input type="submit" class="rss button postfix" value="Reference" />
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="small-12 large-8 columns small-centered">
		<div class="panel">Please <strong>do not</strong> submit here:
 <ul>
 <li>Links to search results. Instead go to the actual item in the result.</li>
 <li>Links to top 10 pages. Instead, link to the ten separate items directly.</li>
 <li>Links to files or images directly. If you do have an awesome video you wish to share. Upload to youtube/vimeo and link to that. If you have an awesome image to share, upload to imgur.</li>
 <li>Links to scripts or plugins etc. Those <strong>ARE</strong> great! <a href="<?php echo BASE_URL.'/wiki/Form:Tool'; ?>">There is another place for that!</a> :)</li>
 </ul>
		</div>
	</div>
</div><?php
return ob_get_clean();
}

##############################################
# WARNINGS
##############################################
function EAP_Warning($error=''){
	ob_start();
?>
<div class="row">
<div class="small-8 columns small-centered">
<div id="Alert" data-alert class="alert-box alert" tabindex="0" aria-live="assertive" role="dialogalert">
<i class="fa fa-exclamation-triangle"></i>
<?php 	if($error){ echo $error; }
		else{ echo 'Something is wrong with your URL. Check that it works and goes to a <strong>site</strong> or <strong>single article</strong>.<br>(search result pages for example are not single articles)'; } ?>
<button href="#" tabindex="0" class="close" aria-label="Close Alert">&times;</button></div>
</div></div><?php
	return ob_get_clean();
}

##############################################
# REDIRECTION
##############################################
function EAP_Redirect($url, $data){
	ob_start();
?>
<div class="row">
	<div class="small-6 columns small-centered">
		<table class="table">
		<?php foreach( $data as $key => $val){
			$val = ( ($key == 'Reference[archive]') && $val ? str_word_count($val).' words.' : $val );
			echo "<tr><td>$key</td><td>$val</td></tr>"; } ?>
		</table>
	</div>
</div>
<div class="row">
	<div class="small-12">
		<form name="test" method="post" action="<?php echo $url; ?>"><?php foreach( $data as $name => $val ){
echo "<input name='$name' type='hidden' value='$val'>";
}?>
			<div class="row">
				<div class="large-8 small-12 small-centered columns text-center">
					<div class="section_dark">
						<i class="fa fa-plane"></i><br/>Data gathered...<br>Moving to page creation or edit
					</div>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="large-3 small-12 columns small-centered">
					<input type="submit" class="button success columns" value="Continue" />
				</div>
			</div>
		</form>
	</div>
</div><?php
	return ob_get_clean();
}

##############################################
# AUTOMATICALLY SUBMIT THE FORM
##############################################
function EAP_AutoSubmit(){
	ob_start();
?>
<SCRIPT LANGUAGE="JavaScript"><!--
	setTimeout('document.test.submit()',300);
//--></SCRIPT><?php
	return ob_get_clean();
}