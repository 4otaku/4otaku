<?

class Templater
{
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
	}

	public function make_clean ($buffer) {
		return preg_replace(
			array('/[\t\r\n]+/', '/\s+/'), 
			array('', ' '), 
			$buffer
		);
	}
	
	public function output ($data) {
		ob_start(array($this, 'make_clean'));

		if (!empty($data->template)) {
			self::$template = Globals::$data['template'];
		}
		
		$data = array(
			'items' => $data->items,
			'flag' => $data->flags,
			'sub' => $data->submodules,
		);
		
		// TODO: убрать этот хак для тестирования
		$data['domain'] = 'http://beta.4otaku.ru';

		if (!empty(self::$template_engine)) {
			include_once ENGINE.SL.'templaters'.SL.self::$template_engine.'.php';

			call_user_func(
				self::$template_engine . '_load_template',
				'html',
				self::$template,
				$data
			);
		} else {
			include_once ROOT.SL.'templates'.SL.self::$template.SL.'index.php';
		}

		ob_end_flush();
	}
}
