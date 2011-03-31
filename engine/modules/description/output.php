<?

class Description_Output extends Module_Output implements Plugins
{
	public function main () {

		$return = array('template' => Globals::$query['module']);
		
		$class = Globals::$query['module'] . '_output';
		
		if (is_callable(array($class, 'description'))) {
			$return = array_merge(call_user_func(array($class, 'description')), $return);
		}
		
		return $return;
	}
}
