<div class="cats">	
	<h2>
		<a href="/post/updates/">
			Обновления
		</a>
		 <a href="#" class="bar_arrow" rel="update">
			<?
				if ($sets['dir']['update']) {
					?>
						<img src="/images/text2391.png">
					<?
				}
				else {
					?>
						<img src="/images/text2387.png">
					<?				
				}
			?>
		</a>
	</h2>
	<div id="update_bar"<?=($sets['dir']['update'] ? '' : ' style="display:none;"');?>>
		<?=$data['sidebar']['update']['author'];?>: 
		<?=$data['sidebar']['update']['text'];?>
		<br />
		<a href="/post/<?=$data['sidebar']['update']['post_id'];?>/show_updates/">
			<?=$data['sidebar']['update']['post_title'];?>
		</a>
		<br />
	</div>
</div>
