<?

class Output_Post implements Output_Interface
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	public function get_function($url) {
		if (is_numeric($url[1])) {
			return 'single';
		}
		
		return 'listing';
	}
	
	public function listing() {
		$query = array();
		
		$query['start'] = 1;
		$query['end'] = 5;
		
		
		return $query;
	}	
	
	public function single() {
		$query = array();
		
		$query['id'] = Globals::$url[1];		
		
		return $query;
	}	
}
