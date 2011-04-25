<?

class Logs_Submodule_Openfire extends Logs_Submodule_Source implements Plugins
{
	
	protected function extract_start() {
		$database = $this->source['key1'];
		$id = (int) $this->source['key2'];
		
		$start = Database::db($database)->get_field(
			'ofMucConversationLog',
			'logTime',
			'roomID = ? order by logTime',
			$id
		);
		
		return date('G-i-s', $start / 1000);
	}
	
	protected function extract_data($query) {
		return array();
	}
}
