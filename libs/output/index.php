<? 
include_once('engine'.SL.'engine.php');
class output__index extends engine
{
	public $allowed_url = array(
		array(1 => '|index|', 2 => 'end')
	);
	public $template = 'index';
	public $side_modules = array(
		'head' => array('title')
	);
	
	function get_data() {
		global $db; global $sets; global $def;
		
		foreach ($def['type'] as $type) 
			$return['count'][$type] = array(
				'total' => $db->sql('select count(id) from '.$type.' where area="main"',2),
				'unseen' => ($sets['visit'][$type] ? $db->sql('select count(id) from '.$type.' where (area="main" and sortdate > '.($sets['visit'][$type]*1000).')',2) : ''),
				'latest' => $db->sql('select id, '.($type == 'art' ? 'thumb, extension' : 'title, comment_count, text').' from '.$type.' where (area="main"'.($sets['show']['nsfw'] ? 
					($sets['show']['furry'] ? '' : ' and !(locate("|nsfw|",category) and locate("|furry|",tag))') .
					($sets['show']['yaoi'] ? '' : ' and !(locate("|nsfw|",category) and locate("|yaoi|",tag))') .
					($sets['show']['guro'] ? '' : ' and !(locate("|nsfw|",category) and locate("|guro|",tag))')
					: ' and !locate("|nsfw|",category)').') order by sortdate desc limit '.($type == 'art' ? '1' : '3'))
			);
			
		$return['count']['order'] = array(
			'total' => $db->sql('select count(id) from orders where area!="deleted"',2),
			'open' => $db->sql('select count(id) from orders where area="workshop"',2),			
			'latest' => $db->sql('select id,username,title,text,comment_count from orders where area="workshop"')
		);
		
		if (is_array($return['count']['order']['latest'])) {
			shuffle($return['count']['order']['latest']);
			$return['count']['order']['latest'] = array_slice($return['count']['order']['latest'], 0, 2);
		}
		
		$return['news'] = $db->sql('select url,title,text,image,comment_count,sortdate from news where area="main" order by sortdate desc limit 1',1);
		$return['news']['text'] = preg_replace('/\{\{\{(.*)\}\}\}/ueU','get_include_contents("templates$1")',$return['news']['text']);
		$return['links'] = $db->sql('select count(id) from gouf_links where status = "error"',2);
		
		return $return;
	}
}
