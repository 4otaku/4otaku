<?

abstract class Controller_Abstract
{
	protected $query;
	
	public function __construct(&$query) {
		$this->query = & $query->query;
		$this->call = Plugins::extend($this);
	}
	
	abstract function build();
}
