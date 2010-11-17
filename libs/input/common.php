<?

class input__common 
{
	function edit_title() {
		global $post; global $db; global $check; global $def;
		if ($check->num($post['id']) && $check->lat($post['type'])) {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();			
			$db->update($post['type'],'title',$post['title'],$post['id']);
		}
	}
	
	function edit_text() {
		global $post; global $db; global $check; global $def; global $transform_text;
		if (!$transform_text) $transform_text = new transform__text();
		if ($check->num($post['id']) && $check->lat($post['type'])) {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$text = $transform_text->format($post['text']);
			$db->update($post['type'],array('text','pretty_text'),array($text,undo_safety($post['text'])),$post['id']);
		}
	}
	
	function edit_category() {
		global $post; global $db; global $check; global $def; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if ($check->num($post['id']) && $check->lat($post['type'])) {
			if ($post['type'] != $def['type'][2]) $area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area && $area != $def['area'][1]) $check->rights();
			$category = $transform_meta->category($post['category']);
			$db->update($post['type'],'category',$category,$post['id']);
		}
	}	
	
	function edit_language() {
		global $post; global $db; global $check; global $def; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if ($check->num($post['id']) && $check->lat($post['type'])) {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$language = $transform_meta->language($post['language']);
			$db->update($post['type'],'language',$language,$post['id']);
		}		
	}
	
	function edit_tag() {
		global $post; global $db; global $check; global $def; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if ($check->num($post['id']) && $check->lat($post['type'])) {
			$data = $db->sql('select area, tag from '.$post['type'].' where id='.$post['id'],1);
			if ($data['area'] != $def['area'][1]) {
				if ($post['type'] != $def['type'][2]) $check->rights();
				$area = $post['type'].'_'.$data['area'];
				$transform_meta->erase_tags(array_unique(array_filter(explode('|',$data['tag']))),$area);
			}
			$tags = $transform_meta->add_tags($transform_meta->parse($post['tags']),$area);
			$db->update($post['type'],'tag',$tags,$post['id']);
		}
	}			
	
	function edit_author() {
		global $post; global $db; global $check; global $def; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if ($check->num($post['id']) && $check->lat($post['type'])) {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$author = $transform_meta->author($transform_meta->parse($post['author'],$def['user']['author']));
			$db->update($post['type'],'author',$author,$post['id']);
		}
	}
	
	function transfer($post) {
		if (empty($post)) global $post;
		global $db; global $check; global $def; global $sets; 
		global $transform_meta; global $transform_text; global $add_res;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if (!$transform_text) $transform_text = new transform__text();
		
		if (isset($post['sure'])) {
			$check->rights();
			if ($post['do'][0] == 'order') $post['do'][0] .= 's';
			if ($post['do'][0] != 'orders') {
				$data = $db->sql('select area, tag from '.$post['do'][0].' where id='.$post['id'],1);
				$tags = array_unique(array_filter(explode('|',$data['tag'])));
				if ($data['area'] == $post['where']) {
					$add_res = array('error' => true, 'text' => 'То что вы пытаетесь переместить уже там куда вы хотите это переместить.');
					return false;
				}	
				if ($post['do'][0] == 'art' && count($tags) < 5 && $post['where'] == $def['area'][0]) {
					$add_res = array('error' => true, 'text' => 'Слишком мало тегов чтобы отправить арт на главную.');
					return false;
				}
				if ($data['area'] == $def['area'][0] || $data['area'] == $def['area'][2])
					$transform_meta->erase_tags($tags,$post['do'][0].'_'.$data['area']);
				if ($post['where'] == $def['area'][0] || $post['where'] == $def['area'][2])
					$transform_meta->add_tags($tags,$post['do'][0].'_'.$post['where']);
			}
			if ($post['do'][0] != 'orders') $db->update($post['do'][0],array('area','pretty_date','sortdate'),array($post['where'],$transform_text->rudate(),ceil(microtime(true)*1000)),$post['id']);
			else $db->update($post['do'][0],'area',$post['where'],$post['id']);
			$db->insert('versions',array($post['do'][0],$post['id'],$post['where'],ceil(microtime(true)*1000),$sets['user']['name'],$_SERVER['REMOTE_ADDR']));
			$db->sql('update search set lastupdate=0 where place="'.$post['do'][0].'" and item_id="'.$post['id'].'"',0);
			if (!in_array($post['where'], $def['area'])) {
				$db->sql('update comment set area="deleted" where place="'.$post['do'][0].'" and post_id='.$post['id'],0);
			}			
			$add_res['meta'] = $post['where'];
		}
		else $add_res = array('error' => true, 'text' => 'Не забывайте перед тем как утащить ставить галочку. Она здесь для защиты от случайных кликов.');
	}
	
//  Секция из Order, для рассылки почты	
	
	function set_events($id,$mail = false) {
		global $db;
		$db->sql('delete from misc where ((type="close_order" and data2="'.$id.'") or (type="mail_notify" and data5="'.$id.'" and data1 > 0))',0);
		$db->insert('misc',array('close_order',(time()+86400*60),$id,'','',''));
		if ($mail) {
			$encrypt = encrypt($id.'extra string');
			$text = 'Ваш заказ на сайте 4отаку.ру, <a href="http://4otaku.ru/order/'.$id.'/">http://4otaku.ru/order/'.$id.'/</a> до сих пор не выполнен.<br />
			Прошло уже не менее месяца с последнего комментария к заказу, прогресса, или емейл-уведомления. Вы все еще заинтересованы в выполнении заказа? <br />
			Если да, то пройдите пожалуйста по ссылке <a href="http://4otaku.ru/order/do/prolong/'.$encrypt.'/">http://4otaku.ru/order/do/prolong/'.$encrypt.'/</a><br />
			Если нет, то просто ничего не делайте, через 30 суток заказ закроется автоматически.'.$this->unsubscribe($id);
			$db->insert('misc',array('mail_notify',(time()+86400*30),$mail,'',$text,$id));
		}
	}
	
	function unsubscribe($id) {
		$encrypt = encrypt($id.'extra string');
		return '<br /><br />
		
				Если в заказе был указан ваш Е-мейл по ошибке, или же вы не желаете больше получать эти уведомления, вы можете отписаться от них пройдя по этой ссылке: <a href="http://4otaku.ru/order/do/unsubscribe/'.$encrypt.'/">http://4otaku.ru/order/do/unsubscribe/'.$encrypt.'/</a>';
	}		
}
