<?
	
class Cache_Memcached implements Cache_Interface_Single, Cache_Interface_Array, Plugins
{	
	public $able_to_work = true;
	
	// Для хранения объекта Memcached
	protected $memcached;
	
	public function __construct ($config) {
		
		if (class_exists('Memcached')) {
			$this->memcached = new Memcached();
			
			if (isset($config['serialize']) && $config['serialize'] == 'igbinary') {
				$serializer = Memcached::SERIALIZER_IGBINARY;
			} else {
				$serializer = Memcached::SERIALIZER_PHP;
			}
							
			$this->memcached->setOption(Memcached::OPT_SERIALIZER, $serializer);
		} else {
			$this->able_to_work = false;
		}
	}
	
	public static function set ($key, $value, $expire = null) {		
		$this->memcached->set($key, $value, $expire);
	}
	
	public static function set_array ($keys, $values, $expire = null) {
		$items = array_combine($keys, $values);
		
		$this->memcached->setMulti($items, $expire);
	}
	
	public static function get ($key) {
		$value = $this->memcached->get($key);
		
		if ($this->memcached->getResultCode() === MEMCACHED::RES_NOTFOUND) {
			$value = false;
		}
		
		return false;
	}
	
	public static function get_array ($keys) {		
		return $this->memcached->getMulti($keys);
	}
	
	public static function delete ($key) {
		$this->memcached->delete($key);
	}
	
	public static function delete_array ($keys) {		
		foreach ($keys as $key) {
			$this->delete($key);
		}
	}
	
	public static function increment ($key, $value = 1) {
		$value = (int) $value;
		
		$this->memcached->increment($key, $value);
	}
	
	public static function increment_array ($keys, $value = 1) {		
		foreach ($keys as $key) {
			$this->increment($key, $value);
		}
	}
	
	public static function decrement ($key, $value = 1) {
		$value = (int) $value;
		
		$this->memcached->decrement($key, $value);
	}
	
	public static function decrement_array ($keys, $value = 1) {		
		foreach ($keys as $key) {
			$this->decrement($key, $value);
		}
	}
}
