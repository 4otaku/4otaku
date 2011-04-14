<?

// Маркированные блоки, маркировка нужна для определения шаблона отображения

abstract class Item_Abstract_Marked extends Item_Abstract_Base implements Plugins
{	
	public function __construct ($data, $flag = false) {
		parent::__construct($data, $flag);
		
		$class = get_called_class();
		
		$this->data['item_type'] = strtolower(str_replace('Item_', '', $class));
	}
}
