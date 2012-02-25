<?php

class Create_Post extends Create_Abstract
{
	protected $field_rights = array(
		'transfer_to' => 1
	);

	protected $function_rights = array(
		'update' => 1
	);

	public function main() {

		$post = $this->correct_main_data($this->reader->get_data());

		if (!$post['title'] || (!$post['link'] && !$post['torrent'])) {
			$this->writer->set_message('Не все обязательные поля заполнены.')
				->set_error(Error_Create::MISSING_INPUT);
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
		$language = $worker->language($post['language']);
		$parsed_author = $worker->parse($post['author'], def::user('author'));
		$author = $worker->author($parsed_author);

		$text = Transform_Text::format($post['text']);

		$links = Transform_Link::parse($post['link']);
		$extras = Transform_Link::parse($post['bonus_link']);

		$images = $post['image'];
		$torrents = $post['torrent'];
		$files = $post['file'];

		$item = new Model_Post();
		$item->set_array(array(
			'title' => $post['title'],
			'text' => $text,
			'pretty_text' => undo_safety($post['text']),
			'author' => $author,
			'category' => $category,
			'language' => $language,
			'tag' => $tags
		));

		foreach($images as $image) {
			$image = explode('.', $image);
			$image = new Model_Post_Image(array(
				'file' => $image[0],
				'extension' => $image[1]
			));
			$item->add_image($image);
		}

		foreach($links as $link) {
			$link = new Model_Post_Link($link);
			$item->add_link($link);
		}

		foreach($torrents as $torrent) {
			$torrent = new Model_Post_Torrent($torrent);
			$item->add_torrent($torrent);
		}

		foreach($extras as $extra) {
			$extra = new Model_Post_Extra($extra);
			$item->add_extra($extra);
		}

		foreach($files as $file) {
			$file = new Model_Post_File($file);
			$item->add_file($file);
		}

		$item->insert();

		// TODO: перемести input__common::transfer в Model_Common
		if (!empty($post['transfer_to'])) {
			input__common::transfer(array(
				'sure' => 1,
				'do' => array('post', 'transfer'),
				'where' => $post['transfer_to'],
				'id' => $item->get_id()
			));
		}

		$this->writer->set_success()->set_message('Ваша запись успешно добавлена, и доступна по адресу '.
			'<a href="/post/'.$item->get_id().'/">http://4otaku.ru/post/'.$item->get_id().'/</a> или в '.
			'<a href="/post/'.def::area(1).'/">мастерской</a>.')
			->set_param('id', $item->get_id());;
	}

	public function update() {

		if (!is_numeric(query::$post['id'])) {
			$this->writer->set_message('Что-то странное с формой обновления, сообщите администрации');
			return;
		}

		$author = trim(strip_tags(query::$post['author']));
		if (empty($author)) {
			$this->writer->set_message('Вы забыли указать автора обновления');
			return;
		}

		$text = Transform_Text::format(query::$post['text']);
		if (!trim(strip_tags($text))) {
			$this->writer->set_message('Вы забыли добавить описание обновления');
			return;
		}

		$links = array();
		foreach (query::$post['link'] as $link) {
			if (!empty($link['use'])) {
				unset($link['use']);
				$links[] = $link;
			}
		}

		$links = Transform_Link::parse($links);
		if (empty($links)) {
			$this->writer->set_message('Проверьте ссылки, с ними была какая-то проблема');
			return;
		}

		$update = new Model_Post_Update(array(
			'post_id' => query::$post['id'],
			'username' => $author,
			'text' => $text,
			'pretty_text' => undo_safety(query::$post['text'])
		));

		foreach ($links as $link) {
			$link = new Model_Post_Update_Link($link);
			$update->add_link($link);
		}

		$update->insert();

		$this->writer->set_success()->set_message('Запись успешно обновлена');
	}

	protected function correct_main_data($data) {

		if (empty($data['tags'])) {
			$data['tags'] = '';
		}

		if (empty($data['torrent'])) {
			$data['torrent'] = array();
		}

		if (empty($data['file'])) {
			$data['file'] = array();
		}

		if (empty($data['image'])) {
			$data['image'] = array();
		}

		$data['link'] = Check::link_array($data['link']);
		$data['bonus_link'] = Check::link_array($data['bonus_link']);

		return $data;
	}
}
