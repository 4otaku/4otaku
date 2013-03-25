<?php

class Read_Edit_Post_Update extends Read_Edit_Abstract
{
	protected function text($url) {
		ob_end_clean();
		$this->data['text'] = Database::get_field('post_update', 'pretty_text', $this->data['id']);
	}

	protected function link($url) {
		$links = Database::join('post_update_link_url', 'pulu.link_id = pul.id')
			->join('post_url', 'pulu.url_id = pu.id')->order('pul.order', 'asc')
			->order('pulu.order', 'asc')->get_full_vector('post_update_link',
				'update_id = ?', $this->data['id']);

		foreach ($links as &$link) {
			$link = new Model_Post_Link($link);
		}

		$this->data['link'] = $links;
	}

	protected function author($url) {

		$this->data['author'] = Database::get_field('post_update', 'username', $this->data['id']);
	}
}
