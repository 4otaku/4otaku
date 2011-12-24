<?

class dynamic__admin extends engine
{
	public function __construct() {
		// Thou shall not pass
		Check::rights();
	}

	public function tag_form () {
		$id = (int) query::$get['id'];
		$return = Database::get_full_row('tag', $id);

		return $return;
	}

/*
	public function edittag () {
		if (query::$get['old_alias'] != query::$get['alias']) {
			obj::db()->sql('update post set tag = replace(tag,"|'.query::$get['old_alias'].'|","|'.query::$get['alias'].'|")',0);
			obj::db()->sql('update video set tag = replace(tag,"|'.query::$get['old_alias'].'|","|'.query::$get['alias'].'|")',0);
			obj::db()->sql('update art set tag = replace(tag,"|'.query::$get['old_alias'].'|","|'.query::$get['alias'].'|")',0);
		}
		$variants = array_unique(array_filter(explode(' ',str_replace(',',' ',query::$get['variants']))));
		if (!empty($variants))
			$variants = '|'.implode('|',$variants).'|'; else $variants = '|';
		obj::db()->update('tag',array('alias','name','variants','color'),array(query::$get['alias'],query::$get['name'],$variants,query::$get['color']),query::$get['id']);

		return 'Сохранено!';
	}
*/

	public function delete_tag () {
		if (empty(query::$get['id']) || !is_numeric(query::$get['id'])) {
			return;
		}

		Database::delete('tag', query::$get['id']);

		Database::sql('update post set tag = replace (tag, ?, "|")',
			'|'.query::$get['old_alias'].'|');

		Database::sql('update video set tag = replace (tag, ?, "|")',
			'|'.query::$get['old_alias'].'|');

		Database::sql('update art set tag = replace (tag, ?, "|")',
			'|'.query::$get['old_alias'].'|');
	}

	public function color_tag() {
		Database::update('tag',
			array('color' => query::$get['color']),
			'name = ?',
			urldecode(query::$get['tag'])
		);

		$this->delete_color_tag();
	}

	public function delete_color_tag() {
		Database::delete('misc', query::$get['id']);
	}

	public function dinamic_tag() {
		$return = array('current' => max(1, query::$get['current']));
		list($return['tags'], $return['page_count']) =
			output__admin::search_tags(query::$get['query'],$return['current'],10);
		return $return;
	}

	public function merge_tag() {

		if (
			empty(query::$get['master']) || !is_numeric(query::$get['master']) ||
			empty(query::$get['slave']) || !is_numeric(query::$get['slave'])
		) {
			return;
		}

		if (query::$get['master'] != query::$get['slave']) {
			$master = Database::get_full_row('tag', query::$get['master']);
			$slave = Database::get_full_row('tag', query::$get['slave']);

			$old_count = $master['post_main'] +
				$master['post_flea_market'] +
				$master['video_main'] +
				$master['video_flea_market'] +
				$master['art_main'] +
				$master['art_flea_market'];

			$params = array(
				'%|'.$master['alias'].'|%',
				'|'.$slave['alias'].'|',
				'|'.$slave['alias'].'|',
				'|'.$master['alias'].'|',
			);

			Database::sql('update post set tag = if (tag like ?,
				replace(tag, ?, "|"), replace(tag, ?, ?))', $params);
			Database::sql('update video set tag = if (tag like ?,
				replace(tag, ?, "|"), replace(tag, ?, ?))', $params);
			Database::sql('update art set tag = if (tag like ?,
				replace(tag, ?, "|"), replace(tag, ?, ?))', $params);

			$variants = explode('|', $master['variants'].$slave['variants']);
			if ($slave['name'] != $master['name']) {
				$variants[] = $slave['name'];
			}

			$params_main = array('|'.$master['alias'].'|', 'main');
			$params_flea = array('|'.$master['alias'].'|', 'flea_market');

			$update = array(
				'post_main' => Database::get_count('post', 'locate(?, tag) and area = ?', $params_main),
				'post_flea_market' => Database::get_count('post', 'locate(?, tag) and area = ?', $params_flea),
				'video_main' => Database::get_count('video', 'locate(?, tag) and area = ?', $params_main),
				'video_flea_market' => Database::get_count('video', 'locate(?, tag) and area = ?', $params_flea),
				'art_main' => Database::get_count('art', 'locate(?, tag) and area = ?', $params_main),
				'art_flea_market' => Database::get_count('art', 'locate(?, tag) and area = ?', $params_flea),
				'variants' => '|'.implode('|',array_unique(array_filter($variants))).'|',
				'color' => empty($master['color']) ? $slave['color'] : $master['color']
			);

			Database::update('tag', $update, query::$get['master']);
			Database::delete('tag', query::$get['slave']);

			$add_count = array_sum(array_slice($update,0,6)) - $old_count;

			engine::add_res("Тег {$slave['name']} успешно влит в {$master['name']}. ".
				"Счет {$master['name']} подрос на $add_count нахождений.", false, true);
		}
	}
}
