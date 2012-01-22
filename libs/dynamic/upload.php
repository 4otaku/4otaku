<?php

class Dynamic_Upload extends Dynamic_Abstract
{
	protected $file;
	protected $name;

	protected $error_messages = array(
		Error_Upload::EMPTY_FILE => 'filetype',
		Error_Upload::FILE_TOO_LARGE => 'maxsize',
		Error_Upload::NOT_AN_IMAGE => 'filetype',
		Error_Upload::ALREADY_EXISTS => 'exists',
		Error_Upload::NOT_A_TORRENT => 'filetype',
	);

	public function __construct() {
		if (!empty($_FILES)) {
			$file = current(($_FILES));

			$this->file = $file['tmp_name'];
			$this->name = $file['name'];
		} elseif (query::$get['qqfile']) {

			$this->file = file_get_contents('php://input');
			$this->name = urldecode(query::$get['qqfile']);
		} else {
			$this->reply(array('error' =>
				$this->error_messages[Error_Upload::EMPTY_FILE]), false);
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
		$extension = strtolower(pathinfo($this->name, PATHINFO_EXTENSION));

		if (class_exists('finfo')) {
			$finfo = new finfo(FILEINFO_MIME);
			if (is_file($this->file)) {
				$mime_type = $finfo->file($this->file);
			} else {
				$mime_type = $finfo->buffer($this->file);
			}
		} else {
			$mime_type = '';
		}

		if ($extension == 'swf' || preg_match('/(shockwave.*flash)/', $mime_type)) {
			$worker = new Transform_Upload_Board_Flash($this->file, $this->name);
		} else {
			$worker = new Transform_Upload_Board_Image($this->file, $this->name);
		}

		$this->common($worker);
	}

	public function news() {
		$worker = new Transform_Upload_News($this->file, $this->name);

		$this->common($worker);
	}

	public function pack() {
		$worker = new Transform_Upload_Pack($this->file, $this->name);

		$worker->set_title(query::$get['name'])->set_text(query::$get['text']);

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

	protected function translate_error(Error_Upload $e) {
		$code = $e->getCode();

		if (!empty($this->error_messages[$code])) {
			return $this->error_messages[$code];
		}

		return 'unknown';
	}
}

class dynamic__upload extends Dynamic_Upload {}
