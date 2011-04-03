<?
	
class Cache_Files implements Cache_Interface_Single, Plugins
{	
	const FOLDER = 'files_cache';
	
	public $able_to_work = true;
	
	protected $igbinary = false;
		
	public function __construct ($config) {
		
		if (!is_writable(CACHE.SL.FOLDER)) {
			$this->able_to_work = false;
		}
		
		if (isset($config['serialize']) && $config['serialize'] == 'igbinary') {
			$this->igbinary = true;
		}		
	}
	
	protected function serialize ($value) {
		if ($this->igbinary) {
			return igbinary_serialize($value);
		}
		
		return serialize($value);
	}
	
	protected function unserialize ($value) {
		if ($this->igbinary) {
			return igbinary_unserialize($value);
		}
		
		return unserialize($value);
	}
	
	protected function get_filename ($key) {
		return CACHE.SL.FOLDER.urlencode($key);
	}
	
	public static function set ($key, $value, $expire = null) {
		$filename = $this->get_filename($key);
		$content = (time() + (int) $expire) . "\n" . $this->serialize($value);
		
		file_put_contents($filename, $content);
	}
	
	public static function get ($key) {
		$filename = $this->get_filename($key);
		
		$content = file_get_contents($filename);
		list($expire, $content) = preg_split('/\n/', $content, 2);
		
		if ((int) $expire > time()) {
			return $this->unserialize($content);
		} else {
			unlink($filename);
		}
	}
	
	public static function delete ($key) {
		$filename = $this->get_filename($key);
		
		unlink($filename);
	}
	
	public static function increment ($key, $value = 1) {
		$filename = $this->get_filename($key);
		
		$content = file_get_contents($filename);
		list($expire, $content) = preg_split('/\n/', $content, 2);
		
		$content = $expire . "\n" . ((int) $content + (int) $value);
		file_put_contents($filename, $content);
	}
	
	public static function decrement ($key, $value = 1) {
		$filename = $this->get_filename($key);
		
		$content = file_get_contents($filename);
		list($expire, $content) = preg_split('/\n/', $content, 2);
		
		$content = $expire . "\n" . ((int) $content - (int) $value);
		file_put_contents($filename, $content);
	}
}
