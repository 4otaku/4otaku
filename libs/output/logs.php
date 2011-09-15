<?

class output__logs extends engine
{
	function __construct() {
		global $url;
		if (!$url[2]) $this->nocache = true;
		if (!$url[2]) $url[2] = date("Y");
		if (!$url[3]) $url[3] = date("n");
		if (!$url[4]) $url[4] = date("j");
	}

	public $allowed_url = array(
		array(1 => '|logs|',2 => 'num',3 => 'num',4 => 'num',5 => 'end'),
		array(1 => '|logs|',2 => '|search|', 3 => 'any'),
		array(1 => '|logs|',2 => '|search|', 3 => 'any', 4 => '|page|', 5 => 'num'),
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		global $url;

		if ($url[2] == 'search') {
			$page = empty($url[5]) ? 1 : $url[5];
			return $this->search($url[3], $page);
		}

		$return['display'] = array('logs_navi','logs_search','logs_body','logs_arrows');
		if (!$this->nocache) {
			$return['logs'] = base64_decode(obj::db()->sql('select cache from logs where (year='.$url[2].' and month ='.$url[3].' and day='.$url[4].')',2,'cache'));
		}
		if (!$return['logs']) {
			$today = mktime(0, 0, 0, $url[3], $url[4], $url[2])*1000;
			$return['logs'] = obj::db('chat')->sql('select nickname, logTime, body from ofMucConversationLog where (roomID < 3 and cast(logTime as unsigned) > '.$today.' and cast(logTime as unsigned) < '.($today + 86400000).') order by logTime');
			if (is_array($return['logs'])) foreach ($return['logs'] as $key => &$log) {
				if (trim($log['body'])) $log['text'] = $this->format_logs($log['body'],$log['nickname']);
				else unset($return['logs'][$key]);
			}
		}
		if (!$return['logs']) $return['nologs'] = 'За этот день нет ни одного лога.';
		$start = array_values(obj::db()->sql('select data1,data2,data3 from misc where type="logs_start"',1));
		$end = array(date("Y"),date("n"),date("j"));
		$current = array($url[2],$url[3],$url[4]);
		if (array_diff_assoc($end,$current) && is_array($return['logs'])) $this->make_logs_cache($return['logs'],$url[2],$url[3],$url[4]);
		$rumonth = array('Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');

		for ($i = $start[0]; $i <= $end[0]; $i++) {
			$start_month = $i == $start[0] ? $start[1] : 1;
			$end_month = $i == $end[0] ? $end[1] : 12;
			for ($j = $start_month; $j <= $end_month; $j++) {
				$return['month'][($i == $current[0] && $j == $current[1] ? 'current' : '/logs/'.$i.'/'.$j.'/'.($j > $start[1] ? 1 : $start[2]).'/')] = $rumonth[($j-1)].' '.$i;
			}
		}
		$yesterday = mktime(12,0,0,$url[3],$url[4]-1,$url[2]); $tomorrow = mktime(12,0,0,$url[3],$url[4]+1,$url[2]);
		if ($yesterday > mktime(0,0,0,$start[1],$start[2],$start[0])) {
			$return['navi']['yesterday']= array(
				'url' => '/logs/'.date("Y",$yesterday).'/'.date("n",$yesterday).'/'.date("j",$yesterday).'/',
				'name' => $rumonth[(date("n",$yesterday)-1)].' '.date("j",$yesterday)
			);
		} elseif ($yesterday < mktime(0,0,0,$start[1],$start[2]-1,$start[0])) {
			$return['nologs'] = "Логи раньше, чем за 15-ое февраля 2010 к сожалению не сохранились.";
			engine::error_headers();
		}
		$return['navi']['today']= array('name' => $rumonth[($url[3]-1)].' '.$url[4]);
		if ($tomorrow < mktime(24,0,0,$end[1],$end[2],$end[0])) {
			$return['navi']['tomorrow']= array(
				'url' => '/logs/'.date("Y",$tomorrow).'/'.date("n",$tomorrow).'/'.date("j",$tomorrow).'/',
				'name' => $rumonth[(date("n",$tomorrow)-1)].' '.date("j",$tomorrow)
			);
		} elseif ($tomorrow > mktime(24,0,0,$end[1],$end[2]+1,$end[0])) {
			$return['nologs'] = "Забегаем в будущее?";
			engine::error_headers();
		}
		return $return;
	}

