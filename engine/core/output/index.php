<?

class Output_Index extends Output_Abstract
{	
	protected $wiki_namespaces = array(
		1 => 'Обсуждение',
		2 => 'Участник',
		3 => 'Обсуждение_участника',
		4 => 'wiki',
		5 => 'Обсуждение_wiki',
		6 => 'Файл',
		7 => 'Обсуждение_файла',
		8 => 'MediaWiki',
		9 => 'Обсуждение_MediaWiki',
		10 => 'Шаблон',
		11 => 'Обсуждение_шаблона',
		12 => 'Справка',
		13 => 'Обсуждение_справки',
		14 => 'Категория',
		15 => 'Обсуждение_категории',	
	);
	
	protected $summary = array('post','video','art');
		
	public function index() {
		
		$return['template'] = 'index';		
		
		foreach ($this->summary as $type) {
			$return['count'][$type] = array(
				'total' => Objects::db()->sql('select count(id) from '.$type.' where area="main"',2),
				'unseen' => ($sets['visit'][$type] ? Objects::db()->sql('select count(id) from '.$type.' where (area="main" and sortdate > '.($sets['visit'][$type]*1000).')',2) : ''),
				'latest' => Objects::db()->sql('select id, '.($type == 'art' ? 'thumb, extension' : 'title, comment_count, text').' from '.$type.' where (area="main"'.($sets['show']['nsfw'] ? 
					($sets['show']['furry'] ? '' : ' and !(locate("|nsfw|",category) and locate("|furry|",tag))') .
					($sets['show']['yaoi'] ? '' : ' and !(locate("|nsfw|",category) and locate("|yaoi|",tag))') .
					($sets['show']['guro'] ? '' : ' and !(locate("|nsfw|",category) and locate("|guro|",tag))')
					: ' and !locate("|nsfw|",category)').') order by sortdate desc limit '.($type == 'art' ? '1' : '3'))
			);
		}
			
		$return['count']['order'] = array(
			'total' => Objects::db()->sql('select count(id) from orders where area!="deleted"',2),
			'open' => Objects::db()->sql('select count(id) from orders where area="workshop"',2),			
			'latest' => Objects::db()->sql('select id,username,title,text,comment_count from orders where area="workshop"')
		);
		
		if (is_array($return['count']['order']['latest'])) {
			shuffle($return['count']['order']['latest']);
			$return['count']['order']['latest'] = array_slice($return['count']['order']['latest'], 0, 2);
		}
			
		if (!empty($sets['visit']['board'])) {
			$return['board'] = array(			
				'new' => Objects::db()->sql('select count(*) from board where `type` = "2" and sortdate > '.$sets['visit']['board']*1000,2),
				'updated' => Objects::db()->sql('select count(*) from board where `type` = "2" and sortdate < '.($sets['visit']['board']*1000).' and updated > '.$sets['visit']['board']*1000,2),
				'link' => _base64_encode(pack('i*',$sets['visit']['board']), true),
			);
		}		
		$return['board']['all'] = Objects::db()->sql('select count(*) from board where `type` = "2"',2);
		
		$wiki = Objects::db('wiki')->sql('select rc_title, rc_namespace from recentchanges where rc_type < 2 order by rc_id desc limit 1',1);
		if (array_key_exists($wiki['rc_namespace'],$this->wiki_namespaces)) {
			$return['wiki'] = $this->wiki_namespaces[$wiki['rc_namespace']].':'.$wiki['rc_title'];
		} else {
			$return['wiki'] = $wiki['rc_title'];
		}
	
		if ($return['news'] = Objects::db()->sql('select url,title,text,image,comment_count,sortdate from news where area="main" order by sortdate desc limit 1',1)) {
			$return['news']['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$return['news']['text']);
		} else {
			$return['news']['sortdate'] = 0;
		}
		
		$return['links'] = Objects::db()->sql('select count(id) from gouf_links where status = "error"',2);
		
		return $return;
	}	
}
