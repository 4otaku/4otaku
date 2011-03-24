<?

abstract class Module_Web extends Module_Web_Library implements Plugins
{
	public function make_query ($url) {
		$query = array();
		
		if (is_array($this->url_parts)) {
			foreach ($this->url_parts as $part) {
				$function = 'get_'.$part;
				if (is_callable(array($this, $function))) {
					$query = array_merge($query, (array) $this->$function($url));
				}
			}
		}
		
		return $query;
	}
	
	abstract public function postprocess ($data);
}
