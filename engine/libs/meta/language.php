<?

class Meta_Language extends Meta_Library implements Plugins
{
	public function get_data_by_alias($aliases) {
		$condition = "type='language' and ".Database::array_in('alias', $aliases);

		$select = array('alias','name');

		return Database::get_vector('meta', $select, $condition, $aliases, false);
	}
}
