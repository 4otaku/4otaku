<?php

class Transform_Upload_Art extends Transform_Upload_Image
{
	protected function get_max_size() {
		return def::art('filesize');
	}
}
