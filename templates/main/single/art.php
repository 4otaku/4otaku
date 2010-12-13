<?
	if ($url[1] == 'search') {
		?>
			<div class="shell">
		<?
	}
?>
<div class="post" id="post-<?=$item['id'];?>">
	<div class="innerwrap show_nsfw_toggler">
		<table width="100%">
			<? 
				if ($url[1] == 'search') {	
					?>
						<tr>
							<td align="left">
								<h2>
									<a href="/art/<?=$item['id'];?>">
										Изображение №<?=$item['id'];?>
									</a>
								</h2>
							</td>
							<td align="right" valign="top">
								<a href="/art/<?=$item['id'];?>">
									Комментировать
								</a>
								<?
									if ($item['comment_count']) {
										?>
											 (<?=$item['comment_count'];?>)
										<?
									}
								?>									
							</td>
						</tr>					
					<?
				}
			?>
			<tr>
				<td class="booru_main"> 
					<?
						if (!$data['feed'] && is_array($item['pool'])) {
							?>
								<div class="margin10">
								<?
									foreach ($item['pool'] as $id => $pool) {	
										?>
											<div class="mini-shell art-bar">
												Эта картинка принадлежит к группе: 
												<a href="/art/pool/<?=$id;?>/">
													<?=$pool['name'];?>
												</a>. 
												<? if ($pool['left']) { ?>
													<a href="/art/<?=$pool['left'];?>/">
														Предыдущая
													</a>. 									
												<? } ?>
												<? if ($pool['right']) { ?>
													<a href="/art/<?=$pool['right'];?>/">
														Следующая
													</a>. 									
												<? } ?>												
												<a href="/art/slideshow/pool/<?=$id;?>#1">
													Перейти в слайдшоу
												</a>. 												
											</div>
										<?
									}
								?>
								</div>
							<?	
						}
					?>
					<?
						if (!$data['feed']) {							
							if (array_key_exists('nsfw',$item['meta']['category'])) {
								if (!$sets['show']['nsfw']) $reason['nsfw'] = true;
								if (!$sets['show']['yaoi'] && array_key_exists('yaoi',$item['meta']['tag'])) $reason['yaoi'] = true;
								if (!$sets['show']['furry'] && array_key_exists('furry',$item['meta']['tag'])) $reason['furry'] = true;
								if (!$sets['show']['guro'] && array_key_exists('guro',$item['meta']['tag'])) $reason['guro'] = true;					
							}
							if (is_array($reason)) {
								?>
									<div class="art_not_showed mini-shell">
										<?
											if ($reason['nsfw']) {
												?>
													У вас отключен показ материалов 18+. 
													<a href="#" class="toggle_show_art disabled" rel="show.nsfw">
														Включить.
													</a>
													<br />
												<?
											}
											if ($reason['yaoi']) {
												?>
													У вас отключен показ картинок содержащих яой. 
													<a href="#" class="toggle_show_art disabled" rel="show.yaoi">
														Включить.
													</a>
													<br />
												<?
											}
											if ($reason['furry']) {
												?>
													У вас отключен показ картинок содержащих фурри. 
													<a href="#" class="toggle_show_art disabled" rel="show.furry">
														Включить.
													</a>
													<br />
												<?
											}
											if ($reason['guro']) {
												?>
													У вас отключен показ картинок содержащих гуро. 
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
							if ($url[1] != 'search') {							
								if ($item['resized'] && $sets['art']['resized']) {
									?>
										<div<?=(is_array($reason) ? ' class="hidden hidden_art"' : "");?>>
											<div class="clear margin20 mini-shell art-bar">
												<span>
													Изображение уменьшено. 
												</span>
												<a href="#" class="disabled booru_show_toggle" rel="<?=$item['extension'];?>">
													Показать в полном размере
												</a><?=($item['resized'] !== 1 ? ' ('.$item['resized'].')' : '');?>. 
												<a href="#" class="disabled booru_show_full_always">
													 Всегда показывать в полном размере
												</a>. 
												<a href="/art/download/<?=$item['md5'].'.'.$item['extension'];?>" target="_blank">
													 Скачать
												</a>.												
											</div>								
											<div class="booru_img image booru_translation_toggle" rel="resized">
												<img src="/images/booru/resized/<?=$item['md5'];?>.jpg">
												<?  
													if (is_array($item['translations']['resized'])) foreach ($item['translations']['resized'] as $translation) {
														?>
															<div class="art_translation<?=($sets['show']['translation'] ? '' : ' hidden');?>" title="<?=$translation['text'];?>" style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;top:<?=$translation['y1'];?>px;left:<?=$translation['x1'];?>px;"></div>
														<?
													}
												?>											
											</div>
										</div>
									<?
								} 
								else {
									?>
										<div<?=(is_array($reason) ? ' class="hidden hidden_art"' : "");?>>
											<div class="image booru_translation_toggle">						
												<img src="/images/booru/full/<?=$item['md5'].'.'.$item['extension'];?>">
												<?
													if (is_array($item['translations']['full'])) foreach ($item['translations']['full'] as $translation) {
														?>
															<div class="art_translation<?=($sets['show']['translation'] ? '' : ' hidden');?>" title="<?=$translation['text'];?>" style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;top:<?=$translation['y1'];?>px;left:<?=$translation['x1'];?>px;"></div>
														<?
													}
												?>				
											</div>
										</div>
									<?
								}
							}
							else {
								?>
									<a href="/art/<?=$item['id'];?>/">
										<img src="/images/booru/thumbs/large_<?=$item['thumb'];?>.jpg">
									</a>
								<?
							}
						}
						else {
							?>
								<a href="<?=$data['feed']['domain'];?>/art/<?=$item['id'];?>/">
									<img src="<?=$data['feed']['domain'];?>/images/booru/thumbs/large_<?=$item['thumb'];?>.jpg">
								</a>
							<?
						}
					?>					
				</td>
			 </tr>
			 <tr>
				 <td>
					<div class="wrapper">
						<p class="meta">
							<?=$item['pretty_date'];?> | 
							<?
								if (count($item['meta']['author']) > 1) {
									?>
										Опубликовали: 
									<?
								}
								else {
									?>
										Опубликовал: 
									<?
								}
								foreach ($item['meta']['author'] as $key => $meta) {
									if ($nonfirst) {
										?>
										, 
										<?
									}	else $nonfirst = true;
									?>
										<a href="<?=$data['main']['navi']['base'];?>author/<?=$key;?>/">
											<?=$meta;?>
										</a>
									<?
								}	unset($nonfirst);
							?>
							  | 
							<?
								include('templates'.SL.'main'.SL.'single'.SL.'tags.php');
							?>
							 | 
							<?
								if (count($item['meta']['category']) > 1) {
									?>
										Категории: 
									<?
								}
								else {
									?>
										Категория: 
									<?
								}
								foreach ($item['meta']['category'] as $key => $meta) {
									if ($nonfirst) {
										?>
										, 
										<?
									}	else $nonfirst = true;
									?>
										<a href="<?=$data['main']['navi']['base'];?>category/<?=$key;?>/">
											<?=$meta;?>
										</a>
									<?
								}	unset($nonfirst);
							?>
							<?
								if ($item['translator']) {
									?>
										  | 
											Переводчик: <?=$item['translator'];?>
									<?
								}
							?>							
							<?
								if ($item['source']) {
									?>
										  | 
											Источник: <?=$item['source'];?>
									<?
								}
							?>	
							 | 
							<a href="/art/download/<?=$item['md5'].'.'.$item['extension'];?>" target="_blank">
								 Скачать
							</a>
							<?								
								if ($sets['user']['rights']) {
									?>
										 | 
										<a href="/admin/revisions/art/<?=$item['id'];?>/">История версий</a>	
									<?
								}
							?>												
						</p>
						<div class="hidden translations_hideout">
							<?
								if (!$data['feed'] && $item['resized']) {
									if (is_array($item['translations']['full'])) foreach ($item['translations']['full'] as $translation) {
										?>
											<div class="art_translation" title="<?=$translation['text'];?>" style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;top:<?=$translation['y1'];?>px;left:<?=$translation['x1'];?>px;"></div>
										<?
									}
								} 
								else {
									if (is_array($item['translations']['resized'])) foreach ($item['translations']['resized'] as $translation) {
										?>
											<div class="art_translation" title="<?=$translation['text'];?>" style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;top:<?=$translation['y1'];?>px;left:<?=$translation['x1'];?>px;"></div>
										<?
									}
								}
							?>								
						</div>
					</div>
				</td>
			 </tr>		 
		</table>
	</div><!-- wrapend -->
</div>
<?
	if (!$data['feed']) {
		?>
			<table width="100%">
				<tr>
					<td>
						<select name="edit_type" id="edit_type-<?=$item['id'];?>">
							<option value="tag">Теги</option>		
							<option value="category">Категории</option>					
							<option value="art_source">Источник</option>
							<option value="art_groups">Добавить в группы</option>				
							<option value="art_translations">Переводы</option>
							<?
								if ($sets['user']['rights']) {
									?>
										<option value="art_image">Картинку</option>
										<option value="author">Автора</option>
										<option value="art_source">Имя переводчика</option>
									<?
								}
							?>
						</select> 
						<input type="submit" value="Редактировать" class="edit" rel="<?=$item['id'];?>" />
					</td>
					<?
						if ($sets['user']['rights']) {
							?>
								<td align="right">
									<div class="right">
										<form method="post" enctype="multipart/form-data">
											<input type="hidden" name="do" value="art.transfer" />
											<input type="checkbox" name="sure" />
											<input type="hidden" name="id" value="<?=$item['id'];?>" />
											<input type="submit" value="Утащить" class="submit" />
											 &nbsp; 
											<select name="where">
												<?
													foreach ($def['area'] as $area) if ($item['area'] != $area) {
														?>
															<option value="<?=$area;?>"> <?=$lang['transfer'][$area];?></option>	
														<?
													}
												?>
												<option value="deleted"> в печь</option>									
											</select>
										</form>
									</div>
								</td>
							<?
						}
					?>
				</tr>	
			</table>
			<div id="loader-<?=$item['id'];?>" class="hidden center loader"><img src="/images/ajax-loader.gif"></div>
			<div id="edit-<?=$item['id'];?>" rel="art" class="edit_field hidden"></div>
		<?
	}
?>		
<?
	if ($url[1] == 'search') {
		?>
			</div>
		<?
	}
?>
