<?

class Output_Post implements Output_Interface
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	public function get_function($url) {
		return 'listing';
	}
	
	public function listing() {
		$query = array();
		
		$query['start'] = 1;
		$query['end'] = 5;
		
		
		return $query;
	}	
}
