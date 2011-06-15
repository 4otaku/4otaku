<?

class Item_Art extends Item_Abstract_Meta implements Plugins
{
	public function postprocess () {
		parent::postprocess();
		
		$this->postprocess_translations(
			$this->data['translation'], 
			$this->data['resized']
		);
		
		list(
			$this->data['weight'], 
			$this->data['weight_type']
		) = Transform_String::round_bytes($this->data['weight']);
		
		$this->postprocess_groups_data(
			$this->data['pools'], 
			$this->data['packs']
		);
	}
	
	protected function postprocess_translations (& $translations, $resized) {
		
		$translations['full'] = (array) $translations['data'];
		unset($translations['data']);
		
		if ($resized) {
			foreach ($translations['full'] as $key => $translation) {
				foreach ($translation as $name => & $field) {
					if (preg_match('/^[xy][12]$/', $name)) {
						$field = floor($field * $resized);
					}
				}
				unset($field);
				
				$translations['resized'][$key] = $translation;
			}
		}		
	}
	
	protected function postprocess_groups_data (& $pools, & $packs) {
		
		$area_needed = array();
		
		foreach ($pools as & $pool) {
			$this->postprocess_single_group($pool, $area_needed);
		}
		unset ($pool);
		
		foreach ($packs as $pack_id => & $pack) {
			$this->postprocess_single_group($pack, $area_needed);
			
			list($pack['weight'], $pack['weight_type']) = 
				Art_Submodule_Pack::get_pack_weight($pack_id);
		}
		unset ($pack);

		if (!empty($area_needed)) {
			$areas = Database::get_vector('art',
				array('id', 'area'), 
				Database::array_in('id', $area_needed), 
				$area_needed
			);
			
			$this->set_group_areas($pools, $areas);
			$this->set_group_areas($packs, $areas);
		}		
	}
	
	protected function postprocess_single_group (& $group, & $area_needed) {		
		$group['name'] = $group['title'];
		
		$order = explode(',', $group['order']);
		
		$position = array_search($this->data['id'], $order);
		
		if (array_key_exists($position + 1, $order)) {
			$group['next'] = array(
				'id' => $order[$position + 1],
			);
			
			$area_needed[] = $group['next']['id'];
		}
		
		if (array_key_exists($position - 1, $order)) {
			$group['previous'] = array(
				'id' => $order[$position - 1],
			);
			
			$area_needed[] = $group['previous']['id'];
		}
	}
	
	protected function set_group_areas (& $groups, & $areas) {
	
		foreach ($groups as & $group) {
			if (
				!empty($group['previous']['id']) && 
				array_key_exists($group['previous']['id'], $areas)
			) {
				$group['previous']['area'] = $areas[$group['previous']['id']];
			}
			
			if (
				!empty($group['next']['id']) && 
				array_key_exists($group['next']['id'], $areas)
			) {
				$group['next']['area'] = $areas[$group['next']['id']];
			}						
		}
	}
}
