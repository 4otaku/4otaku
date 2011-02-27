<?

class Core
{
	function __construct(& $query) {
		$this->call = Plugins::extend($this);
		$this->query = $query;
	}
	
	function process() {		
		$classname = 'process_'.$this->query['area'];
		$function = $this->query['type'];
		
		if (!class_exists($classname)) {
			$classname = 'process_error';
			$function = 'universal';
		}
	
		$worker = new $classname();		
		
		if (method_exists($worker, $function)) {
			$return = (array) $worker->call->$function($this->query);
		}
		
		$return['domain'] = 'http://beta.4otaku.ru';
		
		return $return;
	}
}
