<!-- SimpleBox Embed -->
<div class="embed-wrapper" <?php echo 'style="'; echo isset($options['width'])?'width:'.$options['width'].'px;"':'"'; ?>>
	<div class="embed-media-wrapper <?php echo $data['type']=='video'?'flex-video ':''; echo $data['type'].' '.strtolower($data['providerName']); ?>" >
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
				'<a src="'.$data['url'].'"><img style="margin:auto;" src="'.$data['image'].'" /></a>';
				break;
			case 'link':
				echo $data['image']==EA_DEFAULT_IMG?'<span class="embed-snippet">'.$data['description'].'</span>':'<a src="'.$data['url'].'"><img style="margin:auto;" src="'.$data['image'].'" /></a>';
			default:
				break;
			} ?>
	</div><?php if(!isset($options['meta']) || (isset($options['meta'])&&$options['meta'])){ ?>
	<div class="embed-meta panel">
		<span class="embed-icon">
			<img src="<?php echo $data['providerIcon']; ?>" height=30px width=30px/>
		</span>
		<span class="embed-link">
			<a href="<?php echo $data['url']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title']; ?></a>
		</span>
	</div><?php } ?>
</div>
