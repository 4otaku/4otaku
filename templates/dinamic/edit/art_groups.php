<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=edit_form.js"></script>
<table width="100%">
	<tr>
		<td>
			<div>
				<select name="group[]" class="left">
					<? 
						foreach($data as $alias => $name) {
							?>
								<option value="<?=$alias;?>"><?=$name;?></option>
							<?
						}
					?>
				</select>
				<input type="submit" class="disabled sign add_meta" value="+" />
				<input type="submit" class="disabled hidden sign remove_meta" value="-" />
			</div>
		</td>
	<tr>
	<tr>
		<td>
			Пароль (для закрытых групп) 
			<input type="text" value="" name="password">
		</td>
	</tr>
</table>
<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
