<?

include_once('engine'.SL.'engine.php');
class input__soku
{
	function registration() { 
		global $post; global $db;
		if ($post['nickname']) {
			if (!$db->sql('select id from soku where nickname="'.$post['nickname'].'"',2)) {
				$db->insert('soku',array($post['nickname'],$post['character'],$post['second_character'],$post['email']));
				$add_res['text'] = $post['nickname'] . ', вы успешно зарегистрировались.';
			} else {
				$add_res = array('error' => true, 'text' => 'Этот ник уже кем-то занят.');
			}
		} else {
			$add_res = array('error' => true, 'text' => 'Не все обязательные поля заполнены.');
		}
	}
}
