<?php

include_once 'common.php';

if ($sizefile < $def['post']['filesize']) {

	$torrent = new Transform_Torrent($temp);
	
	if ($torrent->is_valid()) {

		$torrent->delete('announce-list');
		$torrent->set('announce', def::tracker('announce'));

		$data = $torrent->encode();

		$hash = $torrent->get_hash();
		$post_id = Database::get_field('post_torrent', 'post_id', 'hash = ?', $hash);
		if (!$post_id) {
		
			$filename = pathinfo(urldecode($file), PATHINFO_FILENAME);
			$filename = Transform_File::make_name($filename);
			$filename = substr($filename, 0, 200) . '.torrent';

			if (!is_dir(ROOT_DIR.SL.'files'.SL.'torrent'.SL.$hash)) {
				mkdir(ROOT_DIR.SL.'files'.SL.'torrent'.SL.$hash, 0755);
			}
			$newfile = ROOT_DIR.SL.'files'.SL.'torrent'.SL.$hash.SL.$filename;
			file_put_contents($newfile, $data);

			$size = $torrent->get('info', 'length');
			$size = Transform_File::weight_short($size);

			$return_data = 
				'<input size="24%" type="text" name="file[0][name]" value="Скачать" />:'."\n".
				'<input readonly size="24%" type="text" name="file[0][file]" value="'.$filename.'" />'."\n".
				'<input type="hidden" name="file[0][hash]" value="'.$hash.'" />'."\n".
				'<input readonly size="4%" type="text" name="file[0][size]" value="'.$size.'" />'."\n".
				'<input type="submit" class="disabled sign remove_link" rel="file" value="-" />';
				
			$result = array(
				'success' => true, 
				'data' => $return_data, 
			);
		} else {
			$result = array('error' => 'exists', 'post_id' => $post_id);
		}

	} else {
		$result = array('error' => 'incorrect');
	}
} else {
	$result = array('error' => 'maxsize');
} 

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
