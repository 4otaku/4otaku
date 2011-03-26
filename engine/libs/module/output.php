<?

abstract class Module_Output implements Plugins
{
	public function mark_item_types ($items, $type) {
		if (!empty($items) && is_array($items)) {
			foreach ($items as & $item) {
				if (empty($item['item_type'])) {
					$item['item_type'] = $type;
				}
			}
		}

		return $items;
	}

	public function build_listing_condition ($query) {
		$condition = "area = '{$query['area']}'";

		if (!empty($query['meta']) && !empty($query['alias'])) {
			$condition .= " and match (meta) against ('+{$query['meta']}_{$query['alias']}' in boolean mode)";
		}

		return $condition;
	}
}
