<?

class Core implements Plugins
{	
	public static function get_module ($url, $vars) {
		if (!empty($vars['module'])) {
			$module = $vars['module'];
		} else {
			$module = reset($url);
		}

		return class_exists(ucfirst($module).'_Output') ? $module : 'error';
	}
	
	public static function get_worker_name ($module, $query, $type) {
		if (!empty($query['submodule'])) {
			return ucfirst($module).'_Submodule_'.ucfirst($query['submodule']);
		}
		
		return ucfirst($module).'_'.ucfirst($type);
	}
}
