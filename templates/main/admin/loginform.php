<style>
	#sidebar {
		display:none;
	}
</style>
<div style="margin-bottom:450px">
	Залогиниться: 
	<form enctype="multipart/form-data" method="post">
		<input type="hidden" name="do" value="admin.login">
		<table>
			<tr>
				<td>
					Логин: 
				</td>
				<td>
					<input type="text" name="login">
				</td>
			</tr>
			<tr>
				<td>
					Пароль: 
				</td>
				<td>
					<input type="password" name="pass">
				</td>
			</tr>
		</table>
		<input type="submit" value="Вперед">
	</form>
</div>
