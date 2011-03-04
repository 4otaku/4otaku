<?

class Fetch_Area extends Fetch_Abstract
{	
	public function get_data_by_alias($aliases) {
		$aliases = (array) $aliases;
		
		return $aliases;
	}
}
