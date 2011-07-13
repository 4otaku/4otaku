<?

class Header_Output extends Output implements Plugins
{
	public function main () {
		$menu = Config::menu();
		
		foreach ($menu as $title => $part) {
			
			if (isset($part['url'])) {
				$url = $part['url'];
				unset($part['url']);
			} else {
				$url = false;
			}
			
			if (!empty($part['function']) && method_exists($this, $part['function'])) {
				$function = $part['function'];
				$links = (array) $this->$function();
				
				$prefix = empty($part['prefix']) ? '' : $part['prefix'];
				
				foreach ($links as $name => $link) {
					$part[$name] = $prefix.$link;
				}
				
				unset($part['function']);
				unset($part['prefix']);
			}
			
			$this->items[] = array (
				'items' => array_flip($part),
				'title' => $title,
				'url' => $url
			);
		}
	}
	
	protected function get_boards () {
		$boards = Database::get_vector('category', array('name', 'alias'), 'area like "%|board|%"');
		
		foreach ($boards as &$board) {
			$board .= '/';
		}
		
		return $boards;
	}
}
