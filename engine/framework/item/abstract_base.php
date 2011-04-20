<?

abstract class Item_Abstract_Base extends Array_Access implements Plugins
{
	protected $flag;
	protected $cache = array();
	
	public function __construct ($data = array(), $flag = false) {
		
		$this->data = (array) $data;
		$this->flag = $flag;	
	}
	
	// Переопределим пару функций, чтобы получить возможность отложенных вычислений
	
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
}
