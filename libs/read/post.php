<?php

class Read_Post extends Read_Main
{
	protected $template = 'main/post';
	protected $error_template = 'error/post';

	protected $show_template = 'dynamic/post/show';

	protected $side_modules = array(
		'head' => array('js', 'css'),
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
			$torrents_data = array();
			$raw_data = (array) Database::db('tracker')->get_table('peers',
				array('info_hash', 'state'), Database::array_in('info_hash', $hashes, true));
			foreach ($raw_data as $data) {
				$key = $data['info_hash'];
				if (empty($torrents_data[$key])) {
					$torrents_data[$key] = array(
						'seeders' => 0, 'leechers' => 0
					);
				}

				if ($data['state']) {
					$torrents_data[$key]['seeders']++;
				} else {
					$torrents_data[$key]['leechers']++;
				}
			}

			foreach ($torrents as &$torrent) {
				$hash = pack("H*", $torrent['hash']);
				if (!empty($torrents_data[$hash])) {
					$torrent['seeders'] = $torrents_data[$hash]['seeders'];
					$torrent['leechers'] = $torrents_data[$hash]['leechers'];
				} else {
					$torrent['seeders'] = 0;
					$torrent['leechers'] = 0;
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
	protected function get_title() {

		$short = def::site('short_name') . ' ';

		if (count($this->data['items']) == 1) {
			$item = reset($this->data['items']);
			if (empty($item['in_batch'])) {
				return $short . $item->get_title();
			}
		}

		if (count($this->meta) > 1) {
			return $short . 'Записи. Просмотр сложной выборки.';
		}

		if (count($this->meta) == 1) {
			$meta = reset($this->meta);
			if (!$meta->is_simple()) {
				return $short . 'Записи. Просмотр сложной выборки.';
			}
			$type = $meta->get_type();
			$alias = $meta->get_meta();
			$name = Database::get_field($type, 'name', 'alias = ?', $alias);

			$meta_name = $meta->get_type_rus();

			return $short . 'Записи. '.$meta_name.': '.$name;
		}

		return def::site('name');
	}
}
