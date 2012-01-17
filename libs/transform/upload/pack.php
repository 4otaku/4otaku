<?php

class Transform_Upload_Pack extends Transform_Upload_Abstract
{
	protected function get_max_size() {
		return def::art('packsize');
	}
}
