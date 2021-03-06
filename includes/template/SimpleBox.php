<!-- SimpleBox Embed -->
<div class="embed-wrapper" <?php echo 'style="'; echo isset($options['width'])?'width:'.$options['width'].'px;"':'"'; ?>>
	<div class="embed-media-wrapper <?php echo $data['type']=='video'?'flex-video ':''; echo $data['type'].' '.strtolower($data['providerName']); ?>" >
	<?php switch($data['type']){
			case 'audio':
				// What do I do with audio? :s
				break;
			case 'rich':
			case 'video':
				echo $data['code']?$data['code']:($data['image']?$data['image']:$data['thumb']);
				break;
			case 'photo':
			case 'image':
				'<a src="'.$data['url'].'">'.$data['image'].'</a>';
				break;
			case 'link':
				echo '<a href="'.$data['url'].'">'.($data['image']? $data['image']: $data['thumb']).'</a>';
			default:
				break;
			} ?>
	</div><?php if(!isset($options['meta']) || (isset($options['meta'])&&$options['meta'])){ ?>
	<div class="embed-meta panel">
		<span class="embed-icon">
			<?php echo $data['providerIcon']; ?>
		</span>
		<span class="embed-link">
			<a href="<?php echo $data['url']; ?>" title="<?php echo $data['title']; ?>"><?php echo $data['title']; ?></a>
		</span>
	</div><?php } ?>
</div>
