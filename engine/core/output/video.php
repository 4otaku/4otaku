<?

class Output_Video extends Output_Abstract
{	
	protected $sizes = array();
	
	public function __construct($class_name) {
		parent::__construct($class_name);
		
		$this->sizes = array(
			Config::settings('video', 'Width'), 
			Config::settings('video', 'Height')
		);
	}
	
	public function single($query) {
		$video = Globals::db()->get_row('video', $query['id']);
		
		$video['date'] = Globals::db()->date_to_unix($video['date']);
		
		$video['object'] = Crypt::unpack($video['object']);
		
		$meta = Meta::prepare_meta(array($video['id'] => $video['meta']));
		
		$return['items'] = array(
			$video['id'] => array_merge($video, current($meta))
		);

		return $return;
	}	
	
	public function listing($query) {
		$return = array();	
		
		$perpage = Config::settings('video', 'Amount');
		
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		
		$start = ($page - 1) * $perpage;
		
		$listing_condition = $this->call->build_listing_condition($query);
		
		$condition = $listing_condition . " order by date desc limit $start, $perpage";
		
		$return['items'] = Globals::db()->get_full_vector('video', $condition);

		$index = array();
		
		foreach ($return['items'] as $id => & $video) {
			$video = array_merge($video);
			
			$index[$id] = $video['meta'];
			
			$video['date'] = Globals::db()->date_to_unix($video['date']);
			$video['object'] = Crypt::unpack($video['object']);
			
			$video['object'] = str_replace(
				array('%video_width%','%video_height%'),
				$this->sizes,
				$video['object']
			);
		}
		
		$meta = Meta::prepare_meta($index);
		
		$return['items'] = array_replace_recursive($return['items'], $meta);
		
		$count = Globals::db()->get_count('video', $listing_condition);

		$return['curr_page'] = $page;
		$return['pagecount'] = ceil($count / $perpage);

		return $return;
	}
}
