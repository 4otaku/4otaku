<?

class Challenge_Output extends Output implements Plugins
{	
	public function main ($query) {
/*		$type = $query["section"];
		if (empty($query["subsection"])) {
			$items = Config::settings("sections", $type, "items");
			$area = key($items);
		} else {
			$area = $query["subsection"];
		}

		Cache::$prefix = self::CACHE_PREFIX;

		if (!($this->items = Cache::get($type."_".$area))) { 
		
			$this->items = $this->get_full_tag_cloud($type, $area);
			
			Cache::set($type."_".$area, $this->items, DAY);
		}
		
		$this->flags["area"] = $area;
		$this->flags["type"] = $type; */
	}
}
