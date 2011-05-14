<?

class Item_Comment extends Item_Abstract_Container implements Plugins
{
	public function postprocess () {

		switch ($this->flag) {
			case 'quotation':
			
				if (
					empty($this->data['quotation']) &&
					!empty($this->data['parent']) && 
					!empty($this->parent->data['items'][$this->data['parent']])
				) {
					$this->data['quotation'] = $this->parent->data['items'][$this->data['parent']];
				}
				break;
			case 'ladder':
				if (!empty($this->data['items'])) {
					if (empty($this->parent)) {
						$tree_length = 0;
						
						foreach ($this->data['items'] as $child) {
							$tree_length = max($tree_length, count($child['tree']));
						}
						
						$config = Globals::user('comments', 'single_item');
						
						if ($tree_length * $config['max_single_margin'] < $config['max_margin']) {
							$this->data['margin'] = $config['max_single_margin'];
						} else {
							$this->data['margin'] = round($config['max_margin'] / $tree_length);
						}
					}
					
					$indirect_children = array();
					
					foreach ($this->data['items'] as $id => $child) {
						if ($this->data['id'] != $child->last_of('tree')) {
							$indirect_children[$id] = $child;
							unset($this->data['items'][$id]);
						}
					}
					
					foreach ($this->data['items'] as $id => $child) {
						foreach ($indirect_children as $indirect_id => $indirect_child) {
							if (in_array($id, $indirect_child['tree'])) {
								$this->data['items'][$id]->add_to('items', $indirect_child, $indirect_id);
							}
						}
					}
				}
				break;
			default: 
				break;
		}
		
		parent::postprocess();
		
		if (!empty($this->parent) && !empty($this->parent->data['place'])) {
			$this->data['place'] = $this->parent->data['place'];
			$this->data['link'] = $this->parent['link'];
		}
	}
	
	public function get_gravatar () {
		return md5(strtolower($this->data['email']));
	}
	
	public function get_margin () {
		
		if (isset($this->data['margin'])) {
			return $this->data['margin'];
		}

		if (!empty($this->parent)) {
			return $this->parent->inner_get('margin');
		}
		
		return 0;
	}
	
	public function get_short_text () {
		$text = $this->data['text'];
		
		$text = Transform_Text::cut_long_text(strip_tags($text,'<br><em><strong><s>'),100);
		$text = preg_replace('/(<br(\s[^>]*)?>\n*)+/si','<br />',$text);

		return Transform_Text::cut_long_words($text);
	}
}
