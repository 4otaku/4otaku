<?
	
class Meta
{	
	public static function parse_mixed_url($url) {
		
		return array();
	}
	
	public static function prepare_meta($index) {
		
		$common = array();
		$return = array();
		
		foreach ($index as $id => $meta) {
			$meta = explode(' ', substr($meta, 7));
			$meta = array_filter($meta);
					
			$index[$id] = array();
			foreach ($meta as $one) {
				list($type, $name) = preg_split('/_/', $one, 2);
			
				$index[$id][$type][] = $name;
				$common[$type][] = $name;
			}
			
			$return[$id]['meta'] = array();
		}		
		
		foreach ($common as $type => $names) {
			$classname = 'Fetch_'.$type;
			
			$worker = new $classname();
			
			$names = array_unique($names);
			
			$data = $worker->get_data_by_alias($names);
			
			foreach ($index as $id => $item) {
				foreach ($data as $one) {
					if (in_array($one['alias'],$item[$type])) {
						$return[$id]['meta'][$type][] = $one;
					}
				}
			}
		}
		
		return $return;
	}
}
