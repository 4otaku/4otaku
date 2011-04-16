<?

class Profile_Output extends Output implements Plugins
{
	public function main ($query) {
		
		if (!empty($query['part'])) {
			$item = Config::profile($query['part']);
			
			$this->items[$query['part']] = new Item_Config_Block($item);
		} else {
			$items = Config::profile();
			
			foreach ($items as $part => $item) {
				$this->items[$part] = new Item_Config_Block($item);
			}
		}

		$user_settings = Globals::user();
		$merge_prepare = function(& $value) {
			$value = array('user_value' => $value);
		};
		array_walk_recursive($user_settings, $merge_prepare);

		foreach ($this->items as $section_name => & $section) {
			if (isset($user_settings[$section_name])) {
	//			$section = array_replace_recursive($section, $user_settings[$section_name]);
			}		
		}
		unset($section);
	}
}
