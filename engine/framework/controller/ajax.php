<?

class Controller_Ajax implements Controller_Interface, Plugins
{
	public function get_module () {
		return Globals::$vars['module'];
	}
	
	public function query () {
		if (empty(Globals::$vars['module']) || empty(Globals::$vars['function'])) {
			Error::fatal("Неверный формат аякс-запроса");
		}
		
		$query = array(
			'type' => empty(Globals::$vars['input']) ? 'output' : 'input',
		);		

		return array_merge(Globals::$vars, $query);
	}
	
	public function subquery ($submodule, $area, $query) {

		return $query;		
	}	
}
