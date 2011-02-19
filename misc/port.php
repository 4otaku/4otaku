<?
	if (empty($_POST)) die("error");
	
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
						$links = unserialize($value);
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
						$data = unserialize($value);
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
	
	$posts = $db->get_table('post');

	foreach ($posts as $post) {		
		Objects::db()->insert('post',
			array(
				$post['id'],
				$post['title'],
				$post['text'],
				$post['pretty_text'],
				$post['comment_count'],
				$post['update_count'],
				date ("Y-m-d H:i:s",round($post['sortdate'] / 1000)),
				preg_replace('/_.+$/','',$post['area'])
			), array(
				'id',
				'title',
				'text',
				'pretty_text',
				'comments',
				'updates',
				'date',
				'area')
		);
		Objects::db()->insert('meta_index',
			array(
				'post',
				$post['id'],
				trim(str_replace('|',' tag_',rtrim($post['tag'],'|'))).' '.
				trim(str_replace('|',' author_',rtrim($post['author'],'|'))).' '.
				trim(str_replace('|',' category_',rtrim($post['category'],'|'))).' '.
				trim(str_replace('|',' language_',rtrim($post['language'],'|'))).' '.
				'place_post area_'.preg_replace('/_.+$/','',$post['area'])
			)
		);
		
		$other_data = prepare_other_data($post);
		
		foreach ($other_data as $type => $data) {
			foreach ($data as $key => $row) {
				Objects::db()->insert('post_items',array($post['id'],$type,$key,$row));
			}
		}		
	}
	
	
	
	echo "Hooray!";
