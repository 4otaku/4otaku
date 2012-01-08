<? if ($url[1] == 'search') { ?>
	<div class="shell">
<? } ?>
<div class="post" id="post-<?=$item['id'];?>">
	<div class="innerwrap show_nsfw_toggler">
		<table width="100%">
			<? if ($url[1] == 'search') { ?>
				<tr>
					<td align="left">
						<h2>
							<a href="<?=$def['site']['dir']?>/art/<?=$item['id'];?>">
								Изображение №<?=$item['id'];?>
							</a>
						</h2>
					</td>
					<td align="right" valign="top">
						<a href="<?=$def['site']['dir']?>/art/<?=$item['id'];?>">
							Комментировать
						</a>
						<? if ($item['comment_count']) { ?>
							 (<?=$item['comment_count'];?>)
						<? } ?>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td class="booru_main">
					<? if (!$data['feed'] && is_array($item['pool'])) {	?>
						<div class="margin10">
						<? foreach ($item['pool'] as $pool) { ?>
							<div class="mini-shell art-bar">
								Эта картинка принадлежит группе:
								 <a href="<?=$def['site']['dir']?>/art/pool/<?=$pool['id'];?>/">
									<?=$pool['name'];?>
								</a>.
								<? if ($pool['left']) { ?>
									 <a href="<?=$def['site']['dir']?>/art/<?=$pool['left'];?>/">
										Предыдущая
									</a>.
								<? } ?>
								<? if ($pool['right']) { ?>
									 <a href="<?=$def['site']['dir']?>/art/<?=$pool['right'];?>/">
										Следующая
									</a>.
								<? } ?>
								 <a href="<?=$def['site']['dir']?>/art/slideshow/pool/<?=$pool['id'];?>#1">
									Перейти в слайдшоу
								</a>.
							</div>
						<? } ?>
						</div>
					<? } ?>
					<? if (!$data['feed'] && !empty($item['packs'])) { ?>
						<div class="margin10">
						<? foreach ($item['packs'] as $pack) { ?>
							<div class="mini-shell art-bar">
								Вы просматриваете CG из игры "<a href="<?=$def['site']['dir']?>/art/cg_packs/<?=$pack['id'];?>/"><?=$pack['title'];?></a>".
								<? if ($pack['weight'] > 0) { ?>
									 <a href="<?=$def['site']['dir']?>/art/download/pack/<?=$pack['id'];?>/" target="_blank">Скачать их одним архивом.</a> (~<?=ceil($pack['weight']/1024/1024);?> мб)
								<? } ?><br />
								 Имя файла: <a href="<?=$def['site']['dir']?>/art/download/pack/<?=$pack['id'];?>/<?=$pack['art_id'];?>" target="_blank"><?=$pack['filename'];?></a>
							</div>
						<? } ?>
						</div>
					<? } ?>
					<? if (!$data['feed'] && !empty($item['similar'])) { ?>
						<div class="margin10">
							<div class="mini-shell art-bar">
								На эту картинку есть вариации.
								 <a href="/art/<?=$item['id'];?>#1" class="similar_navi similar_navi_1">
									1
									<span class="hidden variant_link">
										<?=$item['md5'].'.'.$item['extension'];?>
									</span>
									<? if ($item['resized']) { ?>
										<span class="hidden variant_resized_link">
											<?=$item['md5'].'.'.($item['animated'] ? 'gif' : 'jpg');?>
										</span>
										<span class="hidden variant_resized_info">
											 (<?=$item['resized'];?>)
										</span>
									<? } ?>
									<span rel="-35">
										<img class="hiddenthumb" src="#" rel="/images/booru/thumbs/<?=$item['thumb'];?>.jpg" />
									</span>
								</a>
								<? foreach ($item['similar'] as $number => $similar) { ?>
									,
									 <a href="/art/<?=$item['id'];?>#<?=($number+2);?>" class="similar_navi similar_navi_<?=($number+2);?>">
										<?=($number+2);?>
										<span class="hidden variant_link">
											<?=$similar['md5'].'.'.$similar['extension'];?>
										</span>
										<? if (!empty($similar['resized'])) { ?>
											<span class="hidden variant_resized_link">
												<?=$similar['md5'].'.'.($similar['animated'] ? 'gif' : 'jpg');?>
											</span>
											<span class="hidden variant_resized_info">
												 (<?=$similar['resized'];?>)
											</span>
										<? } ?>
										<span rel="-35">
											<img class="hiddenthumb" src="#" rel="/images/booru/thumbs/<?=$similar['thumb'];?>.jpg" />
										</span>
									</a>
								<? } ?>
								.
								<img src="/images/loading_variation.gif" class="loading_variation" />
							</div>
						</div>
					<? } ?>
					<? if (!$data['feed']) {
						$reason = array();
						if (is_array($item['meta']['tag'])) {

							foreach ($sets['show'] as $tag => $show) {
								$type = ($tag == 'nsfw') ? 'category' : 'tag';

								if ($show === 0 && array_key_exists($tag, $item['meta'][$type])) {
									$reason[$tag] = true;
								}
							}
						}
						if (!empty($reason)) { ?>
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
						<? }
						if ($url[1] != 'search') { ?>
							<div<?=(!empty($reason) ? ' class="hidden hidden_art"' : "");?>>

								<div class="clear margin20 mini-shell art-bar resize-bar<?=(!$item['resized'] || !$sets['art']['resized'] ? ' hidden' : '');?>">
									<a href="#" class="disabled <?=($item['animated'] ? 'animated ' : '');?>booru_show_toggle" rel="<?=$item['extension'];?>">
										Показать в полном размере
									</a><span class="resize-info"><?=($item['resized'] !== 1 ? ' ('.$item['resized'].')' : '');?></span>.
									 <a href="#" class="disabled booru_show_full_always">
										 Всегда показывать в полном размере
									</a>.
									 <a href="<?=$def['site']['dir']?>/art/download/<?=$item['md5'].'.'.$item['extension'];?>" target="_blank">
										 Скачать
									</a>.
								</div>

								<? if (!$item['resized'] || !$sets['art']['resized']) { ?>
									<div class="booru_img image booru_translation_toggle" rel="full">
										<img src="<?=$def['site']['dir']?>/images/booru/full/<?=$item['md5'].'.'.$item['extension'];?>">
										<?
											if (is_array($item['translations']['full'])) foreach ($item['translations']['full'] as $translation) {
												?>
													<div class="art_translation<?=($sets['show']['translation'] ? '' : ' hidden');?>" title="<?=$translation['text'];?>" style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;top:<?=$translation['y1'];?>px;left:<?=$translation['x1'];?>px;"></div>
												<?
											}
										?>
									</div>
								<? } else { ?>
									<div class="booru_img image booru_translation_toggle" rel="resized">
										<img src="<?=$def['site']['dir']?>/images/booru/resized/<?=$item['md5'];?>.<?=($item['animated'] ? 'gif' : 'jpg');?>">
										<?
											if (is_array($item['translations']['resized'])) foreach ($item['translations']['resized'] as $translation) {
												?>
													<div class="art_translation<?=($sets['show']['translation'] ? '' : ' hidden');?>" title="<?=$translation['text'];?>" style="width:<?=$translation['x2'];?>px;height:<?=$translation['y2'];?>px;top:<?=$translation['y1'];?>px;left:<?=$translation['x1'];?>px;"></div>
												<?
											}
										?>
									</div>
								<? } ?>
							</div>
						<? } else { ?>
							<a href="<?=$def['site']['dir']?>/art/<?=$item['id'];?>/">
								<img src="<?=$def['site']['dir']?>/images/booru/thumbs/large_<?=$item['thumb'];?>.jpg">
							</a>
						<? }
					} else { ?>
						<a href="<?=$data['feed']['domain'];?>/art/<?=$item['id'];?>/">
							<img src="<?=$data['feed']['domain'];?>/images/booru/thumbs/large_<?=$item['thumb'];?>.jpg">
						</a>
					<? } ?>
				</td>
			 </tr>
			 <tr>
				 <td>
					<div class="wrapper">
						<p class="meta">
							<?=$item['pretty_date'];?> |
							<? if (!$data['feed'] && !empty($item['rating'])) { ?>
								 <span class="art_vote_wrapper">
									<img
										 src="/images/minus.gif"
										 class="vote_down with_help
										 <?=($item['rating']['voted'] ? ' inactive_vote' : '');?>"
										 title="<?=($item['rating']['voted'] ? 'Вы уже голосовали' : 'Не понравилось');?>"
										 rel="<?=$item['id'];?>"
										 align="absbottom"
									/>
									<span><?=$item['rating']['score'];?></span>
									<img
										 src="/images/plus.gif"
										 class="vote_up with_help
										 <?=($item['rating']['voted'] ? ' inactive_vote' : '');?>"
										 title="<?=($item['rating']['voted'] ? 'Вы уже голосовали' : 'Понравилось');?>"
										 rel="<?=$item['id'];?>"
										 align="absbottom"
									/>
								</span> |
							<? } ?>
							<? if (!empty($item['meta']['author'])) { ?>
								<? if (count($item['meta']['author']) > 1) { ?>
									 Опубликовали:
								<? } else { ?>
									 Опубликовал:
								<? }
									foreach ($item['meta']['author'] as $key => $meta) {
										if ($nonfirst) { ?>
											,
										<? } else { $nonfirst = true; } ?>
											 <a href="<?=$data['main']['navi']['base'];?>author/<?=$key;?>/">
												<?=$meta;?>
											</a>
										<?
									}	unset($nonfirst);
								?>
								  |
							<? } ?>
							<? if (!empty($item['meta']['tag'])) { ?>
								<?
									include('templates'.SL.'main'.SL.'single'.SL.'tags.php');
								?>
								 |
							<? } ?>
							<? if (!empty($item['meta']['category'])) { ?>
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
							<? } ?>
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
							<? if (!$data['feed']) { ?>
							 |
							 <a href="<?=$def['site']['dir']?>/art/download/<?=$item['md5'].'.'.$item['extension'];?>" target="_blank">
								 Скачать
							</a>
							<? } ?>
							<?
								if ($sets['user']['rights']) {
									?>
										 |
										 <a href="<?=$def['site']['dir']?>/admin/revisions/art/<?=$item['id'];?>/">История версий</a>
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
										<option value="art_variation">Прикрепить сюда картинку</option>
										<option value="art_variation_list">Список вариаций</option>
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
				</tr>
			</table>
			<div id="loader-<?=$item['id'];?>" class="hidden center loader"><img src="<?=$def['site']['dir']?>/images/ajax-loader.gif"></div>
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
