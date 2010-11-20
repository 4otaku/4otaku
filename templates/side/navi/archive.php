<div class="archive_navi">
	<a href="<?=SITE_DIR.'/archive'?>/" class="margin10">Архив</a>:
	<ul>
		<li>
			Записи:
			<ul>
				<li>
					<a href="<?=SITE_DIR.'/archive'?>/post/date/" class="margin30<?=($url[2] == 'post' && ($url[3] == 'date' || !$url[3]) ? ' plaintext' : '');?>">
						По датам
					</a>
				</li>
				<li>
					<a href="<?=SITE_DIR.'/archive'?>/post/author/" class="margin30<?=($url[2] == 'post' && $url[3] == 'author' ? ' plaintext' : '');?>">
						По авторам
					</a>
				</li>
				<li>				
					<a href="<?=SITE_DIR.'/archive'?>/post/category/" class="margin30<?=($url[2] == 'post' && $url[3] == 'category' ? ' plaintext' : '');?>">
						По категориям
					</a>
				</li>				
			</ul>
		</li>
		<li>
			Видео:
			<ul>
				<li>
					<a href="<?=SITE_DIR.'/archive'?>/video/date/" class="margin30<?=($url[2] == 'video' && ($url[3] == 'date' || !$url[3]) ? ' plaintext' : '');?>">
						По датам
					</a>
				</li>
				<li>
					<a href="<?=SITE_DIR.'/archive'?>/video/author/" class="margin30<?=($url[2] == 'video' && $url[3] == 'author' ? ' plaintext' : '');?>">
						По авторам
					</a>
				</li>
				<li>				
					<a href="<?=SITE_DIR.'/archive'?>/video/category/" class="margin30<?=($url[2] == 'video' && $url[3] == 'category' ? ' plaintext' : '');?>">
						По категориям
					</a>
				</li>				
			</ul>
		</li>
		<li>
			Арт:
			<ul>
				<li>
					<a href="<?=SITE_DIR.'/archive'?>/art/date/" class="margin30<?=($url[2] == 'art' && ($url[3] == 'date' || !$url[3]) ? ' plaintext' : '');?>">
						По датам
					</a>
				</li>
				<li>
					<a href="<?=SITE_DIR.'/archive'?>/art/author/" class="margin30<?=($url[2] == 'art' && $url[3] == 'author' ? ' plaintext' : '');?>">
						По авторам
					</a>
				</li>
				<li>				
					<a href="<?=SITE_DIR.'/archive'?>/art/category/" class="margin30<?=($url[2] == 'art' && $url[3] == 'category' ? ' plaintext' : '');?>">
						По категориям
					</a>
				</li>				
			</ul>
		</li>
	</ul>
</div>
