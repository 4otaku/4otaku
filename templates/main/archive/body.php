<div class="car-container car-collapse">
	<a href="#" class="car-toggler" rel="closed">
		Развернуть все
	</a>
	<ul class="car-list">
		<br />
		Всего <?=$data['main']['count'];?> на 4отаку.
		<br /><br />
		<?	$i = 0;
			foreach ($data['main']['archives'] as $key => $type) { $i++
				?>
					<li>
						<img src="<?=$def['site']['dir']?>/images/tb2.gif">
						&nbsp;
						<span class="car-yearmonth car-<?=$i;?>" rel="closed">
							<?=$data['main']['name'][$key];?> 
							<span title="Всего записей">
								(<?=count($type);?>)
							</span>
						</span>
						<ul style="display: none;" class="car-monthlisting car-<?=$i;?>">
							<?
								foreach ($type as $item) {
									?>
										<li>
											<a href="<?=$def['site']['dir']?>/<?=$url[2];?>/<?=$item['id'];?>">
												<?=$item['title'];?>
											</a>.
											<?
												if ($item['comment_count']) {
													?> 
														<span title="Всего комментариев">
															Комментариев: (<?=$item['comment_count'];?>)
														</span>
													<?
												}
											?>
										</li>
									<?
								}
							?>
						</ul>
					</li>
				<?			
			}
