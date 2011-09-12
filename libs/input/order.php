<?

class input__order extends input__common
{
	function add() { 
		global $check; global $def; global $cookie; global $sets;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		if (query::$post['mail'] && query::$post['subject']) {
			if ($check->email(query::$post['mail'],false) && query::$post['mail'] != $def['user']['mail']) {
				$cookie->inner_set('user.mail',query::$post['mail']);
				
				if (!trim(query::$post['user'])) query::$post['user'] = $def['user']['author'];
				if (query::$post['user'] != $def['user']['author']) $cookie->inner_set('user.name',query::$post['user']); else unset(query::$post['user']);
			
				query::$post['user'] = preg_replace('/#.*$/','',query::$post['user']);
				
				$category = obj::transform('meta')->category(query::$post['category']);
				$text = obj::transform('text')->format(trim(query::$post['description']));
				if (query::$post['subscribe']) query::$post['subscribe'] = 1;
				
				obj::db()->insert('orders',$insert_data = array(query::$post['subject'],query::$post['user'],query::$post['mail'],query::$post['subscribe'],$text,undo_safety(query::$post['description']),"",
							$category,0,0,obj::transform('text')->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
				obj::db()->insert('versions',array('order',$id = obj::db()->sql('select @@identity from orders',2),
												base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));								
				if (query::$post['subscribe']) $this->set_events($id,query::$post['mail']); else $this->set_events($id);
				$this->add_res('Заказ успешно добавлен. Страница заказа: <a href="/order/'.$id.'/">http://4otaku.ru/order/'.$id.'</a>');
			}
			else $this->add_res('Вы указали неправильный е-мейл.', true);
		}
		else $this->add_res('Не все обязательные поля заполнены.', true);
	}
	
	function edit_orders_username() {
		global $check;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'orders') {
			$check->rights();
			obj::db()->update('orders','username',query::$post['username'],query::$post['id']);
		}
	}
	
	function edit_orders_mail() {
		global $check;
		if (isset(query::$post['subscribe'])) query::$post['subscribe'] = 1;
		if ($check->email(query::$post['mail'],false) && $check->num(query::$post['id']) && query::$post['type'] == 'orders') {
			$check->rights();
			obj::db()->update('orders',array('email','spam'),array(query::$post['mail'],query::$post['subscribe']),query::$post['id']);
			if (query::$post['subscribe']) $this->set_events(query::$post['id'],query::$post['mail']); else $this->set_events(query::$post['id']);
		}
	}		
	
	function change_link() {
		global $check;
		if ($check->num(query::$post['id'])) {
			obj::db()->update('orders','link',query::$post['link'],query::$post['id']);
			$data = obj::db()->sql('select email, spam from orders where id='.query::$post['id'],1);
			if ($data['spam']) {	
				if (substr(query::$post['link'],0,1) == '/') query::$post['link'] = 'http://'.$_SERVER['HTTP_HOST'].query::$post['link'];
				$this->set_events(query::$post['id'],$data['email']);
				$text = 'В вашем заказе на сайте 4отаку.ру, <a href="http://4otaku.ru/order/'.$id.'/">http://4otaku.ru/order/'.$id.'/</a> добавили ссылку на найденное:<br /><br />
				<a href="'.query::$post['link'].'">'.query::$post['link'].'</a>'.$this->unsubscribe($id);
				obj::db()->insert('misc',array('mail_notify',0,$data['email'],'',$text,query::$post['id']));				
			}
			else $this->set_events(query::$post['id']);			
		}
	}		
}
