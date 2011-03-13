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

	public function make_clean($buffer) {
		return preg_replace(array('/[\t\r\n]+/', '/\s+/'), array('', ' '), $buffer);
	}

	public function postprocess() {

		if (!empty($this->data['pagecount']) && !empty($this->data['curr_page'])) {
			$worker = new Process_Navi();
			$this->data = $worker->process($this->data);
		}

		if (!empty($this->data['items'])) {
			$meta_worker = new Process_Meta();
			$date_worker = new Process_Date();

			foreach ($this->data['items'] as & $item) {
				if (!empty($item['meta'])) {
					$item = $meta_worker->process($item);
				}

				if (!empty($item['date'])) {
					$item['date'] = $date_worker->process($item['date']);
				}
			}
			unset ($item);
		}
		
		if (self::$template == 'index') {
			$worker = new Process_Index();
			
			$this->data = $worker->process($this->data);
		}
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
