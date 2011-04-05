<?

class Profile_Output extends Module_Output implements Plugins
{
	public function main ($query) {
		if (!empty($query['section'])) {
			$return['items'] = array($query['section'] => Config::profile($query['section']));
		} else {
			$return['items'] = Config::profile();
		}
		
		$user_settings = Globals::user();
		$merge_prepare = function(& $value) {
			$value = array('user_value' => $value);
		};
		array_walk_recursive($user_settings, $merge_prepare);

		foreach ($return['items'] as $section_name => & $section) {
			if (isset($user_settings[$section_name])) {
				$section = array_replace_recursive($section, $user_settings[$section_name]);
			}
			
			$section = array('data' => $section, 'name' => $section['name']);
			unset($section['data']['name']);			
		}
		unset($section);
		
		$return['items'] = $this->mark_item_types($return['items'], 'config_block');

		return $return;
	}
}
