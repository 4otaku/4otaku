<?

class Item_Index_Block extends Item_Abstract_Base implements Plugins
{
	public function postprocess () {
		$latest = & $this->data['latest'];
		$latest = (array) $latest;
		
		foreach ($latest as & $item) {
			if (is_array($item) && !empty($item['text'])) {
				$item['headline'] = Transform_Text::headline($item['text']);
				unset($item['text']);
			}
		}
	}
}
