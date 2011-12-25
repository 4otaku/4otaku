<?
	include_once ('side'.SL.'head.php');
?>
<body class="wrapwindow">
	<? include_once('side'.SL.'header.php'); ?>
	<div class="branding">
		<a href="http://4otaku.ru" class="logo">
			<img src="/images/4otakulogo_t.png" alt="4отаку" />
		</a>
		<img class="right yukari" src="/images/yukari120.gif" alt="Юкарин" />
		<div class="mainsearch">
			<div class="margin10 mainsearch_field center">
				<input class="search" type="text">
				 <input value="искать" class="searchb" type="button">
				 <div id="search-tip" class="search-main" rel="0"></div>
			</div>
			<div class="center margin10">
				<input id="search_p" checked="checked" value="p" class="searcharea" type="checkbox"><label for="search_p">В записях</label>
				<input id="search_v" checked="checked" value="v" class="searcharea" type="checkbox"><label for="search_v">В видео</label>
				<input id="search_a" value="a" class="searcharea" type="checkbox"><label for="search_a">В артах</label>
				<input id="search_n" value="n" class="searcharea" type="checkbox"><label for="search_n">В новостях</label>
				<input id="search_o" checked="checked" value="o" class="searcharea" type="checkbox"><label for="search_o">В столе заказов</label>
				<input id="search_c" value="c" class="searcharea" type="checkbox"><label for="search_c">В комментариях</label>
			</div>
		</div>
	</div>
	<table class="wrapwindow">
		<tr>
			<td class="index_centerholder" valign="top">
				<div class="mini-shell margin10">
					Наша комната в джаббере: main@room.4otaku.ru;
					 <a href="http://jabberworld.info/%D0%9A%D0%BB%D0%B8%D0%B5%D0%BD%D1%82%D1%8B_Jabber">
						Помощь по настройке
					</a>.
					 <a href="<?=$def['site']['dir']?>/logs/">
						Логи
					</a>.
					<span class="right">
						<?=$data['main']['links'];?> битых ссылок.
						 <a href="<?=$def['site']['dir']?>/post/gouf/">
							Помочь
						</a>.
					</span>
				</div>
				<div class="mini-shell margin10">
					<a href="<?=$def['site']['dir']?>/board/">
						Борда сайта
					</a>.
					 Всего тредов: <?=$data['main']['board']['all'];?>
					<? if (!empty($data['main']['board']['new'])) { ?>
						, <a href="<?=$def['site']['dir']?>/board/new/<?=$data['main']['board']['link'];?>">
							<?=$data['main']['board']['new'];?> из них новых
						</a>
					<? } ?>
					<? if (!empty($data['main']['board']['updated'])) { ?>
						, <a href="<?=$def['site']['dir']?>/board/updated/<?=$data['main']['board']['link'];?>">
							<?=$data['main']['board']['updated'];?> обновилось
						</a>
					<? } ?>
					.
					<span class="right">
						<? if (!empty($data['main']['wiki'])) { ?>
							<a href="http://wiki.4otaku.ru">
								Вики сайта
							</a>.
							 Последняя правка:
							 <a href="http://wiki.4otaku.ru/<?=str_replace(array('%3A', '%2F'), array(':', '/'), urlencode($data['main']['wiki']));?>">
								<?=$data['main']['wiki'];?>
							</a>.
						<? } ?>
					</span>
				</div>
				<? if (isset($data['main']['news']['title'])) { ?>
					<div class="compressed_news mini-shell margin10 clear<?=($sets['news']['read'] < $data['main']['news']['sortdate'] ? ' hidden' : '');?> margin30">
						<a href="<?=$def['site']['dir']?>/news/<?=$data['main']['news']['url'];?>/">
							<?=$data['main']['news']['title'];?>
						</a>
						<?=($data['main']['news']['comment_count'] ? ' ('.$data['main']['news']['comment_count'].')' : ''); ?>
						<a href="#" class="uncompress_news togglenews news_bar">
							Развернуть новость.
						</a>
						<a href="<?=$def['site']['dir']?>/news/" class="news_bar">
							Архив новостей.
						</a>
					</div>
				<? } ?>
				<div class="left index_smallcolumn defaultvideoholder">
					<div class="mainblock">
						<p class="head">
							<a href="<?=$def['site']['dir']?>/post/">
								Записи
							</a>
						</p>
						Всего <?=$data['main']['count']['post']['total'];?> записей.
						 <?=($data['main']['count']['post']['unseen'] ? $data['main']['count']['post']['unseen'].' из них новых. ' : '');?>
						<? if (is_array($data['main']['count']['post']['latest'])) { ?>
							Последние записи: <br /><br />
							<? foreach ($data['main']['count']['post']['latest'] as $key => $one) { ?>
								<?=($key ? '<br />' : '');?>
								<a href="<?=$def['site']['dir']?>/post/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
									<?=$one['title'];?>
								</a>
								<?=($one['comment_count'] ? ' ('.$one['comment_count'].')' : '');?>
							<? } ?>
						<? } ?>
					</div>
					<? if ($sets['news']['read'] < $data['main']['news']['sortdate']) {	?>
						<div class="mainblock videoblock">
							<p class="head">
								<a href="<?=$def['site']['dir']?>/video/">
									Видео
								</a>
							</p>
							Всего <?=$data['main']['count']['video']['total'];?> видео.
							 <?=($data['main']['count']['video']['unseen'] ? $data['main']['count']['video']['unseen'].' из них новых. ' : '');?>
							<? if (is_array($data['main']['count']['video']['latest'])) { ?>
								Последние видео: <br /><br />
								<? foreach ($data['main']['count']['video']['latest'] as $key => $one) { ?>
									<?=($key ? '<br />' : '');?>
									<a href="<?=$def['site']['dir']?>/video/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
										<?=$one['title'];?>
									</a>
									<?=($one['comment_count'] ? ' ('.$one['comment_count'].')' : '');?>
								<? } ?>
							<? } ?>
						</div>
					<? } ?>
				</div>
				<div class="left index_smallcolumn<?=($sets['news']['read'] < $data['main']['news']['sortdate'] ? ' hidden' : '');?> videoholder">
					<? if ($sets['news']['read'] >= $data['main']['news']['sortdate']) { ?>
						<div class="mainblock videoblock">
							<p class="head">
								<a href="<?=$def['site']['dir']?>/video/">
									Видео
								</a>
							</p>
							Всего <?=$data['main']['count']['video']['total'];?> видео.
							 <?=($data['main']['count']['video']['unseen'] ? $data['main']['count']['video']['unseen'].' из них новых. ' : '');?>
							<? if (is_array($data['main']['count']['video']['latest'])) { ?>
								Последние видео: <br /><br />
								<? foreach ($data['main']['count']['video']['latest'] as $key => $one) { ?>
									<?=($key ? '<br />' : '');?>
									<a href="<?=$def['site']['dir']?>/video/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
										<?=$one['title'];?>
									</a>
									<?=($one['comment_count'] ? ' ('.$one['comment_count'].')' : '');?>
								<? } ?>
							<? } ?>
						</div>
					<? } ?>
				</div>
				<div class="left index_smallcolumn<?=($sets['news']['read'] < $data['main']['news']['sortdate'] ? ' hidden' : '');?> artholder">
					<? if ($sets['news']['read'] >= $data['main']['news']['sortdate']) { ?>
						<div class="mainblock artblock">
							<p class="head">
								<a href="<?=$def['site']['dir']?>/art/">
									Арт
								</a>
							</p>
							Всего <?=$data['main']['count']['art']['total'];?> артов.
							 <?=($data['main']['count']['art']['unseen'] ? $data['main']['count']['art']['unseen'].' из них новых. ' : '');?>
							<? if ($data['main']['count']['art']['latest'][0]['thumb']) { ?>
								Последнее изображение: <br /><br />
								<div style="text-align:center; width: 100%;">
									<a href="<?=$def['site']['dir']?>/art/<?=$data['main']['count']['art']['latest'][0]['id'];?>">
										<img src="<?=$def['site']['dir']?>/images/booru/thumbs/<?=$data['main']['count']['art']['latest'][0]['thumb'];?>.jpg">
									</a>
								</div>
							<? } ?>
						</div>
					<? } ?>
				</div>
				<div class="left index_smallcolumn defaultartholder">
					<div class="mainblock">
						<p class="head">
							<a href="<?=$def['site']['dir']?>/order/">
								Заказы
							</a>
						</p>
						Всего <?=$data['main']['count']['order']['total'];?> заказов.
						 <?=$data['main']['count']['order']['open'];?> из них открыты.
						<? if (is_array($data['main']['count']['order']['latest'])) { ?>
							Последние заказы: <br /><br />
							<? foreach ($data['main']['count']['order']['latest'] as $key => $one) { ?>
								<?=($key ? '<br /><br />' : '');?>
								<?=$one['username'];?> заказал
								 <a href="<?=$def['site']['dir']?>/order/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
									<?=$one['title'];?>
								</a>.
								 <?=($one['comment_count'] ? ' ('.$one['comment_count'].')' : '');?>
							<? } ?>
						<? } ?>
					</div>
					<? if ($sets['news']['read'] < $data['main']['news']['sortdate']) { ?>
						<div class="mainblock artblock">
							<p class="head">
								<a href="<?=$def['site']['dir']?>/art/">
									Арт
								</a>
							</p>
							Всего <?=$data['main']['count']['art']['total'];?> артов.
							 <?=($data['main']['count']['art']['unseen'] ? $data['main']['count']['art']['unseen'].' из них новых. ' : '');?>
							<? if ($data['main']['count']['art']['latest'][0]['thumb']) { ?>
								Последнее изображение: <br /><br />
								<div style="text-align:center; width: 100%;">
									<a href="<?=$def['site']['dir']?>/art/<?=$data['main']['count']['art']['latest'][0]['id'];?>">
										<img src="<?=$def['site']['dir']?>/images/booru/thumbs/<?=$data['main']['count']['art']['latest'][0]['thumb'];?>.jpg">
									</a>
								</div>
							<? } ?>
						</div>
					<? } ?>
				</div>
				<? if (isset($data['main']['news']['title'])) { ?>
					<div class="left index_largecolumn<?=($sets['news']['read'] >= $data['main']['news']['sortdate'] ? ' hidden' : '');?>">
						<div class="post mainblock">
							<p class="head">
								<a href="<?=$def['site']['dir']?>/news/<?=$data['main']['news']['url'];?>/">
									<?=$data['main']['news']['title'];?>
								</a>
							</p>
							<div class="entry">
								<a href="<?=$def['site']['dir']?>/images/full/<?=$data['main']['news']['image'];?>" target="_blank">
									<img src="<?=$def['site']['dir']?>/images/thumbs/<?=$data['main']['news']['image'];?>" align="left" class="news_image" />
								</a>
								<?=stripslashes($data['main']['news']['text']);?>
							</div>
							<span class="semi_large">
								<a href="<?=$def['site']['dir']?>/news/<?=$data['main']['news']['url'];?>/">
									Комментировать
								</a>.
								<?=($data['main']['news']['comment_count'] ? ' ('.$data['main']['news']['comment_count'].')' : '');?>
								<a href="#" class="right compress_news togglenews">
									Я прочел, уберите новость.
								</a>
							</span>
							<div class="center clear">
								<a href="<?=$def['site']['dir']?>/news/">
									Архив новостей.
								</a>
							</div>
						</div>
					</div>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td id="footer">
				<? include_once('side/footer.php'); ?>
			</td>
		</tr>
	</table>
</body>
</html>
