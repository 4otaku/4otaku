<?

class Controller_Web extends Controller_Abstract
{
	public function build() {
		$tests = get_class_methods($this);

		if (!empty(Globals::$vars['do'])) {
			$do = explode('.',Globals::$vars['do']);
			
			if (count($do) != 2) {
				Error::fatal("Неверный формат аргумента 'do'");
			}
			
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
		}
		
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
		
		Error::fatal("Не удалось найти исполнителя для запроса");
	}
}
