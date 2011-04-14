<?

class Index_Output extends Output implements Plugins
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

	public function main () {
		if ($unseen = Globals::user('unseen')) {

			foreach ($unseen as & $unseen_item) {
				$unseen_item = Objects::db()->unix_to_date($unseen_item);
			}
		}

		$latest_fields = array('id', 'title', 'comments', 'text');

		$this->items['post'] = new Item_Index_Block (array(
			'total' => Objects::db()->get_count('post', 'area = "main"'),
			'unseen' => $unseen['post'] ? Objects::db()->get_count('post', 'area = "main" and date > ?', $unseen['post']) : 0,
			'latest' => Objects::db()->get_table('post', $latest_fields, 'area = "main" order by date desc limit 3'),
		));

		$this->items['video'] = new Item_Index_Block (array(
			'total' => Objects::db()->get_count('video', 'area = "main"'),
			'unseen' => $unseen['video'] ? Objects::db()->get_count('video', 'area = "main" and date > ?', $unseen['video']) : 0,
			'latest' => Objects::db()->get_table('video', $latest_fields, 'area = "main" order by date desc limit 3'),
		));

		$latest_fields[] = 'username';

		$this->items['order'] = new Item_Index_Block (array(
			'total' => Objects::db()->get_count('order', 'area != "deleted"'),
			'unseen' => Objects::db()->get_count('order', 'area = "open"'),
			'latest' => Objects::db()->get_table('order', $latest_fields, 'area = "open"'),
		));

		if (is_array($this->items['order']['latest'])) {
//			shuffle($this->items['order']['latest']);
//			array_splice($this->items['order']['latest'], 2);
		}

		$latest_fields = array('id', 'thumbnail');

		$this->items['art'] = new Item_Index_Block (array(
			'total' => Objects::db()->get_count('art', 'area = "main" or area = "sprites"'),
			'unseen' => $unseen['art'] ? Objects::db()->get_count('art', 'area = "main" or area = "sprites" and date > ?', $unseen['art']) : 0,
			'latest' => Objects::db()->get_row('art', $latest_fields, 'area = "main" or area = "sprites" order by date desc'),
		));
/*
		if (!empty($unseen['board'])) {
			$this->items['board'] = array(
				'new' => Objects::db()->get_field('board', count(*) from board where `type` = "2" and sortdate > '.$sets['visit']['board']*1000,2),
				'updated' => Objects::db()->get_field('select count(*) from board where `type` = "2" and sortdate < '.($sets['visit']['board']*1000).' and updated > '.$sets['visit']['board']*1000,2),
				'link' => _base64_encode(pack('i*',$sets['visit']['board']), true),
			);
		}

		$return['board']['all'] = Objects::db()->sql('select count(*) from board where `type` = "2"',2);
*/

		$wiki = Objects::db('wiki')->get_row('recentchanges', 'rc_title, rc_namespace', 'rc_type < 2 order by rc_id desc');

		if (!empty($wiki)) {
			if (array_key_exists($wiki['rc_namespace'], $this->wiki_namespaces)) {
				$this->items['wiki'] = $this->wiki_namespaces[$wiki['rc_namespace']].':'.$wiki['rc_title'];
			} else {
				$this->items['wiki'] = $wiki['rc_title'];
			}
		}

		$this->items['news'] = Objects::db()->get_row('news', array('url', 'title', 'text', 'image', 'comments', 'date'), '`area` = "main" order by `date` desc');

		$this->items['links'] = Objects::db()->get_count('post_items', 'type = "link" and status = "broken"');
	}
}
