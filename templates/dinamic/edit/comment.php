<h2 class="commentsh2">
	Редактировать комментарий:
</h2>
<form enctype="multipart/form-data" method="post" id="comment-form">
	<input type="text" size="22" value="<?=$data['username'];?>" name="author">
	<span>Имя</span><br>
	<input type="text" size="22" value="***" name="mail">
	<span>E-mail</span><br>
	<textarea rows="10" cols="50" name="text"><?=$data['pretty_text'];?></textarea><br>
	<input type="hidden" value="comment.edit" name="do">
	<input type="hidden" value="<?=query::$get['id'];?>" name="id">
	<input type="submit" value="Редактировать" name="submit">
	<a class="comment-not-edit" href="#" rel="<?=query::$get['id'];?>">
		<input type="submit" value="Не редактировать" name="submit">
	</a>
</form>
<script type="text/javascript">
	$(document).ready(function(){  
		$(".comment-not-edit").click(function(event){  
			event.preventDefault();
			$('.edit-'+$(this).attr('rel')).html("");
		});
	});	
</script>
