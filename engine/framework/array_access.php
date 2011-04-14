<?

class Array_Access implements ArrayAccess
{
	protected $data;

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
    
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }
    
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
    
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}
