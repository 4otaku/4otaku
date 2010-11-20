<? 
class dinamic__add
{
	function art() {
		global $db;
		$return['category'] = $db->sql('select alias, name from category where locate("|art|",area) order by id','alias');
		return $return;
	}	
	
	function video() {
		global $db;
		$return['category'] = $db->sql('select alias, name from category where locate("|video|",area) order by id','alias');
		return $return;
	}
	
	function post() {
		global $db;
		$return['category'] = $db->sql('select alias, name from category where locate("|post|",area) order by id','alias');
		$return['language'] = $db->sql('select alias, name from language order by id','alias');
		return $return;
	}		
	
	function board() {
		global $db;
		$return['category'] = $db->sql('select alias, name from category where locate("|board|",area) order by id','alias');
		return $return;
	}
	
	function order() {
		global $db;
		$return['category'] = $db->sql('select alias, name from category where !locate("|board|",area) order by id','alias');
		return $return;
	}
	
	function replay() {
		return true;
	}				

	function comment() {
		return true;
	}	

	function pool() {
		return true;
	}	

	function update() {
		global $get; global $db; global $check;
		if ($check->num($get['id'])) {
			$links = $db->sql('select link from post where id='.$get['id'],2);
		}		
		return unserialize($links);
	}
	
	function checkpassword() {
		global $get; global $db; global $check;
		if (!$check->num($get['id'])) echo 'fail';
		elseif ($db->sql('select password from art_pool where id='.$get['id'],2) == md5($get['val'])) echo 'success';
		else echo 'fail';
	}
}
