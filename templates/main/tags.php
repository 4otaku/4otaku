<div id="tag_bar">
	<?
		if (!is_array($data['main']['tags'])) {
			?>
				Пока слишком мало тегов для облака.
			<?
		}
		else foreach ($data['main']['tags'] as $name => $tag) {
			?>
				<a href="/<?=$url[2];?>/<?=($url[3] == $def['area'][2] ? $def['area'][2].'/': '');?>tag/<?=$tag['alias'];?>/" 
				title="<?=$tag['count'];?> <?=$tag['word'];?>" style="font-size: <?=$tag['size'];?>pt;">
					<?=$name;?>
				</a> 
			<?
		}
	?>
</div>
