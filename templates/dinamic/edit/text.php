<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=SITE_DIR?>/jss/m/?b=jss&f=edit_form.js,add/text.js"></script>
<textarea name="text" rows="8" class="input80 left" id="textfield"><?=$data['value'];?></textarea>
<table cellspacing="3px" class="bbholder">
	<tr>
		<td><img src="<?=SITE_DIR.'/images'?>/bb/bold.png" rel="b" class="bb" title="Выделить жирным" /></td>
	</tr>
	<tr>
		<td><img src="<?=SITE_DIR.'/images'?>/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" /></td>
	</tr>
	<tr>
		<td><img src="<?=SITE_DIR.'/images'?>/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" /></td>
	</tr>
	<tr>
		<td><img src="<?=SITE_DIR.'/images'?>/bb/spoiler.png" rel="spoiler" class="bb" title="Спойлер" /></td>
	</tr>				
	<tr>
		<td><img src="<?=SITE_DIR.'/images'?>/bb/picture.png" rel="img" class="bb" title="Добавить картинку" /></td>
	</tr>
	<tr>
		<td><img src="<?=SITE_DIR.'/images'?>/bb/link.png" rel="url" class="bb" title="Добавить ссылку" /></td>
	</tr>
</table>
<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
