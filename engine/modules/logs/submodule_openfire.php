<?

class Logs_Submodule_Openfire extends Logs_Submodule_Source implements Plugins
{
	
	protected function extract_start() {
		$database = $this->config['source']['key1'];
		$id = (int) $this->config['source']['key2'];
		
		$start = Database::db($database)->get_field(
			'ofMucConversationLog',
			'logTime',
			'roomID = ? order by logTime',
			$id
		);
		
		return date('G-i-s', $start / 1000);
	}
	
	protected function extract_data($query) {
		$database = $this->config['source']['key1'];
		$id = (int) $this->config['source']['key2'];
		
		$day = mktime(0, 0, 0, $query['month'], $query['day'], $query['year'])*1000;
		
		return Database::db($database)->get_table(
			'ofMucConversationLog',
			'`nickname` as name, `logTime` as time, `subject`, `body` as text',
			'roomID = ? and 
				cast(logTime as unsigned) > ? and 
				cast(logTime as unsigned) < ? 
				order by logTime',
			array($id, $day, $day + 86400000)
		);			
	}
}
