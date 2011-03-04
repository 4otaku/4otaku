<?

abstract class Fetch_Abstract
{	
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
}
