<div class="shell">
	<h2>
		Объеднить галереи
	</h2>
	<form enctype="multipart/form-data" method="post">
		<input type="hidden" name="do" value="admin.pack_join">
		
		<input type="hidden" name="id" value="<?=$url[3];?>">
		<table width="100%">
			Куда вливаем: <select name="parent">
			<? foreach ($data['main']['list'] as $id => $gal) { ?>
					<option value="<?=$id;?>"><?=$gal;?></option>
			<? } ?><br /><br />
			</select>; 
			Кого сливаем: <select name="child">
			<? foreach ($data['main']['list'] as $id => $gal) { ?>
					<option value="<?=$id;?>"><?=$gal;?></option>
			<? } ?>
			</select> 			 
		</table>
		<br /><br />
		<input type="submit" value="Объединить">
	</form>	
</div>

