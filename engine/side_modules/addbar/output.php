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
}
