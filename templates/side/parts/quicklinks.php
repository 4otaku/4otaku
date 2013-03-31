<div class="cats">
	<h2>
		<a href="<?=$def['site']['dir']?>/">
			Быстрые ссылки
		</a>
		 <a href="#" class="bar_arrow" rel="quick">
			<?
				if ($sets['dir']['quick']) {
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
	<div id="quick_bar"<?=($sets['dir']['quick'] ? '' : ' style="display:none;"');?>>
		<ul>
			<li>
				<a href="<?=$def['site']['dir']?>/" class="margin30">
					Главная страница
				</a>
			</li>
			<li><hr /></li>
			<li>
				<a href="<?=$def['site']['dir']?>/post/" class="margin30<?=($url[1] == 'post' && $url[2] != 'updates' ? ' plaintext' : '');?>">
					Записи
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/post/updates/" class="margin30<?=($url[1] == 'post' && $url[2] == 'updates' ? ' plaintext' : '');?>">
					Обновления записей
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/video/" class="margin30<?=($url[1] == 'video' ? ' plaintext' : '');?>">
					Видео
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/art/" class="margin30<?=($url[1] == 'art' ? ' plaintext' : '');?>">
					Арты
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/art/cg_packs/" class="margin30<?=($url[1] == 'art' && $url[2] == 'cg_packs' ? ' plaintext' : '');?>">
					CG паки
				</a>
			</li>
			<li><hr /></li>
			<li>
				<a href="<?=$def['site']['dir']?>/news/" class="margin30<?=($url[1] == 'news' ? ' plaintext' : '');?>">
					Новости
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/order/" class="margin30<?=($url[1] == 'order' ? ' plaintext' : '');?>">
					Стол заказов
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/board/" class="margin30<?=($url[1] == 'board' ? ' plaintext' : '');?>">
					Борда 4отаку
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/chat/" class="margin30<?=($url[1] == 'chat' ? ' plaintext' : '');?>">
					Веб-клиент конференции
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/comments/" class="margin30<?=($url[1] == 'comments' ? ' plaintext' : '');?>">
					Лента комментариев
				</a>
			</li>
			<li><hr /></li>
			<li>
				<a href="http://wiki.4otaku.org/Category:FAQ" class="margin30">
					Справка по сайту
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/logs/" class="margin30<?=($url[1] == 'logs' ? ' plaintext' : '');?>">
					Логи конференции
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/post/gouf/" class="margin30<?=($url[1] == 'gouf' ? ' plaintext' : '');?>">
					Список битых ссылок
				</a>
			</li>
			<li>
				<a href="<?=$def['site']['dir']?>/archive/" class="margin30<?=($url[1] == 'archive' ? ' plaintext' : '');?>">
					Архив материалов
				</a>
			</li>
			<li><hr /></li>
			<li>
				<a href="http://raincat.4otaku.ru" class="margin30">
					Кикаки: додзинси и ёнкомы
				</a>
			</li>
			<li>
				<a href="http://dod.4otaku.ru" class="margin30">
					Dreams of Dead
				</a>
			</li>
			<li>
				<a href="http://yukarinsubs.4otaku.ru" class="margin30">
					Yukarin Subs
				</a>
			</li>
			<li>
				<a href="http://alice.4otaku.ru" class="margin30">
					Комикс Randomness
				</a>
			</li>
		</ul>
	</div>
</div>
