<?

class Profile_Input extends Input implements Plugins
{
	public function set_option ($query) {

		Cookie::save_preference($query['cookie'], $query['option_name'], $query['option_value']);
	}
}
