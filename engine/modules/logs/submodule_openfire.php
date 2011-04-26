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

		return !empty($start) ? date('Y-m-d', ceil($start / 1000)) : null;
	}
	
	protected function extract_data($query) {
		$database = $this->config['source']['key1'];
		$id = (int) $this->config['source']['key2'];
		
		$day = mktime(0, 0, 0, $query['month'], $query['day'], $query['year'])*1000;

		$data = Database::db($database)->get_table(
			'ofMucConversationLog',
			'`nickname` as name, `logTime` as time, `subject`, `body` as text',
			'roomID = ? and 
				cast(logTime as unsigned) > ? and 
				cast(logTime as unsigned) < ? 
				order by logTime',
			array($id, $day, $day + 86400000)
		);
		
		foreach ($data as & $row) {
			$row['milliseconds'] = $row['time'] % 1000;
			$row['time'] = ceil($row['time'] / 1000);
			$row['time'] = date('G:i:s', $row['time']);
			
			if (!empty($row['subject']) || substr($row['text'],0,3) == '/me') {
				
				$row['name'] .= empty($row['subject']) ?
					' '.substr($row['text'],3) :
					' установил тему "'.$row['subject'].'"';
				
				$row['text'] = '';
			} else {
				$row['name'] = '&lt;'.$row['name'].'&gt;';
			}
			
			$row['name'] = $this->make_safe($row['name']);
			$row['text'] = $this->make_safe($row['text']);
			unset($row['subject']);
		}
		unset ($row);
		
		return $data;
	}
}
