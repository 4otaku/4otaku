<?php

class Read_Post extends Read_Main
{
	protected $template = 'main/post';
	protected $error_template = 'error/post';

	protected $show_template = 'dynamic/post/show';

	protected $side_modules = array(
		'head' => array('title', 'js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('search', 'comments','update','orders','tags'),
		'footer' => array('year')
	);

	public function __construct() {
		parent::__construct();

		$cookie = new dynamic__cookie();
		$cookie->inner_set('visit.post', time(), false);

		$this->per_page = sets::pp('post');
	}

	// @TODO: public - хак для поиска и RSS, заменить на protected при возможности
	public function get_item($id) {
		$item = new Model_Post($id);
		$this->load_meta($item);

		$this->get_post_data($item);

		if ($item['area'] == 'workshop' || sets::user('rights')) {
			$item['is_editable'] = true;
		}
		$this->load_meta($item);

		$item['update_count'] = Database::get_field('post_update',
			'count(*)', 'post_id = ?', $id);

		query::$url['area'] = $this->area = $item['area'];

		$this->data['items'] = array($id => $item);
		$this->data['base'] = '/post/';
	}

	protected function get_items() {

		$items = $this->load_batch('post');

		foreach ($items as $id => &$item) {
			$item['id'] = $id;
			$item = new Model_Post($item);

			if ($this->area == 'workshop' || sets::user('rights')) {
				$item['is_editable'] = true;
			}
		}

		$this->load_meta($items);

		$this->get_post_data($items);

		$this->data['items'] = $items;
		if ($this->count > $this->per_page) {
			$this->data['navi'] = $this->get_bottom_navi('post');
		}
	}

	protected function get_post_data($items) {
		if (is_object($items)) {
			$items = array($items['id'] => $items);
		}

		$keys = array_keys($items);

		$images = Database::order('order', 'asc')->get_full_table('post_image',
			Database::array_in('post_id', $keys), $keys);

		foreach ($images as $image) {
			$image = new Model_Post_Image($image);
			$items[$image['post_id']]->add_image($image);
		}

		$links = Database::join('post_link_url', 'plu.link_id = pl.id')
			->join('post_url', 'plu.url_id = pu.id')->order('pl.order', 'asc')
			->order('plu.order', 'asc')->get_full_table('post_link',
				Database::array_in('pl.post_id', $keys), $keys);

		foreach ($links as $link) {
			$link = new Model_Post_Link($link);
			$items[$link['post_id']]->add_link($link);
		}

		$torrents = Database::order('order', 'asc')->get_full_table('post_torrent',
			Database::array_in('post_id', $keys), $keys);

		$hashes = array();
		foreach ($torrents as $torrent) {
			$hashes[] = $torrent['hash'];
		}

		if (!empty($hashes)) {
			$torrents_data = (array) Database::db('tracker')->get_vector('xbt_files',
				array('info_hash', 'seeders', 'leechers'),
				Database::array_in('info_hash', $hashes, true));
			foreach ($torrents as &$torrent) {
				$hash = pack("H*", $torrent['hash']);
				if (!empty($torrents_data[$hash])) {
					$torrent['seeders'] = $torrents_data[$hash]['seeders'];
					$torrent['leechers'] = $torrents_data[$hash]['leechers'];
				}
			}
			unset($torrent);
		}

		foreach ($torrents as $torrent) {
			$torrent = new Model_Post_Torrent($torrent);
			$items[$torrent['post_id']]->add_torrent($torrent);
		}

		$files = Database::order('order', 'asc')->get_full_table('post_file',
				Database::array_in('post_id', $keys), $keys);

		foreach ($files as $file) {
			$file = new Model_Post_File($file);
			$items[$file['post_id']]->add_file($file);
		}

		$extras = Database::order('order', 'asc')->get_full_table('post_extra',
				Database::array_in('post_id', $keys), $keys);

		foreach ($extras as $extra) {
			$extra = new Model_Post_Extra($extra);
			$items[$extra['post_id']]->add_extra($extra);
		}
	}

	protected function get_navigation() {
		return array(
			'tag' => $this->get_navi_tag('post'),
			'category' => $this->get_navi_category('post'),
			'language' => $this->get_navi_language(),
			'rss' => $this->get_navi_rss('post')
		);
	}

	protected function display_index($url) {

		$this->get_items();
	}

	protected function display_single_item($url) {

		$this->set_page($url, 4);

		$this->get_item($url[1]);
		$this->data['comment'] = $this->get_comments('post', $url[1]);

		if ($this->count > $this->per_page) {
			$this->data['navi'] = $this->get_comment_navi($url[1]);
		}

		$this->data['single'] = true;
	}

	protected function display_show($url) {
		$this->get_item($url[2]);

		if ($url[3] == 'batch') {
			foreach ($this->data['items'] as $item) {
				$item['in_batch'] = true;
			}
		}

		$this->template = $this->show_template;
	}

	protected function display_page($url) {

		$this->set_page($url, 2);
		$this->get_items();
	}

	protected function display_tag($url) {

		$this->set_page($url, 4);
		$this->set_meta($url, 2, 'tag');
		$this->get_items();
	}

	protected function display_author($url) {

		$this->set_page($url, 4);
		$this->set_meta($url, 2, 'author');
		$this->get_items();
	}

	protected function display_category($url) {

		$this->set_page($url, 4);
		$this->set_meta($url, 2, 'category');
		$this->get_items();
	}

	protected function display_language($url) {

		$this->set_page($url, 4);
		$this->set_meta($url, 2, 'language');
		$this->get_items();
	}

	protected function display_mixed($url) {

		$this->set_page($url, 4);
		$this->set_mixed($url, 2);
		$this->get_items();
	}

	protected function display_updates($url) {

		array_shift($url);
		$worker = new Read_Post_Update();

		$worker->process($url);
	}

	protected function display_gouf($url) {

		array_shift($url);
		$worker = new Read_Post_Gouf();

		$worker->process($url);
	}
}
