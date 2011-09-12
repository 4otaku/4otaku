<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=add/text.js"></script>
<div class="shell">
	<h2 class="commentsh2">
		Редактировать комментарий:
	</h2>
	<form enctype="multipart/form-data" method="post" id="comment-form">
		<? if ($sets['user']['rights']) { ?>
			<input type="text" size="22" value="<?=$data['username'];?>" name="author">
			<span>Имя</span><br>
			<input type="text" size="22" value="***" name="mail">
			<span>E-mail</span><br>
		<? } ?>
		<table cellspacing="3px" class="bbholder right">
			<tr>
				<td><img src="<?=$def['site']['dir']?>/images/bb/bold.png" rel="b" class="bb" title="Выделить жирным" /></td>
			</tr>
			<tr>
				<td><img src="<?=$def['site']['dir']?>/images/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" /></td>
			</tr>
			<tr>
				<td><img src="<?=$def['site']['dir']?>/images/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" /></td>
			</tr>
			<tr>
				<td><img src="<?=$def['site']['dir']?>/images/bb/spoiler.png" rel="spoiler" class="bb" title="Спойлер" /></td>
			</tr>								
			<tr>
				<td><img src="<?=$def['site']['dir']?>/images/bb/picture.png" rel="img" class="bb" title="Добавить картинку" /></td>
			</tr>
			<tr>
				<td><img src="<?=$def['site']['dir']?>/images/bb/link.png" rel="url" class="bb" title="Добавить ссылку" /></td>
			</tr>
		</table>
		<div style="margin-right: 40px">
		<textarea 
			rows="10" cols="50" 
			style="width:100%; height:200px;" 
			name="text" id="textfield" 
		><?=$data['pretty_text'];?></textarea>
		</div>
		<input type="hidden" value="comment.edit" name="do">
		<input type="hidden" value="<?=query::$get['id'];?>" name="id">
		<div style="text-align: center;">
			<input type="submit" value="Редактировать" name="submit">
			<a class="comment-not-edit" href="#" rel="<?=query::$get['id'];?>">
				<input type="submit" value="Не редактировать" name="submit">
			</a>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){  
		$(".comment-not-edit").click(function(event){  
			event.preventDefault();
			$('.edit-'+$(this).attr('rel')).html("");
		});
	});	
</script>
