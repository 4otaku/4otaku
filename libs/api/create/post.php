<?php

class Api_Create_Post extends Api_Create_Abstract
{
	protected $link_size_types = array(
		'кб' => 0,
		'мб' => 1,
		'гб' => 2,
		'килобайт' => 0,
		'мегабайт' => 1,
		'гигабайт' => 2,
		'kb' => 0,
		'mb' => 1,
		'gb' => 2,
		'kilobyte' => 0,
		'megabyte' => 1,
		'gigabyte' => 2,						
	);
	
	public function process() {
		$title = $this->get('title');

		if (empty($title)) {
			throw new Error_Api('Пропущено обязательное поле: title', Error_Api::MISSING_INPUT);
		}
		
		if ($id = Database::get_field('post', 'id', 'title = ? and area != "deleted"', $title)) {
			$this->add_answer('id', $id);
			throw new Error_Api('Запись с таким title уже есть', Error_Api::INCORRECT_INPUT);
		}		

		$links = $this->get('link');
		$torrents = $this->get('torrent');

		if (empty($links) && empty($torrent)) {
			throw new Error_Api('Пропущены оба поля: link и torrent. Заполните хотя бы одно.', 
				Error_Api::MISSING_INPUT);
		}		
		
		$files = $this->get('file');
		$images = $this->get('image');
		
		$links = (array) $links;
		foreach ($links as $index => &$link) {
			if (strpos($link['size'], ' ')) {
				$linksize = preg_split('/ +/u', $link['size']);
			}
			$link['size'] = $linksize[0];
			$link['sizetype'] = $linksize[1];
			if (array_key_exists($link['sizetype'], $this->link_size_types)) {
				 $link['sizetype'] = $this->link_size_types[$link['sizetype']];
			}
		}
		
		$torrents = (array) $torrents;
		foreach ($torrents as $index => &$torrent) {
			try {
				if (empty($torrent['name'])) {
					$torrent['name'] = 'download';
				}
				$torrent['file'] = $this->get_file($torrent['file']);

				$uploader = new Transform_Upload_Post_Torrent($torrent['file'], $torrent['name'] . '.torrent');

				$torrent = $uploader->process_file();
			} catch (Error $e) {
				$number = $index + 1;
				$message = 'Не удалось добавить торрент №' . $number . '; Содержимое: ' . substr($torrent['file'], 0, 2000);
				
				if (strlen($torrent['file']) > 2000) {
					$message .= ' ...';
				}
				
				$this->add_error($e->getCode(), $message);
				unset($torrents[$index]);
			}
		}
		
		$files = (array) $files;
		foreach ($files as $index => &$file) {
			try {
				if (empty($file['name'])) {
					if (preg_match(Transform_Text::URL_REGEX, substr($file['file'], 0, 1000))) {
						$file['name'] = basename($file['file']);
					} 
					
					if (empty($file['name'])) {
						$file['name'] = 'file';
					}
				}				
				$file['file'] = $this->get_file($file['file']);
				
				if (!strpos($file['name'], '.')) {
					$extension = $this->get_extension($file['file']);
					$file['name'] = $file['name'] . '.' . $extension;
				}

				$uploader = new Transform_Upload_Post_File($file['file'], $file['name']);

				$file = $uploader->process_file();
			} catch (Error $e) {
				$number = $index + 1;
				$message = 'Не удалось добавить файл №' . $number . '; Содержимое: ' . substr($file['file'], 0, 2000);
				
				if (strlen($file['file']) > 2000) {
					$message .= ' ...';
				}
				
				$this->add_error($e->getCode(), $message);
				unset($files[$index]);
			}
		}	

		$images = (array) $images;
		foreach ($images as $index => &$image) {
			try {
				$image = $this->get_file($image);
				
				$extension = $this->get_extension($image);
				$filename = 'image.' . $extension;

				$uploader = new Transform_Upload_Post_Image($image, $filename);

				$image = $uploader->process_file();
				$image = $image['data'];
			} catch (Error $e) {
				$number = $index + 1;
				$message = 'Не удалось добавить изображение №' . $number . '; Содержимое: ' . substr($image, 0, 2000);
				
				if (strlen($image) > 2000) {
					$message .= ' ...';
				}
				
				$this->add_error($e->getCode(), $message);
				unset($images[$index]);
			}
		}

		$data = array(
			'title' => $title,
			'link' => $links,
			'torrent' => $torrents,
			'bonus_link' => $this->get('bonus_link'),
			'file' => $files,
			'image' => $images,
			'tag' => (array) $this->get('tag'),
			'category' => $this->transform_category(),
			'author' => implode(',', (array) $this->get('author')),
			'language' => $this->transform_language(),
			'text' => (string) $this->get('text'),
		);

		try {
			$worker = new Create_Post(new Action_Reader_Inner($data),
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
