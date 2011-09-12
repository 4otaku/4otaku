<form method='POST'>
<p>От:<br />
<input name="name" value="<?=$sets['user']['name'];?>" size="22" type="text"></p>
<p>Email:<br />
<input name="mail" value="<?=$sets['user']['mail'];?>" size="22" type="text"></p>
<p>Текст:<br />
<textarea name='body' style='width:100%;height:100px;'></textarea></p>
<input type='submit' value='Отправить' />
</form>
