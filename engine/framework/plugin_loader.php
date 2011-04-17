<?

final class Plugin_Loader
{
	// Массив с уже распарсенными и отформатированными изменениями,
	// которые должны наложить плагины
	private static $plugins = array();
	
	public static function drop_cache () {
		$cached_files = glob(ROOT.SL.'cache'.SL.'extended'.SL.'*');
		
		foreach ($cached_files as $cached_file) {
			if (basename($cached_file) != 'index.php') {
				unlink($cached_file);
			}
		}
	}
	
	public static function make_cache () {
		self::$plugins = array();
		
		$plugin_files = glob(ENGINE.SL.'plugins'.SL.'*.php');
		
		foreach ($plugin_files as $plugin_file) {
			self::load($plugin_file);
		}
		
		// TODO: генерация кеша из готовых плагинов
	}	
	
	public static function load ($file) {
		if (!is_readable($file)) {
			return false;
		}
		
		$plugin = file_get_contents($file);
		
		if (!self::test($plugin)) {
			return false;
		}
		
		// TODO: реализовать непосредственно загрузку
		
		return true;
	}
	
	private static function test ($plugin) {
		// TODO: придумать формат для плагинов и написать проверку
		return true;
	}
}
