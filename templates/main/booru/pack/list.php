<div class="booru_images">
	<?  
		if (is_array($data['main']['pools'])) foreach ($data['main']['pools'] as $key => $gallery) {
			?>
			<div class="thumbnail <?=($sets['art']['largethumbs'] ? 'large_extractor_thumbnail' : 'small_extractor_thumbnail');?>">
				<a href="<?=$def['site']['dir']?>/art/cg_packs/<?=$key;?>/" class="cg_name">
					<?=$gallery['title'];?>
				</a>
				<br /><br />
				<a href="<?=$def['site']['dir']?>/art/cg_packs/<?=$key;?>/" title="<?=($gallery['text'] ? trim(ltrim(strip_tags(preg_replace('/(<br[^>]*>\s*)+/s','; ',$gallery['text'])),';')) : $gallery['name']);?>" class="with_help3">
					<img src="/images/booru/thumbs/<?=($sets['art']['largethumbs'] ? 'large_' : '');?><?=$gallery['cover'];?>.jpg">
				</a>					
			</div>
			<? 
		} 
	?>
</div>
