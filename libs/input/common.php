<?

class input__common extends engine
{
	private static $messages = array(
		'same_target' => 'То что вы пытаетесь переместить уже там куда вы хотите это переместить.',
		'not_enough_tags' => 'Слишком мало тегов чтобы отправить арт на главную.',
		'uncheked_confirmation' => 'Не забывайте перед тем как утащить ставить галочку. Она здесь для защиты от случайных кликов.',
	);

	function edit_title() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && $check->lat(query::$post['type'])) {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			obj::db()->update(query::$post['type'],'title',query::$post['title'],query::$post['id']);
		}
	}

	function edit_text() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && $check->lat(query::$post['type'])) {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$text = obj::transform('text')->format(query::$post['text']);
			obj::db()->update(query::$post['type'],array('text','pretty_text'),array($text,undo_safety(query::$post['text'])),query::$post['id']);
		}
	}

	function edit_category() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && $check->lat(query::$post['type'])) {
			if (query::$post['type'] != $def['type'][2]) $area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area && $area != $def['area'][1]) $check->rights();
			$category = obj::transform('meta')->category(query::$post['category']);
			obj::db()->update(query::$post['type'],'category',$category,query::$post['id']);
		}
	}

	function edit_language() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && $check->lat(query::$post['type'])) {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$language = obj::transform('meta')->language(query::$post['language']);
			obj::db()->update(query::$post['type'],'language',$language,query::$post['id']);
		}
	}

	function edit_tag() {
		global $check; global $def;

		query::$post['tags'] = array_merge((array) query::$post['tags'], (array) query::$post['tag']);

		if ($check->num(query::$post['id']) && $check->lat(query::$post['type'])) {
			$data = obj::db()->sql('select area, tag from '.query::$post['type'].' where id='.query::$post['id'],1);
			if ($data['area'] != $def['area'][1]) {
				if (query::$post['type'] != $def['type'][2]) $check->rights();
				$area = query::$post['type'].'_'.$data['area'];
				obj::transform('meta')->erase_tags(array_unique(array_filter(explode('|',$data['tag']))),$area);
			}
			$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse_array(query::$post['tags']),$area);
			obj::db()->update(query::$post['type'],'tag',$tags,query::$post['id']);
		}
	}

	function edit_author() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && $check->lat(query::$post['type'])) {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$author = obj::transform('meta')->author(obj::transform('meta')->parse(query::$post['author'],$def['user']['author']));
			obj::db()->update(query::$post['type'],'author',$author,query::$post['id']);
		}
	}

	function transfer($post, $display_warnings = true) {
		if (empty($post)) $post = query::$post;
		global $add_res; global $check; global $def; global $sets;

		if (isset($post['sure'])) {
			$check->rights();
			if ($post['do'][0] == 'order') $post['do'][0] .= 's';
			if ($post['do'][0] != 'orders') {
				$data = obj::db()->sql('select area, tag from '.$post['do'][0].' where id='.$post['id'],1);
				$tags = array_unique(array_filter(explode('|',$data['tag'])));
				if ($data['area'] == $post['where']) {
					if ($display_warnings) {
						self::add_res(self::$messages['same_target'],true);
						return;
					} else {
						return 'same_target';
					}

				}
				if ($post['do'][0] == 'art' && count($tags) < 5 && $post['where'] == $def['area'][0]) {
					if ($display_warnings) {
						self::add_res(self::$messages['not_enough_tags'], true);
						return;
					} else {
						return 'not_enough_tags';
					}
				}
				if (in_array($data['area'],$def['area']) && $data['area'] != $def['area'][1])
					obj::transform('meta')->erase_tags($tags,$post['do'][0].'_'.$data['area']);
				if (in_array($post['where'],$def['area']) && $post['where'] != $def['area'][1])
					obj::transform('meta')->add_tags($tags,$post['do'][0].'_'.$post['where']);
			}
			if ($post['do'][0] != 'orders') obj::db()->update($post['do'][0],array('area','pretty_date','sortdate'),array($post['where'],obj::transform('text')->rudate(),ceil(microtime(true)*1000)),$post['id']);
			else obj::db()->update($post['do'][0],'area',$post['where'],$post['id']);
			obj::db()->insert('versions',array($post['do'][0],$post['id'],$post['where'],ceil(microtime(true)*1000),$sets['user']['name'],$_SERVER['REMOTE_ADDR']));
			obj::db()->sql('update search set lastupdate=0 where place="'.$post['do'][0].'" and item_id="'.$post['id'].'"',0);
			if (!in_array($post['where'], $def['area'])) {
				obj::db()->sql('update comment set area="deleted" where place="'.$post['do'][0].'" and post_id='.$post['id'],0);
			}
			$add_res['meta'] = $post['where'];
		} else {
			if ($display_warnings) {
				self::add_res(self::$messages['uncheked_confirmation'],true);
			} else {
				return 'uncheked_confirmation';
			}
		}
	}

//  Секция из Order, для рассылки почты

	function set_events($id,$mail = false) {

		obj::db()->sql('delete from misc where ((type="close_order" and data2="'.$id.'") or (type="mail_notify" and data5="'.$id.'" and data1 > 0))',0);
		obj::db()->insert('misc',array('close_order',(time()+86400*60),$id,'','',''));
		if ($mail) {
			$encrypt = encrypt($id.'extra string');
			if (preg_match('/@mail\.ru/', $mail)) {
				$text = 'Ваш заказ на сайте 4отаку.ру, http://4otaku.ru/order/'.$id.'/ до сих пор не выполнен.
				Прошло уже не менее месяца с последнего комментария к заказу, прогресса, или емейл-уведомления. Вы все еще заинтересованы в выполнении заказа?
				Если да, то пройдите пожалуйста по ссылке http://4otaku.ru/order/do/prolong/'.$encrypt.'/
				Если нет, то просто ничего не делайте, через 30 суток заказ закроется автоматически.'.$this->unsubscribe_mail_ru($id);
			} else {
				$text = 'Ваш заказ на сайте 4отаку.ру, <a href="http://4otaku.ru/order/'.$id.'/">http://4otaku.ru/order/'.$id.'/</a> до сих пор не выполнен.<br />
				Прошло уже не менее месяца с последнего комментария к заказу, прогресса, или емейл-уведомления. Вы все еще заинтересованы в выполнении заказа? <br />
				Если да, то пройдите пожалуйста по ссылке <a href="http://4otaku.ru/order/do/prolong/'.$encrypt.'/">http://4otaku.ru/order/do/prolong/'.$encrypt.'/</a><br />
				Если нет, то просто ничего не делайте, через 30 суток заказ закроется автоматически.'.$this->unsubscribe($id);
			}
			obj::db()->insert('misc',array('mail_notify',(time()+86400*30),$mail,'',$text,$id));
		}
	}

	function unsubscribe($id) {
		$encrypt = encrypt($id.'extra string');
		return '<br /><br />

				Если в заказе был указан ваш Е-мейл по ошибке, или же вы не желаете больше получать эти уведомления, вы можете отписаться от них пройдя по этой ссылке: <a href="http://4otaku.ru/order/do/unsubscribe/'.$encrypt.'/">http://4otaku.ru/order/do/unsubscribe/'.$encrypt.'/</a>';
	}

	function unsubscribe_mail_ru($id) {
		$encrypt = encrypt($id.'extra string');
		return '

				Если в заказе был указан ваш Е-мейл по ошибке, или же вы не желаете больше получать эти уведомления, вы можете отписаться от них пройдя по этой ссылке: http://4otaku.ru/order/do/unsubscribe/'.$encrypt.'/';
	}

// Секция кончилась
}
