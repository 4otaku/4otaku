<?

class Art_Output extends Output_Main implements Plugins
{
	
	public function single ($query) {
		$id = $query['id'];
		$art = Database::get_full_row('art', $id);
		
		$this->test_area($art['area']);
		
		$this->items[$id] = new Item_Art($art);
		
		$meta = Meta::prepare_meta(array($id => $art['meta']));

		$this->items[$id] = Transform_Item::merge(
			$this->items[$id], 
			current($meta),
			array('translation' => current($this->get_translations($id))),
			array('pools' => $this->get_pools(current($meta))),
			array('packs' => $this->get_packs(current($meta))),
			array('variations' => $this->get_variants($id, $art['variations']))
		);
	}

	public function get_content ($query, $perpage, $page, $start) {
		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Database::set_counter()->get_full_vector('art', $condition);
		
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Thumbnail($item);
			$index[$id] = $item['meta'];
		}
		unset ($items);

		$meta = Meta::prepare_meta($index);
		
		foreach ($this->items as $id => & $item) {
			$item = Transform_Item::merge($item, $meta[$id]);
		}
		
		$count = Database::get_counter();
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'art',
		));
	}
	
	protected function get_pools ($meta) {
		if (empty($meta['meta']['pool'])) {
			return array();
		}
		
		return Database::get_vector(
			'art_pool', 
			array('id', 'title', 'order'), 
			Database::array_in('id', $meta['meta']['pool']), 
			$meta['meta']['pool']
		);
	}
	
	protected function get_packs ($meta) {
		if (empty($meta['meta']['cg_pack'])) {
			return array();
		}
		
		return Database::get_vector(
			'art_cg_pack', 
			array('id', 'title', 'order'), 
			Database::array_in('id', $meta['meta']['cg_pack']), 
			$meta['meta']['cg_pack']
		);
	}
	
	protected function get_translations ($ids) {
		$ids = (array) $ids;
		
		$condition = Database::array_in('art_id', $ids);
		$condition .= ' and active = 1';
		
		$fields = array('art_id', 'data', 'translator');

		$translations = (array) Database::get_vector('art_translation', $fields, $condition, $ids);
		
		foreach ($translations as & $translation) {
			$translation['data'] = Crypt::unpack($translation['data']);
		}

		return $translations;
	}
	
	protected function get_variants ($id, $variants_count) {
		if (empty($variants_count)) {
			return array();
		}

		return Database::get_full_vector('art', 'area = "variation" and parent_id = ?', $id);
	}	
}
