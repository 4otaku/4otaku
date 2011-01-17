<? 

class output__index extends engine
{
	public $allowed_url = array(
		array(1 => '|index|', 2 => 'end')
	);
	public $template = 'index';
	public $side_modules = array(
		'head' => array('title')
	);
	
	private $wiki_namespaces = array(
		1 => 'Обсуждение',
		4 => 'wiki',
		5 => 'Обсуждение_wiki',
		6 => 'Файл',
	);
	
	function get_data() {
		global $sets; global $def;
		
		foreach ($def['type'] as $type) {
			$return['count'][$type] = array(
				'total' => obj::db()->sql('select count(id) from '.$type.' where area="main"',2),
				'unseen' => ($sets['visit'][$type] ? obj::db()->sql('select count(id) from '.$type.' where (area="main" and sortdate > '.($sets['visit'][$type]*1000).')',2) : ''),
				'latest' => obj::db()->sql('select id, '.($type == 'art' ? 'thumb, extension' : 'title, comment_count, text').' from '.$type.' where (area="main"'.($sets['show']['nsfw'] ? 
					($sets['show']['furry'] ? '' : ' and !(locate("|nsfw|",category) and locate("|furry|",tag))') .
					($sets['show']['yaoi'] ? '' : ' and !(locate("|nsfw|",category) and locate("|yaoi|",tag))') .
					($sets['show']['guro'] ? '' : ' and !(locate("|nsfw|",category) and locate("|guro|",tag))')
					: ' and !locate("|nsfw|",category)').') order by sortdate desc limit '.($type == 'art' ? '1' : '3'))
			);
		}
			
		$return['count']['order'] = array(
			'total' => obj::db()->sql('select count(id) from orders where area!="deleted"',2),
			'open' => obj::db()->sql('select count(id) from orders where area="workshop"',2),			
			'latest' => obj::db()->sql('select id,username,title,text,comment_count from orders where area="workshop"')
		);
		
		if (is_array($return['count']['order']['latest'])) {
			shuffle($return['count']['order']['latest']);
			$return['count']['order']['latest'] = array_slice($return['count']['order']['latest'], 0, 2);
		}
			
		if (!empty($sets['visit']['board'])) {
			$return['board'] = array(			
				'new' => obj::db()->sql('select count(*) from board where `type` = "2" and sortdate > '.$sets['visit']['board']*1000,2),
				'updated' => obj::db()->sql('select count(*) from board where `type` = "2" and sortdate < '.($sets['visit']['board']*1000).' and updated > '.$sets['visit']['board']*1000,2),
				'link' => _base64_encode(pack('i*',$sets['visit']['board']), true),
			);
		}		
		$return['board']['all'] = obj::db()->sql('select count(*) from board where `type` = "2"',2);
		
		$wiki = obj::db('wiki')->sql('select rc_title, rc_namespace from recentchanges order by rc_id desc limit 1',1);
		if (array_key_exists($wiki['rc_namespace'],$this->wiki_namespaces)) {
			$return['wiki'] = $this->wiki_namespaces[$wiki['rc_namespace']].':'.$wiki['rc_title'];
		} else {
			$return['wiki'] = $wiki['rc_title'];
		}
	
		if ($return['news'] = obj::db()->sql('select url,title,text,image,comment_count,sortdate from news where area="main" order by sortdate desc limit 1',1)) {
			$return['news']['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$return['news']['text']);
		} else {
			$return['news']['sortdate'] = 0;
		}
		
		$return['links'] = obj::db()->sql('select count(id) from gouf_links where status = "error"',2);
		
		return $return;
	}
}
