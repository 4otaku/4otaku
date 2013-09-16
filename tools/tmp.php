<?php
die;
include '../inc.common.php';

$files = glob('/var/www/frontend/demo/art/*');

class Tmp extends Transform_Upload_Abstract_Image
{
	protected function test_file() {}
	protected function get_max_size() {}

	protected function process() {
		global $key;

		$newthumb = '/var/www/frontend/demo/art/_'.$key.'.jpg';

		$this->worker = Transform_Image::get_worker($this->file);
		$this->animated = $this->is_animated($this->file);

		$this->scale(array(150, 150), $newthumb);

		$this->set(array());
	}
}

foreach ($files as $key => $file) {
	$worker = new Tmp($file, basename($file));
	$worker->process_file();
	unlink($file);
}
