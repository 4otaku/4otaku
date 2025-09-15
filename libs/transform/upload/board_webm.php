<?php

class Transform_Upload_Board_Webm extends Transform_Upload_Abstract_Have_Image
{
	protected function get_max_size() {
		return 50*1024*1024*1024;
	}

	protected function process() {
		$md5 = md5_file($this->file);
		$newname = $md5.'.webm';
		$newfile = IMAGES.SL.'board'.SL.'full'.SL.$newname;
		chmod($this->file, 0755);

		if (!file_exists($newfile)) {
			if (!move_uploaded_file($this->file, $newfile)) {
				file_put_contents($newfile, file_get_contents($this->file));
			}
		}
		
		$thumb = md5(microtime(true));
		$newthumb = IMAGES.SL.'board'.SL.'thumbs'.SL.$thumb.'.jpg';
		$tmpthumb = IMAGES.SL.'board'.SL.'thumbs'.SL.$thumb.'.tmp.jpg';
		exec('ffmpeg -i ' . $newfile . ' -vframes 1 ' . $tmpthumb);
		$this->worker = Transform_Image::get_worker($tmpthumb);
		$width = $this->worker->get_image_width();
		$height = $this->worker->get_image_height();
		$this->scale(array(def::board('thumbwidth'), def::board('thumbheight')), $newthumb);
		unlink($tmpthumb);

		$this->set(array(
			'success' => true,
			'image' => SITE_DIR.'/images/board/thumbs/'.$thumb.'.jpg',
			'data' => $newname . '#'.$thumb.'.jpg#' . $this->size,
		));
	}
}
