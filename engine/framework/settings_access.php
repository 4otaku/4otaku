<?

class Settings_Access implements Plugins, ArrayAccess
{
	protected $cache = array();
	protected $mode = 'config';
	
	public function __construct ($mode = 'config') {
		
		$this->mode = (string) $mode;
	}	
	
	// Разумеется эти объекты должны быть ридонли
	
	public function offsetSet ($offset, $value) {}
	
	public function offsetUnset ($offset) {}
	
	// Всегда true, на случай проверок
	
	public function offsetExists ($offset) {		
		return true;
	}
	
	// Собственно, возвращает опции
	
	public function offsetGet ($offset) {
		if (!isset($this->cache[$offset])) {
			
			$arguments = explode('.', $offset);
			
			if ($this->mode == 'user') {
				
				$this->cache[$offset] = 
					call_user_func_array(array('Globals', 'user'), $arguments);
			} else {
				
				$name = array_shift($arguments);
		
				$this->cache[$offset] = 
					call_user_func_array(array('Config', $name), $arguments);
			}
		}
		
		return $this->cache[$offset];
	}
}
