<?
	
class Manager
{
	protected static $template = 'default';
	protected static $template_engine = false;
	protected $data = array();
	
	public function __construct($data) {
		$this->call = Plugins::extend($this);
		$this->data = $data;
		
		$templater = Config::main('template', 'Engine');
		if (!empty($templater)) {
			self::$template_engine = $templater;
		}
	}
	
	public function postprocess() {
		
	}
	
	public function output() {
		if (!empty(self::$template_engine)) {
			include_once ENGINE.SL.'templaters'.SL.self::$template_engine.'.php';
			
			call_user_func(self::$template_engine . '_load_template', self::$template, $this->data);
		} else {
			global $data;
			
			$data = $this->data;
			
			include_once ROOT.SL.'templates'.SL.self::$template.SL.'index.php';
		}
	}	
}