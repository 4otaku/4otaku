<?

abstract class Module_Web extends Module_Web_Library
{
	public function __construct () {
		$this->call = Plugins::extend($this);
	}
	
	abstract public function make_query ($url);
	
	abstract public function postprocess ($data);
}
