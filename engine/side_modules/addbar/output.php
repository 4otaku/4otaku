<?

class Addbar_Output extends Output_Simple implements Plugins
{
	public function main ($query) {

		$class = $this->get_output_module($query);

		$return = array();
		if (is_callable(array($class, 'addbar'))) {
			$return = call_user_func(array($class, 'addbar'), $query);
		}		
		
		if (empty($return['caption'])) {
			$return['caption'] = Config::settings('addbar');
		}
			
		if (empty($return['caption'])) {
			Config::load(__DIR__.SL.'settings.ini', true);
			Config::addbar('caption', $class);
		}
		
		if (empty($return['module'])) {
			$return['module'] = $query['module'];
		}
		
		return $return;
	}
	
	public function display ($query) {
		$this->template = 'addbar';
		
		$vars = array_filter($query['vars']);
		$function = current($vars);

		if (is_callable(array($this, $function))) {
			$return = $this->$function($vars);
		
			if (empty($return['template'])) {
				$return['template'] = $function;
			}
	
			return $return;			
		}
		
		return array('template' => $function);
	}
	
	// Сбор информации для конкретных вызовов формы добавления
	
	protected function challenge ($vars) {
		
		return array();
	}
}
