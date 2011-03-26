<?

class Core implements Plugins
{
	public function process($query) {
		switch ($query['type']) {
			case 'output':
				return $this->process_output($query);
			case 'input':
				return $this->process_input($query);
			case 'ajax':
				return $this->process_ajax($query);
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

		$worker = new $classname($query['module']);

		$return = (array) $worker->$function($query);
		
		if (
			isset($query['header']) && 
			class_exists($header_class = 'output_header_'.$query['header'])
		) {
			$header_worker = new $header_class();
			
			$return['header'] = array_replace(
				array('template' => $query['header'])				
			);
		}

		return $return;
	}

	public function process_input($query) {

	}

	public function process_ajax($query) {

	}
}

/* Вытащенное из пошедших не в ту сторону контроллеров */

/*
			$classname = 'input_'.$do[0];

			if (!class_exists($classname)) {
				Error::fatal("Не удалось найти класс $classname");
			}

			$worker = new $classname();

			$function = $do[1];

			if (!method_exists($worker, $function)) {
				Error::fatal("Не удалось найти метод $function в $classname");
			}

			$this->query = (array) $worker->call->$function();
			return;

		$tests = get_class_methods($this);

		foreach ($tests as $test) {
			if (
				strpos('is_',$test) === 0 &&
				($arguments = $this->$test())
			) {
				$classname = str_replace('is_','output_',$test);
				if (class_exists($classname)) {
					break;
				} else {
					Error::warning("Не удалось найти класс $classname");
				}
			}
		}

		if (empty($classname)) {
			$classname = 'output_'.Globals::$url[0];
		}





		if (class_exists($classname)) {
			$worker = new $classname();
		} else {
//			Тут должен быть вызов ошибки
			$worker = new Output_Index();
		}

		if ($worker instanceOf Output_Interface) {
			$function = $worker->call->get_function(Globals::$url);

			$query = (array) $worker->call->$function();
			return array_merge(array(
				'area' => str_replace('output_','',$classname),
				'type' => $function,
			), $query);
		}
*/
