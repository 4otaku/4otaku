<?php

abstract class Transform_Upload_Image extends Transform_Upload_Abstract
{
	protected $worker_name;
	protected $composite = array();

	protected $info = array();
	protected $animated = false;

	protected $sizes = false;

	protected function test_file() {
		parent::test_file();

		$check = getimagesize($this->file);
		if (!is_array($check)) {
			throw new Error_Upload(Error_Upload::NOT_AN_IMAGE);
		}

		$this->info = $check;
	}

	protected function get_worker($path) {
		$name = $this->get_worker_name();

		return new $name($path);
	}

	protected function get_worker_name() {
		if (empty($this->worker_name)) {
			if (!class_exists('Imagick', false)) {
				$this->worker_name = 'Transform_Image';
				$this->composite['over'] = Transform_Image::COMPOSITE_OVER;
				$this->composite['jpeg'] = Transform_Image::COMPRESSION_JPEG;
			} else {
				$this->worker_name = 'Imagick';
				$this->composite['over'] = Imagick::COMPOSITE_OVER;
				$this->composite['jpeg'] = Imagick::COMPRESSION_JPEG;
			}
		}

		return $this->worker_name;
	}

	function scale($new_size, $target, $compression = 80, $thumbnail = true) {
		$worker = $this->worker;
		$worker_name = $this->get_worker_name();
		$composite = $this->composite;

		if ($new_size === false) {
			$aspect = 1/2;
		} elseif (!is_array($new_size)) {
			$new_size = array('0' => $new_size, '1' => $new_size);
		}

		$format = $worker->getImageFormat();
		if (strtolower($format) == 'gif') {
			$worker = $worker->coalesceImages();

			if ($this->animated || $worker->hasNextImage()) {
				$this->animated = true;
				if (!$thumbnail && ($worker_name == 'Imagick')) {
					return $this->scale_animated($new_size, $target);
				}
			}
		}

		$old_x = $worker->getImageWidth();
		$old_y = $worker->getImageHeight();

		if ($thumbnail) {
			$aspect = empty($aspect) ?
				min($new_size[0]/$old_x, $new_size[1]/$old_y) : $aspect;
			$func = 'thumbnailImage';
		} else {
			$aspect = empty($aspect) ? $new_size[0]/$old_x : $aspect;
			$func = 'scaleImage';
		}

		$x = round($old_x * $aspect);
		$y = round($old_y * $aspect);

		if (strtolower($format) == 'png') {
			$worker->setImageCompressionQuality($compression);
			$worker->$func($x,$y);
			$bg = $worker->clone();
			$bg->colorFloodFillImage('#ffffff', 100, '#777777', 0,0);
			$bg->compositeImage($worker, $composite['over'], 0, 0);
			$bg->setImageCompression($composite['jpeg']);
			$bg->setImageFormat('jpeg');
			$bg->writeImage($target);
		} elseif (strtolower($format) == 'gif') {
			$worker->setImageCompressionQuality($compression);
			$worker->$func($x,$y);
			$worker->setImagePage($x,$y,0,0);
			$bg = $worker->clone();
			$bg->colorFloodFillImage('#ffffff', 100,' #777777', 0, 0);
			$bg->compositeImage($worker, $composite['over'], 0, 0);
			$bg->setImageCompression($composite['jpeg']);
			$worker->setImageFormat('jpeg');
			$bg->writeImage($target);
		} else {
			$worker->setImageCompressionQuality($compression);
			$worker->$func($x,$y);
			$worker->setImageCompression($composite['jpeg']);
			$worker->setImageFormat('jpeg');
			$worker->writeImage($target);
		}
		$worker->clear();
		$this->worker = new $worker_name($target);
		return true;
	}

	function is_animated ($filename) {
		$filecontents = file_get_contents($filename);

		$str_loc = 0;
		$count = 0;
		while ($count < 2) {
			$where1 = strpos($filecontents, "\x00\x21\xF9\x04", $str_loc);
			if ($where1 === FALSE) {
				break;
			} else {
				$str_loc = $where1 + 1;
				$where2 = strpos($filecontents, "\x00\x2C", $str_loc);
				if ($where2 === FALSE) {
					continue;
				} else {
					if ($where1 + 8 == $where2) {
						$count++;
					}
					$str_loc = $where2 + 1;
				}
			}
		}

		return ($count > 1);
	}

	function scale_animated ($new_size, $target) {
		$worker = $this->worker;
		$worker_name = $this->get_worker_name();

		$old_x = $worker->getImageWidth();
		$old_y = $worker->getImageHeight();
		$this->sizes = $old_x.'x'.$old_y;

		$aspect = !empty($new_size) ? $new_size[0]/$old_x : 1/2;

		$x = round($old_x * $aspect);
		$y = round($old_y * $aspect);

		do {
			$worker->scaleImage($x, $y, 1);
		} while ($worker->nextImage());

		$worker = $worker->deconstructImages();

		$target = preg_replace('/\.jpe?g$/i', '.gif', $target);

		$worker->writeImages($target, true);

		$imagick->clear();
		$this->worker = new $worker_name($target);
		return true;
	}
}
