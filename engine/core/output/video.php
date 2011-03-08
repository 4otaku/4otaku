<?

class Output_Video extends Output_Abstract
{	
	public function single($query) {
		$video = Globals::db()->get_row('video',$query['id']);
		
		$video['date'] = Globals::db()->date_to_unix($video['date']);
		
		$meta = Meta::prepare_meta(array($video['id'] => $video['meta']));
		
		$return['items'] = array(
			$video['id'] => array_merge($video, current($meta))
		);

		return $return;
	}	
	
	public function listing($query) {
		$return = array();	
		
		// TODO: perpage и area=main костыли, избавиться
		$perpage = 5;
		
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		
		$start = ($page - 1) * $perpage;
		
		$listing_condition = $this->call->build_listing_condition($query);
		
		$condition = $listing_condition . " order by date desc limit $start, $perpage";
		
		$return['items'] = Globals::db()->get_vector('video',$condition);

		$index = array();
		
		foreach ($return['items'] as $id => & $video) {
			$video = array_merge($video);
			
			$index[$id] = $video['meta'];
			
			$video['date'] = Globals::db()->date_to_unix($video['date']);
		}
		
		$meta = Meta::prepare_meta($index);
		
		$return['items'] = array_replace_recursive($return['items'], $meta);
		
		$count = Globals::db()->get_field('video',$listing_condition,'count(*)');

		$return['curr_page'] = $page;
		$return['pagecount'] = ceil($count / $perpage);

		return $return;
	}
}
