<?

class Controller implements Controller_Interface, Plugins
{
	// Хранит рабочий контроллер
	private $worker;

	function __construct () {

		if (!empty(Globals::$user_data['mobile'])) {
			$this->worker = new Controller_Mobile();
		} elseif (!empty(Globals::$vars['ajax'])) {
			$this->worker = new Controller_Ajax();
		} else {
			$this->worker = new Controller_Web();
		}
		
		if (!($this->worker instanceOf Controller_Interface)) {
			$name = get_class($this->worker);
			Error::fatal("Контроллер $name не использует Controller_Interface");
		}		
	}
	
	public static function test_sub_area ($area, $function) {
		$area = explode(',', $area);
		
		foreach ($area as $test) {
			if ($test == '*') {
				return true;
			}
			
			if (preg_match('/^\{([a-z_]+)\}$/i', $test, $match)) {
				if ($function == $match[1]) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function query() {
		return $this->worker->query();
	}
	
	public function subquery($submodule, $area, $query) {
		return $this->worker->subquery($submodule, $area, $query);
	}

    public function get_type () {
		$worker_name = strtolower(get_class($this->worker));
		return preg_replace('/^[a-z]+?_/', '', $worker_name);
    }
}
