<?

include_once 'libs'.SL.'input'.SL.'common.php';
class input__comment extends input__common 
{
	function add() { 
		global $post; global $db; global $check; global $url;
		global $cookie; global $transform_text; global $def;
		if (!$transform_text) $transform_text = new transform__text();
		if (!$cookie) $cookie = new dinamic__cookie();		

		if (!$post['name']) $post['name'] = $def['user']['name'];
		elseif ($post['name'] != $def['user']['name']) $cookie->inner_set('user.name',$post['name']);
		if (!$post['mail']) $post['mail'] = $def['user']['mail'];
		elseif ($post['mail'] != $def['user']['mail']) $cookie->inner_set('user.mail',$post['mail']);
		
		$comment = $transform_text->format($post['text']);
		
		if ($url[1] == 'order') $table = 'orders'; else $table = $url[1];
		$field = $table == 'news' ? 'url' : 'id';
		$item_id = in_array($url[2], $def['area']) ? $url[3] : $url[2];
		
		$area = $db->sql('select area from '.$table.' where '.$field.'="'.$item_id.'"',2);
		
		if (trim(strip_tags(str_replace('<img', 'img', $comment))) && $area) {

			if ($post['parent'] && !($rootparent = $db->sql('select rootparent from comment where id='.$post['parent'],2)))
				$rootparent = $post['parent'];

			$db->insert('comment',array($rootparent,$post['parent'],$table,$item_id,$post['name'],$post['mail'],
						$_SERVER['REMOTE_ADDR'],$comment,$post['text'],$date = $transform_text->rudate(true),
						$time = ceil(microtime(true)*1000),$area));

			if ($table == 'news') 
				$db->sql('update news set comment_count=comment_count+1, last_comment='.$time.' where url="'.$item_id.'"',0);
			elseif ($table == 'art' && substr($item_id,0,3) == 'cg_')
				$db->base_sql('sub','update w8m_art set comment_count=comment_count+1, last_comment='.$time.' where id='.substr($item_id,3),0);
			else
				$db->sql('update '.$table.' set comment_count=comment_count+1, last_comment='.$time.' where id='.$item_id,0);

			if ($table == 'orders') {
				$data = $db->sql('select email, spam from orders where id='.$url[2],1);
				if ($data['spam'] && $data['email'] != $post['mail']) {	
					$this->set_events($url[2],$data['email']);
					$text = 'В вашем заказе на сайте 4отаку.ру, <a href="http://4otaku.ru/order/'.$item_id.'/">http://4otaku.ru/order/'.$item_id.'/</a> '.$post['name'].' '.($post['name'] != $def['user']['name'] ? 'оставил' : 'оставлен').' новый комментарий. <a href="http://4otaku.ru/order/'.$item_id.'/comments/all#comment-'.$db->sql('select @@identity from comment',2).'">Читать</a>. '.$this->unsubscribe($item_id);
					$db->insert('misc',array('mail_notify',0,$data['email'],'',$text,$item_id));				
				}
			}
		}
	}
	
	function edit() {
		global $post; global $db; global $check; global $url; global $transform_text;
		if (!$transform_text) $transform_text = new transform__text();

		$check->rights();
		
		$comment = $transform_text->format($post['text']);
		if (str_replace('*','',$post['mail']))
			$db->update('comment',array('username','email','text','pretty_text'),array($post['author'],$post['mail'],$comment,$post['text']),$post['id']);
		else
			$db->update('comment',array('username','text','pretty_text'),array($post['author'],$comment,$post['text']),$post['id']);		
	}	
	
	function delete() {
		global $post; global $db; global $check; global $url;

		$check->rights(); 
		
		if (isset($post['sure'])) {
		
			$comment = $db->sql('select parent,rootparent from comment where id='.$post['id'],1);		
			
			$db->update('comment','area','deleted',$post['id']);
			$db->update('comment',array('parent','rootparent'),array($comment['parent'],$comment['rootparent']),$post['id'],'parent');

			if (!$comment['rootparent']) {
				$comments = $db->sql('select * from comment where rootparent='.$post['id'],'id');
				if (!empty($comments)) foreach ($comments as $id => $one) {
					$temp = $one; $i = 0;
					while($temp['rootparent'] && $i < 20) {
						$i++; 
						$rootparent = $temp['parent'];
						$temp = $comments[$temp['parent']];
					}
					$db->update('comment','rootparent',$rootparent,$id);
				}
			}
			
			if ($url[1] == 'news') 
				$db->sql('update news set comment_count=comment_count-1 where url="'.$url[2].'"',0);
			else
				$db->sql('update '.($url[1] == 'order' ? 'orders' : $url[1]).' set comment_count=comment_count-1 where id='.$url[2],0);			
		}
	}	
}
