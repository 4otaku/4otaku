<?

abstract class Art_Submodule_Group extends Art_Output implements Plugins
{	
	protected $type = false;
	
	public function index ($query) {

		$table_name = 'art_'.$this->type;
		$perpage_name = $this->type.'_per_page';
		$item_name = 'Item_Art_'.ucfirst($this->type);
		
		$perpage = Config::settings($perpage_name);
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = (int) ($page - 1) * $perpage;
		
		$items = Objects::db()->get_full_vector($table_name, '1 order by date desc limit '.$start.', '.$perpage);

		foreach ($items as $id => $item) {
			$this->items[$id] = new $item_name ($item);
		}
		
		$count = Objects::db()->get_count($table_name);
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'art',
			'submodule' => $this->type,
		));		
	}
	
	public function group ($query) {
		return $this->main($query);
	}

}
