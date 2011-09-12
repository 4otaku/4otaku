<?

	include_once 'common.php';

	if ($sizefile < def::art('packsize')) {

		Database::insert('art_pack',array(
			'filename' => $file,
			'title' => $_GET['name'],
			'text' => Transform_Text::format(trim($_GET['text'])),
			'pretty_text' => $_GET['text']
		));

		$id = Database::last_id();
		Database::insert('misc', array(
			'type' => 'pack_status',
			'data1' => 'starting',
			'data2' => $id
		));

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