	function format_logs ($text,$author=false,$mark=array()) {
		$text = str_replace(array('<','>'),array('&lt;','&gt;'),$text);
		foreach ($mark as $one) {
			$text = preg_replace('/(?<=\b)'.preg_quote($one,'/').'(?=\b)/uis', '<span style="background-color:yellow;">$0</span>', $text);
		}
		$text = preg_replace(array("/[\r\n]+/su"), array("<br />"), $text);
		$text = preg_replace(transform__text::URL_REGEX, '<a href="$0">$0</a>', $text);
		$text = obj::transform('text')->cut_long_words($text,40);

		if (substr($text,0,3) == '/me') return '<span class="logs-nick">'.$author.'</span>'.substr($text,3);
		else return ($author ? '<span class="logs-nick">&lt;'.$author.'&gt;</span> ' : '').$text;
	}

	function make_logs_cache($logs,$year,$month,$day) {
		if (is_array($logs)) {
			$key = 'even';
			foreach ($logs as &$log) {
				if ($key == 'even') $key = 'odd'; else $key = 'even';
				$cache .= '<div class="logs-'.$key.'" id="time-'.date('G:i:s',$log['logTime']/1000).'.'.($log['logTime']%1000).'">
								<a href="/logs/'.$year.'/'.$month.'/'.$day.'#time-'.date('G:i:s',$log['logTime']/1000).'.'.($log['logTime']%1000).'">
									<span class="logs-time">
										['.date('G:i:s',$log['logTime']/1000).']
									</span>
								</a>
								 '.$log['text'].
						  '</div>';
			}
			if (!obj::db()->sql('select id from logs where (year='.$year.' and month ='.$month.' and day='.$day.')',2,'id'))
				obj::db()->insert('logs',array(base64_encode($cache),$year,$month,$day));
		}
	}

	function search ($query, $page) {
		global $search;
		if (!$search) $search = new search();

		$return = array();
		$return['display'] = array('logs_search','logs_results','navi');

		$perpage = 10; $start = ($page - 1) * $perpage;

		$query = $search->prepare_string(urldecode($query),true);
		$mark = preg_split('/\s+/s', $query);
		$fixed_query = '+'.preg_replace('/\s+/s', ' +', $query);
		$select_query = ' MATCH (text) AGAINST ("'.$fixed_query.'" IN BOOLEAN MODE)';

		$sql = 'SELECT id, date FROM raw_logs WHERE'.$select_query;
		$days = obj::db()->sql($sql.' group by date order by date desc limit '.$start.', '.$perpage, 'id');

		if (!empty($days)) {
			$select_days = '"'.implode('","',$days).'"';
			$data = obj::db()->sql('SELECT `date`, `time`, `text` FROM raw_logs WHERE `date` in ('.$select_days.') and'.$select_query);

			foreach ($days as $day) {
				foreach ($data as $key => $log) {
					if ($log['date'] == $day) {
						$return['days'][$day][] = array(
							'time' => $log['time'],
							'text' => $this->format_logs($log['text'],false,$mark),
						);

						unset($data[$key]);
						if (count($return['days'][$day]) > 4) {
							break;
						}
					}
				}
			}

			$return['navi']['meta'] = '/logs/search/'.$query.'/';
			$return['navi']['curr'] = $page;
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('select count(distinct(date)) from raw_logs WHERE MATCH (text) AGAINST ("'.$fixed_query.'" IN BOOLEAN MODE)',2)/$perpage);
		} else {
			$return['display'] = array('logs_search','logs_results');
		}

		return $return;
	}
}
