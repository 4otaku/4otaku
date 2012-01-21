<?php

class Api_Create_Art extends Api_Create_Abstract {

	const MAX_IMAGE_URL_LENGTH = 2000;

	public function process() {
		$image = $this->get_image();

		$finfo = new finfo(FILEINFO_MIME);
		$info = $finfo->buffer($image);

		$extension = preg_replace(array('/^.*?\//', '/;.*/'), '', $info);
		$filename = 'image.' . $extension;

		try {
			$uploader = new Transform_Upload_Art($image, $filename);

			$image = $uploader->process_file();
		} catch (Error_Upload $e) {
			throw new Error_Api($e->getMessage(), $e->getCode());
		}

		$data = array(
			'images' => array(array(
				'md5' => $image['md5'],
				'thumb' => $image['thumb'],
				'extension' => $image['extension'],
				'resized' => $image['resized'],
				'animated' => $image['animated'],
			)),
			'tag' => (array) $this->get('tag'),
			'category' => $this->transform_category(),
			'author' => implode(',', (array) $this->get('author')),
			'source' => (string) $this->get('source'),
		);

		try {
			$worker = new Create_Art(new Action_Reader_Inner($data),
				new Action_Writer_Inner());

			$worker->main();

			$result = $worker->process_result();
		} catch (Error_Create $e) {
			throw new Error_Api($e->getMessage(), $e->getCode());
		}

		if ($result->get_error()) {
			throw new Error_Api($result->get_error());
		}

		$success = $result->get_success();
		$this->set_success($success);
		if ($success) {
			$this->add_answer('id', $result->get_data('id'));
		}
	}

	protected function get_image() {
		$image = $this->get('image');

		if (empty($image)) {
			throw new Error_Api('Missing required field: image',
				Error_Api::MISSING_INPUT);
		}

		if (strlen($image) < self::MAX_IMAGE_URL_LENGTH && parse_url($image)) {
			$image = Http::download($image);

		} else {
			$image = base64_decode($image);
		}

		if (empty($image)) {
			throw new Error_Api('Image url can\'t be reached',
				Error_Api::INCORRECT_INPUT);
		}

		return $image;
	}
}
