<?
	if (empty($_POST)) die("error");
	
	set_time_limit(0);
	
	include '../engine/init.php';
	
	$dump = file_get_contents('dump.sql');	
	$queries = array_filter(explode(';', $dump));
	
	foreach ($queries as $query) {
		if (trim($query)) {
			Objects::db()->sql($query);
		}
	}

	function prepare_single_data($data) {
		$allowed_types = array(
			'title', 'text', 'pretty_text', 'comment_count', 'area',
			'last_comment', 'update_count', 'pretty_date', 'sortdate');		
		
		$return = array();
		
		foreach ($data as $key => $value) {
			if (in_array($key, $allowed_types) && !empty($value)) {
				$return[$key] = $value;
			}
		}

		return $return;
	}
	
	function prepare_other_data($data) {
		$return = array();
		
		foreach ($data as $key => $value) {
			if (!empty($value)) {
				switch ($key) {
					case 'link' :
						try {
							$links = unserialize($value);
						} catch (Exception $e) {
							var_dump($data['id']);
						}
						if (is_array($links)) {
							foreach ($links as $row) {
								foreach ($row['alias'] as $key2 => $alias) {
									$return['link'][] = Crypt::pack_array(
										array(
											'name' => $row['name'],
											'alias' => $alias,
											'url' => $row['url'][$key2], 
											'size' => $row['size'],
											'sizetype' => $row['sizetype'],
										)
									);
								}
							}
						}
						break;
					case 'info':
					case 'file':
						try {
							$data = unserialize($value);
						} catch (Exception $e) {
							var_dump($data['id']);
						}	
						if (is_array($data)) {
							foreach ($data as $row) {
								$return[$key][] = Crypt::pack_array($row);
							}
						}
						break;	
					case 'image':
						$data = explode('|',$value);
						if (!empty($data)) {
							foreach ($data as $row) {
								$return[$key][] = Crypt::pack_array(
									array(
										'file' => $row,
									)
								);
							}
						}
						break;							
					default:
						break;
				}
			}
		}

		return $return;
	}	
	
	function prepare_unique_data($data) {
		$allowed_types = array('author','category','language','tag');
		
		$return = array();
		
		foreach ($data as $key => $value) {
			if (in_array($key, $allowed_types) && !empty($value)) {
				$value = array_filter(array_unique(explode('|',$value)));
				if (!empty($value)) {
					foreach ($value as $item) {
						$return[$key][] = $item;
					}
				}
			}
		}

		return $return;		
	}
	
	$db = new Database_Mysql($_POST);
	
	$arts = $db->get_table('art');
	
	foreach ($arts as $art) {
		$mainfile = '../images/art/full/'.$art['md5'].'.'.$art['extension'];
		$resfile = '../images/art/resized/'.$art['md5'].'.jpg';
		
		if (!file_exists($mainfile)) {
			$image = @file_get_contents('http://4otaku.ru/images/booru/full/'.$art['md5'].'.'.$art['extension']);
			$resized = @file_get_contents('http://4otaku.ru/images/booru/resized/'.$art['md5'].'.jpg');
			$thumb = @file_get_contents('http://4otaku.ru/images/booru/thumbs/'.$art['thumb'].'.jpg');
			$large_thumb = @file_get_contents('http://4otaku.ru/images/booru/thumbs/large_'.$art['thumb'].'.jpg');
			
			file_put_contents($mainfile,$image);
			if ($resized) file_put_contents($resfile,$resized);
			file_put_contents('../images/art/thumbnail/'.$art['thumb'].'.jpg',$thumb);
			file_put_contents('../images/art/large_thumbnail/'.$art['thumb'].'.jpg',$large_thumb);
		}
		
		$sizes = getimagesize($mainfile);
		$resizes = $resized ? getimagesize($resfile) : 0;
		
		Objects::db()->insert('art',
			array(
				$art['id'],
				$art['md5'],
				$sizes[0],
				$sizes[1],
				filesize($mainfile),
				$resizes[0] / $sizes[0],
				$art['extension'],
				$art['thumb'],
				$art['source'],
				NULL,
				trim(str_replace('|',' tag_',rtrim($art['tag'],'|'))).' '.
				trim(str_replace('|',' author_',rtrim($art['author'],'|'))).' '.
				trim(str_replace('|',' category_',rtrim($art['category'],'|'))).' '.
				($art['pool'] != '|' ? trim(str_replace('|',' pool_',rtrim($art['pool'],'|'))).' ' : '').
				'area_'.preg_replace('/_.+$/','',$art['area']),				
				$art['comment_count'],
				substr_count($art['variation'],'|')-1,
				date ("Y-m-d H:i:s",round($art['sortdate'] / 1000)),
				preg_replace('/_.+$/','',$art['area'])
			), array(
				'id',
				'md5',
				'width',
				'height',
				'weight',
				'resized',
				'extension',
				'thumbnail',
				'source',
				'parent_id',
				'meta',
				'comments',
				'variations',
				'date',
				'area')
		);
	}	

	$posts = $db->get_table('post');

	foreach ($posts as $post) {		
		Objects::db()->insert('post',
			array(
				$post['id'],
				$post['title'],
				$post['text'],
				$post['pretty_text'],
				trim(str_replace('|',' tag_',rtrim($post['tag'],'|'))).' '.
				trim(str_replace('|',' author_',rtrim($post['author'],'|'))).' '.
				trim(str_replace('|',' category_',rtrim($post['category'],'|'))).' '.
				trim(str_replace('|',' language_',rtrim($post['language'],'|'))).' '.
				'area_'.preg_replace('/_.+$/','',$post['area']),				
				$post['comment_count'],
				$post['update_count'],
				date ("Y-m-d H:i:s",round($post['sortdate'] / 1000)),
				preg_replace('/_.+$/','',$post['area'])
			), array(
				'id',
				'title',
				'text',
				'pretty_text',
				'meta',
				'comments',
				'updates',
				'date',
				'area')
		);
		
		$other_data = prepare_other_data($post);
		
		foreach ($other_data as $type => $data) {
			foreach ($data as $key => $row) {
				Objects::db()->insert('post_items',array($post['id'],$type,$key,$row));
			}
		}		
	}
	
	unset ($posts);
	
	$videos = $db->get_table('video');

	foreach ($videos as $video) {		
		Objects::db()->insert('video',
			array(
				$video['id'],
				$video['title'],
				$video['link'],
				$video['object'],				
				$video['text'],
				$video['pretty_text'],
				trim(str_replace('|',' tag_',rtrim($video['tag'],'|'))).' '.
				trim(str_replace('|',' author_',rtrim($video['author'],'|'))).' '.
				trim(str_replace('|',' category_',rtrim($video['category'],'|'))).' '.
				'area_'.preg_replace('/_.+$/','',$video['area']),				
				$video['comment_count'],
				date ("Y-m-d H:i:s",round($video['sortdate'] / 1000)),
				preg_replace('/_.+$/','',$video['area'])
			), array(
				'id',
				'title',
				'link',
				'object',
				'text',
				'pretty_text',
				'meta',
				'comments',
				'date',
				'area')
		);		
	}
	
	unset ($videos);
	
	
	
	echo "Hooray!";
