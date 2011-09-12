<? 
class dynamic__add
{
	function art() {
		global $sets;
		$sets['user']['name'] = preg_replace('/#.*$/','',$sets['user']['name']);
		$return['category'] = obj::db()->sql('select alias, name from category where locate("|art|",area) order by id','alias');
		return $return;
	}	
	
	function video() {
		global $sets;
		$sets['user']['name'] = preg_replace('/#.*$/','',$sets['user']['name']);
		$return['category'] = obj::db()->sql('select alias, name from category where locate("|video|",area) order by id','alias');
		return $return;
	}
	
	function post() {
		global $sets;
		$sets['user']['name'] = preg_replace('/#.*$/','',$sets['user']['name']);
		$return['category'] = obj::db()->sql('select alias, name from category where locate("|post|",area) order by id','alias');
		$return['language'] = obj::db()->sql('select alias, name from language order by id','alias');
		return $return;
	}		
	
	function board() {
		$return['category'] = obj::db()->sql('select alias, name from category where locate("|board|",area) order by id','alias');
		return $return;
	}
	
	function order() {
		global $sets;
		$sets['user']['name'] = preg_replace('/#.*$/','',$sets['user']['name']);
		$return['category'] = obj::db()->sql('select alias, name from category where locate("|post|",area) order by id','alias');
		return $return;
	}
	
	function replay() {
		return true;
	}	
	
	function soku() {
		return true;
	}					

	function comment() {
		global $sets;
		$sets['user']['name'] = preg_replace('/#.*$/','',$sets['user']['name']);	
		return true;
	}	

	function pool() {
		return true;
	}	

	function update() {
		 global $check;
		if ($check->num(query::$get['id'])) {
			$links = obj::db()->sql('select link from post where id='.query::$get['id'],2);
		}		
		return unserialize($links);
	}
	
	function checkpassword() {
		 global $check;
		if (!$check->num(query::$get['id'])) echo 'fail';
		elseif (obj::db()->sql('select password from art_pool where id='.query::$get['id'],2) == md5(query::$get['val'])) echo 'success';
		else echo 'fail';
	}
}
