<?
	
class Manager
{
	protected static $template_type = 'web';
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

		if (!empty($data['template'])) {
			self::$template = $data['template'];
		}
	}
	
	public function postprocess() {
		
		if (!empty($this->data['pagecount']) && !empty($this->data['curr_page'])) {			
			$worker = new Process_Navi();
			$this->data = $worker->process($this->data);
		}
	}
	
	public function output() {
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
	}
}
