<?php

class Transform_Upload_Post_Torrent extends Transform_Upload_Abstract
{
	protected function get_max_size() {
		return def::post('filesize');
	}

	protected function process() {
		$torrent = new Transform_Torrent($this->file);

		if (!$torrent->is_valid()) {
			throw new Error_Upload(Error_Upload::NOT_A_TORRENT);
		}

		$torrent->delete('announce-list');
		$torrent->set('announce', def::tracker('announce'));

		$data = $torrent->encode();

		$hash = $torrent->get_hash();
		$post_id = Database::get_field('post_torrent', 'post_id', 'hash = ?', $hash);
		if ($post_id) {
			throw new Error_Upload(Error_Upload::ALREADY_EXISTS);
		}

		$filename = pathinfo(urldecode($this->name), PATHINFO_FILENAME);
		$filename = Transform_File::make_name($filename);
		$filename = substr($filename, 0, 200) . '.torrent';

		if (!is_dir(FILES.SL.'torrent'.SL.$hash)) {
			mkdir(FILES.SL.'torrent'.SL.$hash, 0755);
		}
		$newfile = FILES.SL.'torrent'.SL.$hash.SL.$filename;
		file_put_contents($newfile, $data);

		$size = $torrent->get_size();
		$size = Transform_File::weight_short($size);

		$return_data =
			'<input size="24%" type="text" name="torrent[0][name]" value="Скачать" />:'."\n".
			'<input readonly size="24%" type="text" name="torrent[0][file]" value="'.$filename.'" />'."\n".
			'<input type="hidden" name="torrent[0][hash]" value="'.$hash.'" />'."\n".
			'<input readonly size="4%" type="text" name="torrent[0][size]" value="'.$size.'" />'."\n".
			'<input type="submit" class="disabled sign remove_link" rel="torrent" value="-" />';

		$this->set(array(
			'success' => true,
			'data' => $return_data,
			'file' => $filename,
			'hash' => $hash,
			'name' => 'Скачать',
			'size' => $size,
		));
	}
}
