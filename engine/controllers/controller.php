<?

abstract class Controller
{
	protected $query;
	
	public function __construct(&$query) {
		$this->query = $query;
		$this->call = Plugins::extend($this);
	}
	
	abstract function build();
}
