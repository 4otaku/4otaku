<?

class Profile_Output extends Module_Output implements Plugins
{
	public function main ($query) {
		if (!empty($query['section'])) {
			$return['items'] = array($query['section'] => Config::profile($query['section']));
		} else {
			$return['items'] = Config::profile();
		}
		
		foreach ($return['items'] as & $item) {
			$item = array('data' => $item, 'name' => $item['name']);
			unset($item['data']['name']);
		}
		unset($item);
		
		$return['items'] = $this->mark_item_types($return['items'], 'config_block');

		return $return;
	}
}
