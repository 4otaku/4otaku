<?php

class Transform_Upload_News extends Transform_Upload_Abstract_Image
{
	protected function get_max_size() {
		return def::post('picturesize');
	}

	protected function process() {
		$time = str_replace('.', '', microtime(true));
		$extension =  strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
		$newfile = IMAGES.SL.'news'.SL.'full'.SL.$time.'.'.$extension;
		$newthumb = IMAGES.SL.'news'.SL.'thumb'.SL.$time.'.jpg';
		chmod($this->file, 0755);

		if (!move_uploaded_file($this->file, $newfile)) {
			file_put_contents($newfile, file_get_contents($this->file));
		}

		$this->worker = Transform_Image::get_worker($newfile);
		$this->scale(array(0 => 200, 1 => 150), $newthumb);

		$this->set(array(
			'success' => true,
			'image' => SITE_DIR.'/images/news/thumb/'.$time.'.jpg',
			'data' => $time.'.'.$extension,
		));
	}
}
