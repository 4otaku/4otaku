<?
	foreach ($data['main']['blocks'] as $block) {
		?>				
			<div class="shell post">
				<div class="left">
					<h2>
						<a href="<?=$def['site']['dir']?>/<?=$block['place'];?>/<?=$block['id'];?>/" title="<?=$block['title'];?>">
							<?=$block['title'];?>
						</a>
					</h2>
				</div>
				<div class="right">
					<b>
						Всего комментариев: <?=$block['comment_count'];?>
					</b>
				</div>
				<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<?
							if ($block['image']) {
								?>
									<td class="imageholder">
										<div class="image-0">
											<img src="<?=$block['image'];?>" />
										</div>
									</td>
								<?
							}
						?>						
						<td valign="top">
							<?=($block['comment_count'] > $sets['pp']['comment_in_post'] ? 
								'<p class="help">
									Показываются только '.$sets['pp']['comment_in_post'].' последних '.
									obj::transform('text')->wcase(sets::pp('comment_in_post'),'комментарий','комментария','комментариев').'. 
									<a href="'.$def['site']['dir'].'/'.$block['place'].'/'.$block['id'].'/comments/all">
										Читать все
									</a>.
								</p>' 
								: '');?>
							<?	
								$i = $block['comment_count'];
								foreach ($block['comments'] as $comment) { 
									$i--; 
									if ($i < $sets['pp']['comment_in_post']) {
										?>
											<div class="comment" id="comment-<?=$comment['id'];?>"<?=($comment['position'] ? ' style="padding-left: '.($comment['position']*50).'px;"' : '');?>>
												<div class="comment-top">
													<b><?=($block['comment_count'] - $i);?>) <?=$comment['username'];?></b>
													<span class="datetime">
														 <?=$comment['pretty_date'];?>
													</span>
													<span class="commentmetadata"><a href="#comment-<?=$comment['id'];?>" title="">#</a></span>
												</div>
												<div class="comment-content">
													<div class="avatar-n">
														<img alt='' src='http://www.gravatar.com/avatar/<?=md5( strtolower($comment['email']) );?>?s=50&d=identicon&r=G' class='avatar avatar-50 photo' height='50' width='50' />
													</div>
													<div class="comment-text">
													<span><?=$comment['text'];?></span>
													<br /><br />
													<a href="<?=$def['site']['dir']?>/<?=$block['place'];?>/<?=$block['id'];?>/comments/all#reply-<?=$comment['id'];?>">Ответить</a>
													<div class="c-wrap"></div>
													</div>
												</div>
												<br />
											</div>
										<?
									}
								}
							?>
							<div style="margin-left:50px;">
								<h2>
									<a href="<?=$def['site']['dir']?>/<?=$block['place'];?>/<?=$block['id'];?>/">
										Ответить
									</a>
								</span>
							</h2>
						</td>
					</tr>
				</table>
			</div>
		<?
	}
?>
