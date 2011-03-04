<?
	
abstract class Process_Abstract
{	
	public function __construct() {
		$this->call = Plugins::extend($this);
	}
	
	public function process() {
		$args = func_get_args();
		$callback = array($this->call,(string) Globals::$controller);
		return call_user_func_array($callback,$args);
	}	
}
