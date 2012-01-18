<?php

interface Transform_Image_Interface
{
	public function __construct($path);

	public function get_composite_over();

	public function get_compression_jpeg();

	public function can_scale_animated();

	public function get_image_width();

	public function get_image_height();

	public function get_image_format();

	public function has_next_image();

	public function next_image();

	public function set_image_compression_quality($compression);

	public function set_image_page($width, $height, $x, $y);

	public function thumbnail_image($x, $y, $bestfit = false);

	public function scale_image($x, $y, $bestfit = false);

	public function color_flood_fill_image($fill, $fuzz, $bordercolor, $x, $y);

	public function composite_image($image, $type, $x, $y);

	public function set_image_compression($compression);

	public function set_image_format($type);

	public function write_image($path);

	public function write_images($path, $adjoin);

	public function coalesce_images();

	public function deconstruct_images();

	public function clear();
}
