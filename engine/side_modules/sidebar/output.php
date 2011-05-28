<?

class Sidebar_Output extends Output_Blocks implements Plugins
{
	public function main ($query) {			
		Config::load(__DIR__.SL.'settings.ini', true);
		
		parent::main($query, 'sidebar');		
	}
	
	protected function search ($settings) {}
	
	public function make_subquery ($query, $module) {
		$query['module'] = $module;
		unset($query['function']);
		
		return $query;
	}	
}
