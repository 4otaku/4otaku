<?

	if (!preg_match('/^\/files\/(post|torrent)\/[\da-f]+\/[^\/]+$/s', $_GET['file'])) {
		die("Ошибка скачивания");
	}

	$file = __DIR__.$_GET['file'];

	if (!file_exists($file)) {
		die("Ошибка скачивания");
	}

	$size = filesize($file);
	$fileinfo = pathinfo($file);

	$filename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ?
		preg_replace('/\./', '%2e', $fileinfo['basename'], substr_count($fileinfo['basename'], '.') - 1) :
		$fileinfo['basename'];

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

	$seek_end = (empty($seek_end)) ? ($size - 1) : min(abs(intval($seek_end)),($size - 1));
	$seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);

	if ($file_extension == 'torrent') {
		header('Content-type: application/x-bittorrent; name="' . $filename . '"');
		header('Content-Disposition: attachment; filename="' . $filename . '"');

		header('Content-Length: '.$size);

		print(file_get_contents($file));
		flush();
		ob_flush();
	} else {
		header('Content-type: text/plain');

		if ($seek_start > 0 || $seek_end < ($size - 1)) {
			header('HTTP/1.1 206 Partial Content');
		}

		header('Accept-Ranges: bytes');
		header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);

		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: '.($seek_end - $seek_start + 1));

		$fp = fopen($file, 'rb');
		fseek($fp, $seek_start);

		while(!feof($fp)) {
			set_time_limit(0);
			print(fread($fp, 1024*1024));
			flush();
			ob_flush();
		}

		fclose($fp);
	}
