<?

class Header_Output extends Module_Output implements Plugins
{
	public function main () {
		$return = array();

		$menu = Config::menu();
		
		foreach ($menu as $title => $part) {
			$name = $part['url'];
			
			unset($part['url']);
			
			$return[$name]['items'] = array_flip($part);
			$return[$name]['title'] = $title;
		}
		
		return $return;
	}
}
