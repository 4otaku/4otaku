<?php

class Read_Edit_Video extends Read_Edit_Abstract
{
	protected function get_video($url) {
		$video = new Model_Video($url[2]);
		$video->load();

		return $video;
	}

	protected function title($url) {

		$video = $this->get_video($url);
		$this->data['title'] = $video['title'];
	}

	protected function text($url) {
		ob_end_clean();

		$video = $this->get_video($url);
		$this->data['text'] = $video['pretty_text'];
	}

	protected function link($url) {

		$video = $this->get_video($url);
		$this->data['link'] = $video['link'];
	}

	protected function category($url) {

		$video = $this->get_video($url);

		$categories = array_keys($video['meta']['category']);

		$options = Database::order('id', 'asc')->get_vector('category',
			array('alias', 'name'), 'locate(?, area)', '|video|');

		$this->data['category'] = $categories;
		$this->data['option'] = $options;
	}

	protected function tag($url) {

		$video = $this->get_video($url);

		$tags = array_keys($video['meta']['tag']);

		$tags = Database::get_vector('tag',
			array('alias', 'name', 'color'),
			Database::array_in('alias', $tags),
			$tags
		);

		uasort($tags, 'Transform_Array::meta_sort');
		$this->data['tag'] = $tags;
		$this->data['area'] = 'video';
	}

	protected function author($url) {

		$video = $this->get_video($url);

		$authors = array_keys($video['meta']['author']);

		$authors = Database::get_vector('author',
			array('alias', 'name'),
			Database::array_in('alias', $authors),
			$authors
		);

		$this->data['author'] = $authors;
	}
}
