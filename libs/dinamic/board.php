<? 

class dinamic__board extends engine
{
	function delete() {
		global $get;
		
		$data = obj::db()->sql('select type, cookie from board where id='.$get['id'],1);
		
		if ($data['cookie'] != $_COOKIE['settings']) {
			return false;
		}
		
		obj::db()->update('board','type',0,$get['id']);

		if ($data['type'] == 2) {
			obj::db()->update('board','type',0,$get['id'],'thread');
		}
	}	
}
