<?php

abstract class Transform_Upload_Abstract_Have_Image extends Transform_Upload_Abstract
{
	protected $info = array();
	protected $animated = false;

	protected $sizes = false;

	public function __construct($file, $name) {
		parent::__construct($file, $name);

		$this->info = getimagesize($this->file);
	}

	protected function scale($new_size, $target, $compression = 80, $thumbnail = true) {
		$worker = $this->worker;

		if ($new_size === false) {
			$aspect = 1/2;
		} elseif (!is_array($new_size)) {
			$new_size = array('0' => $new_size, '1' => $new_size);
		}

		$format = $worker->get_image_format();
		if (strtolower($format) == 'gif') {
			$worker = $worker->coalesce_images();

			if ($this->animated || $worker->has_next_image()) {
				$this->animated = true;
				if (!$thumbnail && $worker->can_scale_animated()) {
					return $this->scale_animated($new_size, $target);
				}
			}
		}

		$old_x = $worker->get_image_width();
		$old_y = $worker->get_image_height();

		if ($thumbnail) {
			$aspect = empty($aspect) ?
				min($new_size[0]/$old_x, $new_size[1]/$old_y) : $aspect;
			$func = 'thumbnail_image';
		} else {
			$aspect = empty($aspect) ? $new_size[0]/$old_x : $aspect;
			$func = 'scale_image';
		}

		$x = round($old_x * $aspect);
		$y = round($old_y * $aspect);

		if (strtolower($format) == 'png') {
			$worker->set_image_compression_quality($compression);
			$worker->$func($x,$y);
			$bg = $worker->clone();
			$bg->color_flood_fill_image('#ffffff', 100, '#777777', 0,0);
			$bg->composite_image($worker, $worker->get_composite_over(), 0, 0);
			$bg->set_image_compression($worker->get_compression_jpeg());
			$bg->set_image_format('jpeg');
			$bg->write_image($target);
		} elseif (strtolower($format) == 'gif') {
			$worker->set_image_compression_quality($compression);
			$worker->$func($x,$y);
			$worker->set_image_page($x,$y,0,0);
			$bg = $worker->clone();
			$bg->color_flood_fill_image('#ffffff', 100,' #777777', 0, 0);
			$bg->composite_image($worker, $worker->get_composite_over(), 0, 0);
			$bg->set_image_compression($worker->get_compression_jpeg());
			$worker->set_image_format('jpeg');
			$bg->write_image($target);
		} else {
			$worker->set_image_compression_quality($compression);
			$worker->$func($x,$y);
			$worker->set_image_compression($worker->get_compression_jpeg());
			$worker->set_image_format('jpeg');
			$worker->write_image($target);
		}
		$worker->clear();
		$this->worker = Transform_Image::get_worker($target);
		return true;
	}

	protected function is_animated ($filename) {
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

	protected function scale_animated ($new_size, $target) {
		$worker = $this->worker;

		$old_x = $worker->get_image_width();
		$old_y = $worker->get_image_height();
		$this->sizes = $old_x.'x'.$old_y;

		$aspect = !empty($new_size) ? $new_size[0]/$old_x : 1/2;

		$x = round($old_x * $aspect);
		$y = round($old_y * $aspect);

		do {
			$worker->scale_image($x, $y, 1);
		} while ($worker->next_image());

		$worker = $worker->deconstruct_images();

		$target = preg_replace('/\.jpe?g$/i', '.gif', $target);

		$worker->write_images($target, true);

		$worker->clear();
		$this->worker = Transform_Image::get_worker($target);
		return true;
	}
}
