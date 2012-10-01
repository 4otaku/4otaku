<?

class input__comment extends input__common
{
	function add() {
		global $check; global $url; global $cookie; global $def;
		if (!$cookie) $cookie = new dynamic__cookie();

		if (
			obj::db()->sql('select id from comment where id='.query::$post['parent'].' and area="deleted"',2)
		) {
			return;
		}

		if (!query::$post['name']) {
			query::$post['name'] = $def['user']['name'];
		} else {
			$cookie->inner_set('user.name', query::$post['name']);
		}
		if (!query::$post['mail']) {
			query::$post['mail'] = $def['user']['mail'];
		} else {
			$cookie->inner_set('user.mail',query::$post['mail']);
		}

		query::$post['name'] = preg_replace('/#.*$/','',query::$post['name']);

		$comment = obj::transform('text')->format(query::$post['text']);

		if ($url[1] == 'order') $table = 'orders'; else $table = $url[1];
		$item_id = in_array($url[2], $def['area']) ? $url[3] : $url[2];

		if (substr($item_id,0,3) == 'cg_') {
			$area = $def['area'][0];
		} else {
			$area = obj::db()->sql('select area from '.$table.' where id="'.$item_id.'"',2);
		}

		if (trim(strip_tags(str_replace('<img', 'img', $comment))) && $area) {

			if (query::$post['parent'] && !($rootparent = obj::db()->sql('select rootparent from comment where id='.query::$post['parent'],2)))
				$rootparent = query::$post['parent'];

			obj::db()->insert('comment',array($rootparent,query::$post['parent'],$table,$item_id,query::$post['name'],query::$post['mail'],
						$_SERVER['REMOTE_ADDR'],query::$cookie,$comment,query::$post['text'],'',$date = obj::transform('text')->rudate(true),
						$time = ceil(microtime(true)*1000),$area));

			obj::db()->sql('update '.$table.' set comment_count=comment_count+1, last_comment='.$time.' where id='.$item_id,0);

			$mail = $this->check_simple_subscriptions($table,$item_id);
			$mail = array_merge($mail, $this->check_complex_subscriptions($table,$item_id));
			if (array_key_exists(query::$post['mail'],$mail)) {
				unset ($mail[array_search(query::$post['mail'],$mail)]);
			}
			if (!empty($mail)) {
				$this->batch_send_mail($mail,$this->subscription_notify_text($table,$item_id,query::$post['name'].': '.$comment));
			}

			if ($table == 'orders') {
				$data = obj::db()->sql('select email, spam from orders where id='.$item_id,1);
				if ($data['spam'] && $data['email'] != query::$post['mail']) {
					$this->set_events($item_id,$data['email']);
					$text = 'В вашем заказе на сайте 4отаку.ру, <a href="http://4otaku.ru/order/'.$item_id.'/">http://4otaku.ru/order/'.$item_id.'/</a> '.query::$post['name'].' '.(query::$post['name'] != $def['user']['name'] ? 'оставил' : 'оставлен').' новый комментарий. <a href="http://4otaku.ru/order/'.$item_id.'/comments/all#comment-'.obj::db()->sql('select @@identity from comment',2).'">Читать</a>. '.$this->unsubscribe($item_id);
					obj::db()->insert('misc',array('mail_notify',0,$data['email'],'',$text,$item_id));
				} else {
					$this->set_events($item_id);
				}
			}
		}
	}

	function edit() {
		global $check;

		$rights = $check->rights(true);

		if ($rights) {
			$this->admin_edit();
			return;
		}

		$cookie = obj::db()->sql('select cookie from comment where id = '.((int) query::$post['id']),2);
		if ($cookie == query::$cookie) {
			$this->user_edit();
		}
	}

	protected function admin_edit () {
		$comment = obj::transform('text')->format(query::$post['text']);
		if (str_replace('*','',query::$post['mail'])) {
			obj::db()->update('comment',array('username','email','text','pretty_text'),array(query::$post['author'],query::$post['mail'],$comment,query::$post['text']),query::$post['id']);
		} else {
			obj::db()->update('comment',array('username','text','pretty_text'),array(query::$post['author'],$comment,query::$post['text']),query::$post['id']);
		}
	}

	protected function user_edit () {
		$comment = obj::transform('text')->format(query::$post['text']);
		obj::db()->update(
			'comment',
			array('text','pretty_text','edit_date'),
			array($comment,query::$post['text'],obj::transform('text')->rudate(true)),
		query::$post['id']);
	}

	function delete() {
		global $check; global $url;

		$check->rights();

		if (isset(query::$post['sure'])) {

			$comment = obj::db()->sql('select parent,rootparent from comment where id='.query::$post['id'],1);

			obj::db()->update('comment','area','deleted',query::$post['id']);
			obj::db()->update('comment',array('parent','rootparent'),array($comment['parent'],$comment['rootparent']),query::$post['id'],'parent');

			if (!$comment['rootparent']) {
				$comments = obj::db()->sql('select * from comment where rootparent='.query::$post['id'],'id');
				if (!empty($comments)) foreach ($comments as $id => $one) {
					$temp = $one; $i = 0;
					while($temp['rootparent'] && $i < 20) {
						$i++;
						$rootparent = $temp['parent'];
						$temp = $comments[$temp['parent']];
					}
					obj::db()->update('comment','rootparent',$rootparent,$id);
				}
			}

			obj::db()->sql('update '.($url[1] == 'order' ? 'orders' : $url[1]).' set comment_count=comment_count-1 where id='.$url[2],0);
		}
	}

