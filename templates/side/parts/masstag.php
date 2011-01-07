<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'на премодерацию',
		$def['area'][2] => 'в барахолку'
	);

	if (is_array($data['main']['display']) && in_array('booru_page',$data['main']['display']))
		{
			?>
				<div class="cats">	
					<h2>
						<a href="#" class="bar_arrow" rel="masstag">
							MassTag
						</a>
						 <a href="#" class="bar_arrow" rel="masstag">
							<?
								if ($sets['dir']['masstag']) {
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
					<div id="masstag_bar"<?=($sets['dir']['masstag'] ? '' : ' style="display:none;"');?>>
						<span style="font-size:90%;">
							Выберите категорию, или напишите теги. После это клик по картинке станет не переходом на нее, а добавлением этих тегов/категории.
						</span>	
						<br /><br />
						Режим:
						<span class="right">
							<select name="sign" id="MassTag9001_sign">
								<option value="add_tag" rel="tag">+ теги</option>
								<? if ($sets['user']['rights']) { ?>
									<option value="danbooru" rel="danbooru">+ теги с</option>
									<option value="substract_tag" rel="tag">- теги</option>
								<? } ?>
								<option value="add_category" rel="cat">+ категория</option>
								<? if ($sets['user']['rights']) { ?>
									<option value="substract_category" rel="cat">- категория</option>
									<option value="transfer" rel="transfer">отправить</option>
								<? } ?>
							</select>
						</span>
						<br />
						<input type="text" name="tags" class="MassTag9001 MassTag9001_tag" />
						<select class="MassTag9001 hidden MassTag9001_cat">
							<? 
								foreach($data['sidebar']['masstag'] as $alias => $name) {
									?>
										<option value="<?=$alias;?>"><?=$name;?></option>
									<?
								}
							?>						
						</select>
						<select class="MassTag9001 hidden MassTag9001_transfer">
							<?
								foreach ($def['area'] as $area) if ($item['area'] != $area) {
									?>
										<option value="<?=$area;?>"> <?=$lang['transfer'][$area];?></option>	
									<?
								}
							?>
							<option value="deleted"> в печь</option>						
						</select>
						<select class="MassTag9001 hidden MassTag9001_danbooru">
							<option value="danbtag">danbooru</option>	
							<option value="iqdb">iqdb</option>							
						</select>
					</div>
				</div>			
			<?		
		}
