<?

class Upload_Input extends Input implements Plugins
{
	protected $module = "";
	
	public function challenge_image ($query) {

		$this->module = "challenge";
		$config = $this->get_settings();

		$sizes = array(
			$config["thumbnail_width"],
			$config["thumbnail_height"]
		);

		$image = new Transform_Image("post");
		
		$md5 = md5(microtime(true));
		$file = "$md5.".$image->get_format();
		$thumbnail = "$md5.jpg";
		
		$image->
			save(IMAGES.SL."challenge".SL."full".SL.$file)->
			target(IMAGES.SL."challenge".SL."thumbnail".SL.$thumbnail)->
			scale($sizes);
	}
	
	protected function get_settings () {
		$module_config_file = ENGINE.SL."modules".SL.$this->module.SL."settings.ini";
		Config::load($module_config_file, true, true);
		
		return call_user_func("Config::$this->module", "upload");	
	}
}
