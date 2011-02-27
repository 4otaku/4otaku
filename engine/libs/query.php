<?
// Начало работы контроллера, определение типа запроса.
	
class Query
{
	// Хранит объект контроллера
	public $controller;
	
	// Хранит сам запрос
	public $query = array();	
	
	function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	function get_controller() {		
		if (!empty(Globals::$user['mobile'])) {
			$this->controller = new Controller_Mobile($this);
			return;
		}
		
		if (!empty(Globals::$vars['ajax'])) {
			$this->controller = new Controller_Ajax($this);
			return;
		}
		
		$this->controller = new Controller_Default($this);
	}
	
	public function make_clean() {
		$params = get_object_vars($this);
		
		foreach ($this->query as $name => $param) {
			if (Validate::is_invalid($name, $param)) {
				unset($this->query[$name]);
			}
		}
	}
}
