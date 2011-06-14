<?
	
class Cron
{	
	public function get_task_list () {
		$tasks = Database::get_table('cron', 'name', '`period` + `last_call` < NOW()');

		foreach ($tasks as & $task) {
			$task = current($task);
		}

		return $tasks;
	}	
	
	public function do_task ($task) {
		
		if (Database::get_count('cron', '`name` = ? and `status` = "idle"', $task)) {
			
			if (method_exists($this, $task)) {
				Database::update('cron', '`name` = ?', array('status' => 'process'), $task);
				
				$time = time(); 
				$memory = memory_get_usage();
				
				$task_memory = $this->$task();
				
				$time = time() - $time;				
				$memory = max(0, $task_memory - $memory);
				
				$update = array(
					'status' => 'idle', 
					'runtime' => $time, 
					'memory' => $memory, 
				);
				
				Database::update('cron', '`name` = ?', $update, $task);
				
				$memory = round($memory / 1024, 2);
				
				return "\n $task выполнено за $time секунд, $memory килобайт оперативы.\n";
			}	
			
			return "\n В классе Cron не нашлось функции под таск $task.\n";
		}
		
		return "\n Не удалось найти таск $task в базе, либо он уже в процессе.\n";
	}
	
	/* Начались непосредственно кронтаски */

	private function check_links () {
		
		$count = Database::get_count('post_items', '`type` = "link"');
		
		$limit = ceil($count/1440);
		
		$links = Database::get_vector('post_items', 'id, data', '`type` = "link" order by last_check limit '.$limit);

		if (is_array($links)) {
			foreach ($links as $id => $link) {
				$result = 'unclear';
				
				$link = Crypt::unpack($link);
				if (!empty($link['url'])) {
					$result = Browser::check_download_link($link['url'], true);
				}
				
				$update = array(
					'status' => $result,
					'last_check' => Database::unix_to_date()
				);
				
				Database::update('post_items', $id, $update);
			}
		}
		
		return memory_get_usage();
	}
	
	private function do_tag_count_cache () {
		Cache::$prefix = '';
		
		if (!$modules = Cache::get('_actual_tag_areas')) {
			$modules = array();
			$config_files = glob(ENGINE.SL.'modules'.SL.'*'.SL.'settings.ini');
			
			if (empty($config_files)) {
				return;
			}
			
			foreach ($config_files as $config_file) {
				$config = parse_ini_file($config_file, true);
				
				if (
					isset($config['meta']['tag']) && 
					is_array($config['area']) &&
					$config['meta']['tag'] == 'enabled'
				) {
					$module = preg_replace('/.*\\'.SL.'/', '', dirname($config_file));
					foreach ($config['area'] as $area => $mode) {
						if ($mode == 'enabled') {
							$modules[$module][] = $area;
						}
					}
				}
			}
			
			Cache::set('_actual_tag_areas', $modules, WEEK);
		}
		
		Database::delete('meta_count', 'expires < NOW()');
		
		$tags = Database::get_vector('meta', 'alias', '`type` = "tag" order by rand() limit 100');
		
		foreach ($modules as $module => $areas) {
			foreach ($areas as $area) {
				foreach ($tags as $tag) {
					$count = Meta_Library::count_tag($tag, $module, $area);
				}
			}
		}
		
		return memory_get_usage();
	}	
	
	private function get_video_thumbnail () {
		$condition = 'object_type = "youtube" and object_thumbnail="" limit 50';
		
		$videos = Database::get_full_table('video', $condition);
		
		foreach ($videos as $video) {
			$data = file_get_contents('http://img.youtube.com/vi/'.$video['object_id'].'/0.jpg');
			
			if (empty($data)) {
				Database::update('video', $video['id'], array('object_thumbnail' => 'deleted'));
				
				continue;
			}
			
			$tmpfname = tempnam('/tmp', 'jpg');
			file_put_contents($tmpfname, $data);		
			
			$pretty_name = $video['id'].'.jpg';
			$name = IMAGES.SL.'video'.SL.'thumbnail'.SL.$pretty_name;
			
			$image = new Transform_Image($tmpfname);
			$image->target($name)->scale(200);
			
			Database::update('video', $video['id'], array('object_thumbnail' => $pretty_name));
			
			unlink($tmpfname);	
		}		
		
		return memory_get_usage();
	}
	
	private function create_art_pack_file () {
		$packs = Database::get_vector('art_cg_pack', array('id', 'title'));
		
		foreach ($packs as $id => $title) {
			$filename = Art_Submodule_Pack::get_pack_filename($id, true);
			
			if (!file_exists($filename)) {
				Art_Submodule_Pack::create_pack_file($id);
				break;
			}
		}
		
		return memory_get_usage();
	}
}
