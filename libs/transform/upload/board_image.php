<?php

class Transform_Upload_Board_Image extends Transform_Upload_Abstract_Image
{
	protected function get_max_size() {
		return def::board('filesize');
	}

	protected function process() {
		$md5 = md5_file($this->file);
		$extension = strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
		$newname = $md5.'.'.$extension;
		$newfile = IMAGES.SL.'board'.SL.'full'.SL.$newname;
		chmod($this->file, 0755);

		if (!file_exists($newfile)) {
			if (!move_uploaded_file($this->file, $newfile)) {
				file_put_contents($newfile, file_get_contents($this->file));
			}
		}

		$thumb = md5(microtime(true));
		$newthumb = IMAGES.SL.'board'.SL.'thumbs'.SL.$thumb.'.jpg';
		$this->worker = Transform_Image::get_worker($newfile);
		$width = $this->worker->get_image_width();
		$height = $this->worker->get_image_height();
		$this->scale(array(def::board('thumbwidth'), def::board('thumbheight')), $newthumb);

		$this->set(array(
			'success' => true,
			'image' => SITE_DIR.'/images/board/thumbs/'.$thumb.'.jpg',
			'data' => $newname.'#'.$thumb.'.jpg#'.$this->size.'#'.$width.'x'.$height,
			'full' => $newname,
			'thumb' => $thumb,
			'size' => $this->size,
			'width' => $width,
			'height' => $height,
		));
	}
}
