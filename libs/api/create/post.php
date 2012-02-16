<?php

class Api_Create_Post extends Api_Create_Abstract
{
	public function process() {
		$title = $this->get('title');

		if (empty($title)) {
			throw new Error_Api('Пропущено обязательное поле: title', Error_Api::MISSING_INPUT);
		}

		$link = $this->get('link');
		$torrents = $this->get('torrent');

		if (empty($link) && empty($torrent)) {
			throw new Error_Api('Пропущены оба поля: link и torrent. Заполните хотя бы одно.', 
				Error_Api::MISSING_INPUT);
		}		
		
		$files = $this->get('file');
		$images = $this->get('image');
		
		$torrents = (array) $torrents;
		foreach ($torrents as $index => $torrent) {
			try {
				$torrent = $this->get_file($torrent);

				$uploader = new Transform_Upload_Post_Torrent($torrent, 'file.torrent');

				$torrent = $uploader->process_file();
			} catch (Error $e) {
				$number = $index + 1;
				$message = 'Не удалось добавить торрент №' . $number . '; Содержимое: ' . substr($torrent, 0, 2000);
				
				if (strlen($message) > 2000) {
					$message .= ' ...';
				}
				
				$this->add_error($e->getCode(), $message);
				unset($torrents[$index]);
			}
		}
		
		$torrents = (array) $torrents;
		foreach ($torrents as $index => &$torrent) {
			try {
				$torrent = $this->get_file($torrent);

				$uploader = new Transform_Upload_Post_Torrent($torrent, 'file.torrent');

				$torrent = $uploader->process_file();
			} catch (Error $e) {
				$number = $index + 1;
				$message = 'Не удалось добавить торрент №' . $number . '; Содержимое: ' . substr($torrent, 0, 2000);
				
				if (strlen($torrent) > 2000) {
					$message .= ' ...';
				}
				
				$this->add_error($e->getCode(), $message);
				unset($torrents[$index]);
			}
		}
		
		$files = (array) $files;
		foreach ($files as $index => &$file) {
			try {
				$file = $this->get_file($file);
				
				$extension = $this->get_extension($image);
				$filename = 'file.' . $extension;

				$uploader = new Transform_Upload_Post_File($file, $filename);

				$file = $uploader->process_file();
			} catch (Error $e) {
				$number = $index + 1;
				$message = 'Не удалось добавить файл №' . $number . '; Содержимое: ' . substr($file, 0, 2000);
				
				if (strlen($file) > 2000) {
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
			'link' => $link,
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
