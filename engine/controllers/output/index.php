<?

class Output_Index implements Output_Interface
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	public function get_function($url) {
		return 'index';
	}	
	
	public function index() {
		return array();
	}	
}
