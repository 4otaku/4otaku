<div class="shell post">
	<div id="post-<?=$item['id'];?>">
		<div class="innerwrap">
			<table width="100%">
				<tr>
					<td align="left">
						<h2>
							<a href="<?=SITE_DIR.$data['feed']['domain'];?>/video/<?=$item['id'];?>" title="<?=$item['title'];?>">
								<?=$item['title'];?>
							</a>
						</h2>
					</td>
					<td align="right" valign="top">
						<?
							if ($data['main']['display'] && !in_array('comments',$data['main']['display'])) {
								?>							
									<a href="<?=SITE_DIR.$data['feed']['domain'];?>/video/<?=$item['id'];?>">
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
			<div class="center clear">
				<center>
					<?=$item['object'];?>
				</center>
			</div>
			<?
				if (!$data['feed']) {
					?>
						<div class="wrapper">
							<p class="meta">
								<?=$item['pretty_date'];?>
								<? if (is_array($item['meta']['author'])) 
									{
										?>
											 | 
											<?
												if (count($item['meta']['author']) > 1) {
													?>
														Опубликовали: 
													<?
												}
												else {
													?>
														Опубликовал: 
													<?
												}
												foreach ($item['meta']['author'] as $key => $meta) {
													if ($nonfirst) {
														?>
														, 
														<?
													}	else $nonfirst = true;
													?>
														<?
															if (!is_numeric($url[2]) && $url[1] != 'search') {
																?>													
																	<a href="<?=SITE_DIR.$output->mixed_add($key,'author');?>">
																		+
																	</a> 
																	<a href="<?=SITE_DIR.$output->mixed_add($key,'author','-');?>">
																		-
																	</a> 
																<?
															}
														?>																													
														<a href="<?=SITE_DIR.$data['main']['navi']['base'];?>author/<?=$key;?>/">
															<?=$meta;?>
														</a>
													<?
												}	unset($nonfirst);
											?>
										<?
									}
								?>
								<? if (is_array($item['meta']['category'])) 
									{
										?>
											 | 
											<?
												if (count($item['meta']['category']) > 1) {
													?>
														Категории: 
													<?
												}
												else {
													?>
														Категория: 
													<?
												}
												foreach ($item['meta']['category'] as $key => $meta) {
													if ($nonfirst) {
														?>
														, 
														<?
													}	else $nonfirst = true;
													?>
														<?
															if (!is_numeric($url[2]) && $url[1] != 'search') {
																?>													
																	<a href="<?=SITE_DIR.$output->mixed_add($key,'category');?>">
																		+
																	</a> 
																	<a href="<?=SITE_DIR.$output->mixed_add($key,'category','-');?>">
																		-
																	</a> 
																<?
															}
														?>																													
														<a href="<?=SITE_DIR.$data['main']['navi']['base'];?>category/<?=$key;?>/">
															<?=$meta;?>
														</a>
													<?
												}	unset($nonfirst);
											?>
										<?
									}
								?>								
								<? if (is_array($item['meta']['tag'])) 
									{
										?>
											 | 
											<?
												include(SITE_FDIR.SL.'templates'.SL.'main'.SL.'single'.SL.'tags.php');												
											?>
										<?
									}
									if ($sets['user']['rights']) {
										?>
											 | 
											<a href="<?=SITE_DIR?>/admin/revisions/video/<?=$item['id'];?>/">История версий</a>	
										<?
									}
								?>								
							</p>
							<?	
								if ($item['text'] && !$data['feed']) {
									?>
										<span class="right">
											<a href="#" class="show-description disabled" rel="<?=$item['id'];?>">
												Показать описание
											</a> 
											<span class="arrow arrow-<?=$item['id'];?>" rel="off">↓</span>
										</span>	
									<?
								}
							?>
						</div>
						<?	
							if ($item['text']) {
								?>
									<div class="shell post description description-<?=$item['id'];?> hidden">
										<h2>
											<span class="postlink">
												<a href="#" class="disabled">
													Описание
												</a>
											</span>
										</h2>
										<br />
										<div class="posttext">
											<?=$item['text'];?>
										</div>
									</div>
								<?
							}
						?>						
					<?
				}
			?>
		</div><!-- wrapend -->				
	</div>
	
	<? 
		if(!$data['feed'] && ($sets['user']['rights'] || $url['area'] == $def['area'][1])) {
			?>
				<table width="100%">
					<tr>
						<td>
							<select name="edit_type" id="edit_type-<?=$item['id'];?>">
								<option value="title">Заголовок</option>
								<option value="text">Описание</option>
								<?
									if ($sets['user']['rights']) {
										?>
											<option value="video_link">Ссылку на видео</option>											
											<option value="author">Автора</option>	
										<?
									}
								?>
								<option value="category">Категории</option>
								<option value="tag">Теги</option>
							</select> 
							<input type="submit" value="Редактировать" class="edit" rel="<?=$item['id'];?>" />
							<?
								if ($sets['user']['rights']) {
									?>
										<td align="right">
											<div class="right">
												<form method="post" enctype="multipart/form-data">
													<input type="hidden" name="do" value="video.transfer" />
													<input type="checkbox" name="sure" />
													<input type="hidden" name="id" value="<?=$item['id'];?>" />
													<input type="submit" value="Утащить" class="submit" />
													 &nbsp; 
													<select name="where">
														<?
															foreach ($def['area'] as $area) if ($item['area'] != $area) {
																?>
																	<option value="<?=$area;?>"> <?=$lang['transfer'][$area];?></option>		
																<?
															}
														?>
														<option value="deleted"> в печь</option>															
													</select>
												</form>
											</div>
										</td>
									<?
								}
							?>
						</td>	
					</tr>	
				</table>
				<div id="loader-<?=$item['id'];?>" class="hidden center loader"><img src="<?=SITE_DIR.'/images'?>/ajax-loader.gif"></div>
				<div id="edit-<?=$item['id'];?>" rel="video" class="edit_field hidden"></div>
			<?
		} 
	?>
</div>
