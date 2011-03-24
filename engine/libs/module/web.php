<?

abstract class Module_Web extends Module_Web_Library implements Plugins
{
	abstract public function make_query ($url);
	
	abstract public function postprocess ($data);
}
