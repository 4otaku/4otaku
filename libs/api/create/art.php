<?php

class Api_Create_Art extends Api_Create_Abstract
{
	public function process() {

		$image = $this->get_file($this->get('image'),
			'Пропущено обязательное поле: image',
			'Не удалось скачать картинку с указнного адреса'
		);

		$extension = $this->get_extension($image);
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
			throw new Error_Api($result->get_message(), $result->get_error());
		}

		$success = $result->get_success();
		$this->set_success($success);
		if ($success) {
			$this->add_answer('id', $result->get_data('id'));
		}
	}
}
