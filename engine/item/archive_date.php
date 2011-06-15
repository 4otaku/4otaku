<?

class Item_Archive_Date extends Item_Abstract_Marked implements Plugins
{	
	public function postprocess () {
		foreach ($this->data as $key => $value) {
			if ($key == 'item_type' || $key == 'months') {
				continue;
			}
			
			$count = 0;
			foreach ($value as $day) {
				$count += isset($day['count']) ? $day['count'] : 1;
			}
			
			$this->data['months'][$key] = array(
				'items' => $value,
				'name' => Transform_String::rumonth($key),
				'count' => $count
			);
			unset($this->data[$key]);
		}
	}
}
