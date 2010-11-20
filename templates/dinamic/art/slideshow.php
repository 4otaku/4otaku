<?	
	$i = $get['id'];
	foreach ($data as $art)
		if ($art['resized'] && $sets['art']['resized']) {
			?>
				<div class="image" id="art-<?=$i++;?>" style="display: none;">
					<img src="/images/booru/resized/<?=$art['md5'];?>.jpg" rel="<?=$art['height'];?>">
			
					<?
						if (is_array($art['translations']['resized'])) foreach ($art['translations']['resized'] as $translation) {
							?>
								<div class="art_translation" title="<?=$translation['text'];?>" 
									style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;left:<?=$translation['x1'];?>px;top:<?=$translation['y1'];?>px;" 
									rel="<?=$translation['x2'];?>:<?=$translation['y2'];?>:<?=$translation['x1'];?>:<?=$translation['y1'];?>">
								</div>
							<?
						}
					?>											
				</div>
			<?
		}
		else {
			?>
				<div class="image" id="art-<?=$i++;?>" style="display: none;">
					<img src="/images/booru/full/<?=$art['md5'].'.'.$art['extension'];?>" rel="<?=$art['height'];?>">
					<?
						if (is_array($art['translations']['full'])) foreach ($art['translations']['full'] as $translation) {
							?>
								<div class="art_translation" title="<?=$translation['text'];?>" 
									style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;left:<?=$translation['x1'];?>px;top:<?=$translation['y1'];?>px;" 
									rel="<?=$translation['x2'];?>:<?=$translation['y2'];?>:<?=$translation['x1'];?>:<?=$translation['y1'];?>">
								</div>
							<?
						}
					?>											
				</div>	
			<?				
		}
