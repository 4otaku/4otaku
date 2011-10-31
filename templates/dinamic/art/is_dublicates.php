<b>
	Загруженные вами изображения выглядят очень похоже. Добавить их как несколько версий одной картинки?
</b>
<select name="dublicates">
	<option value="0" class="selected">Нет, не надо</option>
	<? for($i = 1; $i <= $data; $i++) { ?>
		<option value="<?=$i;?>" class="selected">Да, и главная картинка это № <?=$i;?></option>
	<? } ?>
</select>
