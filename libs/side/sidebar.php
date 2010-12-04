<?
include_once('engine'.SL.'engine.php');
class side__sidebar extends engine
{
	function __construct() {
		global $url; global $searchbutton;
		$known = array('msie', 'firefox');
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
		if (preg_match_all($pattern, $agent, $matches))
			$searchbutton[end($matches['browser'])] = end($matches['version']);
	}		
	
	function comments() {
		global $db; global $sets; global $url; global $transform_text; 
		
		if (!$transform_text) $transform_text = new transform__text();
		if ($url[1] == "order") $area = "orders";
		else if ($url[1] == "search")
		{
			if ($url[2] == "a") $area = 'art'; 
			else if ($url[2] == "p") $area = 'post'; 
			else if ($url[2] == "v") $area = 'video'; 
		}
		else $area = $url[1];
		if (!($return = $db->sql('select * from comment where (place="'.$area.'" and area != "deleted") order by sortdate desc limit '.$sets['pp']['latest_comments']*5,'sortdate')))
			$return = $db->sql('select * from comment where area != "deleted" order by sortdate desc limit '.$sets['pp']['latest_comments']*5,'sortdate');
		if (is_array($return)) {
			$used = array();
			foreach ($return as $key => $one) {
				if (in_array($one['place'].'-'.$one['post_id'],$used)) unset ($return[$key]);
				$used[] = $one['place'].'-'.$one['post_id'];
			}
			krsort($return);
			$return = array_slice($return,0,$sets['pp']['latest_comments'],true);
			foreach ($return as &$comment) {
				if ($comment['place'] != 'art') $comment['title'] = $db->sql('select title from '.$comment['place'].' where ('.($comment['place']== 'news' ? 'url' : 'id').'="'.$comment['post_id'].'") limit 1',2);
				else {
					if (substr($comment['post_id'],0,3) == 'cg_') $comment['title'] = 'CG №'.substr($comment['post_id'],3);
					else $comment['title'] = 'Изображение №'.$comment['post_id'];
				}
				$comment['text'] = nl2br(strip_tags($comment['text'],'<br />'));			
				if (mb_strlen($comment['text']) > 100) $points = '...'; else $points = '';
				$comment['text'] = str_replace(array('<br /><br /><br />','<br /><br />'),array('<br />','<br />'),mb_substr($comment['text'],0,100)).$points;
				$comment['text'] = $transform_text->cut_long_words($comment['text']);
				$comment['href'] =  '/'.($comment['place'] == "orders" ? "order" : $comment['place']).'/'.$comment['post_id'].'/';
				$comment['username'] = mb_substr($comment['username'],0,30);
			}			
			return $return;
		}
	}	
	
	function update() {
		global $db;
		$update = $db->sql('select * from misc where type="latest_update" limit 1',1);
		$return['text'] = undo_quotes(strip_tags($update['data4'],'<br>'));
		if (mb_strlen($return['text']) > 100) $points = '...'; else $points = '';
		$return['text'] = str_replace(array('<br /><br /><br />','<br /><br />'),array('<br />','<br />'),redo_quotes(mb_substr($return['text'],0,100))).$points;
		$return['author'] = mb_substr($update['data3'],0,20);
		$return['post_id'] = $update['data1'];$return['post_title'] = $update['data2'];
		return $return;
	}

	function orders() {
		global $db; global $sets; 
		if ($return = $db->sql('select id, username, title, text, comment_count from orders where area="workshop"')) {
			shuffle($return);
			return array_slice($return, 0, $sets['pp']['random_orders']);
		}
	}

	function tags() {
		global $sets; global $def; global $url;
		
		if ($url['area'] != $def['area'][0] && $url['area'] != $def['area'][2]) $area = $url[1].'_'.$def['area'][0];
		else $area = $url[1].'_'.$url['area'];
		
		$words = array(
			$def['type'][0] => array('запись','записи','записей'),
			$def['type'][1] => array('видео','видео','видео'),
			$def['type'][2] => array('арт','арта','артов')						
		);

		return $this->tag_cloud(22,8,$area,$words[$url[1]],$sets['pp']['tags']);
	}
	
	function art_tags() {
		global $data; global $db; global $check;

		if (is_array($data['main']['art']['thumbs'])) {
			foreach ($data['main']['art']['thumbs'] as $art)
				if (is_array($art['meta']['tag'])) 
					foreach ($art['meta']['tag'] as $alias => $tag)
						if ($tags[$alias]) $tags[$alias]['count']++;
						else $tags[$alias] = array('name' => $tag['name'], 'color' => $tag['color'], 'count' => 1);
		}
		elseif (is_array($data['main']['art'][0]['meta']['tag'])) {
			foreach ($data['main']['art'][0]['meta']['tag'] as $alias => $tag)
				if ($tags[$alias]) $tags[$alias]['count']++;
				else $tags[$alias] = array('name' => $tag['name'], 'color' => $tag['color'], 'count' => 1);
		}
		unset($tags['prostavte_tegi'],$tags['tagme'],$tags['deletion_request']);
		
		if (!empty($tags)) {
			$where = 'where alias="'.implode('" or alias="',array_keys($tags)).'"';
			$global = $db->sql('select alias, art_main from tag '.$where,'alias');
			
			foreach ($global as $alias => $global_count) 
				$return[$tags[$alias]['count']*$global_count.'.'.rand(0,10000)] = array('alias' => $alias, 'num' => $global_count) + $tags[$alias];
				
			krsort($return);
			$return = array_slice($return,0,25);
			shuffle($return);
			
			return $return;
		}
	} 

	function admin_functions() {
		return true;
	}
	
	function quicklinks() {
		return true;
	}	
	function masstag() {
		global $db;
		return $db->sql('select alias, name from category where locate("|art|",area) order by id','alias');
	}	
}
