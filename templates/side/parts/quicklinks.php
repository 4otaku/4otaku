<div class="cats">	
	<h2>
		<a href="<?=SITE_DIR?>/">
			Быстрые ссылки
		</a>
		 <a href="#" class="bar_arrow" rel="quick">
			<?
				if ($sets['dir']['quick']) {
					?>
						<img src="<?=SITE_DIR.'/images'?>/text2391.png">
					<?
				}
				else {
					?>
						<img src="<?=SITE_DIR.'/images'?>/text2387.png">
					<?				
				}
			?>
		</a>
	</h2>
	<div id="quick_bar"<?=($sets['dir']['quick'] ? '' : ' style="display:none;"');?>>
		<ul>
			<li>
				<a href="<?=SITE_DIR?>/" class="margin30">
					Главная страница
				</a>
			</li>
			<li><hr /></li>
			<li>
				<a href="<?=SITE_DIR.'/post'?>/" class="margin30<?=($url[1] == 'post' && $url[2] != 'updates' ? ' plaintext' : '');?>">
					Записи
				</a>
			</li>
			<li>
				<a href="<?=SITE_DIR.'/post'?>/updates/" class="margin30<?=($url[1] == 'post' && $url[2] == 'updates' ? ' plaintext' : '');?>">
					Обновления записей
				</a>
			</li>
			<li>
				<a href="<?=SITE_DIR.'/video'?>/" class="margin30<?=($url[1] == 'video' ? ' plaintext' : '');?>">
					Видео
				</a>
			</li>
			<li>				
				<a href="<?=SITE_DIR.'/art'?>/" class="margin30<?=($url[1] == 'art' ? ' plaintext' : '');?>">
					Арты
				</a>
			</li>
			<li>
				<a href="<?=SITE_DIR.'/art'?>/cg_packs/" class="margin30<?=($url[1] == 'arg' && $url[2] == 'cg_packs' ? ' plaintext' : '');?>">
					CG паки
				</a>
			</li>
			<li><hr /></li>
			<li>				
				<a href="<?=SITE_DIR.'/news'?>/" class="margin30<?=($url[1] == 'news' ? ' plaintext' : '');?>">
					Новости
				</a>
			</li>
			<li>				
				<a href="<?=SITE_DIR.'/order'?>/" class="margin30<?=($url[1] == 'order' ? ' plaintext' : '');?>">
					Стол заказов
				</a>
			</li>
			<li>				
				<a href="<?=SITE_DIR.'/comments'?>/" class="margin30<?=($url[1] == 'comments' ? ' plaintext' : '');?>">
					Лента комментариев
				</a>
			</li>			
			<li><hr /></li>
			<li>
				<a href="<?=SITE_DIR.'/logs'?>/" class="margin30<?=($url[1] == 'logs' ? ' plaintext' : '');?>">
					Логи конференции
				</a>
			</li>
			<li>
				<a href="<?=SITE_DIR.'/gouf'?>/" class="margin30<?=($url[1] == 'gouf' ? ' plaintext' : '');?>">
					Список битых ссылок
				</a>
			</li>
			<li>
				<a href="<?=SITE_DIR.'/archive'?>/" class="margin30<?=($url[1] == 'archive' ? ' plaintext' : '');?>">
					Архив материалов
				</a>
			</li>
			<li><hr /></li>
			<li>
				<a href="http://raincat.4otaku.ru/" class="margin30">
					Кикаки: додзинси и ёнкомы
				</a>
			</li>
			<li>
				<a href="http://dod.4otaku.ru/" class="margin30">
					Dreams of Dead
				</a>
			</li>
			<li>
				<a href="http://yukarinsubs.4otaku.ru/" class="margin30">
					Yukarin Subs
				</a>
			</li>
			<li>
				<a href="http://comics.4otaku.ru/" class="margin30">
					Комикс ЧТТО
				</a>
			</li>
			<li>
				<a href="http://hex.4otaku.ru/" class="margin30">
					CG экстрактор
				</a>
			</li>
		</ul>
	</div>
</div>
