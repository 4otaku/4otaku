<?php

class Transform_Upload_Art extends Transform_Upload_Abstract_Image
{
	protected $md5 = 'unknown';

	protected function get_max_size() {
		return def::art('filesize');
	}

	protected function test_file() {
		parent::test_file();

		$md5 = md5_file($this->file);
		if (
			($id = Database::get_field('art', 'id', 'md5 = ?', $md5)) ||
			($id = Database::get_field('art_variation', 'id', 'md5 = ?', $md5))
		) {
			throw new Error_Upload($id, Error_Upload::ALREADY_EXISTS);
		}

		$this->md5 = $md5;
	}

	protected function process() {
		$pathinfo = pathinfo($this->name);

		$extension = strtolower($pathinfo['extension']);

		$thumb = md5(microtime(true));
		$newname = $this->md5.'.'.$extension;

		$newfile = IMAGES.SL.'booru'.SL.'full'.SL.$newname;
		$newresized = IMAGES.SL.'booru'.SL.'resized'.SL.$this->md5.'.jpg';
		$newthumb = IMAGES.SL.'booru'.SL.'thumbs'.SL.$thumb.'.jpg';
		$newlargethumb = IMAGES.SL.'booru'.SL.'thumbs'.SL.'large_'.$thumb.'.jpg';

		chmod($this->file, 0755);

		if (!move_uploaded_file($this->file, $newfile)) {
			file_put_contents($newfile, file_get_contents($this->file));
		}

		$this->worker = Transform_Image::get_worker($newfile);
		$this->animated = $this->is_animated($newfile);

		$resized = $this->check_resize($newresized);

		$this->scale(def::art('largethumbsize'), $newlargethumb);
		$this->scale(def::art('thumbsize'), $newthumb);

		$this->set(array(
			'image' => '/images/booru/thumbs/'.$thumb.'.jpg',
			'md5' => $this->md5,
			'thumb' => $thumb,
			'extension' => $extension,
			'resized' => (string) $resized,
			'animated' => (int) $this->animated,
			'meta' => $this->get_file_meta($pathinfo['filename'])
		));
	}

	public function resize() {
		$md5 = md5_file($this->file);
		$newresized = IMAGES.SL.'booru'.SL.'resized'.SL.$md5.'.jpg';

		$this->worker = Transform_Image::get_worker($this->file);
		$this->animated = $this->is_animated($this->file);

		$resized = $this->check_resize($newresized);

		return $resized;
	}

	protected function check_resize($target) {
		$resized = false;
		$this->sizes = $this->worker->get_image_width().'x'.$this->worker->get_image_height();

		$resize_width = def::art('resizewidth') * def::art('resizestep');
		if (
			$this->worker->get_image_width() > $resize_width ||
			$this->info[0] > $resize_width
		) {
			if ($this->scale(def::art('resizewidth'), $target, 95, false)) {
				$resized = $this->sizes;
			}
		} elseif ($sizefile > def::art('resizeweight')) {
			if ($this->scale(false, $target, 95, false)) {
				$resized = $this->sizes;
			}
		}

		if (!empty($resized)) {
			$resized .= 'px; '.Transform_File::weight_short($this->size);
		}

		return $resized;
	}

	protected function get_file_meta ($filename) {

		if (stripos($filename, 'auto_tag=') !== 0) {
			return array();
		}

		$filename = str_ireplace('auto_tag=', '', $filename);

		$filename = explode('=', $filename);

		if (count($filename) != 3 || !is_numeric($filename[0]) || !is_numeric($filename[1])) {
			return array();
		}

		$tags = preg_split('/[\+\s]+/u', $filename[2]);

		return array(
			'tags' => $tags,
			'id_group' => $filename[0],
			'id_in_group' => $filename[1],
		);
	}
}
