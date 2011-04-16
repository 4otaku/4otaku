<?

class Art_Output extends Output_Main implements Plugins
{
	const PACK_FILE_SIZE_PREFIX = '_size_packfile_id_';
	
	public function single ($query) {
		$id = $query['id'];
		$art = Globals::db()->get_full_row('art', $id);
		
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

	public function main ($query) {

		$perpage = Config::settings('per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Objects::db()->get_full_vector('art', $condition);
		
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
		
		$count = Globals::db()->get_count('art', $listing_condition);
		
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
		
		$condition = Objects::db()->array_in('art_id', $ids);
		$condition .= ' and active = 1';
		
		$fields = array('art_id', 'data', 'translator');

		$translations = (array) Objects::db()->get_vector('art_translation', $fields, $condition, $ids);
		
		foreach ($translations as & $translation) {
			$translation['data'] = Crypt::unpack($translation['data']);
		}

		return $translations;
	}
	
	protected function get_variants ($id, $variants_count) {
		if (empty($variants_count)) {
			return array();
		}

		return Objects::db()->get_full_vector('art', 'area = "variation" and parent_id = ?', $id);
	}	
}
