<div class="cats">	
	<h2>
		<a href="<?=SITE_DIR.'/comments'?>/">
			Комментарии
		</a>
		 <a href="#" class="bar_arrow" rel="comment">
			<?
				if ($sets['dir']['comment']) {
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
	<div id="comment_bar"<?=($sets['dir']['comment'] ? '' : ' style="display:none;"');?>>
		<?
			if (is_array($data['sidebar']['comments'])) foreach ($data['sidebar']['comments'] as $comment) {
				if ($nonfirst) {
					?>
						<br />
					<?
				}
				else $nonfirst = true;
				?>
					<?=$comment['username'];?>: <?=$comment['text'];?>
					<br />
					<a href="<?=SITE_DIR.$comment['href'].'comments/all#comment-'.$comment['id'];?>">
						<?=$comment['title'];?>
					</a>
					<br />
				<?
			}	unset($nonfirst);
		?> 
	</div>
</div>
