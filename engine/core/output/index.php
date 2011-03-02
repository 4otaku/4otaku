<?

class Output_Index extends Output_Abstract
{	
	public function get_data($query) {
		return $this->index($query);
	}	
	
	public function index() {
		$return = array();
		
		$return['pic'] = Globals::db()->get_row('art', "area != 'deleted' order by RAND()");
		
		$return['template'] = 'error';
		
		return $return;
	}	
}
