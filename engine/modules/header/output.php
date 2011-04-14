<?

class Header_Output extends Output implements Plugins
{
	public function main () {
		$menu = Config::menu();
		
		foreach ($menu as $title => $part) {
			$name = $part['url'];
			
			unset($part['url']);
			
			$this->items[$name]['items'] = array_flip($part);
			$this->items[$name]['title'] = $title;
		}
	}
}
