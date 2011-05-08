<?

// Блок, содержащий дочерние блоки

abstract class Item_Abstract_Container extends Item_Abstract_Meta implements Plugins
{	
	public function postprocess () {

		parent::postprocess();
		
		if (!empty($this->data['items']) && is_array($this->data['items'])) {
			
			foreach ($this->data['items'] as & $item) {
				if (is_object($item)) {
					$item->parent = $this;
				}
			}
			
			foreach ($this->data['items'] as & $item) {
				if (is_object($item) && is_callable(array($item, 'postprocess'))) {
					$item->postprocess();
				}
			}
		}
	}
}
