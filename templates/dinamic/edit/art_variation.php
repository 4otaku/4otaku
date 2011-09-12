<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=edit_form.js"></script>
<input type="hidden" name="to" value="<?=query::$get['id'];?>" />
Номер картинки или ссылка на нее: <input type="text" name="from" value="" /><br />
<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
