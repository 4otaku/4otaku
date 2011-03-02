<?

class Test_Web_Output
{
	public function test_download($url) {
		if (isset($url[0]) && $url[0] == 'download') {
			array_shift($url);
			
			return array_merge($url, array('download' => true));
		}
	}
	
	public function test_mixed($url) {
		if (isset($url[1]) && $url[1] == 'mixed') {
			$mixed = Mixed::parse($url[2]);
			return array_merge($url, array('mixed' => $mixed));
		}
	}	
}
