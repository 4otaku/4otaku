<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=edit_form.js"></script>
<div>
	<?
		foreach ($data['value'] as $category) {
			?>
				<select name="category[]" class="left">
					<? 
						foreach($data['categories'] as $alias => $name) {
							?>
								<option value="<?=$alias;?>"<?=($category == $alias ? ' selected' : '');?>><?=$name;?></option>
							<?
						}
					?>
				</select>
			<?
		}
	?>
	<input type="submit" class="disabled sign add_meta" value="+" />
	<input type="submit" class="disabled<?=(count($data['value']) < 2 ? ' hidden' : '');?> sign remove_meta" value="-" />
</div>
<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
