<?

class output__bugs extends engine
{

	public $allowed_url = array(
		array(1 => '|bugs|', 2 => 'any', 3=> 'any', 4 => 'any', 5 => 'num', 6 => 'end'),
	);

	public $tabs = array(
		'Все' => 'all',
		'Открытые' => 'open',
		'Закрытые' => 'closed',
		'По автору' => 'by_author',
		'По меткам' => 'by_label',
		'Новый' => 'add',
	);

	public $template = 'general';

	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('board_list','comments','quicklinks'),
		'footer' => array()
	);

	public $error_template = 'board';

	function poke_github($call = '', $post = array()) {
		global $def;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_VERBOSE, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if(!empty($def['db']['github_user']) && !empty($def['db']['github_pass'])) {
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC) ;
			curl_setopt($curl, CURLOPT_USERPWD, "{$def['db']['github_user']}:{$def['db']['github_pass']}");
		}
		curl_setopt($curl, CURLOPT_URL, "https://api.github.com/repos/{$def['db']['github_repo']}/{$call}?page=1&per_page=10000");
		if(count($post)) {
			$post = json_encode($post);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		if( ! $response = curl_exec($curl)) {
			curl_close($curl);
			return false;
		}
		//$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		/*
		if($httpcode == 200 || $httpcode == 201) {
			return false;
		}*/
		return json_decode($response, true);
	}

	function get_issues($type) {
		if(in_array($type, array('open', 'closed'))) {
			$temp_file = ROOT_DIR.SL.'files'.SL.'tmp'.SL.'github_'.$type.'.txt';
			if(file_exists($temp_file) && intval(filectime($temp_file)+60) > time() ) {
				$tmp_resp = file_get_contents($temp_file);
				$resp = unserialize($tmp_resp);
			} else {
				if(file_exists($temp_file)) {
					unlink($temp_file);
				}
				$resp = $this->poke_github('issues?state='.$type);
				if($resp !== false) {
					file_put_contents($temp_file, serialize($resp));
				} else return array();
			}
			return $resp;
		} return array();
	}

	function get_comments($bug) {
		$temp_file = ROOT_DIR.SL.'files'.SL.'tmp'.SL.'github_comments_'.$bug.'.txt';
		if(file_exists($temp_file) && intval(filectime($temp_file)+120) > time() ) {
			$tmp_resp = file_get_contents($temp_file);
			$resp = unserialize($tmp_resp);
		} else {
			if(file_exists($temp_file)) {
				unlink($temp_file);
			}
			$resp = $this->poke_github("issues/{$bug}/comments");
			if($resp !== false) {
				file_put_contents($temp_file, serialize($resp));
			} else return array();
		}
		return $resp;
	}

	function _pager($url, $count, $current) {
		$resp = "<ul class='elite_pager'>";
		for($i=0; $i*10<$count; $i++) {
			$dsp = $i + 1;
			if($i == $current) {
				$resp .= "<li><b>{$dsp}</b></li>";
			} else {
				$urlp = str_replace("%page%", $i, $url);
				$resp .= "<li><a href='{$urlp}'>{$dsp}</a></li>";
			}
		}
		$resp .= "</ul>";
		return $resp;
	}

	function _tabs($turl, $items, $current) {
		global $def, $url;
		$resp = "<h1>Багтрекер</h1><ul class='elite_tabs'>";
		$resp .= "<li class='github'><a href='https://github.com/{$def['db']['github_repo']}/issues'>Github</a></li>";
		foreach($items as $title => $tab) {
			$urlp = str_replace("%tab%", $tab, $turl);
			if($tab == $current && empty($url[3])) {
				$resp .= "<li class='active'><b>{$title}</b></li>";
			} elseif ($tab == $current) {
				$resp .= "<li class='active'><a href='{$urlp}'>{$title}</a></li>";
			} else {
				$resp .= "<li><a href='{$urlp}'>{$title}</a></li>";
			}
		}
		$resp .= "</ul><br class='clear' />";
		return $resp;
	}

	function _list($issues, $per_page, $offset) {
		$id = 0; $resp = '';
		foreach($issues as $issue) {
			if($id >= $offset && $id < $offset+$per_page) {
				$labels = '';
				foreach($issue['labels'] as $label) {
					$labels .= (!empty($labels)) ? ', ' : '';
					$labels .= "<a href='{$def['site']['dir']}/bugs/by_label/{$label['name']}'>{$label['name']}</a>";
				}
				$issue['created_at'] = trim(str_replace(array('T','Z'), ' ', $issue['created_at']));
				$d_short = explode(" ", $issue['created_at']);
				$d_short = $d_short[0];
				$resp .= "<div class='issue {$issue['state']}'>
							<div class='id'><a href='{$def['site']['dir']}/bugs/{$issue['number']}'>#{$issue['number']}<br /><span>{$issue['state']}</span></a></div>
							<div class='inner'>
								<h2><a href='{$def['site']['dir']}/bugs/{$issue['number']}'>{$issue['title']}</a></h2>
								<p>
									<span class='more'><a href='{$issue['html_url']}' title='Баг на гитхабе'>github</a></span>
									<span class='comments' title='Комментариев: {$issue['comments']}'><a href='{$def['site']['dir']}/bugs/{$issue['number']}#comments'>{$issue['comments']}</a></span>
									<span class='from'><a href='{$def['site']['dir']}/bugs/by_author/{$issue['user']['login']}' title='Автор репорта - {$issue['user']['login']}'>{$issue['user']['login']}</a></span>
									<span class='created' title='{$issue['created_at']}'>{$d_short}</span>
									<span class='labels'>{$labels}</span>
								</p>
							</div>
						  </div>";
			}
			$id += 1;
		}
		return $resp;
	}

	function _cloud($url, $labels) {
		$resp = '';
		arsort($labels); reset($labels); $max = current($labels); reset($labels);
		foreach($labels as $label => $weight) {
			$font = round(20 * $weight / $max) + 10;
			$turl = str_replace('%key%', $label, $url);
			$resp .= "<a class='cloud_tag' href='{$turl}' style='font-size: {$font}px; '>{$label}<span>{$weight}</span></a> ";
		}
		return $resp;

	}

	function _item($issue) {
		$labels = '';
		foreach($issue['labels'] as $label) {
			$labels .= (!empty($labels)) ? ', ' : '';
			$labels .= "<a href='{$def['site']['dir']}/bugs/by_label/{$label['name']}'>{$label['name']}</a>";
		}
		$issue['created_at'] = trim(str_replace(array('T','Z'), ' ', $issue['created_at']));
		$d_short = explode(" ", $issue['created_at']);
		$d_short = $d_short[0];
		$issue['body'] = nl2br($issue['body']);
		$resp = "
						<h2><span>Баг #{$issue['number']}:</span> {$issue['title']}</h2>
						<p class='info'>
							<span class='more'><a href='{$issue['html_url']}' title='Баг на гитхабе'>github</a></span>
							<span class='from'><a href='{$def['site']['dir']}/bugs/by_author/{$issue['user']['login']}' title='Автор репорта - {$issue['user']['login']}'>{$issue['user']['login']}</a></span>
							<span class='created' title='{$issue['created_at']}'>{$d_short}</span>
							<span class='labels'>{$labels}</span>
						</p>
						<div class='content'>
							{$issue['body']}
						</div>
						<h3 id='comments'>Комментарии:</h3>
				";


		$comments = $this->get_comments($issue['number']);
		if(count($comments)) {
			foreach($comments as $comment) {
				$comment['created_at'] = trim(str_replace(array('T','Z'), ' ', $comment['created_at']));
				$d_short = explode(" ", $comment['created_at']);
				$d_short = $d_short[0];
				$id = array_pop(explode("/", $comment['url']));
				$comment['body'] = nl2br($comment['body']);
				$resp .= "
							<div class='comment'>
								<p class='info'>
									<span class='more'><a href='{$issue['html_url']}#issuecomment-{$id}' title='Коммент на гитхабе'>github</a></span>
									<span class='from'><a href='{$def['site']['dir']}/bugs/by_author/{$comment['user']['login']}' title='Автор репорта - {$comment['user']['login']}'>{$comment['user']['login']}</a></span>
									<span class='created' title='{$comment['created_at']}'>{$d_short}</span>
								</p>
								{$comment['body']}
							</div>
						 ";
			}
		} else {
			$resp .= '<p>Нет комментариев</p>';
		}

		global $def, $check;
		if(!empty($def['db']['github_user']) && !empty($def['db']['github_pass'])) {
			if(!empty(query::$post['body'])) {
				$name = !empty(query::$post['name']) ? htmlspecialchars(query::$post['name']) : 'анонима';
				if(!empty(query::$post['mail']) && $check->email(query::$post['mail'], false)) {
					$name .= " (".query::$post['mail'].")";
				}
				$data = array(
					"body"	=> query::$post['body']."<br />-----<br />Комментарий с сайта от {$name}",
				);
				$n_data = $this->poke_github("issues/{$issue['number']}/comments", $data);
				if(!empty($n_data)) {
					$temp_file = ROOT_DIR.SL.'files'.SL.'tmp'.SL.'github_comments_'.$issue['number'].'.txt';
					if(file_exists($temp_file)) unlink($temp_file);
					header("Location: {$def['site']['dir']}/bugs/{$issue['number']}");
					exit();
				} else {
					$resp .= "<p class='error'>Возникла ошибка при добавлении комментария!</p>";
				}
			}
			$resp .= "
						<br /><hr /><h3>Новый комментарий:</h3>
						<table width='100%'><tr><td align='center' class='bug-comment-add'>
						<img src='".$def['site']['dir']."/images/ajax-loader.gif'>
						</td></tr></table>
					 ";
		}
		return $resp;
	}

	function _add() {
		global $def, $check;
		if(empty($def['db']['github_user']) || empty($def['db']['github_pass'])) {
			return "<p>Администратор отключил отправку репортов с сайта. <a href='https://github.com/{$def['db']['github_repo']}/issues/new'>Перейдите на github</a> для этого.</p>";
		}
		$resp = '';
		if(!empty(query::$post['title']) && !empty(query::$post['body'])) {
			$name = !empty(query::$post['name']) ? htmlspecialchars(query::$post['name']) : 'анонима';
			if(!empty(query::$post['mail']) && $check->email(query::$post['mail'], false)) {
				$name .= " (".query::$post['mail'].")";
			}
			$data = array(
				"title"	=> query::$post['title'],
				"body"	=> query::$post['body']."<br />-----<br />Репорт с сайта от {$name}",
			);
			$bug = $this->poke_github('issues', $data);
			if(!empty($bug['number'])) {
				$temp_file = ROOT_DIR.SL.'files'.SL.'tmp'.SL.'github_open.php';
				if(file_exists($temp_file)) unlink($temp_file);
				$temp_file = ROOT_DIR.SL.'files'.SL.'tmp'.SL.'github_closed.php';
				if(file_exists($temp_file)) unlink($temp_file);
				header("Location: {$def['site']['dir']}/bugs/{$bug['number']}");
				exit();
			} else {
				$resp .= "<p class='error'>Возникла ошибка при добавлении бага!</p>";
			}

		}
		$resp .= "
					<h2>Отправка нового бага</h2>
					<table width='100%'><tr><td align='center' class='bug-add'>
					<img src='".$def['site']['dir']."/images/ajax-loader.gif'>
					</td></tr></table>
				 ";
		return $resp;
	}

	function cmp($a, $b)
	{
		if ($a['number'] == $b['number']) {
			return 0;
		}
		return ($a['number'] > $b['number']) ? -1 : 1;
	}

	function get_data() {
		global $url, $def;
		$return = array();
		if(!empty($def['db']['github_tracker']) && $def['db']['github_tracker'] == '1') {
			$return['display'] = array('bugs_on');
			switch($url[2]) {
				case 'open': case 'closed':
					$issues = $this->get_issues($url[2]);
					$body .= $this->_tabs("{$def['site']['dir']}/bugs/%tab%", $this->tabs, $url[2]);
					$body .= $this->_pager("{$def['site']['dir']}/bugs/{$url[2]}/%page%", count($issues), intval($url[3]));
					$body .= $this->_list($issues, 10, intval($url[3])*10);
					$body .= $this->_pager("{$def['site']['dir']}/bugs/{$url[2]}/%page%", count($issues), intval($url[3]));
					break;
				case 'by_author':
					$issues = array_merge( $this->get_issues('open'), $this->get_issues('closed') );
					$body .= $this->_tabs("{$def['site']['dir']}/bugs/%tab%", $this->tabs, $url[2]);
					if(strlen($url[3]) > 0) {
						foreach($issues as $key=>$issue) {
							if($issue['user']['login'] != $url[3]) {
								unset($issues[$key]);
							}
						}
						$body .= $this->_pager("{$def['site']['dir']}/bugs/{$url[2]}/{$url[3]}/%page%", count($issues), intval($url[4]));
						$body .= $this->_list($issues, 10, intval($url[4])*10);
						$body .= $this->_pager("{$def['site']['dir']}/bugs/{$url[2]}/{$url[3]}/%page%", count($issues), intval($url[4]));
					} else {
						$authors = array();
						foreach($issues as $key=>$issue) {
							if(array_key_exists($issue['user']['login'], $authors)) {
								$authors[$issue['user']['login']] += 1;
						    } else {
								$authors[$issue['user']['login']] = 1;
							}
						}
						$body .= $this->_cloud("{$def['site']['dir']}/bugs/{$url[2]}/%key%", $authors);
					}
					break;
				case 'by_label':
					$issues = array_merge( $this->get_issues('open'), $this->get_issues('closed') );
					$body .= $this->_tabs("{$def['site']['dir']}/bugs/%tab%", $this->tabs, $url[2]);
					if(strlen($url[3]) > 0) {
						$url[3] = urldecode($url[3]);
						foreach($issues as $key=>$issue) {
							$issue_labels = array();
							foreach($issue['labels'] as $label) {
								$issue_labels[] = $label['name'];
							}
							if(!in_array($url[3], $issue_labels)) {
								unset($issues[$key]);
							}
						}
						$body .= $this->_pager("{$def['site']['dir']}/bugs/{$url[2]}/{$url[3]}/%page%", count($issues), intval($url[4]));
						$body .= $this->_list($issues, 10, intval($url[4])*10);
						$body .= $this->_pager("{$def['site']['dir']}/bugs/{$url[2]}/{$url[3]}/%page%", count($issues), intval($url[4]));
					} else {
						$labels = array();
						foreach($issues as $key=>$issue) {
							foreach($issue['labels'] as $label) {
								if(array_key_exists($label['name'], $labels)) {
									$labels[$label['name']] += 1;
								} else {
									$labels[$label['name']] = 1;
								}
							}
						}
						$body .= $this->_cloud("{$def['site']['dir']}/bugs/{$url[2]}/%key%", $labels);
					}
					break;
				case 'add':
					$body .= $this->_tabs("{$def['site']['dir']}/bugs/%tab%", $this->tabs, 'add');
					$body .= $this->_add();
					break;
				default:
					$issues = array_merge( $this->get_issues('open'), $this->get_issues('closed') );
					usort($issues, array($this,'cmp'));
					$numbers = array();
					foreach($issues as $key=>$issue) {
						$numbers[$issue['number']] = $key;
					}
					if(is_numeric($url[2]) && array_key_exists($url[2], $numbers)) {
						$body .= $this->_tabs("{$def['site']['dir']}/bugs/%tab%", $this->tabs, '');
						$body .= $this->_item($issues[$numbers[$url[2]]]);
					} else {
						$body .= $this->_tabs("{$def['site']['dir']}/bugs/%tab%", $this->tabs, 'all');
						$body .= $this->_pager("{$def['site']['dir']}/bugs/all/%page%", count($issues), intval($url[3]));
						$body .= $this->_list($issues, 10, intval($url[3])*10);
						$body .= $this->_pager("{$def['site']['dir']}/bugs/all/%page%", count($issues), intval($url[3]));
					}
					break;
			}
			$return['issues_raw'] = $body;
		} else {
			$return['display'] = array('bugs_off');
		}
		return $return;
	}

	public function _open() {
		$iss = $this->get_issues('open');

		return $return;
	}

}
