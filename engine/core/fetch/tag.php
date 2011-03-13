<?

class Fetch_Tag extends Fetch_Abstract
{
	public function get_data_by_alias($aliases) {
		$condition = Globals::db()->array_in('alias', $aliases);
		$full_condition = "type='tag' and ".$condition;

		$select = array('alias','name','color');

		$tags = Objects::db()->get_vector('meta', $select, $full_condition, $aliases, false);

		$variants = Objects::db()->get_full_table('tag_variants', $condition, $aliases);

		foreach ($variants as $variant) {
			$tags[$variant['alias']]['variants'][] = $variant['variant'];
		}

		return $tags;
	}
}
