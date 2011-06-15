<?

class Item_Comment_Block extends Item_Abstract_Container implements Plugins
{
	protected $item_data = array();
	
	public function postprocess () {

		$this->data['id'] = (int) $this->data['id'];
		$this->data['place'] = preg_replace('/[^a-z_]+/i', '', $this->data['place']);
		$this->item_data = Database::get_full_row($this->data['place'], $this->data['id']);

		$this->data['link'] = !empty($this->item_data['url']) ?
			$this->item_data['url'] :
			$this->data['id'];

		parent::postprocess();
	}
	
	public function get_title () {
		if (!empty($this->item_data['title'])) {
			return $this->item_data['title'];
		}
		
		if ($this->data['place'] == 'art') {
			return 'Изображение №'.$this->data['id'];
		}
		
		return '';
	}
	
	public function get_comments () {
		if (!empty($this->item_data['comments'])) {
			return $this->item_data['comments'];
		}
		
		return 0;
	}
	
	public function get_image () {
		switch ($this->data['place']) {
			case 'art':
				return '/images/art/thumbnail/'.$this->item_data['thumbnail'].'.jpg';
			case 'post':
				$image = Database::get_field(
					'post_items', 
					'data', 
					'item_id = ? and `type` = ? and sort_number = ?', 
					array($this->data['id'], 'image', 0)
				);
				
				$image = Crypt::unpack($image);
			
				return '/images/post/thumbnail/'.$image['file'];
			case 'video':
				if (
					!empty($this->item_data['object_thumbnail']) && 
					$this->item_data['object_thumbnail'] != 'deleted'
				) {
					return '/images/video/thumbnail/'.$this->item_data['object_thumbnail'];
				} else {
					return '/i/novideo.jpg';
				}
			case 'news':
				return '/images/news/thumbnail/'.$this->item_data['image'];
			default:
				return false;
		}
	}
}
