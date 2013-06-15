<?

class output__index extends engine
{
	public $allowed_url = array(
		array(1 => '|index|', 2 => 'end')
	);
	public $template = 'index';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal')
	);

	private $wiki_namespaces = array(
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
		500 => 'Тег',
		501 => 'Обсуждение_тега',
	);

	function get_data() {
		global $sets; global $def;

		$types = array('post', 'video');
		foreach ($types as $type) {
			$return['count'][$type] = array(
				'total' => obj::db()->sql('select count(id) from '.$type.' where area="main"',2),
				'unseen' => ($sets['visit'][$type] ? obj::db()->sql('select count(id) from '.$type.' where (area="main" and sortdate > '.($sets['visit'][$type]*1000).')',2) : ''),
				'latest' => obj::db()->sql('select id, '.'title, comment_count, text from '.$type.' where (area="main"'.($sets['show']['nsfw'] ?
					'' : ' and !locate("|nsfw|",category)').') order by sortdate desc limit 3')
			);
		}

		$art = Database::db('api')->set_counter()->sql('
			SELECT `id`,`md5` FROM `art` AS `a`
			JOIN `meta` as filter1 ON
				filter1.item_type = 1 AND filter1.id_item = id AND
				filter1.meta_type = 2 AND filter1.meta = 2
			JOIN `meta` as filter2 ON
				filter2.item_type = 1 AND filter2.id_item = id AND
				filter2.meta_type = 2 AND filter2.meta = 6
			GROUP BY `id_parent` ORDER BY `sortdate` desc LIMIT 1');
		$count = Database::db('api')->get_counter();

		$return['count']['art'] = array(
			'total' => $count,
			'latest' => $art[0],
			'unseen' => 0
		);

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
				'new' => obj::db()->sql('select count(*) from board where `type` = "thread" and sortdate > '.$sets['visit']['board']*1000,2),
				'updated' => obj::db()->sql('select count(*) from board where `type` = "thread" and sortdate < '.($sets['visit']['board']*1000).' and updated > '.$sets['visit']['board']*1000,2),
				'link' => _base64_encode(pack('i*',$sets['visit']['board']), true),
			);
		}
		$return['board']['all'] = obj::db()->sql('select count(*) from board where `type` = "thread"',2);

		$wiki = obj::db('wiki')->sql('select rc_title, rc_namespace from recentchanges where rc_type < 2 order by rc_id desc limit 1',1);
		if (array_key_exists($wiki['rc_namespace'],$this->wiki_namespaces)) {
			$return['wiki'] = $this->wiki_namespaces[$wiki['rc_namespace']].':'.$wiki['rc_title'];
		} else {
			$return['wiki'] = $wiki['rc_title'];
		}

		if ($return['news'] = obj::db()->sql('select id,title,text,image,extension,comment_count,sortdate from news where area="main" order by sortdate desc limit 1',1)) {
			$return['news']['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$return['news']['text']);
		} else {
			$return['news']['sortdate'] = 0;
		}

		Database::set_counter()
			->join('post_link_url', 'pu.id = plu.url_id')
			->join('post_link', 'pl.id = plu.link_id')
			->join('post', 'p.id = pl.post_id')
			->group('pu.id')->limit(1)
			->get_table('post_url', 'pu.id',
				'pu.status = ? and p.area is not null and p.area != ?',
				array(Cron_Post_Gouf::STATUS_BROKEN, 'deleted'));

		$return['links'] = Database::get_counter();

		return $return;
	}

	public function make_tip ($text) {
		$text = strip_tags($text,'<br>');
		$pos = mb_strpos($text,'<br');
		if ($pos > 20) {
			$return = mb_substr($text,0,$pos);
			return strlen($return) != strlen($text) ? $return.' ... ' : $return;
		} elseif ($pos) {
			$pos = mb_strpos($text,'<br',20);
			$return = mb_substr($text,0,$pos);
			return strlen($return) != strlen($text) ? $return.' ... ' : $return;
		}
		return $text;
	}
}
