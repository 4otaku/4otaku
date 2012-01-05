<?php

class Read_Edit_News extends Read_Edit_Abstract
{
	protected function get_news($url) {
		$news = new Model_News($url[2]);
		$news->load();

		return $news;
	}

	protected function title($url) {

		$news = $this->get_news($url);
		$this->data['title'] = $news['title'];
	}

	protected function text($url) {
		ob_end_clean();

		$news = $this->get_news($url);
		$this->data['text'] = $news['pretty_text'];
	}

	protected function image($url) {

		$news = $this->get_news($url);
		$this->data['image'] = $news['image'];
		$this->data['extension'] = $news['extension'];
	}


	protected function category($url) {

		$news = $this->get_news($url);

		$categories = array_keys($news['meta']['category']);

		$options = Database::order('id', 'asc')->get_vector('category',
			array('alias', 'name'), 'locate(?, area)', '|news|');

		$this->data['category'] = $categories;
		$this->data['option'] = $options;
	}

	protected function author($url) {

		$news = $this->get_news($url);

		$authors = array_keys($news['meta']['author']);

		$authors = Database::get_vector('author',
			array('alias', 'name'),
			Database::array_in('alias', $authors),
			$authors
		);

		$this->data['author'] = $authors;
	}
}
