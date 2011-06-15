<?

class Item_Block extends Item_Abstract_Container implements Plugins
{
	public function postprocess () {
		$this->data['settings'] = $this->flag;
		
		if (!empty($this->data['latest'])) {
			$latest = & $this->data['latest'];
			$latest = (array) $latest;
			
			foreach ($latest as & $item) {
				if (is_array($item) && !empty($item['text'])) {
					$item['headline'] = Transform_Text::headline($item['text']);
					unset($item['text']);
				}
			}
		}
		
		parent::postprocess();
	} 
}
