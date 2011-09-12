<?

class input__soku
{
	function registration() { 		
		
		if (query::$post['nickname']) {
			if (query::$post['character'] == 'Yakumo Yukari' && query::$post['second_character'] == 'Cirno' && query::$post['email'] && preg_match('/^[a-z]+$/',query::$post['nickname'])) {
				die;
			}
			if (!obj::db()->sql('select id from soku where nickname="'.query::$post['nickname'].'"',2)) {
				obj::db()->insert('soku',array(query::$post['nickname'],query::$post['character'],query::$post['second_character'],query::$post['email']));
				$this->add_res(query::$post['nickname'] . ', вы успешно зарегистрировались.');
			} else {
				$this->add_res('Этот ник уже кем-то занят.', true);
			}
		} else {
			$this->add_res('Не все обязательные поля заполнены.', true);
		}

	}
	
	function replay_add() {
		
		if (query::$post['file']) {
			$file = current(query::$post['file']);
			obj::db()->insert('_atai_replays',array(
				$file['folder'].'/'.$file['filename'],
				query::$post['nickname1'],
				query::$post['character1'],
				query::$post['nickname2'],
				query::$post['character2'],
				query::$post['stage']
			));
		}
	}
}
