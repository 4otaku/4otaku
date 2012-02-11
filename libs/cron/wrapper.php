<?

class Cron
{
	protected $workers = array();

	public function process($function) {

		if (strpos($function, '::')) {
			$function = explode('::', $function);
			$class = 'Cron_' . $function[0];
			$function = $function[1];

			if (empty($this->workers[$class])) {
				$this->workers[$class] = new $class();
			}

			$this->workers[$class]->execute($function);
		} else {

			$this->$function();
		}
	}

	function clean_tags() {
		global $def;
/*		foreach ($def['type'] as $type) $query .= ' union select concat("'.$type.'",id) as id, tag, area, "'.$type.'" as type from '.$type;
		$data = obj::db()->sql(substr($query,7));
		foreach ($data as $one) {
			$tags = array_unique(array_filter(explode('|',$one['tag'])));
			foreach ($tags as $tag) $update[$tag][$one['type'].'_'.$one['area']]++;
		}
		$tags = obj::db()->sql('select * from tag','alias');
		foreach ($tags as $alias => $tag)
			foreach ($tag as $key => $field)
				if (strpos($key,'_') && $field != $update[$alias][$key])
					obj::db()->update('tag',$key,$update[$alias][$key],$tag['id']);
*/	}

	function process_pack() {
		@file_get_contents("http://4otaku.ru/engine/process_pack.php");
	}

	function delete_unneeded_variants () {
		obj::db()->sql('
			update `tag` set
			`variants` = Replace(`variants`, Concat("|",`name`,"|"), "|")
			WHERE `variants` like binary Concat("%|",`name`,"|%");'
		);
		obj::db()->sql(
			'update `tag` set
			`variants` = Replace(`variants`, Concat("|",`alias`,"|"), "|")
			WHERE `variants` like binary Concat("%|",`alias`,"|%");'
		);
	}

	function check_wiki_tags() {
		$tags = obj::db('wiki')->sql('select `page_title` from `page` where `page_namespace` = 500');
		$already_marked = obj::db()->sql('select id, alias from tag where have_description = 1', 'id');

		if (empty($already_marked)) {
			$already_marked = array();
		}

		foreach ($tags as $tag) {
			$tag = current($tag);

			if (!in_array($tag, $already_marked)) {
				obj::db()->update('tag',array('have_description'),array(1),$tag,'alias');
			}
		}
	}

	function get_logs() {
		$time = (time() - 3600*4)*1000;
		$logs = obj::db('chat')->sql('select nickname, logTime, body from ofMucConversationLog where (roomID < 3 and cast(logTime as unsigned) > '.$time.') order by logTime');
		foreach ($logs as $log) {
			$md5 = md5(implode($log));
			$text = $log['nickname'].': '.$log['body'];

			$timestamp = round(ltrim($log['logTime'], '0') / 1000);
			$date = date("Y-m-d", $timestamp);
			$time = date("H:i:s", $timestamp);

			obj::db()->insert('raw_logs',array($md5, $date, $time, $text));
		}
	}

	function create_pack_archive () {
		$pack_id = obj::db()->sql('select id from art_pack where weight = 0 and cover != "" limit 1',2);

		if (!empty($pack_id)) {
			$work_dir = ROOT_DIR.SL.'files'.SL.'pack_cache'.SL;
			$image_dir = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL;
			$arts = obj::db()->sql('select * from art as a left join art_in_pack as p on a.id = p.art_id where p.pack_id='.$pack_id);

			$zip_name = 'pack_'.$pack_id.'.zip';
			if (file_exists($work_dir.$zip_name)) {
				unlink($work_dir.$zip_name);
			}

			$zip = new ZipArchive;
			if ($zip->open($work_dir.$zip_name, ZipArchive::CREATE) === true) {
				foreach ($arts as $art) {
					if (file_exists($image_dir.$art['md5'].'.'.$art['extension'])) {
						$zip->addFile($image_dir.$art['md5'].'.'.$art['extension'], $art['filename']);
					}
				}
				$zip->close();
			}
			$weight = filesize($work_dir.$zip_name);
			obj::db()->update('art_pack', 'weight', $weight, $pack_id);
		}
	}

	function clean_settings() {
		obj::db()->sql('DELETE FROM settings WHERE ((data="YTowOnt9" OR data="") AND lastchange < '.(time()-3600).')',0);
	}

