<div class="car-container car-collapse">
	<a href="#" class="car-toggler" rel="closed">
		Развернуть все
	</a>
	<ul class="car-list">
		<br />
		Всего <?=$data['main']['count'];?> на 4отаку.
		<br /><br />
		<?	$i = 0;
			foreach ($data['main']['archives'] as $yearkey => $year) {
				?>
					<li><br /></li>
					<li><b><?=$yearkey;?></b></li>
					<li><br /></li>
					<?
						foreach ($year as $monthkey => $month) { $i++;
							?>
								<li>
									<img src="<?=$def['site']['dir']?>/images/tb2.gif">
									&nbsp;
									<span class="car-yearmonth car-<?=$i;?>" rel="closed">
										<?=$monthkey;?> 
										<span title="Всего записей">
											(<?=count($month);?>)
										</span>
									</span>
									<ul style="display: none;" class="car-monthlisting car-<?=$i;?>">
										<?
											foreach ($month as $daykey => $day) {
												?>
													<li>
														<?=$daykey;?> числа: добавлено 
														<a href="<?=$def['site']['dir']?>/<?=$url[2];?>/date/<?=$yearkey;?>-<?=obj::transform('text')->rumonth($monthkey);?>-<?=$daykey;?>/">
															<?=$day;?> артов.
														</a>
													</li>
												<?
											}
										?>
									</ul>
								</li>
							<?
						}
					?>
				<?			
			}
