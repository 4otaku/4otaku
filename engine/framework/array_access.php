<?

class Array_Access implements ArrayAccess
{
	protected $data = array();
	
    public function __set($key, $value) {		var_dump($key); die;
        $this->offsetSet($key, $value);
    }	

	public function offsetSet ($offset, $value) {		
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->data[$offset] = $value;
		}
	}
	
	public function offsetExists ($offset) {
		return isset($this->data[$offset]);
	}
	
	public function offsetUnset ($offset) {
		unset($this->data[$offset]);
	}
	
	public function offsetGet ($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}
	
	public function add_to ($key, $value, $offset = null) {		
		if (is_null($offset)) {			
			$this->data[$key][] = $value;
		} else {
			$this->data[$key][$offset] = $value;
		}
	}
}