	function send_mails() {
		$data = obj::db()->sql('select * from misc where type = "mail_notify" and data1 < '.time());
		if (!empty($data)) {
			$mail = new mail('html');
			foreach ($data as $send) {
				if (!obj::db()->sql('select id from orders where (id ='.$send['data5'].' and spam = 0)',2)) {
					if ($send['data3']) $mail->text($send['data4'])->send($send['data2'],$send['data3']);
					else $mail->text($send['data4'])->send($send['data2']);
					obj::db()->sql('delete from misc where id ='.$send['id'],0);
				}
			}
		}
	}

	function close_orders() {
	/*
		Перед запуском проверить добавление комментария

		$data = obj::db()->sql('select * from misc where type = "close_order" and data1 < '.time());
		if (!empty($data)) {
			foreach ($data as $delete) {
				if ($id = obj::db()->sql('select id from orders where (id ='.$delete['data2'].' and area = "workshop")',2)) {
					obj::db()->sql('delete from misc where id ='.$delete['id'],0);
					obj::db()->sql('update orders set area = "flea_market", comment_count=comment_count+1, last_comment='.($time = ceil(microtime(true)*1000)).' where id='.$id,0);
					obj::db()->insert('comment',array(0,0,'orders',$id,'Gouf Custom MS-07B-3','gouf@4otaku.ru','255.255.255.255','dummy',
						'Закрыл автоматом за долгое отсутствие интереса и прогресса','Закрыл автоматом за долгое отсутствие интереса и прогресса',
						obj::transform('text')->rudate(),$time,'workshop'));
				}
			}
		}
	*/
	}

	function add_to_search() {
		global $search;
		if (!$search) $search = new search();

		$data['post'] = obj::db()->sql('select * from post where area != "deleted" and sortdate > '.(time() - 7200)*1000,'id');
		$data['video'] = obj::db()->sql('select * from video where area != "deleted" and sortdate > '.(time() - 7200)*1000,'id');
		$data['art'] = obj::db()->sql('select * from art where area != "deleted" and sortdate > '.(time() - 7200)*1000,'id');
		$data['news'] = obj::db()->sql('select * from news where area != "deleted" and sortdate > '.(time() - 7200)*1000,'id');
		$data['orders'] = obj::db()->sql('select * from orders where area != "deleted" and sortdate > '.(time() - 7200)*1000,'id');
		$data['comment'] = obj::db()->sql('select * from comment where area != "deleted" and sortdate > '.(time() - 7200)*1000,'id');

		$index = obj::db()->sql('select place, item_id from search');
		if (is_array($index)) foreach ($index as &$one) $one = $one['place'].$one['item_id'];

		foreach ($data as $table => $batch)
			if (is_array($batch))
				foreach ($batch as $id => $item)
					if (!is_array($index) || !in_array($table.$id,$index))
						$search->$table($item,$id);
	}

	function update_search() {
		global $search;
		if (!$search) $search = new search();

		$index = obj::db()->sql('select place, item_id from search order by lastupdate limit 90');
		$index[] = obj::db()->sql('select place, item_id from search where sortdate > '.(ceil(microtime(true)*1000) - 86400*3*1000).' order by lastupdate limit 1',1);
		$index = array_filter($index);

		foreach ($index as $one) $data[$one['place']][$one['item_id']] = obj::db()->sql('select * from '.$one['place'].' where id = '.$one['item_id'],1);

		foreach ($data as $table => $batch)
			if (is_array($batch))
				foreach ($batch as $id => $item) {
						obj::db()->sql('delete from search where place="'.$table.'" and item_id='.$id,0);
						if ($item['area'] != 'deleted' && $item['area']) $search->$table($item,$id);
					}
	}

