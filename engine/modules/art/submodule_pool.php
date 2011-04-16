<?

class Art_Submodule_Pool extends Art_Submodule_Group implements Plugins
{
	protected $type = 'pool';
	
	public static function description ($query) {

		$pool_id = (int) $query['alias'];
		
		$pool = Objects::db()->get_full_row('art_pool', $pool_id);
		
		return array_merge($pool, array('type' => 'pool'));
	}		
}
