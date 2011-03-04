<?

class Fetch_Author extends Fetch_Abstract
{	
	public function get_data_by_alias($aliases) {
		$condition = "type='author' and ".Globals::db()->array_in('alias',$aliases);
		
		$select = array('alias','name');
		
		return Objects::db()->get_vector('meta', $condition, $select, $aliases, false);
	}
}
