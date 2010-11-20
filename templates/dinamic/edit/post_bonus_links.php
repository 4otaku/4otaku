<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=SITE_DIR?>/jss/m/?b=jss&f=edit_form.js,edit/links.js,add/post.js"></script>
<table class="margin20">
	<tbody class="link_bonus">
		<?	$i = 1;
			if (is_array($data['value'])) foreach ($data['value'] as $link) {
				?>
					<tr class="link" rel="<?=$i;?>">
						<td>
							<input size="20" name="link[<?=$i;?>][name]" value="<?=$link['name'];?>" type="text">: 
							<input size="50" name="link[<?=$i;?>][link]" value="<?=$link['alias'] ? "<".$link['alias'].">": "";?><?=$link['url'];?>" type="text">
							<input type="submit" class="disabled remove_link second_button" rel="bonus" value="-"/>
						</td>
						<td class="handler">
							<img src="<?=SITE_DIR.'/images'?>/str1.png" class="arrow-up clickable" />
							<img src="<?=SITE_DIR.'/images'?>/str2.png" class="arrow-down clickable" />
						</td>						
					</tr>
				<?
				$i++;
			}
		?>
		<tr class="link hidden" rel="<?=$i;?>">
			<td>
				<input size="20" name="link[<?=$i;?>][name]" value="" type="text">: 
				<input size="50" name="link[<?=$i;?>][link]" value="" type="text">
				<input type="submit" class="disabled remove_link second_button" rel="bonus" value="-"/>
			</td>
			<td class="handler">
				<img src="<?=SITE_DIR.'/images'?>/str1.png" class="arrow-up clickable" />
				<img src="<?=SITE_DIR.'/images'?>/str2.png" class="arrow-down clickable" />
			</td>			
		</tr>			
	<tbody>
</table>
<input type="submit" class="disabled add_link left first_button" rel="bonus" value="Добавить ссылку" />
<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
