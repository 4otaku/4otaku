<?

class Profile_Input extends Module_Input implements Plugins
{
	public function set_option ($query) {
		list($section, $key) = preg_split('/\./', $query['option_name'], 2);
		
		Cookie::save_preference($query['cookie'], $section, $key, $query['option_value']);
	}
}
