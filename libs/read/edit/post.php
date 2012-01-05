<?php

class Read_Edit_Post extends Read_Edit_Abstract
{
	protected function title($url) {
		$this->data['title'] = Database::get_field('post', 'title', $url[2]);
	}

	protected function image($url) {
		$images = Database::order('order', 'asc')->get_full_table('post_image',
			'post_id = ?', $url[2]);

		foreach ($images as &$image) {
			$image = new Model_Post_Image($image);
		}

		$this->data['image'] = $images;
	}

	protected function text($url) {
		ob_end_clean();
		$this->data['text'] = Database::get_field('post', 'pretty_text', $url[2]);
	}

	protected function link($url) {
		$links = Database::join('post_link_url', 'plu.link_id = pl.id')
			->join('post_url', 'plu.url_id = pu.id')->order('pl.order', 'asc')
			->order('plu.order', 'asc')->get_full_vector('post_link',
				'post_id = ?', $url[2]);

		foreach ($links as &$link) {
			$link = new Model_Post_Link($link);
		}

		$this->data['link'] = $links;
	}

	protected function torrent($url) {
		$torrents = Database::order('order', 'asc')->get_full_table('post_torrent',
			'post_id = ?', $url[2]);

		foreach ($torrents as &$torrent) {
			$torrent = new Model_Post_Torrent($torrent);
		}

		$this->data['torrent'] = $torrents;
	}

	protected function file($url) {
		$files = Database::order('order', 'asc')->get_full_table('post_file',
			'post_id = ?', $url[2]);

		foreach ($files as &$file) {
			$file = new Model_Post_File($file);
		}

		$this->data['file'] = $files;
	}

	protected function extra($url) {
		$extras = Database::order('order', 'asc')->get_full_table('post_extra',
			'post_id = ?', $url[2]);

		foreach ($extras as &$extra) {
			$extra = new Model_Post_Extra($extra);
		}

		$this->data['extra'] = $extras;
	}

	protected function category($url) {

		$post = new Model_Post($url[2]);
		$post->load();

		$categories = array_keys($post['meta']['category']);

		$options = Database::order('id', 'asc')->get_vector('category',
			array('alias', 'name'), 'locate(?, area)', '|post|');

		$this->data['category'] = $categories;
		$this->data['option'] = $options;
	}

	protected function language($url) {

		$post = new Model_Post($url[2]);
		$post->load();

		$languages = array_keys($post['meta']['language']);

		$options = Database::order('id', 'asc')->get_vector('language',
			array('alias', 'name'));

		$this->data['language'] = $languages;
		$this->data['option'] = $options;
	}

	protected function tag($url) {

		$post = new Model_Post($url[2]);
		$post->load();

		$tags = array_keys($post['meta']['tag']);

		$tags = Database::get_vector('tag',
			array('alias', 'name', 'color'),
			Database::array_in('alias', $tags),
			$tags
		);

		uasort($tags, 'Transform_Array::meta_sort');
		$this->data['tag'] = $tags;
		$this->data['area'] = 'post';
	}

	protected function author($url) {

		$post = new Model_Post($url[2]);
		$post->load();

		$authors = array_keys($post['meta']['author']);

		$authors = Database::get_vector('author',
			array('alias', 'name'),
			Database::array_in('alias', $authors),
			$authors
		);

		$this->data['author'] = $authors;
	}
}
