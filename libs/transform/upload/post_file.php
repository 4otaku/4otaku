<?php

class Transform_Upload_Post_File extends Transform_Upload_Abstract
{
	protected function get_max_size() {
		return def::post('filesize');
	}
}
