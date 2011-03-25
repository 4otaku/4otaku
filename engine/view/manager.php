<?

class Manager
{
	protected static $template_type = 'web';
	protected static $template = 'default';
	protected static $template_engine = false;
	public $data = array();

	public function __construct($data) {
		$this->data = $data;

		$templater = Config::main('template', 'Engine');
		if (!empty($templater)) {
			self::$template_engine = $templater;
		}

		if (!empty($data['template'])) {
			self::$template = $data['template'];
		}
	}

	public function make_clean($buffer) {
		return preg_replace(array('/[\t\r\n]+/', '/\s+/'), array('', ' '), $buffer);
	}
	
	public function output() {
		ob_start(array($this, 'make_clean'));

		if (!empty(self::$template_engine)) {
			include_once ENGINE.SL.'templaters'.SL.self::$template_engine.'.php';

			call_user_func(
				self::$template_engine . '_load_template',
				self::$template_type,
				self::$template,
				$this->data
			);
		} else {
			global $data;

			$data = $this->data;

			include_once ROOT.SL.'templates'.SL.self::$template.SL.'index.php';
		}

		ob_end_flush();
	}
}
