<div class="shell">
	Вы просматриваете CG из игры "<?=$data['main']['pool']['name'];?>". 
	<? if (file_exists('/var/www/nameless/data/www/w8m.4otaku.ru/image/'.$data['main']['pool']['md5'].'.zip')) { ?>
		<a href="/art/download/<?=_base64_encode(hex2bin($data['main']['pool']['md5']),true);?>" target="_blank">Скачать их одним архивом.</a> (~<?=ceil($data['main']['pool']['filesize']/1024/1024);?> мб)
	<? } ?>
	<br />	
	Дополнительная информация:
	<div style="margin-left: 20px;"><?=$data['main']['pool']['text'];?></div>
</div>
<div class="booru_images">
	<?  
		if (is_array($data['main']['thumbs'])) foreach ($data['main']['thumbs'] as $key => $picture) {
			?>
			<div class="thumbnail <?=($sets['art']['largethumbs'] ? 'large_thumbnail' : 'small_thumbnail');?>">
				<a href="/art/cg_<?=$key;?>">
					<img src="http://w8m.4otaku.ru/image/<?=$data['main']['pool']['md5'];?>/<?=($sets['art']['largethumbs'] ? 'large' : 'thumb');?>/<?=$picture['md5'];?>.jpg">
				</a>					
			</div>
			<? 
		} 
	?>
</div>
