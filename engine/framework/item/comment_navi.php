<?

// Блок содержащий навигацию по страницам

class Item_Comment_Navi extends Item_Navi implements Plugins
{	
	protected $item_type = 'navi';
	
	public function postprocess () {
		parent::postprocess ();
		
		$this->data['is_comment'] = true;
		
		$this->data['comment_all'] = $this->data['base'].'all/';
	}
	
	protected function get_base ($module, $query) {
	
		return '/'.$query['place'].'/'.$query['item_id'].'/'.$module.'/';
	}
}
