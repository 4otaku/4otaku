<?
// Начало работы контроллера, определение типа запроса.
	
class Query
{
	// Хранит объект контроллера
	public $controller;
	
	function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	function get_controller() {		
		if (!empty(Globals::$user['mobile'])) {
			$this->controller = new Mobile_Controller($this);
			return;
		}
		
		if (!empty(Globals::$vars['ajax'])) {
			$this->controller = new Ajax_Controller($this);
			return;
		}
		
		$this->controller = new Default_Controller($this);
	}
	
	public function make_clean() {
		$params = get_object_vars($this);
		
		foreach (array ($params) as $name => $param) {
			if (Validate::is_invalid($name, $param)) {
				unset($this->$name);
			}
		}
	}
}
