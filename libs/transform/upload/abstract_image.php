<?php

abstract class Transform_Upload_Abstract_Image extends Transform_Upload_Abstract_Have_Image
{
	protected function test_file() {
		parent::test_file();

		if (!is_array($this->info)) {
			throw new Error_Upload(Error_Upload::NOT_AN_IMAGE);
		}
	}
}
