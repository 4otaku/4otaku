<?php

class Api_Create_Video extends Api_Create_Abstract
{
	public function process() {

		$title = $this->get('title');

		if (empty($title)) {
			throw new Error_Api('Пропущено обязательное поле: title', Error_Api::MISSING_INPUT);
		}

		$link = $this->get('link');

		if (empty($link)) {
			throw new Error_Api('Пропущено обязательное поле: link', Error_Api::MISSING_INPUT);
		}

		$data = array(
			'title' => $title,
			'link' => $link,
			'tag' => (array) $this->get('tag'),
			'category' => $this->transform_category(),
			'author' => implode(',', (array) $this->get('author')),
			'text' => (string) $this->get('text'),
		);

		try {
			$worker = new Create_Video(new Action_Reader_Inner($data),
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
