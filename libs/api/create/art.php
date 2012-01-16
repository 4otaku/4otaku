<?php

class Api_Create_Art extends Api_Create_Abstract {

	const MAX_IMAGE_URL_LENGTH = 2000;

	public function process() {
		$image = $this->get_image();
	}

	protected function get_image() {
		$image = $this->get('image');

		if (empty($image)) {
			throw new Error_Api('Missing required field: image',
				Api_Error::MISSING_INPUT);
		}

		if (strlen($image) < self::MAX_IMAGE_URL_LENGTH && parse_url($image)) {
			$image = Http::download($image);

			if (empty($image)) {
				throw new Error_Api('Image url can\'t be reached',
					Api_Error::INCORRECT_INPUT);
			}
		}

		return $image;
	}
}
