<div class="shell">
	Вы просматриваете CG из игры "<?=$data['main']['pool']['title'];?>". 
	<? if ($data['main']['pool']['weight'] > 0) { ?>
		<a href="<?=$def['site']['dir']?>/art/download/pack/<?=$data['main']['pool']['id'];?>/" target="_blank">Скачать их одним архивом.</a> (~<?=ceil($data['main']['pool']['weight']/1024/1024);?> мб)
	<? } ?><br />	
	<br />	
	Дополнительная информация:
	<div style="margin-left: 20px;"><?=$data['main']['pool']['text'];?></div>
</div>
