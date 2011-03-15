<?
	
class Browser
{	
	public static function check_download_link ($link) {
		$link = str_replace('&apos;',"'",html_entity_decode($link,ENT_QUOTES,'UTF-8'));
		$return = 'works';
		foreach ($base as $one) if (stristr($link,$one['alias'])) {

			$input = $this->gouf_curl(str_replace(' ','%20',stripslashes($link)), ($one['alias'] == 'mediafire.com'));

			if (!trim($input) || ($one['alias'] == 'megaupload.com' && stristr($input,'http://www.megaupload.com/?c=msg')))
				return 'unknown';

			$return = 'error';
			if ($input) {
				$tests = explode('|',$one['text']);
				foreach ($tests as $test) if (stristr($input,$test)) $return = 'works';
			}
			break;

		}
		
		if ($return == 'error' && $one['alias'] == '4shared.com') {
			$fh = fopen("test.txt", 'w');
			fwrite($fh, $link."\n\n".implode("/n", $tests)."\n\n".$input);
			fclose($fh);
		}
		return $return;
	}

	public static function get_url ($link, $simple = false) {

		if (!$simple) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_URL, $link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$input = curl_exec($ch);
			curl_close($ch);
		}
		else $input = @file_get_contents($link);
		return $input;
	}
}
