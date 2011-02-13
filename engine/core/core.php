<?

class Core
{
	function __construct(& $query) {
		$this->call = Plugins::extend($this);
		$this->query = $query;
	}
	
	function process() {
		Database::get_field('art', 'md5', 'id=??', 234);
		return array('agent' => $this->query->agent);
	}
}
