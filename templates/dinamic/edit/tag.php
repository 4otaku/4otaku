<? if (sets::edit('newtags')) { ?>
	<div class="left">
		<input type="submit" value="Сохранить" name="save" rel="<?=(is_numeric($get['num']) ? 1 : 0);?>" class="disabled save_changes" />
		<input type="submit" value="Не сохранять" name="nosave" class="disabled drop_changes second_button" />
		<input type="submit" value="Очистить" class="disabled clear_tags second_button" />
	</div>
<? } ?>		
		
<?
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=edit_form.js"></script>


<? if (!sets::edit('newtags')) { ?>
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

	</form>
	<? if (!sets::edit('newtags')) { ?>
		<div class="left">
			<input type="submit" value="Сохранить" name="save" rel="<?=(is_numeric($get['num']) ? 1 : 0);?>" class="disabled save_changes" />
			<input type="submit" value="Не сохранять" name="nosave" class="disabled drop_changes second_button" />
		</div>
	<? } ?>
	<div class="clear"></div>
</div>
