<?

class Meta_Tag extends Meta_Library implements Plugins
{	
	public function get_data_by_alias($aliases) {
		
		$condition = Database::array_in('alias', $aliases);
		$full_condition = "type='tag' and ".$condition;

		$select = array('alias','name','color');

		$tags = Database::get_vector('meta', $select, $full_condition, $aliases, false);
	
		if (empty($tags)) {
			return array();
		}

		$variants = Database::get_full_table('tag_variants', $condition, $aliases);

		foreach ($variants as $variant) {
			$tags[$variant['alias']]['variants'][] = $variant['variant'];
		}
		
		if (!empty($this->items_info['type'])) {
			
			$numbers = $this->get_meta_numbers(
				$aliases, 
				'tag', 
				$this->items_info['type'], 
				$this->items_info['area']
			);
			
			foreach ($numbers as $alias => $number) {
				$tags[$alias]['count'] = $number;
			}
		}

		return $tags;
	}
}
