<script type="text/javascript" src="<?=SITE_DIR?>/jss/m/?b=jss&f=add/text.js"></script>
<h2 class="commentsh2">
	Написать комментарий:
  </h2>
  <form id="comment-form" method="post" enctype="multipart/form-data">
	  <table>
		  <tr>
			  <td colspan="2">								
				<input name="name" value="<?=$sets['user']['name'];?>" size="22" type="text">
				<span>Имя</span>
			</td>
		  </tr>
		  <tr>
			  <td colspan="2">						
				<input name="mail" value="<?=$sets['user']['mail'];?>" size="22" type="text">
				<span>E-mail (не публикуется)</span>
			  </td>
		</tr>
		<tr>
			<td>						
				<textarea id="textfield" name="text" cols="70" rows="10"></textarea>
			</td>
			<td valign="top">
				<table cellspacing="3px" class="bbholder">
					<tr>
						<td>
							<img src="<?=SITE_DIR.'/images'?>/bb/bold.png" rel="b" class="bb" title="Выделить жирным" />
						</td>
					</tr>
					<tr>
						<td>
							<img src="<?=SITE_DIR.'/images'?>/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" />
						</td>
					</tr>
					<tr>
						<td>
							<img src="<?=SITE_DIR.'/images'?>/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" />
						</td>
					</tr>
					<tr>
						<td>
							<img src="<?=SITE_DIR.'/images'?>/bb/spoiler.png" rel="spoiler" class="bb" title="Спойлер" />
						</td>
					</tr>							
					<tr>
						<td>
							<img src="<?=SITE_DIR.'/images'?>/bb/picture.png" rel="img" class="bb" title="Добавить картинку" />
						</td>
					</tr>
					<tr>
						<td>
							<img src="<?=SITE_DIR.'/images'?>/bb/link.png" rel="url" class="bb" title="Добавить ссылку" />
						</td>
					</tr>
				</table>
			</td>
		  </tr>
		  <tr>
			<td colspan="2">						
				<input name="do" value="comment.add" type="hidden">
				<input type="hidden" name="remember" value="true" />				
				<input name="parent" value="0" type="hidden" id="comment-parent">
				<input name="submit" value="Отправить комментарий" type="submit">
			</td>
		  </tr>
	</table>
</form>
<div id="comment-main" class="hidden">
	<br /><br />
	<a href="#" class="disabled">Не отвечать</a>
</div>
