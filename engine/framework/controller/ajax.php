<?

class Controller_Ajax implements Controller_Interface, Plugins
{
	public function query () {
		if (empty(Globals::$vars['module']) || empty(Globals::$vars['function'])) {
			Error::fatal("Неверный формат аякс-запроса");
		}
		
		$query = array(
			'type' => empty(Globals::$vars['input']) ? 'output' : 'input',
			'module' => Globals::$vars['module'],
			'function' => Globals::$vars['function'],
		);		

		return array_merge(Globals::$vars, $query);
	}
	
	public function subquery ($submodule, $area, $query) {

		return $query;		
	}	
}
