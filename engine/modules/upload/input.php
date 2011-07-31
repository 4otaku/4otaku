<?

class Upload_Input extends Input implements Plugins
{
	protected $module = "";
	
	protected $image_formats = array("png", "jpg", "jpeg", "gif");
	
	public function challenge_image ($query) {

		$this->module = "challenge";
		$config = $this->get_settings();

		$sizes = array(
			$config["thumbnail_width"],
			$config["thumbnail_height"]
		);

		$image = new Transform_Image("post");

		$md5 = md5(microtime(true));
		$format = $image->get_format();

		$this->test_image_format($format);
		$this->test_size($image->get_size(), $config["max_image_weight"]);
		
		$file = "$md5.$format";
		$thumbnail = "$md5.jpg";
		
		$image->
			save(IMAGES.SL."challenge".SL."full".SL.$file)->
			target(IMAGES.SL."challenge".SL."thumbnail".SL.$thumbnail)->
			scale($sizes);
			
		$this->make_output(array(
			"full" => $file,
			"thumbnail" => $thumbnail,
		));
	}
	
	protected function get_settings () {
		$module_config_file = ENGINE.SL."modules".SL.$this->module.SL."settings.ini";
		Config::load($module_config_file, true, true);
		
		return call_user_func("Config::$this->module", "upload");	
	}
	
	protected function test_image_format ($format) {
		if (!in_array($format, $this->image_formats)) {
			$this->make_error_output("filetype");
		}
	}
	
	protected function test_size ($size, $maxsize) {
		$maxsize = Transform_String::parse_weight($maxsize);
		
		if ($size > $maxsize) {
			$this->make_error_output("maxsize");
		}	
	}
	
	protected function make_error_output ($message) {
		$result = array("error" => $message);
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit();
	}
	
	protected function make_output ($result) {
		$result["success"] = true;
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit();
	}
}
