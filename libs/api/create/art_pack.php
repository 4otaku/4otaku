<?php

class Api_Create_Art_Pack extends Api_Create_Abstract
{
	const
		ALREADY_EXISTS_MESSAGE = 'CG-пак с таким заголовком уже существует. Убедитесь что он не содержит то же самое, и если нет, поменяйте название своего пака.',
		ONLY_ZIP_MESSAGE = 'Пока принимаются только zip-архивы.';

	public function process() {

		$title = $this->get('title');
		$text = $this->get('text');
		$filename = $this->get('filename');

		if (empty($title)) {
			throw new Error_Api('Пропущено обязательное поле: title', Error_Api::MISSING_INPUT);
		}

		$pack = $this->get_file($this->get('archive'),
			'Пропущено обязательное поле: archive',
			'Не удалось скачать архив с указнного адреса'
		);

		$extension = $this->get_extension($pack);

		if ($extension != 'zip') {
			throw new Error_Api(self::ONLY_ZIP_MESSAGE, Error_Api::INCORRECT_INPUT);
		}

		try {
			if (empty($filename)) {
				$filename = 'temp.zip';
			}

			$uploader = new Transform_Upload_Pack($pack, $filename);
			$uploader->set_title($title)->set_text($text);

			$result = $uploader->process_file();
		} catch(Error_Upload $e) {

			throw new Error_Api($e->getMessage(), $e->getCode());
		}

		$this->set_success(true);
		$this->add_answer('id', $result['id']);
	}
}
