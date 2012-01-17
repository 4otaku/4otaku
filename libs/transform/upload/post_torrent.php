<?php

class Transform_Upload_Post_Torrent extends Transform_Upload_Abstract
{
	protected function get_max_size() {
		return def::post('filesize');
	}
}
