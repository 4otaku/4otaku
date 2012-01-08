<?php

// Класс для основных контентых разделов

abstract class Read_Main extends Read_Abstract
{
	protected $area = 'main';
	protected $meta = array();

	protected $possible_areas = array(
		'main', 'workshop', 'flea_market'
	);

	protected $rss_name = array(
		'tag' => 'тега',
		'author' => 'автора',
		'language' => 'языка',
		'category' => 'категории',
		'pool' => 'группы'
	);


	abstract protected function get_item($id);
	abstract protected function get_items();
	abstract protected function get_navigation();

	public function process($url) {
		if (isset($url[1]) && in_array($url[1], $this->possible_areas)) {

			$this->area = $url[1];

			array_splice($url, 1, 1);
		}

		query::$url['area'] = $this->area;

		parent::process($url);
	}

	protected function load_batch($table) {

		$start = ($this->page - 1) * $this->per_page;

		$condition = 'area = ?';
		$params = array($this->area);

		foreach ($this->meta as $meta) {

			$condition .= $meta->get_condition();
			$params = array_merge($params, $meta->get_params());
		}

		$return = Database::set_counter()->order('sortdate')
			->limit($this->per_page, $start)
			->get_full_vector($table, $condition, $params);

		$this->count = Database::get_counter();

		foreach ($return as &$item) {
			$item['in_batch'] = true;
		}

		return $return;
	}

	protected function do_output($template, $data = array()) {
		$data['navigation'] = $this->get_navigation();
		parent::do_output($template, $data);
	}

	protected function set_meta($url, $index, $type) {
		if (!ctype_alnum($type) || empty($url[$index])) {
			return;
		}

		$this->meta[] = new Query_Meta($url[$index], $type);
	}

	protected function set_mixed($url, $index) {
		if (empty($url[$index])) {
			return;
		}

		$temp_params = explode('&', str_replace(' ', '+', $url[$index]));
		$params = array();
		foreach ($temp_params as $param) {
			$param_part = explode('=', $param);
			$params[$param_part[0]] = $param_part[1];
		}

		$mixed = array();
		foreach ($params as $key => $param) {

			$value = ''; $sign = "+";
			for ($i = 0; $i <= strlen($param); $i++) {
				if ($param{$i} == "+" || $param{$i} == "-" || $i == strlen($param)) {

					if (!empty($value)) {
						$data = explode(',', urldecode($value));
						$this->meta[] = new Query_Meta($data, $key, $sign);
					}
					$sign = $param{$i}; $value = '';
				} else {
					$value .= $param{$i};
				}
			}
		}
	}

	protected function load_meta($models) {
		if (is_object($models)) {
			$models = array($models);
		}

		foreach ($models as $key => $model) {
			if ($model->is_phantom()) {
				unset($models[$key]);
			}
		}

		$meta = array();
		foreach ($models as $model) {
			$meta = array_replace_recursive($meta, $model->get('meta'));
		}

		foreach ($meta as $table => $data) {

			$aliases = array_keys($data);
			if ($table != 'tag') {

				$meta[$table] = Database::get_vector($table,
					array('alias', 'name',  'id'),
					Database::array_in('alias', $aliases),
					$aliases
				);
			} else {

				$meta[$table] = Database::get_vector($table,
					array('alias', 'name', 'color', 'variants', 'have_description'),
					Database::array_in('alias', $aliases),
					$aliases
				);
				if (!empty($meta[$table])) {
					foreach ($meta[$table] as &$one) {
						$one['variants'] = array_filter(explode('|',trim($one['variants'],'|')));
					}
				}
			}
		}

		foreach ($meta as $table => $data) {

			foreach ($data as $alias => $item) {

				$url_meta = $this->meta;
				foreach ($url_meta as $key => $object) {
					if ($object->get_type() == $table && $object->have_meta($alias)) {
						unset($url_meta[$key]);
					}
				}
				$url_meta_add = $url_meta;
				$url_meta_remove = $url_meta;
				$url_meta_add[] = new Query_Meta($alias, $table);
				$url_meta_remove[] = new Query_Meta($alias, $table, '-');

				$meta[$table][$alias]['mixed_add'] =
					$this->make_meta_url($url_meta_add);
				$meta[$table][$alias]['mixed_remove'] =
					$this->make_meta_url($url_meta_remove);
			}
		}


		foreach ($models as $model) {
			$model_meta = $model->get('meta');
			foreach ($model_meta as $type => &$data) {
				foreach ($data as $alias => $item) {
					if (!empty($meta[$type][$alias])) {
						$data[$alias] = $meta[$type][$alias];
					}
				}
				uasort($data, 'Transform_Array::meta_sort');
			}
			$model->set('meta', $model_meta);
		}
	}

	protected function get_navi_category($type) {

		return Database::order('id', 'asc')->get_vector('category',
			array('alias', 'name'), 'locate(?, area)', $type);
	}

	protected function get_navi_language() {

		return Database::order('id', 'asc')->
			get_vector('language', array('alias', 'name'));
	}

