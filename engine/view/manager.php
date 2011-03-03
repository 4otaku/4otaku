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
			$this->data['navi'] = array();
			
			$base = '/post/';
			
			$radius = 5;
			$low_end = max($this->data['curr_page'] - $radius, 2);
			$high_end = min($this->data['curr_page'] + $radius, $this->data['pagecount'] - 1);
			
			for ($i = 1; $i <= $this->data['pagecount']; $i++) {
				
				$inside = ($i <= $high_end && $i >= $low_end);
				
				if ($i == 1 || $inside || $i == $this->data['pagecount']) {
					if ($i == $this->data['curr_page']) {
						$this->data['navi'][$i] = array('type' => 'active');
					} else {
						$this->data['navi'][$i] = array('type' => 'enabled');
					}
					
					if ($i == 1) {
						$this->data['navi'][$i]['url'] = $base;
					} else {
						$this->data['navi'][$i]['url'] = $base.'page/'.$i.'/';
					}
				} else {
					$this->data['navi'][$i] = array('type' => 'skip');
					if ($i > $high_end) {
						$i = $this->data['pagecount'] - 1;
					} else {
						$i = $low_end - 1;
					}
				}
			}
			
			if ($this->data['curr_page'] == 2) {
				$this->data['navi_back'] = $base;
			} elseif ($this->data['curr_page'] > 2) {
				$this->data['navi_back'] = $base.'page/'.($this->data['curr_page'] - 1).'/';
			}
			
			if ($this->data['curr_page'] < $this->data['pagecount']) {
				$this->data['navi_forward'] = $base.'page/'.($this->data['curr_page'] + 1).'/';
			}
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
