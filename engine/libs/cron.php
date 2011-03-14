<?
	
class Cron
{	
	public function get_task_list () {
		$tasks = Objects::db()->get_full_vector('cron', '`period` + `last_call` < NOW()');
		
		return array_keys($tasks);
	}	
	
	public function do_task ($task) {
		
		if (Objects::db()->get_count('cron', '`name` = ? and `status` = "idle"', $task)) {
			
			if (method_exists($this, $task)) {
				Objects::db()->update('cron', '`name` = ?', 'status', array('process', $task));
				
				$time = time(); 
				$memory = memory_get_usage();
				
				$task_memory = $this->$task();
				
				$time = time() - $time;				
				$memory = max(0, $task_memory - $memory);
				
				$fields = array('status', 'runtime', 'memory');
				$values = array('idle', $time, $memory, $task)
				
				Objects::db()->update('cron', '`name` = ?', $fields, $values);
			}	
			
			return "\n В классе Cron не нашлось функции под таск $task.\n";
		}
		
		return "\n Не удалось найти таск $task в базе, либо он уже в процессе.\n";
	}	
	
	
	/* Gouf - проверяльщик ссылок */

	public function check_links () {
		
		$test = array();
		
		for ($i = 1; $i < 100000; $i++) {
			$j = $i; $result = 1;	
			
			while (--$j) $result = $result * $j;
			
			$test[] = $result;
		}

		return memory_get_usage();
/*		$count = obj::db()->sql('select count(id) from gouf_links',2);
		$limit = ceil($count/1440);
		$links = obj::db()->sql('select id, link, status from gouf_links order by checkdate limit '.$limit);
		$base = obj::db()->sql('select * from gouf_base order by id');
		if (is_array($links)) foreach ($links as $link) {
			$result = $this->gouf_check_single($base, $link['link']);
			if ($result != 'unknown' && $result != $link['status']) {
				obj::db()->update('gouf_links',array('checkdate','status'),array(time(),$result),$link['id']);
			} else {
				obj::db()->update('gouf_links',array('checkdate'),array(time()),$link['id']);
			}
		}	*/
	}

	public function gouf_refresh_links () {
		$posts = obj::db()->sql('select id, title, link from post where area = "main"');
		$gouf_temp_links = obj::db()->sql('select id, link from gouf_links');

		$post_links = array(); $gouf_links = array();
		if (is_array($gouf_temp_links)) foreach ($gouf_temp_links as $link) {
			$link['link'] = html_entity_decode($link['link'], ENT_QUOTES, "utf-8" );
			$gouf_links[$link['link']] = $link;
		}
		if (is_array($posts)) foreach ($posts as $post) {
			$links = unserialize ($post['link']);
			if (is_array($links)) foreach ($links as $row)
				  if (is_array($row['url'])) foreach ($row['url'] as $link)
					  $post_links[$link] = array('id' => $post['id'],'link' => $link,'title' => $post['title']);
		}

		$delete_row = array_diff_key($gouf_links,$post_links);
		$insert_row = array_diff_key($post_links,$gouf_links);

		if (is_array($delete_row)) foreach ($delete_row as $link) obj::db()->sql('delete from gouf_links where id='.$link['id'],0);
		if (is_array($insert_row)) foreach ($insert_row as $link) obj::db()->insert('gouf_links',array($link['id'],$link['title'],0,'works',$link['link']));
	}

	public function gouf_check_single ($base, $link){
		$link = str_replace('&apos;',"'",html_entity_decode($link,ENT_QUOTES,'UTF-8'));
		$return = 'works';
		foreach ($base as $one) if (stristr($link,$one['alias'])) {

			$input = $this->gouf_curl(str_replace(' ','%20',stripslashes($link)), ($one['alias'] == 'mediafire.com'));

			if (!trim($input) || ($one['alias'] == 'megaupload.com' && stristr($input,'http://www.megaupload.com/?c=msg')))
				return 'unknown';

			$return = 'error';
			if ($input) {
				$tests = explode('|',$one['text']);
				foreach ($tests as $test) if (stristr($input,$test)) $return = 'works';
			}
			break;

		}
		
		if ($return == 'error' && $one['alias'] == '4shared.com') {
			$fh = fopen("test.txt", 'w');
			fwrite($fh, $link."\n\n".implode("/n", $tests)."\n\n".$input);
			fclose($fh);
		}
		return $return;
	}

	public function gouf_curl ($link, $simple=false) {

		if (!$simple) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
			curl_setopt($ch, CURLOPT_URL, $link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$input = curl_exec($ch);
			curl_close($ch);
		}
		else $input = @file_get_contents($link);
		return $input;
	}
	
	/* Gouf закончился */
}
