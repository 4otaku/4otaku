<?php

class Create_News extends Create_Abstract
{
	protected $minimal_rights = 1;

	public function main() {

		$post = $this->correct_main_data($this->reader->get_data());

		if (!$post['title']) {
			$this->writer->set_message('Вы забыли указать заголовок для новости.');
			return;
		}

		if ($post['author'] != def::user('name') && $post['author']) {
			$cookie = new dynamic__cookie();
			$cookie->inner_set('user.name', $post['author']);
		}

		$worker = new Transform_Meta();

		$category = $worker->category($post['category']);
		$parsed_author = $worker->parse($post['author'], def::user('author'));
		$author = $worker->author($parsed_author);

		$text = Transform_Text::format($post['text']);

		$item = new Model_News();
		$item->set_array(array(
			'title' => $post['title'],
			'text' => $text,
			'pretty_text' => undo_safety($post['text']),
			'image' => $post['image']['image'],
			'extension' => $post['image']['extension'],
			'author' => $author,
			'category' => $category
		));

		$item->insert();

		$this->writer->set_success()->set_message('Ваша новость успешно добавлена, и доступна по адресу '.
			'<a href="/news/'.$item->get_id().'/">http://4otaku.ru/news/'.$item->get_id().'/</a>.');
	}

	protected function correct_main_data($data) {

		if (empty($data['title'])) {
			$data['title'] = '';
		}

		if (empty($data['image'])) {
			$data['image'] = array('image' => '', 'extension' => '');
		} else {
			$image = explode('.', $data['image']);
			$data['image'] = array('image' => $image[0], 'extension' => $image[1]);
		}

		return $data;
	}
}
