<div id="navi_bottom">
	<code>
		<ul>
			<li class="page_info">
				<? 
					if (is_numeric($data['main']['navi']['curr'])) {
						?>
						<?=($data['main']['navi']['name'] ? $data['main']['navi']['name'] : 'Страница');?> <?=$data['main']['navi']['curr'];?> из <?=$data['main']['navi']['last'];?>
						<?
					}
					else {
						?>
							Отображаются все комментарии
						<?
					}
				?>
			</li>
			<?
				if ($data['main']['navi']['curr']==2) {
					?>
						<li>
							<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>">
								&lt;
							</a>
						</li>
					<?
				}
				elseif ($data['main']['navi']['curr'] > 2) {
					?>
						<li>
							<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>page/<?=($data['main']['navi']['curr']-1);?>/">
								&lt;
							</a>
						</li>
					<?
				}
				if ($data['main']['navi']['curr']==1) {
					?>
						<li class="active_page">
							<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>">
								1
							</a>
						</li>						
					<?
				}
				else {
					?>
						<li class="first_last_page">
							<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>">
								1
							</a>
						</li>	
					<?
				}
				if ($data['main']['navi']['curr']>7) {
					?>
						<li class="space">
							...
						</li>
					<?
				}
				while ($data['main']['navi']['start'] < $data['main']['navi']['curr'] + 6 && $data['main']['navi']['start'] < $data['main']['navi']['last']) {
					if ($data['main']['navi']['start'] == $data['main']['navi']['curr']) {
						?>
							<li class="active_page">
								<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>page/<?=$data['main']['navi']['start'];?>/">
									<?=$data['main']['navi']['start'];?>
								</a>
							</li>
						<?					
					}
					else {
						?>
							<li>
								<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>page/<?=$data['main']['navi']['start'];?>/">
									<?=$data['main']['navi']['start'];?>
								</a>
							</li>
						<?
					}
					$data['main']['navi']['start']++; 
				}
				if ($data['main']['navi']['curr'] < $data['main']['navi']['last'] - 6) {
					?>
						<li class="space">
							...
						</li>
					<?
				}
				if ($data['main']['navi']['last'] > 1)
					if ($data['main']['navi']['last'] == $data['main']['navi']['curr']) {
						?>
							<li class="active_page">
								<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>page/<?=$data['main']['navi']['last'];?>/">
									<?=$data['main']['navi']['last'];?>
								</a>
							</li>
						<?
					}
					else {
						?>
							<li class="first_last_page">
								<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>page/<?=$data['main']['navi']['last'];?>/">
									<?=$data['main']['navi']['last'];?>
								</a>
							</li>
							<?
								if (is_numeric($data['main']['navi']['curr'])) {
									?>
										<li>
											<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>page/<?=($data['main']['navi']['curr']+1);?>/">
												&gt;
											</a>
										</li>
									<?
								}
							?>
						<?
					}
				if ($data['main']['navi']['all']) {
					?>
						<li class="space">
							
						</li>
						<li<?=($data['main']['navi']['curr'] == 'all' ? ' class="active_page"' : "");?>>
							<a href="<?=$data['main']['navi']['base'].$data['main']['navi']['meta'];?>all/">
								Показать все
							</a>
						</li>											
					<?
				}
			?>
		</ul>
		<div>
			&nbsp;
		</div>
	</code>
</div>
