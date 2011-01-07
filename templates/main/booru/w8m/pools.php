<div class="booru_images">
	<?  
		if (is_array($data['main']['pools'])) foreach ($data['main']['pools'] as $key => $gallery) {
			?>
			<div class="thumbnail <?=($sets['art']['largethumbs'] ? 'large_extractor_thumbnail' : 'small_extractor_thumbnail');?>">
				<a href="<?=$def['site']['dir']?>/art/cg_packs/<?=$key;?>/" class="cg_name">
					<?=$gallery['name'];?>
				</a>
				<br /><br />
				<a href="<?=$def['site']['dir']?>/art/cg_packs/<?=$key;?>/" title="<?=($gallery['text'] ? strip_tags(str_replace('<br />','; ',$gallery['text'])) : $gallery['name']);?>" class="with_help3">
					<img src="http://w8m.4otaku.ru/image/<?=$gallery['md5'];?>/<?=($sets['art']['largethumbs'] ? 'large' : 'thumb');?>/<?=pathinfo($gallery['image'],PATHINFO_FILENAME);?>.jpg">
				</a>					
			</div>
			<? 
		} 
	?>
</div>
