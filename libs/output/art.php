<?php

class output__art extends engine
{
	function __construct() {
		global $cookie; global $url; global $sets;
		if (!$cookie) $cookie = new dynamic__cookie();
		$cookie->inner_set('visit.art',time(),false);
		$this->parse_area();
		if (!$url[2]) $this->error_template = 'booru_empty';
		if ($url[2] == 'slideshow' && $url[3] && $url[4]) {
			foreach ($url as $key => $one) if ($key > 2) $url[$key-1] = $one;
			unset($url[count($url)-1]);
			$this->template = 'slideshow';
			$this->side_modules = array();
		}
	}
	public $allowed_url = array(
		array(1 => '|art|', 2 => '|page|', 3 => 'num', 4 => 'end'),
		array(1 => '|art|', 2 => '|tag|author|category|mixed|date|', 3 => 'any', 4 => '|page|', 5 => 'num', 6 => 'end'),
		array(1 => '|art|', 2 => '|pool|cg_packs|', 3 => '|page|', 4 => 'num', 5 => 'end'),
		array(1 => '|art|', 2 => '|pool|cg_packs|', 3 => 'num', 4 => '|page|sort|', 5 => 'num', 6 => 'end'),
		array(1 => '|art|', 2 => '|download|', 3 => 'any', 4 => 'any', 5 => 'any', 6 => 'end'),
		array(1 => '|art|', 2 => 'any', 3 => '|comments|', 4 => '|all|', 5 => 'end'),
		array(1 => '|art|', 2 => 'any', 3 => '|comments|', 4 => '|page|', 5 => 'num', 6 => 'end')
	);
	public $template = 'booru';
	public $error_template = 'booru';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('masstag','art_tags','comments'),
		'footer' => array()
	);

	function get_data() {
		if ($this->template != 'slideshow') {
			global $url; global $error; global $sets; global $def;
			if (is_numeric($url[2])) {
				$return['display'] = array('booru_single','comments');
				$return['art'] = $this->get_art(1,'id='.$url[2].' and area != "deleted"');
				$url['area'] = $return['art'][0]['area'];
				$return['comments'] = $this->get_comments($url[1],$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
				$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
				$return['navi']['all'] = true;
				$return['navi']['name'] = "Страница комментариев";
				$return['navi']['meta'] = $url[2].'/comments/';
				$return['navi']['start'] = max($return['navi']['curr']-5,2);
				$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);
				$this->side_modules['top'] = array();

				$return['art'][0]['rating'] = $this->get_rating($url[2]);
				$return['art'][0]['packs'] = $this->get_packs($url[2]);
				$return['art'][0]['pool'] = $this->get_pools($url[2]);
			}
			elseif ($url[2] != 'pool' && $url[2] != 'cg_packs' && $url[2] != 'download') {
				$return['display'] = array('booru_page','navi');
				if ($url[2] == 'page' || !$url[2]) {
					$area = 'area = "'.$url['area'].'"';
					$return['navi']['curr'] = max(1,$url[3]);
					if ($sets['art']['sort'] == 'tag-desc') $return['art']['thumbs'] =  $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'], $area, 'order by (length(tag) - length(replace(tag,\'|\',\'\'))) desc');
					elseif ($sets['art']['sort'] == 'tag-asc') $return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'], $area, 'order by (length(tag) - length(replace(tag,\'|\',\'\')))');
					else $return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'], $area);
				}
				elseif ($url[2] == 'date') {
					$parts = explode('-',$url[3]);
					if (is_numeric($parts[0].$parts[1].$parts[2]) && count($parts) == 3) {
						$area = 'area = "'.$url['area'].'" and pretty_date ="'.Transform_Time::ru_month($parts[1]).' '.$parts[2].', '.$parts[0].'"';
						$return['navi']['curr'] = max(1,$url[5]);
						$return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],$area);
						$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
					}
					else $error = true;
				}
				elseif ($url[2] != 'mixed') {
					$this->mixed_parse($url[2].'='.$url[3]);
					$area = 'area = "'.$url['area'].'" and locate("|'.($url['tag'] ? $url['tag'] : mysql_real_escape_string($url[3])).'|",art.'.$url[2].')';
					$return['navi']['curr'] = max(1,$url[5]);
					$return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],$area);
					$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
					$return['rss'] = $this->make_rss($url[1],$url[2],$url[3]);
				}
				else {
					$area = $this->mixed_make_sql($this->mixed_parse($url[3]));
					$return['navi']['curr'] = max(1,$url[5]);
					$return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],$area);
					$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
				}
				$return['navi']['start'] = max($return['navi']['curr']-5,2);
				$return['navi']['last'] = ceil(obj::db()->sql('select count(id) from art where ('.$area.')',2)/$sets['pp']['art']);
			}
			else {
				if ($url[2] == 'pool') {
					if (is_numeric($url[3])) {
						if ($url[4] != 'sort') {
							$return['display'] = array('booru_poolsingle','booru_page','navi');
						} else {
							$return['display'] = array('booru_poolsingle','booru_page');
						}

						$return['pool'] = Database::get_full_row('art_pool', $url[3]);
						$query = Database::set_counter()->order('order', 'asc');

						if ($url[4] != 'sort') {
							$return['navi']['curr'] = max(1,$url[5]);
							$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
							$return['navi']['start'] = max($return['navi']['curr']-5,2);
							$query->limit(sets::pp('art'), ($return['navi']['curr']-1)*sets::pp('art'));
						}

						$pool = $query->get_vector('art_in_pool', 'art_id', 'pool_id = ?', $url[3]);
						$return['pool']['count'] = Database::get_counter();

						if ($url[4] != 'sort') {
							$return['navi']['last'] = ceil($return['pool']['count']/$sets['pp']['art']);
						}

						$where = 'id='.implode(' or id=',$pool);
						$pool = array_flip($pool);
						if ($art = $this->get_art(false,$where,'')) {
							foreach ($art as $one) $return['art']['thumbs'][$pool[$one['id']]] = $one;
							ksort($return['art']['thumbs']);
						}
						$return['rss'] = $this->make_rss($url[1],$url[2],$url[3]);
					}
					else {
						$return['display'] = array('booru_poolpage','navi');
						$return['navi']['curr'] = max(1,$url[4]);
						$return['navi']['meta'] = $url[2].'/';
						$return['navi']['start'] = max($return['navi']['curr']-5,2);
						$return['navi']['last'] = ceil(obj::db()->sql('select count(id) from art_pool',2)/$sets['pp']['art_pool']);
						$return['pools'] = obj::db()->sql('
							select p.id as id, p.name as name, count(*) as count
							from art_pool as p left join art_in_pool as a on p.id = a.pool_id
							group by p.id order by p.sortdate desc
							limit '.($return['navi']['curr']-1)*$sets['pp']['art_pool'].', '.$sets['pp']['art_pool']
						,'id');
					}
				}
				elseif ($url[2] == 'cg_packs') {
					$this->side_modules['top'] = array();
					if (is_numeric($url[3])) {
						$return['display'] = array('booru_pack_single','booru_page','navi');

						$return['navi']['curr'] = max(1,$url[5]);
						$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
						$return['navi']['start'] = max($return['navi']['curr']-5,2);
						$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from art_in_pack where pack_id='.$url[3],2)/$sets['pp']['art']);
						$return['pool'] = obj::db()->sql('select * from art_pack where id='.$url[3],1);
						$return['art']['thumbs'] = $this->get_art(
							($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],
							'p.pack_id='.$url[3],
							'order by p.`order`',
							'as a left join art_in_pack as p on a.id = p.art_id'
						);
					} else {
						$return['display'] = array('booru_pack_list','navi');
						$return['navi']['curr'] = max(1,$url[4]);
						$return['navi']['meta'] = $url[2].'/';
						$return['navi']['start'] = max($return['navi']['curr']-5,2);
						$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from art_pack where cover != ""',2)/$sets['pp']['art_cg_pool']);
						$return['pools'] = obj::db()->sql('select * from art_pack where cover != "" order by `date` desc limit '.($return['navi']['curr']-1)*$sets['pp']['art_cg_pool'].', '.$sets['pp']['art_cg_pool'],'id');
					}
				}
				else {
					if ($url[3] == 'pack') {
						if (empty($url[4]) || !is_numeric($url[4])) {
							$error = true;
						} else {
							if (empty($url[5]) || !is_numeric($url[5])) {
								$pack = obj::db()->sql("select title, weight from art_pack where id = $url[4]", 1);
								if ($pack['weight'] == 0) {
									$error = true;
								} else {
									$this->template = 'download';
									$this->side_modules = array();
									$name = str_replace(array(' ',';'),'_',$pack['title']);
									$file = ROOT_DIR.SL.'files'.SL.'pack_cache'.SL.'pack_'.$url[4].'.zip';
									return array('file' => $file, 'name' => $name.'.zip');
								}
							} else {
								$this->template = 'download';
								$this->side_modules = array();

								$art = obj::db()->sql("
									select a.md5, a.extension, p.filename
									from art as a left join art_in_pack as p on a.id = p.art_id
									where a.id = $url[5] and p.pack_id = $url[4]", 1);
								$file = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$art['md5'].'.'.$art['extension'];

								if (pathinfo($file,PATHINFO_EXTENSION) == 'jpg') {
									$type = 'jpeg';
								} else {
									$type = pathinfo($file,PATHINFO_EXTENSION);
								}
								return array('file' => $file, 'name' => $art['filename'], 'type' => $type);
							}
						}
					}
					else {
						$this->template = 'download';
						$this->side_modules = array();
						if (pathinfo($url[3],PATHINFO_EXTENSION) == 'jpg') $type = 'jpeg'; else $type = pathinfo($url[3],PATHINFO_EXTENSION);
						return array('file' => ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$url[3], 'name' => $url[3], 'type' => $type);
					}
				}
			}
			$return['navi']['base'] = '/art'.($url['area'] != $def['area'][0] ? '/'.$url['area'] : '').'/';
			return $return;
		}
	}

	function get_art($limit, $area, $order = false, $join = '') {
		global $error; global $url; global $check; global $def; global $sets;

		if ($order === false) {
			$order_sets = explode('-', $sets['art']['sort']);
			$order = 'order by '.
				($order_sets[0] == 'rating' ? 'rating' : 'sortdate').' '.
				($order_sets[1] == 'asc' ? 'asc' : 'desc').' '.
				($order_sets[0] == 'rating' ? ', sortdate desc' : '');
		}

		if ($limit) $limit = 'limit '.$limit;
		$return = obj::db()->sql('select * from art '.$join.' where ('.$area.') '.$order.' '.$limit);
		if (is_array($return)) {
			if ($limit == 'limit 1') {
				$return[0]['translations']['full'] = unserialize(base64_decode(obj::db()->sql('select data from art_translation where (art_id='.$return[0]['id'].' and active = 1)',2)));
				if (is_array($return[0]['translations']['full'])) foreach ($return[0]['translations']['full'] as $key => $one) $return[0]['translations']['full'][$key]['text'] = str_replace('"','&quot;',$one['text']);
				if ($return[0]['resized'] && is_array($return[0]['translations']['full'])) {
					$size = explode('x',$return[0]['resized']);
					$small_size = getimagesize(ROOT_DIR.SL.'images/booru/resized/'.$return[0]['md5'].'.jpg');
					$coeff = $size[0] / $small_size[0];
					foreach ($return[0]['translations']['full'] as $one) {
						foreach ($one as $key => $param) if ($key != 'text') $one[$key] = round($param / $coeff);
						$return[0]['translations']['resized'][] = $one;
					}
				}
				$return[0]['similar'] = obj::db()->sql('select * from art_variation where art_id = '.$return[0]['id'].' order by `order`', 'order');
			} else {
				$ids = 'art_id in (';
				foreach ($return as $art) {
					$ids .= $art['id'].',';
				}
				$ids = substr($ids, 0, -1).')';
				$variations = obj::db()->sql('select count(*), art_id from art_variation where '.$ids.' group by art_id', 'art_id');
				foreach ($return as &$art) {
					if (isset($variations[$art['id']])) {
						$art['similar_count'] = $variations[$art['id']];
					} else {
						$art['similar_count'] = 0;
					}
				}
			}
			foreach ($return as &$art) {
				if ($check->link($art['source'])) $art['source'] = '<a href="'.$art['source'].'" target="_blank">'.$art['source'].'</a>';
			}
			$meta = $this->get_meta($return,array('category','author','tag'));
			foreach ($meta as $key => $type) {
				if (is_array($type)) {
					foreach ($return as &$art) {
						foreach ($type as $alias => $name) {
							if (stristr($art[$key],'|'.$alias.'|')) {
								$art['meta'][$key][$alias] = $name;
							}
						}
						foreach ($art['meta'] as &$art_meta) {
							uasort($art_meta, 'transform__array::meta_sort');
						}
					}
				}
			}
			return $return;
		}
		else $error = true;
	}

	public function get_packs ($id) {
		return obj::db()->sql("
			select * from
				art_pack as p left join
				art_in_pack as a on
				a.pack_id = p.id
			where a.art_id = $id
			group by p.id");
	}

	public function get_pools ($id) {
		return obj::db()->sql("
			SELECT p.id as `id`, p.name as `name`, r.art_id as `right`, l.art_id as `left`
			FROM art_pool AS p
				LEFT JOIN art_in_pool AS a ON a.pool_id = p.id
				LEFT JOIN art_in_pool AS l ON a.pool_id = l.pool_id
					AND a.order - 1 = l.order
				LEFT JOIN art_in_pool AS r ON a.pool_id = r.pool_id
					AND a.order + 1 = r.order
			WHERE a.art_id = $id
			GROUP BY p.id");
	}

	public function get_rating ($id) {
		$cookie = query::$cookie;
		$ip = ip2long($_SERVER['REMOTE_ADDR']);

		$return = array();
		$return['voted'] = obj::db()->sql("select id from art_rating where art_id = $id and (ip = $ip or cookie = '$cookie')",2);
		$return['score'] = (int) obj::db()->sql("select SUM(rating) from art_rating where art_id = $id",2);

		return $return;
	}
}
