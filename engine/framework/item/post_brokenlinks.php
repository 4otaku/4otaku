<?

class Item_Post_Brokenlinks extends Item_Abstract_Base implements Plugins
{
	public function postprocess () {
		$this->data['item_type'] = 'post_links_'.$this->flag;
	}
}
	
