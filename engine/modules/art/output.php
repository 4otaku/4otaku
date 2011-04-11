<?

class Art_Output extends Module_Output implements Plugins
{
	const PACK_FILE_SIZE_PREFIX = '_size_packfile_id_';
	
	public function single ($query) {
		$art = Globals::db()->get_row('art', $query['id']);
		$this->test_area($art['area']);	
		
		$art['date'] = Globals::db()->date_to_unix($art['date']);
		
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

		$return['items'] = Globals::db()->get_full_vector('art', $condition);

		$keys = array_keys($return['items']);
		$index = array();

		foreach ($return['items'] as $id => & $art) {
			$index[$id] = $art['meta'];
			unset($art['date']);
		}

		$meta = Meta::prepare_meta($index);

		$return['items'] = array_replace_recursive($return['items'], $meta);
		$return['items'] = $this->mark_item_types($return['items'], 'art_in_list');

		$count = Globals::db()->get_count('art', $listing_condition);

		$return['curr_page'] = $page;
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
