<?
	
	// Задаем autoloader
	// Не __autoload, потому как в дальнейшем плагину или шаблонизатору 
	// может потребоваться добавить свой autoload	
	
	class Autoload
	{	
		const CACHE_FILE = 'autoload.cache';
		
		protected static $cached = array();
		protected static $cached_diff_add = array();
		protected static $cached_diff_remove = array();
		
		public static function init () {
			self::init_cache();
			
			spl_autoload_register('Autoload::cache', false);
			spl_autoload_register('Autoload::extended', false);
			spl_autoload_register('Autoload::normal', false);
			spl_autoload_register('Autoload::wrapper', false);
			
			register_shutdown_function('Autoload::write_cache');	
		}		
		
		public static function write_cache () {			

			if (empty(self::$cached_diff_remove)) {
				if  (empty(self::$cached_diff_add)) {
					// Нечего добавить или убавить, не трогаем файл.
					return;
				} else {
					// Нечего убавлять, достаточно дописать новое.
					
					$data = array_diff_key(self::$cached_diff_add, self::$cached);
					$flag = FILE_APPEND;
				}
			} else {
				// Надо удалить строки, проще пересобрать весь файл
				// Что и делаем.
				
				$data = array_diff_key(self::$cached, self::$cached_diff_remove);
				$data = array_merge($data, self::$cached_diff_add);
				$flag = 0;
			}
			
			if (empty($data)) {
				return;
			}
			
			$text = '';
			$cache_file = ROOT.SL.'cache'.SL.self::CACHE_FILE;
			
			foreach ($data as $name => $file) {
				$text .= $name.'='.$file.PHP_EOL;
			}
			
			file_put_contents($cache_file, $text, $flag);
		}
		
		public static function init_cache () {
			$cache_file = ROOT.SL.'cache'.SL.self::CACHE_FILE;
			
			if (file_exists($cache_file)) {
				$data = file($cache_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			} else {
				touch($cache_file);
				$data = array();
			}
			
			foreach ($data as $line) {
				list($name, $file) = explode('=', $line);
				
				self::$cached[$name] = $file;
			}			
		}
		
		public static function cache ($name) {
			
			if (array_key_exists($name, self::$cached)) {
				
				$file = self::$cached[$name];
				
				if (file_exists($file)) {
					
					include_once($file);
					return true;								
				} else {
					
					self::$cached_diff_remove[$name] = $file;
				}
			} 
			
			return false;
		}

		public static function extended ($class) {
			if ($class == 'index') {
				return false;
			}
			
			$extended = ROOT.SL.'cache'.SL.'extended'.SL.strtolower($class).'.php';
			
			if (file_exists($extended)) {
				self::$cached_diff_add[$class] = $extended;					
				
				include_once($extended);
				return true;
			}
			
			return false;
		}
		
		public static function normal ($class) {
			$name = str_replace('_', SL, strtolower($class)).'.php';

			if ($library = self::search_lib($name)) {
				self::$cached_diff_add[$class] = $library;
				
				include_once($library);
				return true;
			}
			
			$alt_name = preg_replace('/^(.+)\\'.SL.'(.+?)$/', '$1_$2', $name);
			while ($alt_name != $name) {
			
				if ($library = self::search_lib($alt_name)) {
					self::$cached_diff_add[$class] = $library;
					
					include_once($library);
					return true;
				}
				
				$name = $alt_name;
				$alt_name = preg_replace('/^(.+)\\'.SL.'(.+?)$/', '$1_$2', $name);
			}
			
			return false;
		}	
		
		public static function wrapper ($class) {
			$name = str_replace('_', '/', strtolower($class)).SL.'wrapper.php';

			if ($library = self::search_lib($name)) {
				self::$cached_diff_add[$class] = $library;
				
				include_once($library);
				return true;
			}
			
			return false;
		}
		
		protected static function search_lib ($name) {
			$directories = array(
				ENGINE.SL.'framework',
				ENGINE.SL.'modules',
				ENGINE.SL.'side_modules',
				ENGINE.SL.'libs',
			);

			foreach ($directories as $directory) {

				if (file_exists($directory.SL.$name)) {
					return $directory.SL.$name;
				}
			}
			
			return false;
		}
	}
	
	Autoload::init();
