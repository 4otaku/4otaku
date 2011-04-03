<?

class Meta_Category extends Meta_Library implements Plugins
{
	public function get_data_by_alias($aliases) {
		$condition = "type='category' and ".Globals::db()->array_in('alias', $aliases);

		$select = array('alias','name','area');

		return Objects::db()->get_vector('meta', $select, $condition, $aliases, false);
	}
}
