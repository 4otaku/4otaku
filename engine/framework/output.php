<?

abstract class Output extends Query implements Plugins
{
	// Флаги вывода, вроде размера тамбнейлов
	public $flags = array();
	
	// Для сущностей, которые надо вывести
	public $items = array();
	
	// Для дополнительных модулей, вроде шапки или последних комментариев
	public $submodules = array();
	
	public function process ($query) {
		$query = (array) $query;		
		$function = empty($query['function']) ? 'main' : $query['function'];

		$this->$function($query);

		foreach ($this->items as & $item) {
			if (is_object($item) && is_callable(array($item, 'postprocess'))) {
				$item->postprocess();
			}
		}
	
		return $this;
	}
	
	public function add_sub_data ($data, $name) {

		if (!is_object($data)) {
			$this->submodules[$name] = $data;
			
		} else {		
			$this->submodules[$name] = array(
				'items' => $data->items, 
				'flag' => $data->flags
			);
		}
	}
}
