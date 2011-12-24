<?	

	include_once 'common.php';
	
	if ($sizefile<$def['post']['filesize']) {
		
		$time = str_replace('.', '', microtime(true));
		$extension =  pathinfo($file, PATHINFO_EXTENSION);
		if ($extension != 'mp3') {
			$filename = substr(Transform_File::make_name(pathinfo($file, PATHINFO_FILENAME)),0,200);
		} else {
			$filename = 'audiotrack';
		}
		
		if ($sizefile > 1048576) {
			$sizefile = str_replace('.',',',round(($sizefile / 1048576), 1)).' мб';
		} elseif ($sizefile > 1024) {
			$sizefile = str_replace('.',',',round(($sizefile / 1024), 1)).' кб';
		} else {
			$sizefile .= ' байт';	
		}

		mkdir(ROOT_DIR.SL.'files'.SL.'post'.SL.$time, 0755);
		$newfile = ROOT_DIR.SL.'files'.SL.'post'.SL.$time.SL.$filename.'.'.$extension;		
		chmod($temp, 0755);
		if (!move_uploaded_file($temp, $newfile)) {
			file_put_contents($newfile, file_get_contents($temp));
		}
				
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

			include_once(ENGINE.SL.'external'.SL.'getid3'.SL.'getid3.php');
				
			$getid3 = new getID3();
			$getid3->encoding = 'UTF-8';
			$getid3->Analyze($newfile);
			if (
				empty($getid3->info['tags']) || 
				!is_array($getid3->info['tags'])) {
				
				$name = 'Сэмпл';
			} else {
				$tags = current($getid3->info['tags']);
				
				if (is_array($tags['artist'])) {
					$tags['artist'] = reset($tags['artist']);
				}
				
				if (is_array($tags['title'])) {
					$tags['title'] = reset($tags['title']);
				}
				
				if (empty($tags['artist'])) {
					$tags['artist'] = 'Неизвестный исполнитель';
				}
				
				if (empty($tags['title'])) {
					$tags['title'] = 'неизвестная композиция';
				}	
				
				$name = $tags['artist'] . ' - '. $tags['title'];
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
