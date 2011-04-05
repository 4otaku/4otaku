<?
	
class Http
{		
	public static function redirect($url, $permanent = false) {
		if ((bool) $permanent) {
			header("HTTP/1.x 301 Moved Permanently");
		} else {
			header("HTTP/1.x 302 Moved Temporarily");
		}
		
		if (empty($url)) {
			$url = $_SERVER['REQUEST_URI'];
		}
		
		header("Location: $url");
	}
}
