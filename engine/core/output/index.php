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
	
	public function index() {
		
		$return['template'] = 'index';		
		
		$unseen = Globals::user('unseen');
		
		$return['post'] = array(
			'total' => Objects::db()->get_field('post', 'area = "main"', 'count(*)'),
			'unseen' => $unseen['post'] ? Objects::db()->get_field('post', 'area = "main" and date > ?', 'count(*)', $unseen['post']) : 0,
			'latest' => Objects::db()->get_table('post', 'area = "main" order by date desc limit 3', 'id, title, comments, text'),
		);
		
		$return['video'] = array(
			'total' => Objects::db()->get_field('video', 'area = "main"', 'count(*)'),
			'unseen' => $unseen['video'] ? Objects::db()->get_field('video', 'area = "main" and date > ?', 'count(*)', $unseen['video']) : 0,
			'latest' => Objects::db()->get_table('video', 'area = "main" order by date desc limit 3', 'id, title, comments, text'),
		);
		
		$return['art'] = array(
			'total' => Objects::db()->get_field('art', 'area = "main" or area = "sprites"', 'count(*)'),
			'unseen' => $unseen['art'] ? Objects::db()->get_field('art', 'area = "main" or area = "sprites" and date > ?', 'count(*)', $unseen['art']) : 0,
			'latest' => Objects::db()->get_row('art', 'area = "main" or area = "sprites" order by date desc', 'id, thumb'),
		);
			
		$return['order'] = array(
			'total' => Objects::db()->get_field('order', 'area != "deleted"', 'count(*)'),
			'unseen' => Objects::db()->get_field('order', 'area = "workshop"', 'count(*)'),		
			'latest' => Objects::db()->get_table('order', 'area = "workshop"', 'id, username, title, comments, text'),
		);
		
		if (is_array($return['order']['latest'])) {
			shuffle($return['order']['latest']);
			array_slice($return['order']['latest'], 2);
		}
/*			
		if (!empty($unseen['board'])) {
			$return['board'] = array(			
				'new' => Objects::db()->get_field('board', count(*) from board where `type` = "2" and sortdate > '.$sets['visit']['board']*1000,2),
				'updated' => Objects::db()->get_field('select count(*) from board where `type` = "2" and sortdate < '.($sets['visit']['board']*1000).' and updated > '.$sets['visit']['board']*1000,2),
				'link' => _base64_encode(pack('i*',$sets['visit']['board']), true),
			);
		}

		$return['board']['all'] = Objects::db()->sql('select count(*) from board where `type` = "2"',2);
*/
		
		$wiki = Objects::db('wiki')->get_row('recentchanges', 'rc_type < 2 order by rc_id desc limit 1', 'rc_title, rc_namespace');
		
		if (!empty($wiki)) {
			if (array_key_exists($wiki['rc_namespace'], $this->wiki_namespaces)) {
				$return['wiki'] = $this->wiki_namespaces[$wiki['rc_namespace']].':'.$wiki['rc_title'];
			} else {
				$return['wiki'] = $wiki['rc_title'];
			}
		}
	
		$return['news'] = Objects::db()->get_field('news', 'area="main" order by sortdate desc', 'url, title, text, image, comments, date');
		
		if (!empty($return['news'])) {
			$return['news']['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$return['news']['text']);
		}
		
		$return['links'] = Objects::db()->get_field('post_items', 'type = "link" and status = "broken"', 'count(*)');
		
		return $return;
	}	
}
