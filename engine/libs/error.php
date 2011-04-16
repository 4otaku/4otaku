<?

final class Error
{	
	public static function fatal($message) {
		echo "<br /><br />$message<br /><br />";
		die;
	}
	
	public static function warning($message) {
		echo "<br /><br />$message<br /><br />";
	}
}
