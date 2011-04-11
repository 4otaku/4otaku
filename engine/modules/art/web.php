<?

class Art_Web extends Module_Web implements Plugins
{	
	public $url_parts = array('area', 'mixed', 'meta', 'page', 'id');
	
	public function postprocess ($data) {
		
		$data = $this->postprocess_items($data);
		$data = $this->postprocess_navi($data);
		
		if (Globals::$query['function'] == 'single') {
			$art = reset($data['items']);
			
			list($art['weight'], $art['weight_type']) = Transform_String::round_bytes($art['weight']);
			
			$area_needed = array();
			
			foreach ($art['pools'] as & $pool) {
				$pool['name'] = $pool['title'];
				
				$order = explode(',', $pool['order']);
				
				$position = array_search($art['id'], $order);
				
				if (array_key_exists($position + 1, $order)) {
					$pool['next'] = array(
						'id' => $order[$position + 1],
					);
					
					$area_needed[] = $pool['next']['id'];
				}
				
				if (array_key_exists($position - 1, $order)) {
					$pool['previous'] = array(
						'id' => $order[$position - 1],
					);
					
					$area_needed[] = $pool['previous']['id'];
				}
			}
			
			foreach ($art['packs'] as $pack_id => & $pack) {
				$pack['name'] = $pack['title'];
				list($pack['weight'], $pack['weight_type']) = 
					Art_Output::get_pack_weight($pack_id);
				
				$order = explode(',', $pack['order']);
				
				$position = array_search($art['id'], $order);
				
				if (array_key_exists($position + 1, $order)) {
					$pack['next'] = array(
						'id' => $order[$position + 1],
					);
					
					$area_needed[] = $pack['next']['id'];
				}
				
				if (array_key_exists($position - 1, $order)) {
					$pack['previous'] = array(
						'id' => $order[$position - 1],
					);
					
					$area_needed[] = $pack['previous']['id'];
				}				
			}
			
			if (!empty($area_needed)) {
				$areas = Objects::db()->get_vector('art',
					array('id', 'area'), 
					Objects::db()->array_in('id', $area_needed), 
					$area_needed
				);
				
				foreach ($art['pools'] as & $pool) {
					if (
						!empty($pool['previous']['id']) && 
						array_key_exists($pool['previous']['id'], $areas)
					) {
						$area = $areas[$pool['previous']['id']];
						$pool['previous']['area'] = $area == 'main' ? '' : '/'.$area;
					}
				}
				
				foreach ($art['packs'] as & $pack) {
					if (
						!empty($pack['previous']['id']) && 
						array_key_exists($pack['previous']['id'], $areas)
					) {
						$area = $areas[$pack['previous']['id']];
						$pack['previous']['area'] = $area == 'main' ? '' : '/'.$area;
					}
				}
			}
			
			$data['items'] = array($art['id'] => $art);
		}
		
		return $data;	
	}
}
