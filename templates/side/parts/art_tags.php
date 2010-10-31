<? 
	if (is_array($data['sidebar']['art_tags'])) {
		?>
			<div class="cats">	
				<h2>
					<a href="/tags/<?=$url[1];?>/<?=($url['area'] == $def['area'][2] ? $url['area'] : '');?>">
						Теги
					</a>
					 <a href="#" class="bar_arrow" rel="art_tag">
						<?
							if ($sets['dir']['art_tag']) {
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
				<div id="art_tag_bar"<?=($sets['dir']['art_tag'] ? '' : ' style="display:none;"');?>>
					<ul class="art_tags">
						<?
							foreach ($data['sidebar']['art_tags'] as $tag) {
								?>
									<li>
										<?
											if ($url[2] == 'tag' || $url[2] == 'category' || $url[2] == 'author' || $url[2] == 'mixed') {
												?>
													<a href="<?=$output->mixed_add($tag['alias'],'tag');?>">
														+
													</a> 
													<a href="<?=$output->mixed_add($tag['alias'],'tag','-');?>">
														-
													</a> 
												<?
											}
										?>					
										<a href="/art/tag/<?=$tag['alias'];?>/"<?=($tag['color'] ? ' style="color:#'.$tag['color'].';"' : '');?>>
											<?=str_replace('_',' ',$tag['name']);?>
										</a> 
										<span>
											<?=$tag['num'];?>
										</span>
									</li>
								<?
							}
						?>
					</ul>
				</div>
			</div>
		<?
	}	