	function check_dropout_search() {
		global $search;
		$data['post'] = obj::db()->sql('select id, concat(id,"#post") from post where area != "deleted"','id');
		$data['video'] = obj::db()->sql('select id, concat(id,"#video") from video where area != "deleted"','id');
		$data['art'] = obj::db()->sql('select id, concat(id,"#art") from art where area != "deleted"','id');
		$data['news'] = obj::db()->sql('select id, concat(id,"#news") from news where area != "deleted"','id');
		$data['orders'] = obj::db()->sql('select id, concat(id,"#orders") from orders where area != "deleted"','id');
		$data['comment'] = obj::db()->sql('select id, concat(id,"#comment") from comment where area != "deleted"','id');

		$all = array();
		foreach ($data as $key => $table) if (empty($table)) $data[$key] = array();
		unset($table);
		$all = array_merge($all,$data['post'],$data['video'],$data['art'],$data['news'],$data['orders'],$data['comment']);
		unset($data);

		$index = obj::db()->sql('select id, concat(item_id,"#",place) from search','id');
		$index = array_diff($all, $index);
		unset($all);

		$sql = 'INSERT INTO search (`item_id`, `place`) VALUES ("' . str_replace('#','","',implode('"), ("', $index)) . '");';
		obj::db()->sql($sql, 0);
	}

	function search_balance_weights() {
		$types = array('post', 'video', 'art', 'news', 'orders', 'comment');

		$time = time();
		foreach ($types as $type) {
			$data[$type] = obj::db()->sql('select id, `index` from search where place = "'.$type.'" order by md5(id+'.$time.') limit 100','id');
			empty($data[$type]) ? $stop = true : null;
		}

		if (empty($stop)) {
			foreach ($types as $type) {
				$weights = array();
				foreach ($data[$type] as $index) {
					preg_match_all('/=(\d+)\|/u', $index, $tmp_weights);
					$weights[] = array_sum($tmp_weights[1]);
				}
				if (!empty($weights)) {
					$weight = array_sum($weights) / count($weights);
					obj::db()->sql('insert into search_weights values("'.$type.'","'.$weight.'") on duplicate key update weight = '.$weight,0);
				}
			}
		}
	}

	function resize_arts() {
		global $def;

		$arts = obj::db()->sql('select id, md5, extension from art where locate("|need_resize|",tag)');

		if (!empty($arts)) {

			foreach ($arts as $art) {
				$name = $art['md5'].'.'.$art['extension'];
				$path = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$name;

				$worker = new Transform_Upload_Art($path, $name);

				$resized = $worker->resize();

				obj::db()->sql('update art set resized="'.$resized.'", tag=replace(tag,"|need_resize|","|") where id='.$art['id'],0);
			}
		}
	}

	function track_similar_pictures() {
		if (
			!function_exists('puzzle_fill_cvec_from_file') ||
			!function_exists('puzzle_vector_normalized_distance') ||
			!function_exists('puzzle_compress_cvec') ||
			!function_exists('puzzle_uncompress_cvec')
		) {
			return;
		}
/*
		$max  = obj::db()->sql('select max(id) from art_similar',2);
		$arts = obj::db()->sql('select id, thumb from art where id > '.($max ? $max : 0).' and area != "deleted" order by id limit 2000');

		foreach ($arts as $art) {
			$image = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.'large_'.$art['thumb'].'.jpg';
			$vector = puzzle_fill_cvec_from_file($image);
			$vector = base64_encode(puzzle_compress_cvec($vector));

			obj::db()->insert('art_similar',array($art['id'], $vector, 0, ''),false);
		}
*/
		$all = obj::db()->sql('select id, vector from art_similar where vector != ""','id');
		$arts = obj::db()->sql('select * from art_similar where vector != "" and checked=0 limit 100','id');

		if (is_array($all) && is_array($arts)) {
			foreach ($all as $compare_id => $vector) {
				$all[$compare_id] = puzzle_uncompress_cvec(base64_decode($vector));
			}

			foreach ($arts as $id => $art) {
				$art['vector'] = puzzle_uncompress_cvec(base64_decode($art['vector']));
				$similar = '|';
				$art_area = false;
				foreach ($all as $compare_id => $vector) {
					if (
						$id != $compare_id &&
						puzzle_vector_normalized_distance($art['vector'], $vector) < 0.3
					) {
						if (empty($art_area)) {
							$art_area = Database::get_field('art', 'area', $id);
						}
						
						if ($art_area == 'cg' &&
							Database::get_field('art', 'area', $compare_id) == 'cg') {
								
							continue;
						}
						
						$similar .= $compare_id.'|';
					}
				}
				obj::db()->update('art_similar',array('checked','similar'),array(1,$similar),$id);
			}
		}
	}
}
