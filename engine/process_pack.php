<?
	include '../inc.common.php';
	include_once ROOT_DIR.SL.'engine/upload/functions.php';
	$imagetypes = array("image/jpeg", "image/gif", "image/png");

	function _unpack($id) {
		obj::db()->sql('update misc set data1 = "unpacking" where type="pack_status" and data2 = '.$id,0);


		require ROOT_DIR.SL.'engine'.SL.'extract'.SL.'ArchiveExtractor.class.php';

		$archExtractor = new ArchiveExtractor();
	
		$newdir = ROOT_DIR.SL.'files'.SL.'pack_uploaded'.SL.$id.SL;
		$newfile = ROOT_DIR.SL.'files'.SL.'pack_uploaded'.SL.$id.'.zip';
		if (file_exists($newfile)) {
			$ext = 'zip';
		} else {
			obj::db()->sql('update misc set data1 = "error" where type="pack_status" and data2 = '.$id,0);
		}
		
		if (!empty($ext)) {
			mkdir($newdir);
			$archExtractor->extractArchive($newfile,$newdir);
			obj::db()->sql('update misc set data1 = "unpacked" where type="pack_status" and data2 = '.$id,0);
		}
	}	
	
	function _getimages($gallery_id) {
		global $order;

		$order=0;
		$fulldir = ROOT_DIR.SL.'files'.SL.'pack_uploaded'.SL.$gallery_id.SL;
		parse_dir($fulldir,$gallery_id);
		
		obj::db()->sql('
			update misc set 
				data1 = "processing", 
				data3 = 0, 
				data4 = '.$order.' 
			where type="pack_status" and data2 = '.$gallery_id,0);
	}
	
	function _parseimages($gallery_id) {
		global $imagick; global $def; global $image_class;
		
		$art = obj::db()->sql('select * from misc where type="pack_art" and data1 = '.$gallery_id.' order by data4 limit '.rand(4,9));
		$total = obj::db()->sql('select data4 from misc where type="pack_status" and data2 = '.$gallery_id, 2);
		$current_order = obj::db()->sql('select max(`order`) from art_in_pack where pack_id='.$gallery_id,2);
		$next_order = (!is_numeric($current_order)) ? 0 : $current_order + 1;

		if (is_array($art)) {
			foreach ($art as $one) {
				
				$file = $one['data2'];
				
				obj::db()->sql('delete from misc where type="pack_art" and id = '.$one['id'],0);
				
				$md5=md5_file($file);
				$sizefile=filesize($file);
				$resized = false;
				
				$exists = obj::db()->sql('select id, area from art where md5="'.$md5.'"',1);
				if (!$exists) {					
					$extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
					$thumb = md5(microtime(true));
					$newname = $md5.'.'.$extension;
					$newfile = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$newname;
					$newthumb = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.$thumb.'.jpg';
					$newlargethumb = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.'large_'.$thumb.'.jpg';
					chmod($file, 0755);
					if (!move_uploaded_file($file, $newfile)) 
						file_put_contents($newfile, file_get_contents($file));

					$imagick =  new $image_class($path = $newfile);
					$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
					if ($imagick->getImageWidth() > $def['booru']['resizewidth']*$def['booru']['resizestep']) {
						if (scale($def['booru']['resizewidth'],ROOT_DIR.SL.'images/booru/resized/'.$md5.'.jpg',95,false))
							$resized = $sizes;
					} elseif ($sizefile > $def['booru']['resizeweight']) {
						if (scale(false,ROOT_DIR.SL.'images/booru/resized/'.$md5.'.jpg',95,false))
							$resized = $sizes;
					}
							
					if (!empty($resized)) {					
						if ($sizefile > 1024*1024) {
							$sizefile = round($sizefile/(1024*1024),1).' мб';
						} elseif ($sizefile > 1024) {
							$sizefile = round($sizefile/1024,1).' кб';
						} else {
							$sizefile = $sizefile.' б';
						}
						$resized .= 'px; '.$sizefile;
					}
					
					$animated = 0;
					
					scale($def['booru']['largethumbsize'],$newlargethumb);
					scale($def['booru']['thumbsize'],$newthumb);
					
					obj::db()->insert('art',array(
						$md5,
						$thumb,
						$extension,
						$resized,
						$animated,
						'|',
						'|nsfw|game_cg|',
						'|tagme|',
						'|',
						0,
						'',
						'',
						0,
						0,
						obj::transform('text')->rudate(),
						ceil(microtime(true)*1000),
						'cg'
					));	
					
					$art_id = obj::db()->sql('select @@identity from art',2);					
				} else {
					$art_id = $exists['id'];
					
					if ($exists['area'] == 'deleted') {
						obj::db()->update('art','area','cg',$exists['id']);
					}
				}
				
				obj::db()->insert('art_in_pack',array(
					$art_id,
					$gallery_id,
					$next_order,
					pathinfo($file,PATHINFO_BASENAME)
				), false);

				$next_order++;
			}
		}

		if ($count = obj::db()->sql('select count(*) from misc where type="pack_art" and data1 = '.$gallery_id,2)) {
			obj::db()->sql('update misc set data3 = data4 - '.$count.' where type="pack_status" and data2 = '.$gallery_id,0);
		} else {
			obj::db()->sql('update misc set data1 = "done" where type="pack_status" and data2 = '.$gallery_id,0);
			obj::db()->sql('update art_pack set weight = 0 where id = '.$gallery_id,0);
		}
	}	
	
	 function parse_dir($fulldir,$gallery_id) {
		global $imagetypes;	global $order; 
		 
		$d = @dir($fulldir);
		while(false !== ($entry = $d->read())) { 
			if($entry[0] == ".") {
				continue; 
			}
			if (is_dir("$fulldir$entry")) {
				parse_dir("$fulldir$entry/",$gallery_id);
			} elseif(in_array(mime_content_type("$fulldir$entry"), $imagetypes)) {
				$order++;
				
				$sort = str_repeat("0", 5 - strlen($order)).$order;
				
				obj::db()->insert('misc',array('pack_art',$gallery_id,$fulldir.$entry,$sort,$entry,0));
			}
		} 
		$d->close();		
	}

	$status = obj::db()->sql('select * from misc where type="pack_status" and data1 != "done" and data1 != "error"',1);

	if ($status) {

		switch ($status['data1']) {
			case 'starting': _unpack($status['data2']); break;
			case 'unpacking': _unpack($status['data2']); break;
			case 'unpacked': _getimages($status['data2']); break;
			case 'processing': _parseimages($status['data2']); break;
			default: break;
		}	
		echo $status['data1'];

	} else {
		echo "ничего не обрабатываю";
	}
