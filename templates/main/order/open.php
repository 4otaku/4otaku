<div class="shell">
	<div class="post">
		<h2>
			В розыске
		</h2>
		<div class="entry">				
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td>
						<ul class="car-list">
							<?
								foreach ($data['main']['order_open'] as $name => $category) {
									?>
										<li>
											<img src="<?=SITE_DIR.'/images'?>/tb2.gif"> 
											<span rel="<?=($sets['order'][$name] ? 'open' : 'closed' );?>" class="car-yearmonth car-<?=$name;?> remember <?=$name;?>">
												Заказы в категории <?=$data['main']['category'][$name];?> 
												<span title="Всего заказов">
													(<?=count($category);?>)
												</span>
											</span>
											<br />
											<ul class="car-monthlisting car-<?=$name;?>"<?=($sets['order'][$name] ? '' : ' style="display:none;"');?>>
												<li>&nbsp;</li>
												<?
													foreach ($category as $order) {
														?>
															<li>
																<?=$order['username'];?> заказал 
																<a href="<?=SITE_DIR.'/order'?>/<?=$order['id'];?>" class="with_help" title="<?=strip_tags($order['text']);?>">
																	<?=$order['title'];?>
																</a>. 
																(Комментариев: <?=$order['comment_count'];?>).
															</li>
														<?
													}
												?>
												<li>
													&nbsp;
												</li>
												<li>
													<a href="<?=SITE_DIR.'/order'?>/category/<?=$name;?>/">
														Все заказы в категории "<?=$data['main']['category'][$name];?>"
													</a>
												</li>
											</ul>
											<br />
									<?
								}
							?>
						</ul>
						<br />
						<a href="<?=SITE_DIR.'/order'?>/all/">Все заказы</a>
					</td>
				</tr>
			</table>		
		</div>
	</div>
</div>
