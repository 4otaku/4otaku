<?
	
class Cache_Memcache implements Cache_Interface_Single, Plugins
{	
	const	COMPRESS = true,
			MIN_COMPRESSION_RATIO = 0.9,
			MIN_COMPRESS_SIZE = 10240;
	
	public $able_to_work = true;
	
	// Для хранения объекта Memcache
	protected $memcache;
	
	public function __construct () {
		
		if (class_exists('Memcache')) {
			$this->memcache = new Memcache();
			
			if (self::COMPRESS) {
				$this->memcache->setCompressThreshold(
					self::MIN_COMPRESS_SIZE, 
					1 - self::MIN_COMPRESSION_RATIO
				);
			}
		} else {
			$this->able_to_work = false;
		}
	}
	
	public static function set ($key, $value, $expire = null) {
		$compression = self::COMPRESS ? MEMCACHE_COMPRESSED : 0;
		
		$this->memcache->set($key, $value, $compression, $expire);
	}
	
	public static function get ($key) {
		return $this->memcache->get($key);
	}
	
	public static function delete ($key) {
		$this->memcache->delete($key);
	}
	
	public static function increment ($key, $value = 1) {
		$value = (int) $value;
		
		$this->memcache->increment($key, $value);
	}
	
	public static function decrement ($key, $value = 1) {
		$value = (int) $value;
		
		$this->memcache->decrement($key, $value);
	}
}
