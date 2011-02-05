<div class="booru_images">
	<?  
		if (is_array($data['main']['art']['thumbs'])) foreach ($data['main']['art']['thumbs'] as $key => $picture) {
			?>
			<div class="thumbnail <?=($sets['art']['largethumbs'] ? 'large_thumbnail' : 'small_thumbnail');?> show_nsfw_toggler" rel="<?=$picture['id'];?>">
				<?
					unset ($reason);
					if (array_key_exists('nsfw',$picture['meta']['category'])) {
						if (!$sets['show']['nsfw']) $reason['nsfw'] = true;
						if (!$sets['show']['yaoi'] && array_key_exists('yaoi',$picture['meta']['tag'])) $reason['yaoi'] = true;
						if (!$sets['show']['furry'] && array_key_exists('furry',$picture['meta']['tag'])) $reason['furry'] = true;
						if (!$sets['show']['guro'] && array_key_exists('guro',$picture['meta']['tag'])) $reason['guro'] = true;					
					}
					if (is_array($reason)) {
						?>
							<div class="art_not_showed">
								<?
									if ($reason['nsfw']) {
										?>
											18+ отключено. 
											<a href="#" class="toggle_show_art disabled" rel="show.nsfw">
												Включить.
											</a>
											<br />
										<?
									}
									if ($reason['yaoi']) {
										?>
											Яой отключен.
											<a href="#" class="toggle_show_art disabled" rel="show.yaoi">
												Включить.
											</a>
											<br />
										<?
									}
									if ($reason['furry']) {
										?>
											Фурри отключено.
											<a href="#" class="toggle_show_art disabled" rel="show.furry">
												Включить.
											</a>
											<br />
										<?
									}
									if ($reason['guro']) {
										?>
											Гуро отключено. 
											<a href="#" class="toggle_show_art disabled" rel="show.guro">
												Включить.
											</a>
											<br />
										<?
									}
									if (count($reason) > 1) {
										?>
											<a href="#" class="toggle_show_art disabled" rel="show.<?=implode(',show.',array_keys($reason));?>">
												Включить все.
											</a>
											<br />
										<?
									}								
								?>
								<br /><a href="#" class="show_art disabled">Показать эту картинку.</a>
							</div>
						<?
					}
						?>
							<a href="<?=$def['site']['dir']?>/art/<?=($sets['art']['download_mode'] ? 'download/'.$picture['md5'].'.'.$picture['extension'] : $picture['id']);?>" rel="<?=$picture['id'];?>" class="with_help3<?=(is_array($reason) ? " hidden hidden_art" : "");?>" title="
									<?
										if (count($picture['meta']['tag']) > 1) {
											?>
												Теги: 
											<?
										}
										else {
											?>
												Тег: 
											<?
										}
										if (is_array($picture['meta']['tag'])) {
											foreach ($picture['meta']['tag'] as &$tag) $tag = $tag['name'];
											echo implode(', ',$picture['meta']['tag']);
										}
									?>
									  | 
									<?	
										if (count($picture['meta']['author']) > 1) {
											?>
												Опубликовали: 
											<?
										}
										else {
											?>
												Опубликовал: 
											<?
										}
										echo implode(', ',$picture['meta']['author']);
									?>
									  | 
									<?
										if (count($picture['meta']['category']) > 1) {
											?>
												Категории: 
											<?
										}
										else {
											?>
												Категория: 
											<?
										}
										echo implode(', ',$picture['meta']['category']);
									?>
							"<?=($sets['art']['blank_mode'] ? ' target="_blank"' : '');?>>
								<?=(!empty($picture['similar']) ? '<img class="similar_sign" src="/images/plus_'.min(5,count($picture['similar'])).'.png">' : '');?>
								<img src="<?=$def['site']['dir']?>/images/booru/thumbs/<?=($sets['art']['largethumbs'] ? 'large_' : '');?><?=$picture['thumb'];?>.jpg">
							</a>					
						<?
				
				?>
			</div>
			<? 
		} 
	?>
</div>
