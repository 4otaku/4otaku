<?
	
class Browser
{
	protected static $link_domain_alias = array(
		'narod.ru' => 'yandex.ru',
	);		
		
	protected static $link_works = array(
		'yandex.ru' => '<b>Скачать<\/b>',
	);
	
	protected static $link_broken = array(
		
	);	
	
	public static function check_download_link ($link, $save_unknown = false) {
		
		$link = str_replace('&apos;', "'", html_entity_decode($link, ENT_QUOTES, 'UTF-8'));
		
		$domain = parse_url($link, PHP_URL_HOST);

		preg_match('/^.*?([^\.]+\.[^\.]+)$/', $domain, $domain);
		$domain = $domain[1];

		if (isset(self::$link_domain_alias[$domain])) {
			$domain = self::$link_domain_alias[$domain];
		}

		if (empty($domain)) {
			return 'unclear';
		}		
		
		$html = self::download($link);
		
		if (empty($html)) {
			return 'unclear';
		}
		
		if (!empty(self::$link_works[$domain])) {
			$tests = (array) self::$link_works[$domain];
			
			foreach ($tests as $test) {
				if (preg_match('/'.$test.'/u', $html)) {
					return 'ok';
				}
			}
		}
		
		if (!empty(self::$link_broken[$domain])) {
			$tests = (array) self::$link_broken[$domain];
			
			foreach ($tests as $test) {
				if (preg_match('/'.$test.'/u', $html)) {
					return 'broken';
				}
			}
		}
var_dump($save_unknown, file_exists(ROOT.SL.'cache'.SL.'unknown_links'.SL.$domain));
		if ($save_unknown && !file_exists(ROOT.SL.'cache'.SL.'unknown_links'.SL.$domain)) {
			file_put_contents(ROOT.SL.'cache'.SL.'unknown_links'.SL.$domain, $html);
		}
		
		return 'unclear';
	}

	public static function download ($link, $simple = false) {
		$simple = (bool) $simple;

		if (!$simple) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($curl, CURLOPT_URL, $link);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$html = curl_exec($curl);
			curl_close($curl);
		} else {
			$html = @file_get_contents($link);
		}
		
		return $html;
	}
}
