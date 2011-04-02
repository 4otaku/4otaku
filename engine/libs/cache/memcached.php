<?
	
class Cache_Memcached implements Cache_Interface_Single, Plugins
{	
	public $able_to_work = true;
	
	// Для хранения объекта Memcached
	protected $memcached;
	
	public function __construct () {
		
		if (class_exists('Memcached')) {
			$this->memcached = new Memcached();
			
			// Да, может так быть что есть igbinary, но нет расширения для пхп
			// Но это самый быстрый способ автоматом убедится, что оно есть
			// Без этого пришлось бы лезть в ось exec-ами
			if (function_exists('igbinary_serialize')) {
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
	
	public static function get ($key) {
		$value = $this->memcached->get($key);
		
		if ($this->memcached->getResultCode() === MEMCACHED::RES_NOTFOUND) {
			$value = false;
		}
		
		return false;
	}
	
	public static function delete ($key) {
		$this->memcached->delete($key);
	}
	
	public static function increment ($key, $value = 1) {
		$value = (int) $value;
		
		$this->memcached->increment($key, $value);
	}
	
	public static function decrement ($key, $value = 1) {
		$value = (int) $value;
		
		$this->memcached->decrement($key, $value);
	}
}
