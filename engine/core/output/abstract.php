<?

abstract class Output_Abstract
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}	
	
	abstract public function get_data($query);
}
