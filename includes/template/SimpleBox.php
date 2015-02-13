<!-- SimpleBox Embed -->
<div class="embed-wrapper">
	<div class="flex-video embed-item <?php echo $data['type']; ?>">
	<?php switch($data['type']){
			case 'audio':
				break;
			case 'rich':
			case 'video':
				break;
			case 'photo':
			case 'image':
				break;
			case 'link':
			default:
				break;
			} ?>
	</div>
	<div class="embed-text">
		<img class="embed-icon" src="<?php echo $data['providerIcon']; ?>" />
		<span class="embed-title">
			<a href="<?php echo $data['url']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title']; ?></a>
		</span>
	</div>
</div>
