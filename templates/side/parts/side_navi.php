<div class="cats">
	<h2>
		<span class="href">
			Навигация
		</span> 
		<a href="#" class="bar_arrow" rel="navi">
			<?
				if ($sets['dir']['navi']) {
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
	<div id="navi_bar"<?=($sets['dir']['navi'] ? '' : ' style="display:none;"');?>>
		<table width="100%"<?=($url['area'] != 'main' ? ' rel="post/'.$url['area'].'"' : 'rel="post"');?>>
			<?
				foreach ($data['sidebar']['side_navi'] as $row) {
					?>
						<tr>
							<?
								foreach ($row as $cell) {
									?>
										<td width="50%">
											<?
												if ($cell) {
													?>
														<a class="shifter" href="<?=($url['area'] != 'main' ? '/'.$url['area'] : '');?>/category/<?=$cell['alias'];?>" rel="<?=$cell['alias'];?>">
															<?=$cell['name'];?>
														</a>
													<?
												}
												else {
													?>
														&nbsp;
													<?
												}
											?>
										</td>
									<?
								}
							?>
						</tr>
					<?
				}
			?>
		</table>
		<table>	
			<tr>
				<td>
					<a href="#" class="shift-switcher disabled" rel="russian" title="<?=($sets['flag']['ru'] != 'off' ? 'on' : 'off');?>">
						<img src="<?=$def['site']['dir']?>/images/ru.<?=($sets['flag']['ru'] != 'off' ? 'png' : 'gif');?>">
					</a>
				</td>
				<td>
					<a href="#" class="shift-switcher disabled" rel="english" title="<?=($sets['flag']['en'] != 'off' ? 'on' : 'off');?>">
						<img src="<?=$def['site']['dir']?>/images/en.<?=($sets['flag']['en'] != 'off' ? 'png' : 'gif');?>">
					</a>
				</td>
				<td>
					<a href="#" class="shift-switcher disabled" rel="japanese" title="<?=($sets['flag']['jp'] != 'off' ? 'on' : 'off');?>">
						<img src="<?=$def['site']['dir']?>/images/jp.<?=($sets['flag']['jp'] != 'off' ? 'png' : 'gif');?>">
					</a>
				</td>
				<td>
					<a href="#" class="shift-switcher disabled" rel="nolanguage" title="<?=($sets['flag']['no'] != 'off' ? 'on' : 'off');?>">
						<img src="<?=$def['site']['dir']?>/images/no.<?=($sets['flag']['no'] != 'off' ? 'png' : 'gif');?>">
					</a>
				</td>
			</tr>
		</table>
	</div>
</div>
