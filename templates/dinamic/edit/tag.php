<?
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=edit_form.js"></script>

<? if (false) { ?>
	<input type="text" class="input80 save_on_enter" name="tags" value="<?=implode(', ',$data['value']);?>, " />
<? } else { ?>
	<div id="tags">
		<div class="tags-loader">
			<img src="/images/ajax-loader.gif" />
		</div>
		<select data-placeholder=" "
			 multiple
			 id="chozen"
			 name="tags[]">
			<? foreach ($data['value'] as $tag) { ?>
				<option value="<?=$tag;?>" selected="selected"
					<? if ($data['colors'][$tag]) { ?>
						 style="color: #<?=$data['colors'][$tag];?>;"
					<? } ?>
				>
					<?=$tag;?>
				</option>
			<? } ?>
		</select>
	</div>

	<script type="text/javascript">
		get_tags("<?=$data['area'];?>");
	</script>
<? } ?>

<?
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
