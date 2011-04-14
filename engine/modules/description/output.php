<?

class Description_Output extends Output_Simple implements Plugins
{
	public function main ($query) {
		$module = reset($query);

		$return = array('template' => $module);
		
		$class = $module . '_Output';
		
		if (is_callable(array($class, 'description'))) {
			$data = call_user_func(array($class, 'description'));
			$return = array_merge((array) $data, $return);
		}

		return $return;
	}
	
	public function make_subquery ($query, $module) {
		return array($module);
	}
}
