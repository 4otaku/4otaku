<?
	
class Meta extends Meta_Library implements Plugins
{
	public static function prepare_meta($index, $items_type = false, $items_area = false) {
		
		$common = array();
		$return = array();

		foreach ($index as $id => $meta) {
			$meta = explode(' ', substr($meta, 7));
			$meta = array_filter($meta);
					
			$index[$id] = array();
			foreach ($meta as $one) {
				list($type, $name) = preg_split('/__/', $one, 2);
			
				$index[$id][$type][] = $name;
				$common[$type][] = $name;
			}
			
			$return[$id]['meta'] = array();
		}		
		
		foreach ($common as $type => $names) {
			$classname = 'Meta_'.$type;
			
			if (!class_exists($classname)) {
				$classname = 'Meta_Default';
			}
			
			$worker = new $classname($items_type, $items_area);
			
			$names = array_unique($names);

			$data = $worker->get_data_by_alias($names);

			foreach ($index as $id => $item) {
				if (!empty($item[$type])) {
					
					foreach ($data as $one) {
						if ( 
							in_array($one, $item[$type])|| 
							(isset($one['alias']) &&
							in_array($one['alias'], $item[$type]))
						) {
							$return[$id]['meta'][$type][] = $one;
						}
					}
				}
			}
		}
		
		return $return;
	}
}
