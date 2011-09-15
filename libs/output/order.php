<?

class output__order extends engine
{
	public $allowed_url = array(
		array(1 => '|order|', 2 => '|all|', 3 => 'end'),
		array(1 => '|order|', 2 => '|category|', 3 => 'any', 4 => 'end'),
		array(1 => '|order|', 2 => '|do|', 3 => '|prolong|unsubscribe|', 4 => 'any', 5 => 'end'),
		array(1 => '|order|', 2 => 'num', 3 => '|comments|', 4 => '|all|', 5 => 'end'),
		array(1 => '|order|', 2 => 'num', 3 => '|comments|', 4 => '|page|', 5 => 'num', 6 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		global $url;
		if (!$url[2] || $url[2] == 'do') return $this->order_main();
		elseif (is_numeric($url[2])) return $this->order_single($url[2]);
		else return $this->order_all();
	}

	function order_main() {
		global $url;
		$return['display'] = array('order_main','order_open');
		$orders = obj::db()->sql('select * from orders where area="workshop" order by sortdate');
		if (is_array($orders)) foreach ($orders as $order) {
			$order['category'] = explode('|',trim($order['category'],'|'));
			if (is_array($order['category'])) foreach ($order['category'] as $one)
				$return['order_open'][$one][] = $order;
		}
		$return['category'] = obj::db()->sql('select alias, name from category','alias');
		if ($url[2]) $this->action($url[3],intval(decrypt($url[4])));
		return $return;
	}

	function order_all() {
		global $url; global $error;
		$return['display'] = array('order_all');
		if ($url[2] == 'all') $return['orders'] = obj::db()->sql('select * from orders order by area desc, sortdate');
			else $return['orders'] = obj::db()->sql('select * from orders where locate("|'.mysql_real_escape_string($url[3]).'|",orders.category) order by area desc, sortdate');
		if ($return['orders']) return $return;
			else $error = true;
	}

	function order_single($id) {
		global $sets; global $url; global $error;
		$return['display'] = array('order_single','comments');
		$return['order_single'] = obj::db()->sql('select * from orders where id='.$id.' limit 1',1);
		if ($return['order_single']) {
			if ($sets['user']['rights']) $return['order_single']['cat'] = obj::db()->sql('select alias,name from category order by id');
			$return['order_single']['category'] = obj::db()->sql('select name, alias from category where alias="'.implode('" or alias="',array_unique(array_filter(explode('|',$return['order_single']['category'])))).'"','alias');
			$return['comments'] = $this->get_comments('orders',$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
			$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
			$return['navi']['all'] = true;
			$return['navi']['name'] = "Страница комментариев";
			$return['navi']['meta'] = $url[2].'/comments/';
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);
			return $return;
		}
		else $error = true;
	}

	function action($action,$id) {
		if ($id) {
			if ($action == 'prolong') {
				$data = obj::db()->sql('select email,spam from orders where id='.$id,1);
				$action = new input__common();
				if ($data['spam']) $action->set_events($id,$data['email']);
				else $action->set_events($id);
				$this->add_res('Заказ успешно продлен');
			}
			elseif ($action == 'unsubscribe') {
				obj::db()->update('orders','spam',0,$id);
				obj::db()->sql('delete from misc where (type="mail_notify" and data5="'.$id.'")',0);
				$this->add_res('Вы отписались от уведомлений по заказу <a href="/order/'.$id.'/">http://4otaku.ru/order/'.$id.'/</a>');
			}
		}
		else {
			$this->add_res('Ошибка, неправильный URL', true);
		}
	}

}
