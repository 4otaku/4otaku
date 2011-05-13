<?

// Маркированные блоки, маркировка нужна для определения шаблона отображения

abstract class Item_Abstract_Marked extends Item_Abstract_Base implements Plugins
{	
	public function __construct ($data = array(), $flag = false) {
		parent::__construct($data, $flag);
		
		if (empty($this->data['item_type'])) {
			
			if (!empty($this->item_type)) {
				$this->data['item_type'] = $this->item_type;
			} else {
				$class = get_called_class();
		
				$this->data['item_type'] = strtolower(str_replace('Item_', '', $class));
			}
		}
	}
}
