<?

abstract class Controller
{
	protected $query;
	
	public function __construct(&$query) {
		$this->query = $query;
	}
	
	abstract function build();
}
