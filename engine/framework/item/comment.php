<?

class Item_Comment extends Item_Abstract_Container implements Plugins
{
	public function postprocess () {

		parent::postprocess();

		switch ($this->flag) {
			case 'quotation':
				if (
					!empty($this->data['parent']) && 
					!empty($this->parent->data['items'][$this->data['parent']])
				) {
					$this->data['quotation'] = $this->parent->data['items'][$this->data['parent']];
				}
				break;
			case 'ladder':
				break;
			default: 
				break;
		}
		
		if (!empty($this->parent) && !empty($this->parent->data['place'])) {
			$this->data['place'] = $this->parent->data['place'];
			$this->data['link'] = $this->parent['link'];
		}
	}
	
	public function get_gravatar () {
		return md5(strtolower($this->data['email']));
	}
}
