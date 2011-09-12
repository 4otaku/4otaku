<div class="shell">
	<div class="post" id="post-<?=$item['id'];?>">
		<div class="innerwrap">
			<table width="100%">
				<tr>
					<td align="left">
						<h2>
							<a href="<?=$data['feed']['domain'];?>/order/<?=$item['id'];?>/" title="<?=$item['title'];?>">
								Ищу: <?=($data['feed']['domain'] ? mb_substr($item['title'],7) : $item['title']);?>
							</a>
						</h2>
					</td>
				</tr>
			</table>		
			<div class="entry">				
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td class="imageholder">
							<img alt='' src='http://www.gravatar.com/avatar/<?=md5(strtolower($item['email']));?>?s=70&d=identicon&r=G' />
						</td>
						<td valign="top">
							<p>
								<?=$item['text'];?>
							</p>
							<?
								if ($item['link']) {
									?>
										<p>
											Ссылка на найденное: <a href="<?=$item['link'];?>"><?=($item['link']{0} == '/' ? 'http://4otaku.ru'.$item['link'] : $item['link']);?></a>.
										</p>
									<?
								}
							?>
						</td>
					</tr>
				</table>
			</div>
			<?
				if (!$data['feed']) {
					?>			
						<div class="wrapper">
							<p class="meta">
								<?=$item['pretty_date'];?> | 
								Оставил заказ: <?=$item['username'];?> | 
								<?
									if (count($item['category']) > 1) {
										?>
											Категории: 
										<?
									}
									else {
										?>
											Категория: 
										<?
									}
									foreach ($item['category'] as $key => $meta) {
										if ($nonfirst) {
											?>
											, 
											<?
										}	else $nonfirst = true;
										?>
											<a href="<?=$def['site']['dir']?>/order/category/<?=$key;?>/">
												<?=$meta;?>
											</a>
										<?
									}	unset($nonfirst);
								?> | 
								Статус:  <?=$lang['status'][$item['area']];?>
								<?
									if ($sets['user']['rights']) {
										?>
											 | 
											<a href="<?=$def['site']['dir']?>/admin/revisions/order/<?=$item['id'];?>/">История версий</a>	
										<?
									}					
								?>.
							</p>
						</div>	
					<?
				}
			?>								
		</div><!-- wrapend -->			
	</div>
	<? 
		if(!$data['feed'] && $sets['user']['rights']) {
			?>
				<br />
				<div class="left first_button">
					<select name="edit_type" id="edit_type-<?=$item['id'];?>">
						<option value="title">Заголовок</option>
						<option value="text">Описание</option>
						<option value="orders_username">Имя пользователя</option>
						<option value="orders_mail">Е-мейл и подписку</option>													
						<option value="category">Категории</option>
					</select> 
					<input type="submit" value="Редактировать" class="edit" rel="<?=$item['id'];?>" />
				</div>
				<div class="right">
					<form method="post" enctype="multipart/form-data">
						<input type="hidden" name="do" value="order.transfer" />
						<input type="hidden" name="type" value="orders" />
						<input type="checkbox" name="sure" class="hidden" checked="checked" />
						<input type="hidden" name="id" value="<?=$item['id'];?>" />
						<input type="submit" value="Изменить статус" class="submit" />
						 &nbsp; 
						<select name="where">
							<?
								foreach ($def['area'] as $area) if ($item['area'] != $area) {
									?>
										<option value="<?=$area;?>"> <?=$lang['status'][$area];?></option>	
									<?
								}
							?>
							<option value="deleted"> Удалено</option>
						</select>
					</form>
				</div>	
				<div class="first_button">
					<form id="addpost" method="post" enctype="multipart/form-data">
						<input size="20" name="link" value="" type="text">
						<input class="submit" value="Дать ссылку" type="submit">
						<input type="hidden" name="do" value="order.change_link">
						<input type="hidden" name="id" value="<?=$item['id'];?>">
					</form>
				</div>
				<div id="loader-<?=$item['id'];?>" class="hidden center loader"><img src="<?=$def['site']['dir']?>/images/ajax-loader.gif"></div>
				<div id="edit-<?=$item['id'];?>" rel="orders" class="edit_field hidden"></div>								
				<div class="clear"></div>
			<?
		}
	?>
</div>
