<div class="shell">
	<div class="entry">
		<h2>
			<a href="<?=SITE_DIR.$data['feed']['domain'];?>/post/<?=$item['post_id'];?>/show_updates/" title="<?=$item['title'];?>">
				<?=$item['title'];?>
			</a>
		</h2>
		<p>
			Обновил: 
			<b>
				<?=$item['username'];?>
			</b>; 
			Дата обновления: <?=$item['pretty_date'];?>
		</p>
		<p>
			<?=$item['text'];?>
		</p>
		<p>
			<?
				if (is_array($item['link'])) foreach ($item['link'] as $key => $link) { 
					if ($nonfirst) {
						?>
							<br />
						<?													
					} else $nonfirst = true;
					?>								
						<?=$link['name'];?>: 
						<?
							if (is_array($link['url'])) foreach ($link['url'] as $key2 => $linkurl) {
									?>
										<?
												if ($nonfirst2) {
														?>
																	 | 
														<?
												}	else $nonfirst2 = true;
										?>
										<a href="<?=SITE_DIR.($data['feed']['domain'] ? $linkurl : '/go?'.urlencode($linkurl));?>" target="_blank">
											<?=$link['alias'][$key2];?>
										</a>
									<?
							}	unset($nonfirst2);
						?>
						 (~<?=$link['size'];?> <?=$link['sizetype'];?>)
					<?
				}	unset($nonfirst);
			?>
		</p>
	</div>
</div>	
