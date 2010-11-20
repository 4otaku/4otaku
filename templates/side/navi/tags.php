<div class="archive_navi">
	<a href="<?=SITE_DIR.'/tags'?>/" class="margin10">Теги</a>:
	<ul>
		<li>
			Записи:
			<ul>
				<li>
					<a href="<?=SITE_DIR.'/tags'?>/post/" class="margin30<?=($url[2] == 'post' && ($url[3] == 'main' || !$url[3]) ? ' plaintext' : '');?>">
						На главной
					</a>
				</li>
				<li>
					<a href="<?=SITE_DIR.'/tags'?>/post/flea_market/" class="margin30<?=($url[2] == 'post' && $url[3] == 'flea_market' ? ' plaintext' : '');?>">
						В барахолке
					</a>
				</li>
			</ul>
		</li>
		<li>
			Видео:
			<ul>
				<a href="<?=SITE_DIR.'/tags'?>/video/" class="margin30<?=($url[2] == 'video' && ($url[3] == 'main' || !$url[3]) ? ' plaintext' : '');?>">
						На главной
					</a>
				</li>
				<li>
					<a href="<?=SITE_DIR.'/tags'?>/video/flea_market/" class="margin30<?=($url[2] == 'video' && $url[3] == 'flea_market' ? ' plaintext' : '');?>">
						В барахолке
					</a>
				</li>				
			</ul>
		</li>
		<li>
			Арт:
			<ul>
				<a href="<?=SITE_DIR.'/tags'?>/art/" class="margin30<?=($url[2] == 'art' && ($url[3] == 'main' || !$url[3]) ? ' plaintext' : '');?>">
						На главной
					</a>
				</li>
				<li>
					<a href="<?=SITE_DIR.'/tags'?>/art/flea_market/" class="margin30<?=($url[2] == 'art' && $url[3] == 'flea_market' ? ' plaintext' : '');?>">
						В барахолке
					</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
