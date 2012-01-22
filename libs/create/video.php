<?php

class Create_Video extends Create_Abstract
{
	protected $field_rights = array(
		'transfer_to' => 1
	);

	public function main() {

		$post = $this->correct_main_data($this->reader->get_data());

		if (!$post['title']) {
			$this->writer->set_message('Вы забыли указать заголовок для видео.')
				->set_error(Error_Create::MISSING_INPUT);
			return;
		}

		if (!$post['link']) {
			$this->writer->set_message('Вы не предоставили ссылки, или же ссылка почему-то битая.')
				->set_error(Error_Create::MISSING_INPUT);
			return;
		}

		$test = new Transform_Video($post['link']);
		if(!$test->enable_nico()->get_html()) {
			$this->writer->set_message('Ссылка не ведет на видео, либо этот видеохостинг не поддерживается')
				->set_error(Error_Create::INCORRECT_INPUT);
			return;
		}

		$already_have = Database::get_field('video', 'id', 'link = ?', $post['link']);
		if ($already_have) {
			$error = 'Это видео уже у нас есть, оно находится по адресу <a href="/video/'.
				$already_have.'/">http://4otaku.ru/video/'.$already_have.'/</a>.';
			$this->writer->set_message($error)->set_error(Error_Create::ALREADY_EXISTS);
			return;
		}

		if ($post['author'] != def::user('name') && $post['author']) {
			$cookie = new dynamic__cookie();
			$cookie->inner_set('user.name', $post['author']);
		}

		$worker = new Transform_Meta();

		$parsed_tags = $worker->parse_array($post['tag']);
		$tags = $worker->add_tags($parsed_tags);
		$category = $worker->category($post['category']);
		$parsed_author = $worker->parse($post['author'], def::user('author'));
		$author = $worker->author($parsed_author);

		$text = Transform_Text::format($post['text']);

		$item = new Model_Video();
		$item->set_array(array(
			'title' => $post['title'],
			'link' => $post['link'],
			'text' => $text,
			'pretty_text' => undo_safety($post['text']),
			'author' => $author,
			'category' => $category,
			'tag' => $tags
		));

		$item->insert();

		// TODO: перемести input__common::transfer в Model_Common
		if (!empty($post['transfer_to'])) {
			input__common::transfer(array(
				'sure' => 1,
				'do' => array('video', 'transfer'),
				'where' => $post['transfer_to'],
				'id' => $item->get_id()
			));
		}

		$this->writer->set_success()->set_message('Ваша видео успешно добавлено, и доступно по адресу '.
			'<a href="/video/'.$item->get_id().'/">http://4otaku.ru/video/'.$item->get_id().'/</a> или в '.
			'<a href="/video/'.def::area(1).'/">очереди на премодерацию</a>.')
			->set_param('id', $item->get_id());
	}

	protected function correct_main_data($data) {

		if (empty($data['title'])) {
			$data['title'] = '';
		}

		$data['link'] = Check::link(undo_safety($data['link']));

		return $data;
	}
}
