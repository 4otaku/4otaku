<div class="archive_navi">
	<a href="<?=$def['site']['dir']?>/tags/" class="margin10">Теги</a>:
	<ul>
		<li>
			Записи:
			<ul>
				<li>
					<a href="<?=$def['site']['dir']?>/tags/post/" class="margin30<?=($url[2] == 'post' && ($url[3] == 'main' || !$url[3]) ? ' plaintext' : '');?>">
						На главной
					</a>
				</li>
				<li>
					<a href="<?=$def['site']['dir']?>/tags/post/flea_market/" class="margin30<?=($url[2] == 'post' && $url[3] == 'flea_market' ? ' plaintext' : '');?>">
						В барахолке
					</a>
				</li>
			</ul>
		</li>
		<li>
			Видео:
			<ul>
				<a href="<?=$def['site']['dir']?>/tags/video/" class="margin30<?=($url[2] == 'video' && ($url[3] == 'main' || !$url[3]) ? ' plaintext' : '');?>">
						На главной
					</a>
				</li>
				<li>
					<a href="<?=$def['site']['dir']?>/tags/video/flea_market/" class="margin30<?=($url[2] == 'video' && $url[3] == 'flea_market' ? ' plaintext' : '');?>">
						В барахолке
					</a>
				</li>				
			</ul>
		</li>
		<li>
			Арт:
			<ul>
				<a href="<?=$def['site']['dir']?>/tags/art/" class="margin30<?=($url[2] == 'art' && ($url[3] == 'main' || !$url[3]) ? ' plaintext' : '');?>">
						На главной
					</a>
				</li>
				<li>
					<a href="<?=$def['site']['dir']?>/tags/art/flea_market/" class="margin30<?=($url[2] == 'art' && $url[3] == 'flea_market' ? ' plaintext' : '');?>">
						В барахолке
					</a>
				</li>
			</ul>
		</li>
	</ul>
</div>
