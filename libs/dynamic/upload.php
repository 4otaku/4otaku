<?php

class Dynamic_Upload extends Dynamic_Abstract
{
	protected $file;
	protected $name;

	protected error_messages = array(
		Error_Upload::FILE_TOO_LARGE => 'maxsize',
		Error_Upload::NOT_AN_IMAGE => 'filetype',
	);

	public function __construct() {
		if (!empty($_FILES)) {
			$file = current(($_FILES));

			$this->file = $file['tmp_name'];
			$this->name = $file['name'];
		} elseif ($_GET['qqfile']) {

			$this->file = file_get_contents('php://input');
			$this->name = urldecode($_GET['qqfile']);
		} else {
			$this->reply(array('error' => 'no-file-found'), false);
		}
	}

	protected function common(Transform_Upload_Abstract $worker) {
		try {
			$data = $worker->process_file();
			$success = true;
		} catch (Error_Upload $e) {
			$data = array('error' => $this->translate_error($e));
			$success = false;
		}

		$this->reply($data, $success);
	}

	public function art() {
		$worker = new Transform_Upload_Art($this->file, $this->name);

		$this->common($worker);
	}

	public function board() {
		$worker = new Transform_Upload_Board($this->file, $this->name);

		$this->common($worker);
	}

	public function news() {
		$worker = new Transform_Upload_News($this->file, $this->name);

		$this->common($worker);
	}

	public function pack() {
		$worker = new Transform_Upload_Pack($this->file, $this->name);

		$this->common($worker);
	}

	public function post_file() {
		$worker = new Transform_Upload_Post_File($this->file, $this->name);

		$this->common($worker);
	}

	public function post_image() {
		$worker = new Transform_Upload_Post_Image($this->file, $this->name);

		$this->common($worker);
	}

	public function post_torrent() {
		$worker = new Transform_Upload_Post_Torrent($this->file, $this->name);

		$this->common($worker);
	}

	protected function translate_error(Error_Update $e) {
		$code = $e->getCode();

		if (!empty($this->error_messages[$code])) {
			return $this->error_messages[$code];
		}

		return 'unknown';
	}
}

class dynamic__upload extends Dynamic_Upload {}
