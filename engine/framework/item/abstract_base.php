<?

abstract class Item_Abstract_Base implements Plugins, ArrayAccess
{
	protected $parent;
	protected $flag;
	protected $cache = array();
	protected $data = array();
	
	public function __construct ($data = array(), $flag = false) {
		
		$this->data = (array) $data;
		$this->flag = $flag;	
	}
	
    public function __set($key, $value) {
        $this->offsetSet($key, $value);
    }	

	public function offsetSet ($offset, $value) {		
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}
	
	public function offsetUnset ($offset) {
		unset($this->data[$offset]);
	}
	
	// Надо, чтобы получить возможность отложенных вычислений
	
	public function offsetExists ($offset) {		
		return isset($this->data[$offset]) || method_exists($this, 'get_'.$offset);
	}
	
	public function offsetGet ($offset) {
		$data = $this->inner_get($offset);
		if (!empty($data)) {
			return $data;
		}
		
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}	
	
	public function inner_get ($offset) {
		if (!isset($this->cache[$offset])) {
			
			$method = 'get_'.$offset;
			$this->cache[$offset] = method_exists($this, $method) ?
				$this->$method() :
				null;
		}
		
		return $this->cache[$offset];
	}
	
	// Немного утилитарных функций доступа
	
	public function add_to ($key, $value, $offset = null) {		
		if (is_null($offset)) {			
			$this->data[$key][] = $value;
		} else {
			$this->data[$key][$offset] = $value;
		}
	}
	
	public function last_of ($key) {
		$return = $this->data[$key];
		
		return is_array($return) ? end($return) : $return;
	}
	
	public function first_of ($key) {
		$return = $this->data[$key];
		
		return is_array($return) ? reset($return) : $return;
	}
	
	// Алиас для краткости и читаемости, в случаях когда надо вызвать функцию напрямую.
	
	public function add ($offset, $value) {
		$this->offsetSet($offset, $value);
	}
}
