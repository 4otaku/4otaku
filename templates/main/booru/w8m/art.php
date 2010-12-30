<div class="mini-shell">
	Вы просматриваете CG из игры "<a href="/art/cg_packs/<?=$data['main']['gallery']['id'];?>/"><?=$data['main']['gallery']['name'];?></a>". 
	<? if (file_exists('/var/www/nameless/data/www/w8m.4otaku.ru/image/'.$data['main']['gallery']['md5'].'.zip')) { ?>
		<a href="/art/download/<?=_base64_encode(hex2bin($data['main']['gallery']['md5']),true);?>" target="_blank">Скачать их одним архивом.</a> (~<?=ceil($data['main']['gallery']['filesize']/1024/1024);?> мб)
	<? } ?><br />
	Имя файла: <a href="/art/download/<?=_base64_encode(hex2bin($data['main']['art']['md5']),true);?>" target="_blank"><?=$data['main']['art']['filename'];?></a> 
</div>
<br />
<div class="post">
	<div class="innerwrap">
		<table width="100%">
			<tr>
				<td class="booru_main"> 
					<? if ($data['main']['art']['resized'] && $sets['art']['resized']) { ?>
						<div class="booru_show_full_container clear margin20">
							<span>
								Изображение было уменьшено. 
							</span>
							<a href="#" class="disabled booru_show_toggle" rel="<?=$data['main']['art']['ext'];?>">
								Показать в полном размере
							</a>. 
							<a href="#" class="disabled booru_show_full_always">
								 Всегда показывать в полном размере
							</a>.
						</div>
						<div class="booru_img image booru" rel="resized">
							<a href="/art/download/<?=_base64_encode(hex2bin($data['main']['art']['md5']),true);?>" target="_blank">
								<img src="http://w8m.4otaku.ru/image/<?=$data['main']['gallery']['md5'];?>/resized/<?=$data['main']['art']['md5'];?>.jpg">
							</a>	
						</div>						
					<? } else { ?>
					<div class="image">							
						<a href="/art/download/<?=_base64_encode(hex2bin($data['main']['art']['md5']),true);?>" target="_blank">			
							<img src="http://w8m.4otaku.ru/image/<?=$data['main']['gallery']['md5'];?>/full/<?=$data['main']['art']['md5'];?>.<?=$data['main']['art']['ext'];?>">
						</a>
					</div>
					<? } ?>
				</td>
			</tr>
		</table>
	</div>
</div>	
<hr />
