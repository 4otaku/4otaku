<h2 class="commentsh2">
	Подписаться на комментарии.
</h2>
<form method="post" enctype="multipart/form-data">
	E-mail: <input name="email" value="<?=sets::get('user','mail');?>" size="22" type="text" />
	<br /><br />
	<select name="rule_type" class="subscribe_type">
		<option value="all" class="selected">Подписаться на все</option>
		<option value="author">Подписаться на автора</option>
		<option value="category">Подписаться на категорию</option>
		<option value="language">Подписаться на язык</option>
	</select> 
	<span class="subscribe_author subscribe_field hidden">
		<select>
			<? 
				foreach($data['main']['author'] as $alias => $name) {
					?>
						<option value="<?=$alias;?>"><?=$name;?></option>
					<?
				}
			?>
		</select>
	</span>
	<span class="subscribe_category subscribe_field hidden">
		<select>
			<? 
				foreach($data['main']['category'] as $alias => $name) {
					?>
						<option value="<?=$alias;?>"><?=$name;?></option>
					<?
				}
			?>
		</select>
	</span>
	<span class="subscribe_language subscribe_field hidden">
		<select>
			<? 
				foreach($data['main']['language'] as $alias => $name) {
					?>
						<option value="<?=$alias;?>"><?=$name;?></option>
					<?
				}
			?>
		</select>
	</span> 
	<select name="area">
		<option value="post" class="selected">в записях</option>
		<option value="video">в разделе видео</option>
		<option value="art">в разделе арта</option>
		<option value="order">в столе заказов</option>
		<option value="news">в новостях</option>
	</select>	
	<br /><br />
	<input name="do" value="comment.subscription" type="hidden" />
	<input type="hidden" name="remember" value="true" />
	<input name="submit" value="Подписаться" type="submit" />
</form>	