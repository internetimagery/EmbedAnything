<?php
// Simple Box template

function SimpleBox($data){
	ob_start(); ?>
<div class="embed-wrapper">
	<div class="flex-video embed-item <?php echo $data['type']; ?>">
	<?php echo $data['primary']; ?>
	</div>
	<div class="embed-text">
		<img class="embed-icon" src="<?php echo $data['icon']; ?>" />
		<span class="embed-title">
			<a href="<?php echo $data['link']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title']; ?></a>
		</span>
	</div>
</div>
<?php
	return ob_get_clean();
}

