<?

	include_once 'common.php';

	if ($sizefile<200*1024*1024) {
	
		obj::db()->insert('art_pack',array(
			$file,
			0,
			'',
			$_GET['name'],
			obj::transform('text')->format(trim($_GET['text'])),
			$_GET['text'],
			0,
			date("Y-m-d H:i:s")
		));
		$id = obj::db()->sql('select @@identity from art_pack',2);
		obj::db()->insert('misc',array('pack_status','starting',$id,'','',''));

		$newfile = ROOT_DIR.SL.'files'.SL.'pack_uploaded'.SL.$id.'.zip';
		if (!move_uploaded_file($temp, $newfile)) {
			$contents = file_get_contents($temp);
			$start = substr($contents, 0, 3000);
			$end = substr($contents, 3000);
			unset($contents);
			$new_start = preg_replace('/^.{0,2000}?Content-Type:\s+application\/zip\s+/is','',$start);
			
			if (empty($new_start)) {
				$new_start = $start;
			}

			file_put_contents($newfile, $start.$end);			
		}

		$result = array('id' => $id, 'file' => $file);
	} else {
		$result = array('error' => 'maxsize');
	} 

	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