	function subscription() {
		global $check; global $url;
		return;

		if (!$check->email(query::$post['email'],false)) {
			self::add_res('Вы указали неправильный Е-мейл.',true);
			return;
		}

		$check = self::check_subscription_right(query::$cookie,query::$post['email']);

		if (!empty(query::$post['rule_type'])) {
			$area = query::$post['area'];
			$rule = rtrim(query::$post['rule_type'].'|'.query::$post['rule'],'|');
			$id = null;
		} else {
			$area = $url[1];
			$rule = null;
			$id = $url[2];
		}

		if ($check === 'blocked') {
			self::add_res('Владелец этого Е-мейла отказался от подписок на комментарии.',true);
		} elseif ($check) {
			self::subscribe_comments(query::$post['email'],$area,$rule,$id);
		} else {
			self::send_confirmation_mail(query::$post['email'],$area,$rule,$id);
		}
	}

	function check_simple_subscriptions($table, $id) {
		$emails = obj::db()->sql(
			'select id, email from subscription where
			area = "'.$table.'" and
			item_id = "'.$id.'"'
		,'id');

		return empty($emails) ? array() : $emails;
	}

	function check_complex_subscriptions($table, $id) {
		$emails = array();

		$all = obj::db()->sql(
			'select id, email from subscription where
			area = "'.$table.'" and
			rule = "all"',
		'id');

		if (is_array($all)) {
			$emails = $all;
		}

		if ($table == 'video' || $table == 'art' || $table == 'post') {
			$data = obj::db()->sql('select * from '.$table.' where id = '.$id,1);

			$check['category'] = array_filter(explode('|', $data['category']));
			$check['language'] = array_filter(explode('|', $data['language']));
			$check['author'] = array_filter(explode('|', $data['author']));

			$condition = '';
			foreach ($check as $type => $aliases) {
				foreach ($aliases as $alias) {
					$condition .= ' or rule="'.$type.'|'.$alias.'"';
				}
			}
			$condition = substr($condition,4);

			if (!empty($condition)) {
				$custom = obj::db()->sql(
					'select id, email from subscription where
					area = "'.$table.'" and '.$condition,
				'id');
			}

			if (is_array($custom)) {
				$emails = array_merge($custom, $emails);
			}
		}

		return $emails;
	}

	function subscription_notify_text($table, $id, $text) {
		$table = $table == 'orders' ? 'order' : $table;

		$url = 'http://'.def::site('domain').'/'.$table.'/'.$id.'/';
		$text =
			'<br /><br />'."\n\n".
			'По адресу <a href="'.$url.'">'.$url.'</a>'.
			', по которому вы подписаны на комментарии оставлен новый комментарий: '.
			'<br /><br />'."\n\n".
			'-------------------------'.
			'<br /><br />'."\n\n".$text.'<br /><br />'."\n\n".
			'-------------------------';

		return $text;
	}

	static function check_subscription_right($cookie, $email) {
		$check = obj::db()->sql(
			'select id from subscription where
				rule = "blacklist" and
				email="'.$email.'"
			limit 1', 2);
		if ($check) {
			return 'blocked';
		}

		$check = obj::db()->sql(
			'select id from subscription where
				cookie="'.$cookie.'" and
				email="'.$email.'"
			limit 1', 2);
		return (bool) $check;
	}

	static function subscribe_comments($email, $area, $rule, $id) {
		$values = array(query::$cookie,$email,$area,$rule,$id);
		obj::db()->insert('subscription',$values);
		self::add_res('Вы успешно подписались на рассылку комментариев.',false,true);
	}

	static function add_to_black_list($email) {
		obj::db()->sql('delete from subscription where email="'.$email.'"',0);
		$values = array(query::$cookie,$email,'all','blacklist',null);
		obj::db()->insert('subscription',$values);
		$message = 'Вы отписались от всех рассылок комментариев, и исключили возможность повторной подписки.';
		self::add_res($message,false,true);
	}

	static function send_confirmation_mail($email, $area, $rule, $id) {
		$code = encrypt($email,true);

		if (!empty($rule)) {
			$second = $rule == 'all' ? '' : str_replace('|','/',$rule).'/';
		} else {
			$second = $id.'/';
		}

		$text =
			'Вы захотели подписаться на сайте 4отаку.ру на комментарии. '.
			'Подтвердите пожалуйста, что этот Е-мейл принадлежит вам, пройдя по ссылке '.
			'http://'.def::site('domain').'/confirm/'.$code.'/'.$area.'/'.$second.'. <br /><br />'."\n\n".
			'Если вы получили это письмо по ошибке, просто проигнорируйте его. <br />'."\n".
			'Если вы больше не хотите получать писем о подписке на комментарии 4отаку.ру, '.
			'пройдите по этой ссылке: '.
			'http://'.def::site('domain').'/stop_emails/'.$code.'/'.$area.'/'.$second.'.';
		obj::db()->insert('misc',array('mail_notify',0,$email,'',$text,''));
		self::add_res('Вам выслано письмо для подтверждения прав пользования Е-мейлом.');
	}

	function batch_send_mail($emails, $text) {
		global $check;

		$emails = array_unique($emails);

		if (!empty($emails) && is_array($emails)) {
			foreach ($emails as $email) {
				if ($check->email($email,false)) {
					$code = encrypt($email,true);
					$send_text =
						$text.' <br /><br />'."\n\n".
						'Если вы больше не хотите получать писем о подписке на комментарии 4отаку.ру, '.
						'пройдите по этой ссылке: '.
						'http://'.def::site('domain').'/stop_emails/'.$code.'/';
					obj::db()->insert('misc',array('mail_notify',0,$email,'',$send_text,''));
				}
			}
		}
	}
}
