<?

abstract class Controller_Abstract
{	
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	abstract function build();
}
