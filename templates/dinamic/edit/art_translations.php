<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=SITE_DIR?>/jss/m/?b=jss&f=edit_form.js,edit/ui.js,edit/translations.js"></script>
<br /><span class="add_translation">Чтобы добавить перевод, кликните на свободное место.</span> 
Автор перевода: <input type="text" name="author" class="translation_author margin20" value="<?=$sets['user']['name'];?>" /><br />
<? 
include_once(SITE_FDIR._SL.'templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
