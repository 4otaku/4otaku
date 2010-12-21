<?	

	include_once 'common.php';
	
	if ($sizefile<$def['post']['filesize']) {
		$time = str_replace('.','',microtime(true));
		$extension =  pathinfo($file, PATHINFO_EXTENSION);
		$filename = substr(obj::transform('meta')->make_alias(pathinfo($file,PATHINFO_FILENAME)),0,200);
		if ($sizefile > 1048576) $sizefile = str_replace('.',',',round(($sizefile / 1048576), 1)).' мб';
		elseif ($sizefile > 1024) $sizefile = str_replace('.',',',round(($sizefile / 1024), 1)).' кб';
		else $sizefile .= ' байт';				
		mkdir(ROOT_DIR.SL.'files'.SL.$time, 0755);
		$newfile = ROOT_DIR.SL.'files'.SL.$time.SL.$filename.'.'.$extension;		
		chmod($temp, 0755);
		move_uploaded_file($temp, $newfile);
				
		if (is_array($check)) {
			$newthumb = ROOT_DIR.SL.'files'.SL.$time.SL.'thumb_'.$filename.'.'.$extension;
			$imagick =  new $image_class($path = $newfile);
			scale(200,$newthumb);
			$type = 'image';
			$name = 'Сэмпл ('.$extension.')';
			?>
				<input type="hidden" name="file[0][height]" value="<?=$imagick->getImageHeight();?>" />
			<?
		}
		elseif ($extension == 'mp3') {
			$type = 'audio';
			$name = 'Сэмпл';
			if (function_exists('id3_get_version')) {
				if (id3_get_version($newfile) & ID3_V1_0) $version = ID3_V1_0;
				if (id3_get_version($newfile) & ID3_V1_1) $version = ID3_V1_1;
				if (id3_get_version($newfile) & ID3_V2) $version = ID3_V2;
				if (id3_get_version($newfile)) {
					$tags = id3_get_tag($newfile, $version);
					if ($tags['artist']) $tags['artist'] = convert_to($tags['artist'],'UTF-8');
					if ($tags['title']) $tags['title'] = convert_to($tags['title'],'UTF-8');
					if ($tags['title'] || $tags['artist']) {
						$name = ($tags['artist'] ? $tags['artist'] : 'Неизвестный исполнитель').' - '.($tags['title'] ? $tags['title'] : 'неизвестная композиция');
					}
				} 
			} 
		}
		else {
			$type = 'plain';
			$name = "Новый файл";
		}
		
		?>
			<input size="24%" type="text" name="file[0][name]" value="<?=$name;?>" />:
			<input readonly size="24%" type="text" name="file[0][filename]" value="<?=$filename.'.'.$extension;?>" />
			<input type="hidden" name="file[0][folder]" value="<?=$time;?>" />
			<input type="hidden" name="file[0][type]" value="<?=$type;?>" />
			<input readonly size="4%" type="text" name="file[0][size]" value="<?=$sizefile;?>" />
			<input type="submit" class="disabled sign remove_link" rel="file" value="-" />
		<?
	}
	else {echo 'error-maxsize';}
