<?php

class Transform_Upload_Board_Flash extends Transform_Upload_Abstract
{
	protected function get_max_size() {
		return def::board('flashsize');
	}

	protected function process() {
		$md5 = md5_file($this->file);
		$newname = $md5.'.swf';
		$newfile = IMAGES.SL.'board'.SL.'full'.SL.$newname;
		chmod($this->file, 0755);

		if (!file_exists($newfile)) {
			if (!move_uploaded_file($this->file, $newfile)) {
				file_put_contents($newfile, file_get_contents($this->file));
			}
		}

		$this->set(array(
			'success' => true,
			'image' => SITE_DIR.'/images/flash.png',
			'data' => $newname . '#flash#' . $this->size,
		));
	}
}
