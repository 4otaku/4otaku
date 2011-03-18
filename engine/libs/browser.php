<?
	
class Browser
{
	// Для хранения объекта Curl
	
	protected static $curl;
	
	protected static $link_domain_alias = array(
		'narod.ru' => 'yandex.ru',
	);		
		
	protected static $link_works = array(
		'yandex.ru' => '<b>Скачать<\/b>',
		'megaupload.com' => array(
			'<[Tt][Dd]\s+align="center">[Pp]lease\s+wait<\/[Tt][Dd]>',
			'<center>The\s+file\s+you\s+are\s+trying\s+to\s+access\s+is\s+temporarily\s+unavailable\.',
			'<center>Файл,\s+который\s+Вы\s+пытаетесь\s+открыть,\s+временно\s+недоступен',
		),
		'mediafire.com' => array(
			'Preparing download\.{3}', 
			'Data is loading from the server\.{2}',
		),
		'4shared.com' => '<font>Скачать<\/font>',
		'megashares.com' => array(
			'<td\s+[^>]*>Choose\s+download\s+service:<\/td>',
			'<dd\s+class="red">All\s+download\s+slots\s+for\s+this\s+link\s+are\s+currently\s+filled.',
		),
	);
	
	protected static $link_broken = array(
		'yandex.ru' => 'Закончился\s+срок\s+хранения\s+файла\.\s*Файл\s+удален',
		'rghost.ru' => '<div\s+[^>]*>[\s\r\n]*Файл\s+удален\.[\s\r\n]*<\/div>',
		'megaupload.com' => 'Unfortunately,\s+the\s+link\s+you\s+have\s+clicked\s+is\s+not\s+available\.'
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
		
		$html = self::download_html($link);
		
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

		if ($save_unknown && !file_exists(ROOT.SL.'cache'.SL.'unknown_links'.SL.$domain)) {
			file_put_contents(ROOT.SL.'cache'.SL.'unknown_links'.SL.$domain, $html);
		}
		
		return 'unclear';
	}
	
	public static function download_html($link, $simple = false) {
		$simple = (bool) $simple;
		
		if ($simple) {
			return @file_get_contents($link);
		}
		
		if (empty(self::$curl)) {
			self::init_curl();
		}
		
		curl_setopt(self::$curl, CURLOPT_URL, $link);
		curl_setopt(self::$curl, CURLOPT_NOBODY, true);
        $headers =  curl_exec(self::$curl);
        
        var_dump($headers);
	}

	public static function download ($link) {
		if (empty(self::$curl)) {
			self::init_curl();
		}

		curl_setopt(self::$curl, CURLOPT_URL, $link);
		curl_setopt(self::$curl, CURLOPT_NOBODY, false);
		$html = curl_exec(self::$curl);
		
		return $html;
	}
	
	protected static function init_curl () {
		self::$curl = curl_init();
		curl_setopt(self::$curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt(self::$curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		curl_setopt(self::$curl, CURLOPT_TIMEOUT, 20);
		curl_setopt(self::$curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt(self::$curl, CURLOPT_RETURNTRANSFER, true);
	}	
}
