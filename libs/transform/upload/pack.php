<?php

class Transform_Upload_Pack extends Transform_Upload_Abstract
{
	protected $title = '';
	protected $text = '';

	public function set_title($title) {
		$this->title = (string) $title;

		return $this;
	}

	public function set_text($text) {
		$this->text = (string) $text;

		return $this;
	}

	protected function get_max_size() {
		return def::art('packsize');
	}

	protected function test_file() {
		parent::test_file();

		$md5 = md5_file($this->file);
		if (
			$id = Database::get_field('art_pack', 'id', 'title = ?', $this->title)
		) {
			throw new Error_Upload($id, Error_Upload::ALREADY_EXISTS);
		}

		$this->md5 = $md5;
	}

	protected function process() {
		Database::insert('art_pack', array(
			'filename' => $this->name,
			'title' => $this->title,
			'text' => Transform_Text::format(trim($this->text)),
			'pretty_text' => $this->text
		));

		$id = Database::last_id();
		Database::insert('misc', array(
			'type' => 'pack_status',
			'data1' => 'starting',
			'data2' => $id
		));

		$newfile = ROOT_DIR.SL.'files'.SL.'pack_uploaded'.SL.$id.'.zip';

		if (!move_uploaded_file($this->file, $newfile)) {
			$contents = file_get_contents($this->file);
			$start = substr($contents, 0, 3000);
			$end = substr($contents, 3000);
			unset($contents);
			$new_start = preg_replace('/^.{0,2000}?Content-Type:\s+application\/zip\s+/is', '', $start);

			if (empty($new_start)) {
				$new_start = $start;
			}

			file_put_contents($newfile, $start.$end);
		}

		$this->set(array(
			'success' => true,
			'id' => $id,
			'file' => $this->name
		));
	}
}
