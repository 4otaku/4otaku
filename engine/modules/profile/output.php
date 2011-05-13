<?

class Profile_Output extends Output implements Plugins
{
	public function main ($query) {
		
		$user_settings = Globals::user();
		$merge_prepare = function(& $value) {
			$value = array('user_value' => $value);
		};
		array_walk_recursive($user_settings, $merge_prepare);

		if (!empty($query['part'])) {
			$item = Config::profile($query['part']);
			
			$item = array_replace_recursive($item, $user_settings[$query['part']]);
			
			$this->items[$query['part']] = new Item_Config_Block($item);
		} else {
			$items = Config::profile();
			
			foreach ($items as $part => $item) {
				if (isset($user_settings[$part])) {
					$item = array_replace_recursive($item, $user_settings[$part]);
				}
				
				$this->items[$part] = new Item_Config_Block($item);
			}
		}
	}
}
