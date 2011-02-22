<?

class Core
{
	function __construct(& $query) {
		$this->call = Plugins::extend($this);
		$this->query = $query;
	}
	
	function process() {
		$res = Globals::db()->get_row('art', "area != 'deleted' order by RAND()");
		
		return array('pic' => $res);
	}
}
