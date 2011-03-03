<?

abstract class Output_Abstract
{
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
}
