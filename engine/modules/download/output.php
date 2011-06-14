<?

class Download_Output extends Output implements Plugins
{
	protected static $content_types = array(
		'zip' => 'application/zip',
	);
	
	public function main ($query) {

		// Убираем первый элемент из $url - это был указатель на модуль скачки
		$url = Globals::$url;
		array_shift($url);
		$url = array_values($url);
		
		// Повторяем сбор запроса, чтобы было с чем запрашивать скачку		
		$module = Query::get_module($url);

		$module_config_file = ENGINE.SL.'modules'.SL.$module.SL.'settings.ini';
		Config::load($module_config_file, true, true);		
		
		$query_output = Query::make_query_output($url);
		
		$worker = Query::get_worker_name($module, $query_output, 'output');
		
		$worker = new $worker();
		
		// Убеждаемся, что попали в подходящий модуль
		if (!($worker instanceOf Downloadable)) {
			Error::fatal("Некорректный путь для скачки");
		}
		
		$file = $worker->get_download_file($query_output);
		$name = $worker->get_download_name($query_output);

		$this->make_download($file, $name);
	}
	
	public static function make_download ($file, $name = false) {

		$size = filesize($file);
		$fileinfo = pathinfo($file);
		
		$name = empty($name) ? $fileinfo['basename'] : $name;
	   
		$name = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ?
			preg_replace('/\./', '%2e', $name, substr_count($name, '.') - 1) :
			$name;
	   
		$file_extension = strtolower($fileinfo['extension']);

		if(isset($_SERVER['HTTP_RANGE'])) {
			list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			if ($size_unit == 'bytes') {
				list($range, $extra_ranges) = explode(',', $range_orig, 2);
			} else {
				$range = '';
			}
		} else {
			$range = '';
		}

		list($seek_start, $seek_end) = explode('-', $range, 2);

		$seek_end = (empty($seek_end)) ? 
			($size - 1) : 
			min(abs(intval($seek_end)),($size - 1));
			
		$seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 
			0 : 
			max(abs(intval($seek_start)),0);

		if ($seek_start > 0 || $seek_end < ($size - 1)) {
			header('HTTP/1.1 206 Partial Content');
		}

		header('Accept-Ranges: bytes');
		header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);
		
		self::send_content_type($file_extension);
		
		header('Content-Disposition: attachment; filename="' . $name . '"');
		header('Content-Length: '.($seek_end - $seek_start + 1));

		$file_handle = fopen($file, 'rb');
		fseek($file_handle, $seek_start);

		while(!feof($file_handle)) {
			set_time_limit(0);
			print(fread($file_handle, 1024*1024));
			flush();
			ob_flush();
		}

		fclose($file_handle);
		exit();	
	}
	
	protected static function send_content_type ($extension) {
		if (!empty(self::$content_types[$extension])) {
			header('Content-type: '.self::$content_types[$extension]);
		}
	}
}
