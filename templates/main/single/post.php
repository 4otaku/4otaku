<div class="shell">
	<div class="post" id="post-<?=$item['id'];?>">
		<div class="innerwrap">
			<table width="100%">
				<tr>
					<td align="left">
						<h2>
							<a href="<?=$data['feed']['domain'];?>/post/<?=$item['id'];?>" title="<?=$item['title'];?>">
								<?=$item['title'];?>
							</a>
						</h2>
					</td>
					<td align="right" valign="top">
						<? if ($data['main']['display'] && in_array('comments',$data['main']['display'])) {
							if ($item['update_count']) { ?>
								<a href="#" class="disabled show_updates" rel="<?=$item['id'];?>">
									Показать обновления
								</a> 
								(<?=$item['update_count'];?>)
							<? }
						} else { ?>
							<a href="<?=$data['feed']['domain'];?>/post/<?=$item['id'];?>">
								Комментировать
							</a>
							<? if ($item['comment_count']) { ?>
								 (<?=$item['comment_count'];?>)
							<? } ?>	
						<? } ?>
					</td>
				</tr>
			</table>
			<table cellspacing="0" cellpadding="0" width="100%">
				<? if(
					!$sets['show']['nsfw'] && 
					!$data['feed']['domain'] && 
					array_key_exists('nsfw',$item['meta']['category'])
				) { ?>
					<tr>
						<td align="center">
							У вас отключен показ материалов для взрослых. 
							<a href="#" class="disabled show_nsfw">
								Показать эту запись.
							</a> 
							<a href="#" class="disabled always_show_nsfw">
								Всегда показывать такие материалы.
							</a>										
						</td>
					</tr>
					<tr class="hidden">
				<? } else { ?>
					<tr>							
				<? } ?>
					<? if (is_array($item['image'])) { ?>
						<td class="imageholder"<?=($data['feed'] ? ' valign="top"' : '');?>>
							<? foreach ($item['image'] as $key => $image) { ?>
								<div class="image-<?=$key;?>"<?=($data['feed'] ? ($key ? ' style="margin: 10px 10px 0 0;"' : ' style="margin: 0 10px 0 0;"') : '');?>>
									<a href="<?=$data['feed']['domain'];?>/images/full/<?=$image;?>" target="_blank">
										<img src="<?=$data['feed']['domain'];?>/images/thumbs/<?=preg_replace('/\.[a-z]+$/ui', '.jpg', $image);?>" />
									</a>
								</div>
							<? } ?>
						</td>
					<? } ?>						
					<td valign="top">
						<div class="posttext">
							<?=$item['text'];?>
						</div>
						<? if (is_array($item['links'])) { ?>
							<p>
								<? foreach ($item['links'] as $key => $link) { 
									if ($nonfirst) { ?>
										<br />
									<? } else {
										$nonfirst = true;
									} ?>
									<?=$link['name'];?>: 
									<? if (is_array($link['url'])) {
										foreach ($link['url'] as $key2 => $linkurl) {
											if ($nonfirst2) { ?>
												 | 
											<? } else {
												$nonfirst2 = true;
											} ?>
											<a href="<?=($data['feed']['domain'] ? 
												$linkurl : 
												str_replace('&apos;', "'", html_entity_decode($linkurl,ENT_QUOTES,'UTF-8')));?>"
											 target="_blank">
												<?=$link['alias'][$key2];?>
											</a>
											
										<? }
										unset($nonfirst2); ?>
										 (~<?=$link['size'];?> <?=$link['sizetype'];?>)
										<?
									}
									unset($nonfirst);
								?>
							</p>
						<? } ?>							
						<? if (is_array($item['files'])) { ?>
							<p class="post-files">
								<? foreach ($item['files'] as $key => $file) { 
									if ($nonfirst) {
										?>
											<br />
									<? } else {
										$nonfirst = true;
									}
									if ($file['type'] == 'plain') { ?>
										<img src="<?=$data['feed']['domain'];?>/images/file.png" class="post-image"> 
										<?=$file['name'];?>: 
										<a href="<?=$data['feed']['domain'];?>/files/post/<?=$file['folder'];?>/<?=$file['filename'];?>">
											<?=$file['filename'];?>
										</a>
										 (<?=$file['size'];?>)												
									<? } elseif ($file['type'] == 'image') { ?>
										<img src="<?=$data['feed']['domain'];?>/images/file-image.png" class="post-image"> 
										<?=$file['name'];?>: 
										<a href="<?=$data['feed']['domain'];?>/files/post/<?=$file['folder'];?>/<?=$file['filename'];?>" class="imageholder" target="_blank">
											<?=$file['filename'];?>
											<span rel="<?=$file['height'];?>">
												<img class="hiddenthumb" src="#" rel="/files/post/<?=$file['folder'];?>/thumb_<?=$file['filename'];?>" />
											</span>	
										</a>
										 (<?=$file['size'];?>)
									<? } elseif ($file['type'] == 'audio') { ?>
										<img src="<?=$data['feed']['domain'];?>/images/file-audio.png" class="post-image"> 
										<span><?=$file['name'];?>: </span>
											<br />
											<span class="player_margin">				
											<object 
												type="application/x-shockwave-flash" 
												align="bottom" 
												data="<?=$data['feed']['domain'];?>/jss/musicplayer/player_mp3_maxi.swf" 
												width="380" 
												height="16" 
											>
												<param name="movie" value="<?=$data['feed']['domain'];?>/jss/musicplayer/player_mp3_maxi.swf" />
												<param name="bgcolor" value="#ffffff" />
												<param name="FlashVars" value="mp3=<?=urlencode($data['feed']['domain']);?>/files/post/<?=$file['folder'];?>/<?=urlencode($file['filename']);?>&amp;width=380&amp;height=16&amp;showstop=1&amp;showvolume=1&amp;buttonwidth=20&amp;sliderwidth=15&amp;volumewidth=40" />
											</object>
										</span>
									<? }
								} unset($nonfirst); ?>
							</p>
						<? } ?>		
						<?  
							if (is_array($item['info'])) {
								?>
									<p>
										<?
											foreach ($item['info'] as $key => $link) if (trim($link['name'])) { 
												if ($nonfirst) {
													?>
														<br />
													<?													
												}	else $nonfirst = true;
												?>
													<?=$link['name'];?>: 
													<a href="<?=($data['feed']['domain'] ? $link['url'] : '/go?'.urlencode(str_replace('&apos;',"'",html_entity_decode($link['url'],ENT_QUOTES,'UTF-8'))));?>" target="_blank">
														<?=$link['alias'];?>
													</a>.
												<?
											}	unset($nonfirst);
										?>
									</p>
								<?
							}
						?>
					</td>
				</tr>
			</table>
			<?
				if (!$data['feed']) {
					?>
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
											<?
												if (!is_numeric($url[2]) && $url[1] != 'search') {
													?>										
														<a href="<?=$output->mixed_add($key,'author');?>">
															+
														</a> 
														<a href="<?=$output->mixed_add($key,'author','-');?>">
															-
														</a> 
													<?
												}
											?>																						
											<a href="<?=$data['main']['navi']['base'];?>author/<?=$key;?>/">
												<?=$meta;?>
											</a>
										<?
									}	unset($nonfirst);
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
											<?
												if (!is_numeric($url[2]) && $url[1] != 'search') {
													?>										
														<a href="<?=$output->mixed_add($key,'category');?>">
															+
														</a> 
														<a href="<?=$output->mixed_add($key,'category','-');?>">
															-
														</a> 
													<?
												}
											?>													
											<a href="<?=$data['main']['navi']['base'];?>category/<?=$key;?>/">
												<?=$meta;?>
											</a>
										<?
									}	unset($nonfirst);
								?>
								 | 
								<?
									if (count($item['meta']['language']) > 1) {
										?>
											Языки: 
										<?
									}
									else {
										?>
											Язык: 
										<?
									}
									foreach ($item['meta']['language'] as $key => $meta) {
										if ($nonfirst) {
											?>
											, 
											<?
										}	else $nonfirst = true;
										?>
											<?
												if (!is_numeric($url[2]) && $url[1] != 'search') {
													?>										
														<a href="<?=$output->mixed_add($key,'language');?>">
															+
														</a> 
														<a href="<?=$output->mixed_add($key,'language','-');?>">
															-
														</a> 
													<?
												}
											?>
											<a href="<?=$data['main']['navi']['base'];?>language/<?=$key;?>/">
												<?=$meta;?>
											</a>
										<?
									}	unset($nonfirst);
								?>
								 | 
								<?
									include('templates'.SL.'main'.SL.'single'.SL.'tags.php');
									if ($sets['user']['rights']) {
										?>
											 | 
											<a href="<?=$def['site']['dir']?>/admin/revisions/post/<?=$item['id'];?>/">История версий</a>	
										<?
									}
								?>									
							</p>
						</div>
					<?
				}
			?>
		</div><!-- wrapend -->
	</div>
	<? 
		if (!$data['feed'] && $item['updates_count']) { 
			?>
				<div class="center hidden" id="updates_field_loader">
					<img src="<?=$def['site']['dir']?>/images/ajax-loader.gif">
				</div>
				<div id="updates_field" class="hidden">
					&nbsp;
				</div>
			<?
		}
	?>
	<? 
		if (!$data['feed'] && ($sets['user']['rights'] || $url['area'] == $def['area'][1])) {
			?>
				<table width="100%">
					<tr>
						<td>
							<select name="edit_type" id="edit_type-<?=$item['id'];?>">
								<option value="title">Заголовок</option>
								<option value="post_images">Изображения</option>
								<option value="text">Текст</option>
								<option value="post_links">Ссылки</option>
								<option value="post_bonus_links">Дополнительные ссылки</option>
								<option value="post_files">Прикрепленные файлы</option>
								<?
									if ($sets['user']['rights']) {
										?>
											<option value="author">Автора</option>	
										<?
									}
								?>
								<option value="category">Категории</option>
								<option value="language">Языки</option>
								<option value="tag">Теги</option>
							</select> 
							<input type="submit" value="Редактировать" class="edit" rel="<?=$item['id'];?>" />
							<?
								if ($sets['user']['rights']) {
									?>
										<td align="right">
											<div class="right">
												<form method="post" enctype="multipart/form-data">
													<input type="hidden" name="do" value="post.transfer" />
													<input type="checkbox" name="sure" />
													<input type="hidden" name="id" value="<?=$item['id'];?>" />
													<input type="submit" value="Утащить" class="submit" />
													 &nbsp; 
													<select name="where">
														<?
															foreach ($def['area'] as $area) if ($item['area'] != $area && !empty($lang['transfer'][$area])) {
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
						</td>	
					</tr>	
				</table>
				<div id="loader-<?=$item['id'];?>" class="hidden center loader"><img src="<?=$def['site']['dir']?>/images/ajax-loader.gif"></div>
				<div id="edit-<?=$item['id'];?>" rel="post" class="edit_field hidden"></div>
			<?
		} 
		if ($data['main']['display'] && in_array('comments',$data['main']['display'])) {
			?>
				<? if ($sets['user']['rights'] && $item['area'] == $def['area'][0]) { ?>
					<div class="addborder">
						<div id="downscroller" rel="update"> 
							<div>
								<a href="#bugfix" class="disabled">
									Добавить обновление
								</a>
								<span class="arrow"> ↓</span> 
							</div>
							<div id="add_loader">
								<img src="<?=$def['site']['dir']?>/images/ajax-loader.gif">
							</div>
							<div id="add_form" rel="<?=$item['id'];?>">
								&nbsp;
							</div>
						</div>
					</div>					
				<? } ?>
				<div id="updates_field_loader" class="hidden center loader"><img src="<?=$def['site']['dir']?>/images/ajax-loader.gif"></div>
				<div id="updates_field" class="hidden"></div>				
			<?
		}
	?>
</div>
