<?

class Fetch_Language implements Plugins
{
	public function get_data_by_alias($aliases) {
		$condition = "type='language' and ".Globals::db()->array_in('alias', $aliases);

		$select = array('alias','name');

		return Objects::db()->get_vector('meta', $select, $condition, $aliases, false);
	}
}
