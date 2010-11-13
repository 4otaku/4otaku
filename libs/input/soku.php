<?

include_once('engine'.SL.'engine.php');
class input__soku
{
	function registration() { 
		global $post; global $db;
/*		
		if ($post['nickname']) {
			if ($post['character'] == 'Yakumo Yukari' && $post['second_character'] == 'Cirno' && $post['email'] && preg_match('/^[a-z]+$/',$post['nickname'])) {
				die;
			}
			if (!$db->sql('select id from soku where nickname="'.$post['nickname'].'"',2)) {
				$db->insert('soku',array($post['nickname'],$post['character'],$post['second_character'],$post['email']));
				$add_res['text'] = $post['nickname'] . ', вы успешно зарегистрировались.';
			} else {
				$add_res = array('error' => true, 'text' => 'Этот ник уже кем-то занят.');
			}
		} else {
			$add_res = array('error' => true, 'text' => 'Не все обязательные поля заполнены.');
		}
*/
	}
	
	function replay_add() {
		global $post; global $db;
		if ($post['file']) {
			$file = current($post['file']);
			$db->insert('misc',array('soku_replay',$file['folder'],$file['filename'],$post['nick1'],$post['nick2'],$post['stage']));
		}
	}
}
