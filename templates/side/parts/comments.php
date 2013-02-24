<div class="cats">
	<h2>
		<a href="<?=$def['site']['dir']?>/comments/<?=(empty($data['sidebar']['comments']['link']) ? '' : $data['sidebar']['comments']['link'].'/');?>">
			Комментарии
		</a>
		 <a href="#" class="bar_arrow" rel="comment">
			<?
				if ($sets['dir']['comment']) {
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
	<div id="comment_bar"<?=($sets['dir']['comment'] ? '' : ' style="display:none;"');?>>
		<?
			if (is_array($data['sidebar']['comments']['data'])) foreach ($data['sidebar']['comments']['data'] as $comment) {
				if ($nonfirst) {
					?>
						<br />
					<?
				}
				else $nonfirst = true;
				?>
					<?=$comment['username'];?>: <?=$comment['text'];?>
					<br />
					<a href="<?=$comment['href'].'comments/all#comment-'.$comment['id'];?>">
						<?=$comment['title'];?>
					</a>
					<br />
				<?
			}	unset($nonfirst);
		?>
	</div>
</div>
