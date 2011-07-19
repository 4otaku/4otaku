<?

class Description_Output extends Output_Simple implements Plugins
{
	public function main ($query) {	
		$module = $query['module'];

		$template = $module;
		if (!empty($query['submodule'])) {
			$template .= '_'.$query['submodule'];
		}
		$return = array('template' => $template);
		

		$class = $this->get_output_module($query);

		if (is_callable(array($class, 'description'))) {
			$data = call_user_func(array($class, 'description'), $query);
			$return = array_merge((array) $data, $return);
		}

		return $return;
	}
}
