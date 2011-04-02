<?

class Templater
{
	protected static $template_type = 'web';
	protected static $template = 'default';
	protected static $template_engine = false;

	public function __construct () {
		$templater = Config::main('template', 'engine');
		if (!empty($templater)) {
			self::$template_engine = $templater;
		}
		
		if (Config::settings('template')) {
			self::$template = Config::settings('template');
		}		

		if (!empty(Globals::$data['template'])) {
			self::$template = Globals::$data['template'];
		}
		
		self::$template_type = Objects::$controller->get_type();
	}

	public function make_clean ($buffer) {
		return preg_replace(array('/[\t\r\n]+/', '/\s+/'), array('', ' '), $buffer);
	}
	
	public function output () {
		ob_start(array($this, 'make_clean'));

		if (!empty(self::$template_engine)) {
			include_once ENGINE.SL.'templaters'.SL.self::$template_engine.'.php';

			call_user_func(
				self::$template_engine . '_load_template',
				self::$template_type,
				self::$template,
				Globals::$data
			);
		} else {
			global $data;

			$data = Globals::$data;

			include_once ROOT.SL.'templates'.SL.self::$template.SL.'index.php';
		}

		ob_end_flush();
	}
}
