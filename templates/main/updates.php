<?
foreach ($data['main']['updates'] as $update) {
	?>
		<div class="shell">
			<div class="update post" id="update-<?=$update['id'];?>">
				<table width="100%">
					<tr>
						<td align="left">
							<h2>
								<a href="<?=$data['feed']['domain'];?>/post/<?=$update['post_id'];?>/show_updates/" title="<?=$update['title'];?>">
									<?=$update['title'];?>
								</a>
							</h2>
						</td>
						<td align="right" valign="top">
							<a href="<?=$data['feed']['domain'];?>/post/<?=$update['post_id'];?>">
								Комментировать
							</a>
							<?
								if ($update['comment_count']) {
									?>
										 (<?=$update['comment_count'];?>)
									<?
								}
								if ($sets['user']['rights']) {
									?>
										 &nbsp; <img src="/images/comment_delete.png" class="delete-post" rel="<?=$update['id'];?>">
									<?																				
								}
							?>									
						</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<?
							if (is_array($update['image'])) {
								?>
									<td class="imageholder">
										<div class="image-0">
											<a href="<?=$data['feed']['domain'];?>/images/full/<?=current($update['image']);?>" target="_blank">
												<img src="<?=$data['feed']['domain'];?>/images/thumbs/<?=current($update['image']);?>" />
											</a>													
										</div>
									</td>
								<?
							}
						?>						
						<td valign="top">
							<p>
								Обновил: <b><?=$update['username'];?></b>; 
								Дата обновления: <?=$update['pretty_date'];?>;
							</p>
							<div class="posttext">
								<?=$update['text'];?>
							</div>
							<?  
								if (is_array($update['link'])) {
									?>
										<p>
											<?
												foreach ($update['link'] as $key => $link) { 
													if ($nonfirst) {
														?>
															<br />
														<?													
													}	else $nonfirst = true;
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
																			<a href="/go?<?=urlencode($linkurl);?>" target="_blank">
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
									<?
								}
							?>
						</td>
					</tr>
				</table>
			</div>	
		</div>
	<? 
}
