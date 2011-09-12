<?	

	include_once 'common.php';
	
	if ($sizefile<$def['post']['filesize']) {
		$time = str_replace('.','',microtime(true));
		$extension =  pathinfo($file, PATHINFO_EXTENSION);
		$filename = substr(obj::transform('meta')->make_alias(pathinfo($file,PATHINFO_FILENAME)),0,200);
		if ($sizefile > 1048576) $sizefile = str_replace('.',',',round(($sizefile / 1048576), 1)).' мб';
		elseif ($sizefile > 1024) $sizefile = str_replace('.',',',round(($sizefile / 1024), 1)).' кб';
		else $sizefile .= ' байт';				
		mkdir(ROOT_DIR.SL.'files'.SL.'post'.SL.$time, 0755);
		$newfile = ROOT_DIR.SL.'files'.SL.'post'.SL.$time.SL.$filename.'.'.$extension;		
		chmod($temp, 0755);
		if (!move_uploaded_file($temp, $newfile)) file_put_contents($newfile, file_get_contents($temp));
				
		$return_data = '';
		
		if (is_array($check)) {
			$newthumb = ROOT_DIR.SL.'files'.SL.'post'.SL.$time.SL.'thumb_'.$filename.'.'.$extension;
			$imagick =  new $image_class($path = $newfile);
			scale(200,$newthumb);
			$type = 'image';
			$name = 'Сэмпл ('.$extension.')';
			$return_data .= 
				'<input type="hidden" name="file[0][height]" value="'.($imagick->getImageHeight()).'" />'."\n";
		}
		elseif ($extension == 'mp3') {
			$type = 'audio';
			$name = 'Сэмпл';
			if (function_exists('id3_get_tag')) {
						
				$version = id3_get_version($newfile);
				
				$tags = array();
				if ($version & ID3_V1_0) {
					$tags = id3_get_tag($newfile, ID3_V1_0);
				} elseif ($version & ID3_BEST) {
					$tags = id3_get_tag($newfile, ID3_BEST);
				}			
				
				if ($tags['artist']) $tags['artist'] = convert_to($tags['artist'],'UTF-8');
				if ($tags['title']) $tags['title'] = convert_to($tags['title'],'UTF-8');
				if ($tags['title'] || $tags['artist']) {
					$name = ($tags['artist'] ? $tags['artist'] : 'Неизвестный исполнитель').' - '.($tags['title'] ? $tags['title'] : 'неизвестная композиция');
				}
			}
		}
		else {
			$type = 'plain';
			$name = "Новый файл";
		}
		
		$return_data .= 
			'<input size="24%" type="text" name="file[0][name]" value="'.$name.'" />:'."\n".
			'<input readonly size="24%" type="text" name="file[0][filename]" value="'.$filename.'.'.$extension.'" />'."\n".
			'<input type="hidden" name="file[0][folder]" value="'.$time.'" />'."\n".
			'<input type="hidden" name="file[0][type]" value="'.$type.'" />'."\n".
			'<input readonly size="4%" type="text" name="file[0][size]" value="'.$sizefile.'" />'."\n".
			'<input type="submit" class="disabled sign remove_link" rel="file" value="-" />';
			
		$result = array(
			'success' => true, 
			'data' => $return_data, 
		);			
	}
	else {$result = array('error' => 'maxsize');} 

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
