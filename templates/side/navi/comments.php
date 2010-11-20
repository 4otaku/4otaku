<ul class="plain_list">
	<li>
		<a href="<?=SITE_DIR.'/comments'?>/" class="margin30<?=($url[2] == 'page' || !$url[2] ? ' plaintext' : '');?>">
			Все комментарии
		</a>
	</li>
	<li>
		<a href="<?=SITE_DIR.'/comments'?>/post/" class="margin30<?=($url[2] == 'post' ? ' plaintext' : '');?>">
			Комментарии к записям
		</a>
	</li>
	<li>
		<a href="<?=SITE_DIR.'/comments'?>/video/" class="margin30<?=($url[2] == 'video' ? ' plaintext' : '');?>">
			Комментарии к видео
		</a>
	</li>
	<li>				
		<a href="<?=SITE_DIR.'/comments'?>/art/" class="margin30<?=($url[2] == 'art' ? ' plaintext' : '');?>">
			Комментарии к артам
		</a>
	</li>
	<li>				
		<a href="<?=SITE_DIR.'/comments'?>/news/" class="margin30<?=($url[2] == 'news' ? ' plaintext' : '');?>">
			Комментарии к новостям
		</a>
	</li>
	<li>				
		<a href="<?=SITE_DIR.'/comments'?>/order/" class="margin30<?=($url[2] == 'order' ? ' plaintext' : '');?>">
			Комментарии в столе заказов
		</a>
	</li>
</ul>
