<?php

class Model_Comment extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'rootparent',
		'parent',
		'place',
		'post_id',
		'username',
		'email',
		'ip',
		'cookie',
		'text',
		'pretty_text',
		'edit_date',
		'pretty_date',
		'sortdate',
		'area',
	);	
	
	protected $place_notify = array(
		'main' => 'оставлен на главной',
		'workshop' => 'оставлен в мастерской', 
		'flea_market' => 'оставлен в барахолке'
	);	
	
	public function __construct($data = array()) {
		parent::__construct($data);
		
		$this->set('comment_rights', sets::user('rights') || 
			$this->get('cookie') == query::$cookie);
			
		$this->set('text', Transform_Text::cut_long_words($this->get('text')));
			
		$this->set('delete_rights', sets::user('rights'));
		$this->set('avatar', md5(strtolower($this->get('email'))));
	}	
	
	public function add_child(Model_Comment $child) {
		$children = (array) $this->get('children');
		$orphans = (array) $this->get('orphans');
		
		if ($child->get('parent') != $this->get_id()) {
			$orphans[$child->get_id()] = $child;
		} else {
			$children[$child->get_id()] = $child;
		}
		
		$this->search_parents($children, $orphans);
		
		$this->set('children', $children);
		$this->set('orphans', $orphans);		
	}
	
	public function count_depth() {
		$children = (array) $this->get('children');
		
		$depth = 0;
		foreach ($children as $child) {
			$depth = max($depth, $child->count_depth());
		}
		
		return $depth + 1;
	}
	
	public function get_notify($area) {
		$comment_area = $this->get('area');
		
		if (
			$comment_area != $area && 
			array_key_exists($comment_area, $this->place_notify)
		) {
			return $this->place_notify[$comment_area];
		}

		return '';
	}
	
	protected function search_parents($children, $orphans) {
		$found = false;
		
		foreach ($orphans as $orphan_id => $orphan) {
			if ($orphan->get('found')) {
				continue;
			}
			
			$parent = $orphan->get('parent');			
			
			foreach ($orphans as $search_orphan_id => $search_orphan) {	
				if ($search_orphan_id == $parent) {
					$search_orphan->add_child($orphan);
					$orphan->set('found', true);
					$found = true;
					continue;
				}				
			}
			
			foreach ($children as $child_id => $child) {
				if ($child_id == $parent) {
					$child->add_child($orphan);
					$orphan->set('found', true);
					$found = true;
					continue;
				}
			}
		}
		
		if ($found) {
			$this->search_parents($children, $orphans);
		}
	}
}
