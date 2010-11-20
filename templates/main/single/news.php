<div class="shell">
	<div class="post">
		<table width="100%">
			<tr>
				<td align="left">
					<h2>
						<a href="<?=SITE_DIR.$data['feed']['domain'];?>/news/<?=$item['url'];?>" title="<?=$item['title'];?>">
							<?=$item['title'];?>
						</a>
					</h2>
				</td>
				<td align="right" valign="top">
					<?
						if ($data['main']['display'] && in_array('comments',$data['main']['display'])) {
							?>
								<?=$item['pretty_date'];?>
							<?
						}
						else {
							?>
								<a href="<?=SITE_DIR.$data['feed']['domain'];?>/news/<?=$item['url'];?>">
									Комментировать
								</a>
								<?
									if ($item['comment_count']) {
										?>
											 (<?=$item['comment_count'];?>)
										<?
									}
								?>
							<?							
						}
					?>															
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<?
					if ($item['image']) {
						?>
							<td class="imageholder"<?=($data['feed'] ? ' valign="top"' : '');?>>
								<div class="image-0"<?=($data['feed'] ?  ' style="margin: 0 10px 0 0;"' : '');?>>
									<a href="<?=SITE_DIR.$data['feed']['domain'];?>/images/full/<?=$item['image'];?>" target="_blank">
										<img src="<?=SITE_DIR.$data['feed']['domain'];?>/images/thumbs/<?=$item['image'];?>" />
									</a>													
								</div>
							</td>
						<?
					}
				?>						
				<td valign="top">
					<div class="posttext">
						<?=$item['text'];?>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
