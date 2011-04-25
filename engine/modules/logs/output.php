<?

class Logs_Output extends Output implements Plugins
{	
	public function main ($query) {
		$query['section'] = 'main';
		
		$this->section($query);
	}
	
	public function section ($query) {
		
		$type = Config::settings('sections', $query['section'], 'type');		
		$worker = 'Logs_Submodule_'.ucfirst($type);

		$worker = new $worker($query['section']);
		
		$this->flags = $worker->get_start();
		
		$this->items = $worker->get_data($query);
	}
	
	public static function description ($query) {
		
			
	}	
}
