<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'top.php');
?>
<script type="text/javascript" src="/jss/m/?b=jss&f=edit_form.js"></script>
<div class="right">
	Подписаться на уведомления? 
	<input type="checkbox" name="subscribe"<?=($data['value']['spam'] ? ' checked' : '');?>>
</div>
<input type="text" class="input60" name="mail" value="<?=$data['value']['email'];?>" />
<? 
include_once('templates'.SL.'dinamic'.SL.'edit'.SL.'bottom.php');
?>
