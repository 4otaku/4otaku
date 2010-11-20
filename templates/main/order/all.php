<div class="shell">
	<div class="post">
		<h2>
			В розыске
		</h2>
		<div class="entry">				
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td>
						<ol class="orders_list">
							<?
								$i = key($data['main']['orders']);
								while ($data['main']['orders'][$i]['area'] == 'workshop') {
									?>
										<li>
											<?=$data['main']['orders'][$i]['username'];?> заказал 
											<a href="/order/<?=$data['main']['orders'][$i]['id'];?>">
												<?=$data['main']['orders'][$i]['title'];?>
											</a>. 
											(Комментариев: <?=$data['main']['orders'][$i]['comment_count'];?>).
										</li>
									<?
									$i++;
								}
							?>
						</ol>
					</td>
				</tr>
			</table>		
		</div>
	</div>	
</div>
<div class="shell">
	<div class="post">
		<h2>
			Выполненные
		</h2>
		<div class="entry">				
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td>
						<ol class="orders_list">
							<?
								while ($data['main']['orders'][$i]['area'] == 'main') {
									?>
										<li>
											<?=$data['main']['orders'][$i]['username'];?> заказал 
											<a href="/order/<?=$data['main']['orders'][$i]['id'];?>">
												<?=$data['main']['orders'][$i]['title'];?>
											</a>. 
											(Комментариев: <?=$data['main']['orders'][$i]['comment_count'];?>).
											&nbsp;&nbsp;&nbsp;
											<nobr>
												[<a href="<?=$data['main']['orders'][$i]['link'];?>">
													Ссылка на выполненный заказ
												</a>.]
											</nobr>
										</li>
									<?
									$i++;
								}
							?>
						</ol>
					</td>
				</tr>
			</table>		
		</div>
	</div>	
</div>
<div class="shell">
	<div class="post">
		<h2>
			Закрытые (не выполненные)
		</h2>
		<div class="entry">				
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td>
						<ol class="orders_list">
							<?
								while ($data['main']['orders'][$i]['area'] == 'flea_market') {
									?>
										<li>
											<?=$data['main']['orders'][$i]['username'];?> заказал 
											<a href="/order/<?=$data['main']['orders'][$i]['id'];?>">
												<?=$data['main']['orders'][$i]['title'];?>
											</a>. 
											(Комментариев: <?=$data['main']['orders'][$i]['comment_count'];?>).
										</li>
									<?
									$i++;
								}
							?>
						</ol>
					</td>
				</tr>
			</table>		
		</div>
	</div>	
</div>
