<?php

class Transform_Image_Imagick extends Transform_Image_Abstract_Animation
{
	protected $worker;

	public function __construct($path) {
		$this->set_worker(new Imagick($path));
	}

	public function __call($name, $arguments) {
		if ($name == 'clone') {
			$new_object = clone $this;
			$new_object->set_worker($this->worker->clone());
			return $new_object;
		} 

		throw new Error_Image(Error_Image::UNEXPECTED_FUNCTION_CALL);
	}

	public function set_worker(Imagick $worker) {
		$this->worker = $worker;
	}

	public function get_worker() {
		return $this->worker;
	}

	public function get_composite_over() {
		return Imagick::COMPOSITE_OVER;
	}

	public function get_compression_jpeg() {
		return Imagick::COMPRESSION_JPEG;
	}

	public function get_image_width() {
		return $this->worker->getImageWidth();
	}

	public function get_image_height() {
		return $this->worker->getImageHeight();
	}

	public function get_image_format() {
		return $this->worker->getImageFormat();
	}

	public function has_next_image() {
		return $this->worker->hasNextImage();
	}

	public function next_image() {
		return $this->worker->nextImage();
	}

	public function set_image_compression_quality($compression) {
		return $this->worker->setImageCompressionQuality($compression);
	}

	public function set_image_page($width, $height, $x, $y) {
		return $this->worker->setImagePage($width, $height, $x, $y);
	}

	public function thumbnail_image($x, $y, $bestfit = false) {
		return $this->worker->thumbnailImage($x, $y, $bestfit);
	}

	public function scale_image($x, $y, $bestfit = false) {
		return $this->worker->scaleImage($x, $y, $bestfit);
	}

	public function color_flood_fill_image($fill, $fuzz, $bordercolor, $x, $y) {
		return $this->worker->colorFloodFillImage($fill, $fuzz, $bordercolor, $x, $y);
	}

	public function composite_image($image, $type, $x, $y) {
		return $this->worker->compositeImage($image->get_worker(), $type, $x, $y);
	}

	public function set_image_compression($compression) {
		return $this->worker->setImageCompression($compression);
	}

	public function set_image_format($type) {
		return $this->worker->setImageFormat($type);
	}

	public function write_image($path) {
		return $this->worker->writeImage($path);
	}

	public function write_images($path, $adjoin) {
		return $this->worker->writeImages($path, $adjoin);
	}

	public function coalesce_images() {
		$this->set_worker($this->worker->coalesceImages());
		return $this;
	}

	public function deconstruct_images() {
		$this->set_worker($this->worker->deconstructImages());
		return $this;
	}

	public function clear() {
		$this->worker->clear();
		unset($this->worker);
	}
}