	protected function get_navi_tag($type) {

		if ($this->area != def::area(2)) {
			$area = $type.'_'.def::area(0);
		} else {
			$area = $type.'_'.def::area(2);
		}

		return Database::order($area)->limit(70)
			->get_vector('tag', array('alias', 'name'));
	}

	protected function get_navi_rss($area) {

		$return = array();

		if (count($this->meta) == 1 && ($item = reset($this->meta)) &&
			$item->is_simple()) {

			$type = $item->get_type();
			$value = $item->get_meta();
			$return['metaname'] = Database::get_field($type,
				'name', 'alias = ?', $value);
			$return['typename'] = $this->rss_name[$return['type']];
		} else {
			$type = 'mixed';
			$value = $this->make_meta_url($this->meta);
			if (strlen($value) < 8) {
				return $return;
			}

			$value = substr($value, 6);
			$return['metaname'] = false;
			$return['typename'] = false;
		}

		$return['link'] = _base64_encode("$area|$type|$value");

		return $return;
	}

	protected function get_bottom_navi($type) {
		$return = array();

		$return['curr'] = $this->page;

		$return['last'] = ceil($this->count / $this->per_page);

		$return['start'] = max($return['curr'] - 5, 2);
		$return['end'] = min($return['curr'] + 6, $return['last'] - 1);

		if (count($this->meta)) {
			$return['meta'] = $this->make_meta_url($this->meta);
		} else {
			$return['meta'] = '';
		}

		$area = $this->area != def::area(0) ? '/' . $this->area : '';
		$return['base'] = '/' . $type . $area . '/';

		return $return;
	}

	protected function make_meta_url($meta) {
		if (count($meta) == 1) {
			$item = reset($meta);
			if ($item->is_simple()) {
				return $item->get_type() . '/' .
					$item->get_meta() . '/';
			}
		}

		$return = 'mixed/';

		$parts = array();
		foreach ($meta as $item) {

			$type = $item->get_type();
			if (!isset($parts[$type])) {
				$parts[$type] = array();
			}

			$parts[$type][] = $item->get_sign() . $item->get_meta();
		}

		foreach ($parts as $type => $part) {
			$parts[$type] = $type . '=' . ltrim(implode($part), '+');
		}

		$return .= implode('&', $parts) . '/';
		return $return;
	}

	protected function get_comments($id) {
		$place = $this->get_place();

		$query = Database::set_counter();

		$this->per_page = sets::pp('comment_in_post');
		if (is_numeric($this->page)) {
			$start = ($this->page - 1) * $this->per_page;

			$query->limit($this->per_page, $start);
		} else {
			$start = 0;
		}

		if (sets::dir('comments_tree')) {
			$query->order('sortdate');
		} else {
			$query->order('sortdate', 'asc');
		}

		$condition = 'place = ? and post_id= ? and rootparent = ? and area != ?';
		$params = array($place, $id, 0, 'deleted');

		$comments = $query->get_full_vector('comment', $condition, $params);
		$this->count = $query->get_counter();

		if (sets::dir('comments_tree')) {
			$label = $this->count - $start + 1;
		} else {
			$label = $start;
		}

		foreach ($comments as $id => &$comment) {
			$comment['id'] = $id;
			$comment = new Model_Comment($comment);

			if (sets::dir('comments_tree')) {
				$comment['label'] = --$label;
			} else {
				$comment['label'] = ++$label;
			}
		}
		unset($comment);

		$params = array_keys($comments);
		$condition = Database::array_in('rootparent', $params).' and area != ?';
		array_push($params, "deleted");

		if (sets::dir('comments_tree')) {
			$query = Database::order('sortdate');
		} else {
			$query = Database::order('sortdate', 'asc');
		}

		$children = $query->get_full_table('comment', $condition, $params);

		foreach ($children as $child) {
			$child = new Model_Comment($child);
			$child['place_notify'] = $child->get_notify($this->area);
			$comments[$child['rootparent']]->add_child($child);
		}

		foreach ($comments as $comment) {
			$comment['depth'] = $comment->count_depth();
			$comment['place_notify'] = $comment->get_notify($this->area);
		}

		return $comments;
	}

	protected function get_comment_navi($item_id) {
		$return = array();

		$return['curr'] = $this->page;

		$return['last'] = ceil($this->count / $this->per_page);

		$return['start'] = max($return['curr'] - 5, 2);
		$return['end'] = min($return['curr'] + 6, $return['last'] - 1);

		$return['anchor'] = '#comments';
		$return['have_all'] = true;
		$return['all'] = $this->page == 'all';
		$return['name'] = 'Страница комментариев';

		$return['base'] = '/'.$this->get_place().'/'.$item_id.'/comments/';

		return $return;
	}

	protected function display_index($url) {

		$this->get_items();
	}

	protected function display_single_item($url) {

		$this->set_page($url, 4);

		$this->get_item($url[1]);

		$item = reset($this->data['items']);

		if ($item->is_phantom() || $item['area'] == 'deleted') {
			$this->do_output($this->error_template);
			return;
		}

		$this->data['comment'] = $this->get_comments($url[1]);
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
