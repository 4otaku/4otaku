<?

class Header_Output extends Module_Output implements Plugins
{
	public function main () {
		$return = array();

		$menu = Config::menu();
		
		foreach ($menu as $part) {
			$name = $part['url'];
			
			unset($part['url']);
			
			$return[$name] = array_flip($part);
		}
		
		return $return;
	}
}
