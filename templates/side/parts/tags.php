<div class="cats">	
	<h2>
		<a href="<?=$def['site']['dir']?>/tags/<?=$url[1];?>/<?=($url['area'] == $def['area'][2] ? $url['area'] : '');?>">
			Частые теги
		</a>
		 <a href="#" class="bar_arrow" rel="tag">
			<?
				if ($sets['dir']['tag']) {
					?>
						<img src="<?=$def['site']['dir']?>/images/text2391.png">
					<?
				}
				else {
					?>
						<img src="<?=$def['site']['dir']?>/images/text2387.png">
					<?				
				}
			?>
		</a>
	</h2>
	<div id="tag_bar"<?=($sets['dir']['tag'] ? '' : ' style="display:none;"');?>>
		<?
			if (!is_array($data['sidebar']['tags'])) {
				?>
					Пока слишком мало тегов для облака.
				<?
			}
			else foreach ($data['sidebar']['tags'] as $name => $tag) {
				?>
					<a href="<?=($url['area'] == $def['area'][0] || $url['area'] == $def['area'][1] ? '/'.$url[1] : '/'.$url[1].'/'.$url['area']);?>/tag/<?=$tag['alias'];?>/" title="<?=$tag['count'];?> <?=$tag['word'];?>" style="font-size: <?=$tag['size'];?>pt;"><?=$name;?></a> 
				<?
			}
		?> 
	</div>
</div>
