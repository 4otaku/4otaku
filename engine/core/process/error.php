<?

class Process_Error
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	public function universal($query) {
		$return = array();
		
		$return['pic'] = Globals::db()->get_row('art', "area != 'deleted' order by RAND()");
		
		$return['template'] = 'error';
		
		return $return;
	}
}
