<?php

class Transform_Image_Gd extends Transform_Image_Abstract_Static
{
	protected $path;
	protected $data;
	protected $width;
	protected $height;
	protected $format;
	protected $compression = 80;

	const COMPOSITE_OVER = 'over';
	const COMPRESSION_JPEG = 'jpeg';

	public function __construct ($file) {
		$this->path = $file;
		$sizes = getimagesize($this->path);
		$this->width = $sizes[0];
		$this->height = $sizes[1];
		$format = explode('/', image_type_to_mime_type($this->exif_imagetype($this->path)));
		$this->format = $format[1];
		switch ($this->format) {
			case 'png': $this->data = imagecreatefrompng($this->path); break;
			case 'jpeg': $this->data = imagecreatefromjpeg($this->path); break;
			case 'gif': $this->data = imagecreatefromgif($this->path); break;
			default: throw new Error_Image(Error_Image::NOT_AN_IMAGE);
		}
	}

	public function __call($name, $arguments) {
		if ($name == 'clone') {
			return $this;
		}

		throw new Error_Image(Error_Image::UNEXPECTED_FUNCTION_CALL);
	}

	public function get_composite_over() {
		return self::COMPOSITE_OVER;
	}

	public function get_compression_jpeg() {
		return self::COMPRESSION_JPEG;
	}

	public function get_image_width() {
		return $this->width;
	}

	public function get_image_height() {
		return $this->height;
	}

	public function get_image_format() {
		return $this->format;
	}

	public function set_image_compression_quality($compression) {
		$this->compression = $compression;
	}

	public function set_image_page($width, $height, $x, $y) {}

	public function thumbnail_image($x, $y, $bestfit = false) {
		if ($bestfit) {
			if ($this->height > $this->width) {
				$x = $this->width/($this->height/$y);
			} else {
				$y = $this->height/($this->width/$x);
			}
		}

		$thumbnail = imagecreatetruecolor($x, $y);
		imagecopyresampled($thumbnail,$this->data,0,0,0,0,$x,$y,$this->width,$this->height);
		$this->data = $thumbnail;
	}

	public function scale_image($x, $y, $bestfit = false) {
		$this->thumbnail_image($x, $y, $bestfit);
	}

	public function color_flood_fill_image($fill, $fuzz, $bordercolor, $x, $y) {
		list($r, $g, $b) = str_split(substr($fill, 1),2);
		$color = imagecolorallocate(
			$this->data,
			'0x' . strtoupper($r),
			'0x' . strtoupper($g),
			'0x' . strtoupper($b)
		);
		imagefill($this->data, $x, $y, $color);
	}

	public function composite_image($image, $type, $x, $y) {
		switch ($type) {
			case 'over':
				imagecopymerge(
					$this->data,
					$image->data,
					$this->width,
					$this->height,
					$x,
					$y,
					$image->width,
					$image->height,
					100
				);
				break;
			default:
				throw new Error_Image(Error_Image::NOT_AN_IMAGE);
		}
	}

	public function set_image_compression($compression) {
		$this->format = $compression;
	}

	public function set_image_format($type) {
		$this->format = $type;
	}

	public function write_image($path) {
		$function = 'image' . $this->format;
		$compression = $this->format == 'png' ?
			floor(($this->compression - 1) / 10) :
			$this->compression;
		$function($this->data, $path, $compression);
	}

	public function coalesce_images() {
		return $this;
	}

	public function clear() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $var) {
			unset($this->$key);
		}
	}

	public function exif_imagetype($filename) {
		if (!function_exists('exif_imagetype')) {
			if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
				return $type;
			}
			return false;
		}

		return exif_imagetype($filename);
	}
}
