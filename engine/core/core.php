<?

class Core
{
	function __construct(& $query) {
		$this->call = Plugins::extend($this);
		$this->query = $query;
	}
	
	function process() {
		$res = Globals::db()->get_row('art', 234);
		Globals::db()->debug();
		
		return array('agent' => serialize($res));
	}
}
