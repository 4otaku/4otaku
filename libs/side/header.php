<?

class side__header
{	
	function top_buttons() {
		global $def;
		$return = $def['type'];		
		
		$menu = obj::db()->sql('select * from head_menu order by `order`', 'id');
		$return['menu'] = array();
		if (!empty($menu)) {
			foreach ($menu as $key => $element) {
				if ($element['parent'] == 0) {
					$return['menu'][$key] = $element;
					$return['menu'][$key]['items'] = array();
					unset($menu[$key]);
				}
			}
			
			foreach ($menu as $key => $element) {
				if (array_key_exists($element['parent'], $return['menu'])) {
					$return['menu'][$element['parent']]['items'][$key] = $element;
				}
			}		
		}
		
		return $return;
	}
}
