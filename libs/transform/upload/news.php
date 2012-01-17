<?php

class Transform_Upload_News extends Transform_Upload_Image
{
	protected function get_max_size() {
		return def::post('picturesize');
	}
}
