<?php

abstract class Transform_Image_Abstract_Static implements Transform_Image_Interface
{
	public function can_scale_animated() {
		return false;
	}

	public function has_next_image() {
		return false;
	}

	public function next_image() {
		throw new Error_Image(Error_Image::CANT_SCALE_ANIMATED);
	}

	public function write_images($path, $adjoin) {
		throw new Error_Image(Error_Image::CANT_SCALE_ANIMATED);
	}

	public function deconstruct_images() {
		throw new Error_Image(Error_Image::CANT_SCALE_ANIMATED);
	}
}
