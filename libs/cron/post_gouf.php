<?php

class Cron_Post_Gouf extends Cron_Abstract
{
	const LINKS_PER_CHECK = 50;
	const MAX_DOWNLOAD_SIZE = 1000000;

	const
		STATUS_WORKS = 1,
		STATUS_UNKNOWN = 2,
		STATUS_BROKEN = 3;

	protected $alias = array();
	protected $works = array();
	protected $broken = array();

	protected $debug_mode = false;

	protected $worker;

	public function __construct() {
		$rules = Database::get_full_vector('post_url_rule');

		foreach ($rules as $rule) {
			switch ($rule['type']) {
				case 'alias':
					$this->alias[$rule['domain']] = $rule['value'];
					break;
				case 'works':
					if (empty($this->works[$rule['domain']])) {
						$this->works[$rule['domain']] = array($rule['value']);
					} else {
						$this->works[$rule['domain']][] = $rule['value'];
					}
					break;
				case 'broken':
					if (empty($this->broken[$rule['domain']])) {
						$this->broken[$rule['domain']] = array($rule['value']);
					} else {
						$this->broken[$rule['domain']][] = $rule['value'];
					}
					break;
				default:
					break;
			}
		}

		$this->worker = new Http(array(
			CURLOPT_FOLLOWLOCATION => true
		));
	}

	public function check() {
		$links = Database::order('lastcheck', 'ASC')
			->limit(self::LINKS_PER_CHECK)
			->get_vector('post_url', array('id', 'url'));

		foreach ($links as &$link) {
			$link = html_entity_decode($link, ENT_QUOTES, 'UTF-8');
			$link = str_replace('&apos;', "'", $link);
			$link = trim($link);
		}

		$this->test_links($links);
	}

	public function test($link) {
		$this->debug_mode = true;

		$this->worker->set_debug();

		$this->test_links((array) $link);
	}

	protected function test_links($links) {

		$this->worker->enable_limit(self::MAX_DOWNLOAD_SIZE)
			->add($links)->exec();

		foreach ($links as $id => $link) {
			$status = $this->test_result($link);
			Database::update('post_url', array('status' => $status,
				'lastcheck' => Database::unix_to_date()), $id);
			if ($status == self::STATUS_UNKNOWN) {
				$this->create_unknown_file($link);
			}
		}

		$this->worker->flush();

		$keys = array_keys($links);

		$post_ids = Database::join('post_link_url', 'plu.link_id = pl.id')
			->join('post_url', 'plu.url_id = pu.id')->get_vector('post_link',
				'post_id', Database::array_in('pu.id', $keys), $keys);
		$post_ids = array_unique($post_ids);

		foreach ($post_ids as $post_id) {
			$status = new Model_Post_Status($post_id);
			$status->load()->calculate()->commit();
		}

		$update_ids = Database::join('post_update_link_url', 'pulu.link_id = pul.id')
			->join('post_url', 'pulu.url_id = pu.id')->get_vector('post_update_link',
				'update_id', Database::array_in('pu.id', $keys), $keys);

		$update_ids = array_unique($update_ids);
		foreach ($update_ids as $update_id) {
			$status = new Model_Post_Update_Status($update_id);
			$status->load()->calculate()->commit();
		}
	}

	protected function test_result($link) {
		$domain = $this->get_domain($link);
		$html = $this->worker->get_full($link);

		$this->echo_debug('link', $link);
		$this->echo_debug('domain', $domain);
		$this->echo_debug('html', strlen($html));

		if (empty($domain)) {
			$this->echo_debug('domain empty');
			return self::STATUS_BROKEN;
		}

		if (!empty($this->works[$domain])) {
			$works = $this->works[$domain];
			foreach ($works as $test) {
				if (strpos($test, '\\') === 0) {
					$this->echo_debug('test working regex', $test);
					if (preg_match('/'.$test.'/u', $html)) {
						$this->echo_debug('works');
						return self::STATUS_WORKS;
					}
				} else {
					$this->echo_debug('test working string', $test);
					if (strpos($html, $test)) {
						$this->echo_debug('works');
						return self::STATUS_WORKS;
					}
				}
			}
		}

		if (!empty($this->broken[$domain])) {
			$broken = $this->broken[$domain];
			foreach ($broken as $test) {
				if (strpos($test, '\\') === 0) {
					$this->echo_debug('test broken string', $test);
					if (preg_match('/'.$test.'/u', $html)) {
						$this->echo_debug('broken');
						return self::STATUS_BROKEN;
					}
				} else {
					$this->echo_debug('test broken string', $test);
					if (strpos($html, $test)) {
						$this->echo_debug('broken');
						return self::STATUS_BROKEN;
					}
				}
			}
		}

		$this->echo_debug('unknown');
		return self::STATUS_UNKNOWN;
	}

	protected function create_unknown_file($link) {
		$domain = $this->get_domain($link);
		$html = $this->worker->get_full($link);

		$directory = FILES . SL . 'gouf' . SL . $domain . SL;
		if (!is_dir($directory)) {
			mkdir($directory, 0777);
		}
		$file = $directory . time() . '_' .
			substr(md5(microtime()), 0, 6) . '.html';

		$data = "$domain\n\n$link\n\n$html";

		file_put_contents($file, $data);
		chmod($file, 0777);
	}

	protected function get_domain($link) {

		$domain = parse_url($link, PHP_URL_HOST);

		if (!empty($domain)) {
			preg_match('/^(?:www\.)?([^:]+)/', $domain, $domain);
			$domain = $domain[1];

			$i = 0;
			while (array_key_exists($domain, $this->alias) && ++$i < 100) {
				$domain = $this->alias[$domain];
			}
		}

		return $domain;
	}

	protected function echo_debug($header, $message = '') {
		if (!$this->debug_mode) {
			return;
		}

		if ($message) {
			$message = ': ' . $message;
		}
		echo '<br />' . $header . $message;
	}
}
