<?

class Art_Output extends Module_Output implements Plugins
{
	const PACK_FILE_SIZE_PREFIX = '_size_packfile_id_';
	
	public function single ($query) {
		$art = Objects::db()->get_row('art', $query['id']);
		$this->test_area($art['area']);	
		
		$art['date'] = Objects::db()->date_to_unix($art['date']);
		
		$meta = Meta::prepare_meta(array($art['id'] => $art['meta']));

		$return['items'] = array(
			$art['id'] => array_merge(
				$art, 
				current($meta),
				array('translations' => current($this->get_translations($art['id']))),
				array('pools' => $this->get_pools(current($meta))),
				array('packs' => $this->get_packs(current($meta))),
				array('variations' => current($this->get_variants($art['id'])))
			),
		);
		
		$return['items'] = $this->mark_item_types($return['items'], 'art');		

		return $return;
	}

	public function main ($query) {
		$return = array();

		$perpage = Config::settings('per_page');

		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;

		$start = ($page - 1) * $perpage;

		$listing_condition = $this->build_listing_condition($query);

		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$return['items'] = Objects::db()->get_full_vector('art', $condition);

		$keys = array_keys($return['items']);
		$index = array();

		foreach ($return['items'] as $id => & $art) {
			$index[$id] = $art['meta'];
			unset($art['date']);
		}

		$meta = Meta::prepare_meta($index);

		$return['items'] = array_replace_recursive($return['items'], $meta);
		$return['items'] = $this->mark_item_types($return['items'], 'art_in_list');

		$count = Objects::db()->get_count('art', $listing_condition);

		$return['curr_page'] = $page;
		$return['pagecount'] = ceil($count / $perpage);

		return $return;
	}	
	
	public static function description () {
		if (Globals::$query['function'] == 'pack') {
			$pack_id = (int) Globals::$query['alias'];
			
			$pack = Objects::db()->get_row('art_cg_pack', array('title', 'text'), $pack_id);
			list($weight, $weight_type) = self::get_pack_weight($pack_id);
			
			return array(
				'text' => $pack['text'], 
				'name' => $pack['title'], 
				'type' => 'pack', 
				'weight' => $weight, 
				'weight_type' => $weight_type, 
				'id' => $pack_id
			);
		}
		
		if (Globals::$query['function'] == 'pool') {
			$pool_id = (int) Globals::$query['alias'];
			
			$pool = Objects::db()->get_full_row('art_pool', $pool_id);
			
			return array_merge($pool, array('type' => 'pool'));
		}
		
		return array();
	}
	
	public function pool_list ($query) {
		$return = array();
		$perpage = Config::settings('pool_per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = (int) ($page - 1) * $perpage;
		$return['items'] = Objects::db()->get_full_vector('art_pool', '1 order by date desc limit '.$start.', '.$perpage);

		$return['items'] = $this->mark_item_types($return['items'], 'art_pool');
		
		$count = Objects::db()->get_count('art_pool');
		$return['curr_page'] = $page;
		$return['navi_base'] = 'pool';
		$return['pagecount'] = ceil($count / $perpage);		
		
		return $return;
	}
	
	public function pack_list ($query) {
		$return = array();
		$perpage = Config::settings('pack_per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = (int) ($page - 1) * $perpage;
		$return['items'] = Objects::db()->get_full_vector('art_cg_pack', '1 order by date desc limit '.$start.', '.$perpage);

		$return['items'] = $this->mark_item_types($return['items'], 'art_pack');
		
		$count = Objects::db()->get_count('art_cg_pack');
		$return['curr_page'] = $page;
		$return['navi_base'] = 'cg_pack';
		$return['pagecount'] = ceil($count / $perpage);				
		
		return $return;		
	}	
	
	// Алиасы для настройки субмодулей
	
	public function pool ($query) {
		return $this->main($query);
	}
	
	public function pack ($query) {
		return $this->main($query);
	}
	
	protected function get_pools ($meta) {
		if (empty($meta['meta']['pool'])) {
			return array();
		}
		
		return Objects::db()->get_vector(
			'art_pool', 
			array('id', 'title', 'order'), 
			Objects::db()->array_in('id', $meta['meta']['pool']), 
			$meta['meta']['pool']
		);
	}
	
	protected function get_packs ($meta) {
		if (empty($meta['meta']['cg_pack'])) {
			return array();
		}
		
		return Objects::db()->get_vector(
			'art_cg_pack', 
			array('id', 'title', 'order'), 
			Objects::db()->array_in('id', $meta['meta']['cg_pack']), 
			$meta['meta']['cg_pack']
		);
	}
	
	protected function get_translations ($ids) {
		$ids = (array) $ids;

//		var_dump()

		return array();
	}
	
	protected function get_variants ($ids) {
		$ids = (array) $ids;

//		var_dump()

		return array();
	}
		
	public static function get_pack_weight ($pack_id) {
		Cache::$prefix = self::PACK_FILE_SIZE_PREFIX;
		
		if (!($size = Cache::get($pack_id))) {
			
			$filename = FILES.SL.'art'.SL.'cg_packs'.SL.$pack_id.'.zip';
			
			if (!file_exists($filename)) {
				Art_Input::create_pack_file($pack_id);
			}
			
			$size = filesize($filename);
			
			Cache::set($pack_id, $size, MONTH);
		}
		
		return Transform_String::round_bytes($size);
	}	
}
