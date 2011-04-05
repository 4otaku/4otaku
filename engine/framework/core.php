<?

class Core implements Plugins
{
	const STOP_SIGNAL = '==Stop execution==';
	
	public function process($query) {
		switch ($query['type']) {
			case 'output':
				return $this->process_output($query);
			case 'input':
				return $this->process_input($query);
			default:
				Error::fatal("Некорректный тип запроса {$query['type']}");
		}
	}

	public function process_output($query) {
		$classname = $query['module'].'_output';
		$function = $query['function'];

		if (!class_exists($classname)) {
			$classname = 'error_output';
			$function = 'class_not_found';
		}

		$worker = new $classname();

		$return = (array) $worker->$function($query);
		
		return $return;
	}

	public function process_input($query) {
		$classname = $query['module'].'_input';
		$function = $query['function'];

		if (!class_exists($classname)) {
			$classname = 'error_input';
			$function = 'class_not_found';
		}

		$worker = new $classname();

		$worker->$function($query);
		
		if ($worker->redirect_address !== false) {
			Http::redirect($worker->redirect_address);
		}
		
		if ($query['type'] != 'output') {
			if (!empty($worker->status_message)) {
				echo $worker->status_message;
			}
			
			return self::STOP_SIGNAL;
		}
		
		return $this->process_output($query);
	}
}
