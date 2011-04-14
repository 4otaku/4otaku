<?

// Для вывода простых модулей и суб-модулей, не содержащих блоков

class Output_Simple extends Output implements Plugins
{
	public function process ($query) {
		$query = (array) $query;		
		$function = empty($query['function']) ? 'main' : $query['function'];

		return $this->$function($query);
	}
}
