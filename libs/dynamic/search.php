<?

class dynamic__search
{
	private $areas = array('p' => 'post', 'v' => 'video', 'a' => 'art', 'n' => 'news', 'c' => 'comment', 'o' => 'orders');

	function searchtip() {
		global $check; global $search;
		if (!$search) $search = new search();
		$query = $search->prepare_string(urldecode(query::$get['data']),true);
		if (!empty($query)) {

			if (!in_array(query::$get['area'],$this->areas)) {
				$area = str_split(query::$get['area']);
				foreach ($area as &$one) $one = $this->areas[$one];
				$area = array_filter($area);
				unset($one);
			}
			else $area = array(query::$get['area']);

			$order = implode('+',$area);
			$where = 'and ('.implode('>0 or ',$area).'> 0)';

			$variants[] = obj::db()->sql('select id, query, query as alias, "search" as type from search_queries where (Left(query , '.mb_strlen($query).') = "'.$query.'" '.$where.') order by '.$order.' desc limit 10','id');

			if (query::$get['area'] == 'a' || query::$get['area'] == 'p' || query::$get['area'] == 'v') {
				switch (query::$get['area']) {
					case 'a': $area = "art"; break;
					case 'p': $area = "post"; break;
					case 'v': $area = "video"; break;
					default: break;
				}
				$variants[] = obj::db()->sql('select id, alias, name as query, "tag" as type from tag where ((Left(alias , '.mb_strlen($query).') = "'.$query.'" or Left(name, '.mb_strlen($query).') = "'.$query.'" or locate("|'.$query.'",tag.variants)) and '.$area.'_main > 0) order by '.$area.'_main desc limit 2','id');
				$variants[] = obj::db()->sql('select id, alias, name as query, "category" as type from category where ((Left(alias , '.mb_strlen($query).') = "'.$query.'" or Left(name, '.mb_strlen($query).') = "'.$query.'") and locate("|'.$area.'|",category.area)) limit 2','id');
				$variants[] = obj::db()->sql('select id, alias, name as query, "language" as type from language where (Left(alias , '.mb_strlen($query).') = "'.$query.'" or Left(name, '.mb_strlen($query).') = "'.$query.'") limit 2','id');
//				$variants[] = obj::db()->sql('select id, alias, name as query, "author" as type from author where (Left(alias , '.mb_strlen($query).') = "'.$query.'" or Left(name, '.mb_strlen($query).') = "'.$query.'") limit 2','id');
			}

			$return = array();
			foreach ($variants as $one)
				if (!empty($one))
					$return = array_merge($return,$one);
			if (!empty($return)) {
				shuffle($return);
				$return = array_slice($return,0,10);
				foreach ($return as &$one) {
					switch ($one['type']) {
						case 'tag': $one['query'] = "Тег: ".$one['query']; break;
						case 'category': $one['query'] = "Категория: ".$one['query']; break;
						case 'language': $one['query'] = "Язык: ".$one['query']; break;
						case 'author': $one['query'] = "Автор: ".$one['query']; break;
						default: break;
					}
					if ($one['type'] != 'search') $one['alias'] = '/'.$area.'/'.$one['type'].'/'.$one['alias'].'/';
				}
				return $return;
			}
		}
	}
}
