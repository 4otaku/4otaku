<div>
	<select class="dinamic_tag_page">
		<? $i=0; while (++$i <= $data['page_count']) { ?>
			<option value="<?=$i;?>"<?=($i == query::$get['current'] ? ' selected="selected"' : '');?>>
				Страница № <?=$i;?>
			</option>
		<? } ?>
	</select>
</div>
<table>
	<tr>
		<th>
			Алиас:
		</th>
		<th>
			Имя:
		</th>
		<th>
			Варианты:
		</th>
	</tr>	
	<? if (is_array($data['tags'])) foreach ($data['tags'] as $id => $item) {
		?>
			<tr>						
				<td>
					<input type="hidden" name="id" value="<?=$id;?>">
					<input type="text" value="<?=$item['alias'];?>" name="alias" size="17">
				</td>
				<td>
					<font color="#<?=$item['color'];?>">
						<input type="text" value="<?=$item['name'];?>" name="name" size="17">
					</font>
				</td>
				<td>
					<input type="text" value="<?=str_replace('|',', ',trim($item['variants'],'|'));?>" name="variants" size="17">
				</td>	
				<td>
					<input type="button" value="Выбрать" class="choose_dinamic_tag">
				</td>
			</tr>
		<?
	}
?>
</table>
