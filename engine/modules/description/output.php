<?

class Description_Output extends Output_Simple implements Plugins
{
	public function main ($query) {	
		$module = $query['module'];

		$return = array('template' => $module);
		
		$class = Core::get_worker_name($module, $query, 'output');

		if (is_callable(array($class, 'description'))) {
			$data = call_user_func(array($class, 'description'), $query);
			$return = array_merge((array) $data, $return);
		}

		return $return;
	}
	
	public function make_subquery ($query, $module) {
		unset($query['function']);
		
		$subquery = array_merge($query, array('module' => $module));
		
		return $subquery;
	}
}
