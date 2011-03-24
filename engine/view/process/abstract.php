<?
	
abstract class Process_Abstract
{	
	public function process() {
		$args = func_get_args();
		$callback = array($this,(string) Globals::$controller);
		return call_user_func_array($callback,$args);
	}	
}
