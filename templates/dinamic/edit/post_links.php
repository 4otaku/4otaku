<? 
include_once('templates/dinamic/edit/top.php');
?>
<script type="text/javascript" src="/jss/m/?b=jss&f=edit_form.js,edit/links.js,add/post.js"></script>
<table class="margin20">
	<tbody class="link_main">
		<?	$i = 1;
			if (is_array($data['value'])) foreach ($data['value'] as $link) {
				if (!is_array($link['url'])) {$link['url'] = array($link['url']); $link['alias'] = array($link['alias']);}
				foreach ($link['url'] as $key => $oneurl) { 					
					?>
						<tr class="link" rel="<?=$i;?>">
							<td>
								<input size="20" name="link[<?=$i;?>][name]" value="<?=$link['name'];?>" type="text">: 
								<input size="50" name="link[<?=$i;?>][link]" value="<<?=$link['alias'][$key];?>><?=$oneurl;?>" type="text">
								~(<input size="2" name="link[<?=$i;?>][size]" value="<?=$link['size'];?>" type="text">
								<select name="link[<?=$i;?>][sizetype]">
									<option value="кб"<?=($link['sizetype']=='кб' ? ' selected' : '');?>>кб</option>
									<option value="мб"<?=($link['sizetype']=='мб' ? ' selected' : '');?>>мб</option>
									<option value="гб"<?=($link['sizetype']=='гб' ? ' selected' : '');?>>гб</option>
								</select>
								)
								<input type="submit" class="disabled remove_link second_button" rel="main" value="-"/>
							</td>
							<td class="handler">
								<img src="/images/str1.png" class="arrow-up clickable" />
								<img src="/images/str2.png" class="arrow-down clickable" />
							</td>
						</tr>
					<?
					$i++;
				} 
			}
		?>
	<tbody>
</table>
<input type="submit" class="disabled add_link left first_button" rel="main" value="Добавить ссылку" />
<? 
include_once('templates/dinamic/edit/bottom.php');
?>
