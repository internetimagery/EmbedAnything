<!-- SimpleBox Embed -->
<div class="embed-wrapper">
	<div class="embed-media-wrapper <?php echo $data['type']; ?>">
	<?php switch($data['type']){
			case 'audio':
				// What do I do with audio? :s
				break;
			case 'rich':
			case 'video':
				echo $data['code']?$data['code']:'<img src="'.$data['image'].'" />';
				break;
			case 'photo':
			case 'image':
				'<img src="'.$data['image'].'" />';
				break;
			case 'link':
				echo $data['image']==EA_DEFAULT_IMG?'<span class="embed-snippet">'.$data['description'].'</span>':'<img src="'.$data['image'].'" />';
			default:
				break;
			} ?>
	</div>
	<div class="embed-link-wrapper">
		<img class="embed-icon" src="<?php echo $data['providerIcon']; ?>" />
		<span class="embed-link">
			<a href="<?php echo $data['url']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title']; ?></a>
		</span>
	</div>
</div>
