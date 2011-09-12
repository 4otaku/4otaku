<form method='POST'>
<p>Название:<br />
<input type='text' style='width:100%' name='title' /></p>
<p>От:<br />
<input name="name" value="<?=$sets['user']['name'];?>" size="22" type="text"></p>
<p>Email:<br />
<input name="mail" value="<?=$sets['user']['mail'];?>" size="22" type="text"></p>
<p>Описание:<br />
<textarea name='body' style='width:100%;height:200px;'></textarea></p>
<input type='submit' value='Отправить' />
</form>
