<?

class Core
{
	function __construct(& $query) {
		$this->call = Plugins::extend($this);
		$this->query = $query;
	}
	
	function process() {
		$res = Globals::db()->get_field('art', 'md5', 'id=??', 234);		
		Globals::db()->debug();
		
		return array('agent' => $res);
	}
}
