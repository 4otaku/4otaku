<?

class Meta_Category extends Meta_Library implements Plugins
{
	public function get_data_by_alias($aliases) {
		$condition = "type='category' and ".Database::array_in('alias', $aliases);

		$select = array('alias','name','area');

		return Database::get_vector('meta', $select, $condition, $aliases, false);
	}
}
