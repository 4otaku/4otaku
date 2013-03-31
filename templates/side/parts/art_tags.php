<? 
	if (is_array($data['sidebar']['art_tags'])) {
		?>
			<div class="cats">	
				<h2>
					<a href="<?=$def['site']['dir']?>/tags/<?=$url[1];?>/<?=($url['area'] == $def['area'][2] ? $url['area'] : '');?>">
						Теги
					</a>
					 <a href="#" class="bar_arrow" rel="art_tag">
						<?
							if ($sets['dir']['art_tag']) {
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
				<div id="art_tag_bar"<?=($sets['dir']['art_tag'] ? '' : ' style="display:none;"');?>>
					<ul class="art_tags">
						<?
							foreach ($data['sidebar']['art_tags'] as $tag) {
								$tag_alias = end(explode('/',$tag['alias']));
								?>
									<li>
										<?
											if ($url[2] == 'tag' || $url[2] == 'category' || $url[2] == 'author' || $url[2] == 'mixed') {
												?>
													<a href="<?=$output->mixed_add($tag_alias,'tag');?>">
														+
													</a> 
													<a href="<?=$output->mixed_add($tag_alias,'tag','-');?>">
														-
													</a> 
												<?
											}
										?>					
										<? if ($tag['description']) { ?>
											<span class="right question">
												<a 
													href="http://wiki.4otaku.org/Tag:<?=$tag_alias;?>" 
													target="_blank"
													class="with_help"
													title="Описание тега <?=str_replace('_',' ',$tag['name']);?> в вики"
												>
													(?)
												</a>
											</span>	
										<? } else { ?>
											<span class="right inactive_question">
												<a 
													href="http://wiki.4otaku.org/index.php?title=Tag:<?=$tag_alias;?>&action=edit" 
													target="_blank"
													class="with_help"
													title="Добавить описание для тега <?=str_replace('_',' ',$tag['name']);?>"
												>
													(?)
												</a>
											</span>	
										<? } ?>	
										<span class="right"><?=$tag['num'];?></span>
										<a href="<?=$def['site']['dir']?>/art/<?=$tag['alias'];?>/"<?=($tag['color'] ? ' style="color:#'.$tag['color'].';"' : '');?>>
											<?=str_replace('_',' ',$tag['name']);?>
										</a> 
									</li>
								<?
							}
						?>
					</ul>
				</div>
			</div>
		<?
	}	
