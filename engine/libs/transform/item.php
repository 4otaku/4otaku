<?

class Transform_Item
{
	// Слияние блока с любым количеством массивов, 
	// переданных в качестве дополнительных аргументов
	
	public static function merge ($item) {
		if (
			is_array($item) ||
			(is_object($item) && $item instanceOf ArrayAccess) 
		) {
			$arrays = func_get_args();
			array_shift($arrays);
			
			foreach ($arrays as $data) {
				$data = (array) $data;
				
				foreach ($data as $key => $value) {
					$item[$key] = $value;
				}
			}
		}
		
		return $item;		
	}
}
