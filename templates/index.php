<? include_once (SITE_FDIR._SL.'templates'.SL.'side'.SL.'head.php'); ?>

<body class="wrapwindow">
	<table class="wrapwindow">
		<tr>
			<td align="center" class="index_topholder">
				<div class="center margin10">				
					<a href="<?=SITE_DIR?>">
						<img src="<?=SITE_DIR.'/images'?>/4otakulogo.png" alt="4отаку. Материалы для отаку." style="margin-bottom: 15px;">
					</a>
				</div>
				<div class="center margin20">
				<a title="Dreams of Dead HQ" href="http://dod.4otaku.ru" class="with_help">
					<img src="<?=SITE_DIR.'/images'?>/dod2.png">
				</a>
				<a title="Архив" href="<?=SITE_DIR.'/archive'?>/" class="with_help">
					<img src="<?=SITE_DIR.'/images'?>/arch2.png">
				</a>
				<a title="Кикаки: додзинси и ёнкомы" href="http://raincat.4otaku.ru" class="with_help">
					<img src="<?=SITE_DIR.'/images'?>/ki2.png">
				</a>				
				<a title="Yukarin Subs" href="http://yukarinsubs.4otaku.ru" class="with_help">
					<img src="<?=SITE_DIR.'/images'?>/yukarin2.png">
				</a>	
				<a title="Что такое теория относительности?" href="http://comics.4otaku.ru" class="with_help">
					<img src="<?=SITE_DIR.'/images'?>/t_chtto.png">
				</a>
				</div>
				<div class="center margin10">
					<input type="text" size="50" class="search"> <input type="button" value="искать" class="searchb">						
				</div>
				<div id="search-tip" class="center search-main" rel="0"></div>
				<div class="center margin10">					
					<input type="checkbox" checked="checked" value="p" class="searcharea"> В записях 
					<input type="checkbox" checked="checked" value="v" class="searcharea"> В видео 
					<input type="checkbox" value="a" class="searcharea"> В артах 
					<input type="checkbox" value="n" class="searcharea"> В новостях
					<input type="checkbox" checked="checked" value="o" class="searcharea"> В столе заказов
					<input type="checkbox" value="c" class="searcharea"> В комментариях							
				</div>
				<div class="yukari_corner">
					<div class="margin10">
						<a href="<?=SITE_DIR?>/">
							<img src="<?=SITE_DIR.'/images'?>/yukari.gif" class="right">
						</a>
					</div>
				</div>					
				<div class="rss_corner">
					<div class="right">		
						<a href="<?=($sets['rss']['default'] == $def['rss']['default'] ? SITE_DIR.'/go?http%3A%2F%2Ffeeds.feedburner.com%2F4otaku' : SITE_DIR.'/rss/='.$sets['rss']['default'].'/');?>" title="RSS записей">
							<img align="middle" src="<?=SITE_DIR.'/images'?>/feed_80x80.png" alt="RSS записей" />
						</a>
					</div>
					<div class="margin10 box first_box">
						<a href="http://wiki.4otaku.ru/%D0%9A%D0%B0%D1%82%D0%B5%D0%B3%D0%BE%D1%80%D0%B8%D1%8F:FAQ" title="Частые вопросы по сайту">
							Частые вопросы
						</a>
					</div>
					<div class="margin10 box">
						<a href="<?=SITE_DIR?>/ajax.php?m=box&f=rss&width=600&height=240" title="Выберите, что показывать вам в RSS" class="thickbox">
							Выберите свой RSS
						</a>
					</div>
					<div class="margin10 box">
						<a href="<?=SITE_DIR?>/ajax.php?m=box&f=settings&width=500&height=650" title="Ваши личные настройки" class="thickbox">
							Настройки
						</a>
					</div>
				</div>		
			</td>
		</tr>
		<tr>		
			<td class="index_centerholder" valign="top">
				<div class="shell">
					Наша комната в джаббере: main@room.4otaku.ru; 
					<a href="http://jabberworld.info/%D0%9A%D0%BB%D0%B8%D0%B5%D0%BD%D1%82%D1%8B_Jabber">
						Помощь по настройке
					</a>. 
					<a href="<?=SITE_DIR.'/logs'?>/">
						Логи
					</a>. 
					<span class="right">
					<?=$data['main']['links'];?> битых ссылок. 
						<a href="<?=SITE_DIR.'/gouf'?>/">
							Помочь
						</a>.
					</span>
				</div>
				<? if ($data['main']['news']['title']) { ?>
					<div class="compressed_news shell clear<?=($sets['news']['read'] < $data['main']['news']['sortdate'] ? ' hidden' : '');?> margin30">
						<a href="<?=SITE_DIR.'/news'?>/<?=$data['main']['news']['url'];?>/">
							<?=$data['main']['news']['title'];?>
						</a>
						<?=($data['main']['news']['comment_count'] ? ' ('.$data['main']['news']['comment_count'].')' : ''); ?>
						<a href="#" class="uncompress_news togglenews">
							Развернуть новость.
						</a>
						<a href="<?=SITE_DIR.'/news'?>/" class="uncompress_news">
							Архив новостей.
						</a>						
					</div>			
				<? } ?>
				<div class="left index_smallcolumn defaultvideoholder">		
					<div class="mainblock">
						<p class="head">
							<a href="<?=SITE_DIR.'/post'?>/">
								Записи
							</a>
						</p>
						Всего <?=$data['main']['count']['post']['total'];?> записей. 
						<?=($data['main']['count']['post']['unseen'] ? $data['main']['count']['post']['unseen'].' из них новых. ' : '');?>
						<? if (is_array($data['main']['count']['post']['latest'])) { ?>
							Последние записи: <br /><br />
							<? foreach ($data['main']['count']['post']['latest'] as $key => $one) { ?>
								<?=($key ? '<br />' : '');?>
								<a href="<?=SITE_DIR.'/post'?>/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
									<?=$one['title'];?>
								</a>
								<?=($one['comment_count'] ? ' ('.$one['comment_count'].')' : '');?>
							<? } ?>
						<? } ?>
					</div>
					<? if ($sets['news']['read'] < $data['main']['news']['sortdate']) {	?>
						<div class="mainblock videoblock">
							<p class="head">
								<a href="<?=SITE_DIR.'/video'?>/">
									Видео
								</a>
							</p>				
							Всего <?=$data['main']['count']['video']['total'];?> видео. 
							<?=($data['main']['count']['video']['unseen'] ? $data['main']['count']['video']['unseen'].' из них новых. ' : '');?>
							<? if (is_array($data['main']['count']['post']['latest'])) { ?>
								Последние видео: <br /><br />
								<? foreach ($data['main']['count']['video']['latest'] as $key => $one) { ?>
									<?=($key ? '<br />' : '');?>
									<a href="<?=SITE_DIR.'/video'?>/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
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
								<a href="<?=SITE_DIR.'/video'?>/">
									Видео
								</a>
							</p>				
							Всего <?=$data['main']['count']['video']['total'];?> видео. 
							<?=($data['main']['count']['video']['unseen'] ? $data['main']['count']['video']['unseen'].' из них новых. ' : '');?>
							<? if (is_array($data['main']['count']['post']['latest'])) { ?>
								Последние видео: <br /><br />
								<? foreach ($data['main']['count']['video']['latest'] as $key => $one) { ?>
									<?=($key ? '<br />' : '');?>
									<a href="<?=SITE_DIR.'/video'?>/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
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
								<a href="<?=SITE_DIR.'/art'?>/">
									Арт
								</a>
							</p>				
							Всего <?=$data['main']['count']['art']['total'];?> артов. 
							<?=($data['main']['count']['art']['unseen'] ? $data['main']['count']['art']['unseen'].' из них новых. ' : '');?>
							<? if ($data['main']['count']['art']['latest'][0]['thumb']) { ?>
								Последнее изображение: <br /><br />
								<div style="text-align:center; width: 100%;">
									<a href="<?=SITE_DIR.'/art'?>/<?=$data['main']['count']['art']['latest'][0]['id'];?>">		
										<img src="<?=SITE_DIR.'/images'?>/booru/thumbs/<?=$data['main']['count']['art']['latest'][0]['thumb'];?>.jpg">
									</a>	
								</div>
							<? } ?>	
						</div>		
					<? } ?>	
				</div>				
				<div class="left index_smallcolumn defaultartholder">
					<div class="mainblock">
						<p class="head">
							<a href="<?=SITE_DIR.'/order'?>/">
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
								<a href="<?=SITE_DIR.'/order'?>/<?=$one['id'];?>" class="with_help" title="<?=$output->make_tip($one['text']);?>">
									<?=$one['title'];?>
								</a>.
								<?=($one['comment_count'] ? ' ('.$one['comment_count'].')' : '');?>
							<? } ?>
						<? } ?>	
					</div>
					<? if ($sets['news']['read'] < $data['main']['news']['sortdate']) { ?>
						<div class="mainblock artblock">
							<p class="head">
								<a href="<?=SITE_DIR.'/art'?>/">
									Арт
								</a>
							</p>				
							Всего <?=$data['main']['count']['art']['total'];?> артов. 
							<?=($data['main']['count']['art']['unseen'] ? $data['main']['count']['art']['unseen'].' из них новых. ' : '');?>
							<? if ($data['main']['count']['art']['latest'][0]['thumb']) { ?>
								Последнее изображение: <br /><br />
								<div style="text-align:center; width: 100%;">
									<a href="<?=SITE_DIR.'/art'?>/<?=$data['main']['count']['art']['latest'][0]['id'];?>">		
										<img src="<?=SITE_DIR.'/images'?>/booru/thumbs/<?=$data['main']['count']['art']['latest'][0]['thumb'];?>.jpg">
									</a>	
								</div>
							<? } ?>
						</div>
					<? } ?>		
				</div>
				<? if ($data['main']['news']['title']) { ?>
					<div class="left index_largecolumn<?=($sets['news']['read'] >= $data['main']['news']['sortdate'] ? ' hidden' : '');?>">
						<div class="post mainblock">
							<p class="head">
								<a href="<?=SITE_DIR.'/news'?>/<?=$data['main']['news']['url'];?>/">
									<?=$data['main']['news']['title'];?>
								</a>
							</p>					
							<div class="entry">
								<a href="<?=SITE_DIR.'/images'?>/full/<?=$data['main']['news']['image'];?>" target="_blank">
									<img src="<?=SITE_DIR.'/images'?>/thumbs/<?=$data['main']['news']['image'];?>" align="left" class="news_image" />
								</a>
								<?=stripslashes($data['main']['news']['text']);?>
							</div>
							<span class="semi_large">
								<a href="<?=SITE_DIR.'/news'?>/<?=$data['main']['news']['url'];?>/">
									Комментировать
								</a>.
								<?=($data['main']['news']['comment_count'] ? ' ('.$data['main']['news']['comment_count'].')' : '');?>
								<a href="#" class="right compress_news togglenews">
									Я прочел, уберите новость.
								</a>
							</span>
							<div class="center clear">
								<a href="<?=SITE_DIR.'/news'?>/">
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
				<? include_once(SITE_FDIR._SL.'templates'.SL.'side'.SL.'footer.php');?>
			</td>
		</tr>
	</table>
</body>
</html>
