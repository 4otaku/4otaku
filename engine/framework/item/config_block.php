<?

class Item_Config_Block extends Item_Abstract_Marked implements Plugins
{	
	public function postprocess () {
		
		foreach ($this->data as $key => $value) {
			if ($key == 'data' || $key == 'value' || $key == 'item_type') {
				continue;
			}
			
			$this->data['data'][$key] = $value;
			unset($this->data[$key]);
		}
	}
}
	
