<?
	
class Cron
{	
	public function get_task_list () {
		$tasks = Objects::db()->get_table('cron', 'name', '`period` + `last_call` < NOW()');

		foreach ($tasks as & $task) {
			$task = current($task);
		}

		return $tasks;
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
				$values = array('idle', $time, $memory, $task);
				
				Objects::db()->update('cron', '`name` = ?', $fields, $values);
				
				$memory = round($memory / 1024, 2);
				
				return "\n $task выполнено за $time секунд, $memory килобайт оперативы.\n";
			}	
			
			return "\n В классе Cron не нашлось функции под таск $task.\n";
		}
		
		return "\n Не удалось найти таск $task в базе, либо он уже в процессе.\n";
	}
	
	/* Начались непосредственно кронтаски */

	private function check_links () {
		
		$count = Objects::db()->get_count('post_items', '`type` = "link"');
		
		$limit = ceil($count/1440);
		
		$links = Objects::db()->get_vector('post_items', 'id, data', '`type` = "link" order by last_check limit '.$limit);

		if (is_array($links)) {
			foreach ($links as $id => $link) {
				$result = 'unclear';
				
				$link = Crypt::unpack($link);
				if (!empty($link['url'])) {
					$result = Browser::check_download_link($link['url'], true);
				}
				
				$update = array(
					'status' => $result,
					'last_check' => Objects::db()->unix_to_date()
				);
				
				Objects::db()->update('post_items', $link['id'], $update);
			}
		}
		
		return memory_get_usage();
	}
}
