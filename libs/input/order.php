<?

class input__order extends input__common
{
	function add() { 
		global $post; global $check; global $def; global $cookie; global $sets;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		if ($post['mail'] && $post['subject']) {
			if ($check->email($post['mail'],false) && $post['mail'] != $def['user']['mail']) {
				$cookie->inner_set('user.mail',$post['mail']);
				
				if (!trim($post['user'])) $post['user'] = $def['user']['author'];
				if ($post['user'] != $def['user']['author']) $cookie->inner_set('user.name',$post['user']); else unset($post['user']);
			
				$post['user'] = preg_replace('/#.*$/','',$post['user']);
				
				$category = obj::transform('meta')->category($post['category']);
				$text = obj::transform('text')->format($post['description']);				
				if ($post['subscribe']) $post['subscribe'] = 1;
				
				obj::db()->insert('orders',$insert_data = array($post['subject'],$post['user'],$post['mail'],$post['subscribe'],$text,undo_safety($post['description']),"",
							$category,0,0,obj::transform('text')->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
				obj::db()->insert('versions',array('order',$id = obj::db()->sql('select @@identity from orders',2),
												base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));								
				if ($post['subscribe']) $this->set_events($id,$post['mail']); else $this->set_events($id);
				$this->add_res('Заказ успешно добавлен. Страница заказа: <a href="/order/'.$id.'/">http://4otaku.ru/order/'.$id.'</a>');
			}
			else $this->add_res('Вы указали неправильный е-мейл.', true);
		}
		else $this->add_res('Не все обязательные поля заполнены.', true);
	}
	
	function edit_orders_username() {
		global $post; global $check;
		if ($check->num($post['id']) && $post['type'] == 'orders') {
			$check->rights();
			obj::db()->update('orders','username',$post['username'],$post['id']);
		}
	}
	
	function edit_orders_mail() {
		global $post; global $check;
		if (isset($post['subscribe'])) $post['subscribe'] = 1;
		if ($check->email($post['mail'],false) && $check->num($post['id']) && $post['type'] == 'orders') {
			$check->rights();
			obj::db()->update('orders',array('email','spam'),array($post['mail'],$post['subscribe']),$post['id']);
			if ($post['subscribe']) $this->set_events($post['id'],$post['mail']); else $this->set_events($post['id']);
		}
	}		
	
	function change_link() {
		global $post; global $check;
		if ($check->num($post['id'])) {
			obj::db()->update('orders','link',$post['link'],$post['id']);
			$data = obj::db()->sql('select email, spam from orders where id='.$post['id'],1);
			if ($data['spam']) {	
				if (substr($post['link'],0,1) == '/') $post['link'] = 'http://'.$_SERVER['HTTP_HOST'].$post['link'];
				$this->set_events($post['id'],$data['email']);
				$text = 'В вашем заказе на сайте 4отаку.ру, <a href="http://4otaku.ru/order/'.$id.'/">http://4otaku.ru/order/'.$id.'/</a> добавили ссылку на найденное:<br /><br />
				<a href="'.$post['link'].'">'.$post['link'].'</a>'.$this->unsubscribe($id);
				obj::db()->insert('misc',array('mail_notify',0,$data['email'],'',$text,$post['id']));				
			}
			else $this->set_events($post['id']);			
		}
	}		
}
