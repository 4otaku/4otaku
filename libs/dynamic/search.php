<?

class dynamic__search
{
	private $_areas = array(
		'p' => 'post',
		'v' => 'video',
		'a' => 'art',
		'n' => 'news',
		'c' => 'comment',
		'o' => 'orders');

	private $_meta_tips = array('post', 'video', 'art');

	function searchtip () {

		$search = obj::get('search');

		$data = urldecode(query::$get['data']);
		$query = $search->prepare_string($data, true);

		if (empty($query)) {
			return;
		}

		$area = $this->parse_area(query::$get['area']);

		$order = implode('+', $area);
		$where = 'and ('.implode('>0 or ',$area).'> 0)';

		$queries = Database::get_vector('search_queries',
			'id, query',
			'(Left(query, ?) = ? '.$where.') order by '.$order.' desc limit 10',
			array(mb_strlen($query), $query));

		$return = array();
		foreach ($queries as $one) {
			$return[] = array('query' => $one,
				'alias' => $one,
				'type' => 'search');
		}

		if (count($area) == 1) {
			$single = reset($area);
			if (in_array($single, $this->_meta_tips)) {

				$meta = array();

				$field = $single.'_main';
				$params = array(mb_strlen($query), $query,
					mb_strlen($query), $query, '|'.$query);
				$meta['tag'] = Database::get_vector('tag',
					'`id`, `alias`, `name` as query',
						'(Left(alias , ?) = ? or Left(name, ?) = ? or
						locate(?, tag.variants)) and '.$field.' > 0
						order by '.$field.' desc limit 2',
					$params
				);

				$params = array(mb_strlen($query), $query,
					mb_strlen($query), $query, '|'.$single.'|');
				$meta['category'] = Database::get_vector('category',
					'`id`, `alias`, `name` as query',
						'(Left(alias , ?) = ? or Left(name, ?) = ?)
						 and locate(?, category.area) limit 2',
					$params
				);

				$params = array(mb_strlen($query), $query,
					mb_strlen($query), $query);
				$meta['language'] = Database::get_vector('language',
					'`id`, `alias`, `name` as query',
					'Left(alias , ?) = ? or Left(name, ?) = ? limit 2',
					$params
				);

				foreach ($meta as $key => $one) {
					foreach ((array) $one as $variant) {
						$variant['type'] = $key;
						$return[] = $variant;
					}
				}
			}
		}

		shuffle($return);
		$return = array_slice($return,0,10);

		foreach ($return as &$one) {

			switch ($one['type']) {
				case 'tag':
					$one['query'] = "Тег: ".$one['query'];
					break;
				case 'category':
					$one['query'] = "Категория: ".$one['query'];
					break;
				case 'language':
					$one['query'] = "Язык: ".$one['query'];
					break;
				default: break;
			}

			if ($one['type'] != 'search') {
				$one['alias'] = '/'.$single.'/'.$one['type'].'/'.$one['alias'].'/';
			}
		}

		return $return;
	}

	protected function parse_area ($area) {
		if (!in_array($area, $this->_areas)) {
			$area = str_split($area);

			$return = array();
			foreach ($area as $one) {
				if (isset($this->_areas[$one])) {
					$return[] = $this->_areas[$one];
				}
			}

			return array_unique($return);
		}

		return array($area);
	}
}
