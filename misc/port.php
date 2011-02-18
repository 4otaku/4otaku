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
		$single_data = prepare_single_data($post);
		Objects::db()->data_insert('post',$post['id'],$single_data);		

		$other_data = prepare_other_data($post);

		foreach ($other_data as $type => $data) {
			foreach ($data as $key => $row) {
				Objects::db()->insert('data',array($type,$post['id'],'post',$key,$row));
			}
		}		
		
		$unique_data = prepare_unique_data($post);
		
		foreach ($unique_data as $type => $data) {
			foreach ($data as $row) {
				Objects::db()->conditional_insert(
					'data',
					array($type,$post['id'],'post',$post['area'],$row),
					false,
					"type=? and item_id=? and item_type=? and data=?",
					array($type,$post['id'],'post',$row)
				);
			}
		}
	}
	
	
	
	echo "Hooray!";
