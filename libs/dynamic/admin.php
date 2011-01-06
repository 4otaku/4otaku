<?

include_once('engine/engine.php');
class dynamic__admin extends engine
{
	function delete_tag() {
		global $get; global $check;
		$check->rights();
		obj::db()->sql('delete from tag where id='.$get['id'],0);
		obj::db()->sql('update post set tag = replace(tag,"|'.$get['old_alias'].'|","|")',0);
		obj::db()->sql('update video set tag = replace(tag,"|'.$get['old_alias'].'|","|")',0);
		obj::db()->sql('update art set tag = replace(tag,"|'.$get['old_alias'].'|","|")',0);			
	}
	
	function color_tag() {
		global $get; global $check;
		$check->rights();
		obj::db()->update('tag','color',$get['color'],urldecode($get['tag']),'name');
		$this->delete_color_tag();
	}
		
	function delete_color_tag() {
		global $get; global $check;
		$check->rights();
		obj::db()->sql('delete from misc where id='.$get['id'],0);		
	}	
	
	function dinamic_tag() {
		global $get; global $check;
		$check->rights();
		$return['current'] = max(1, $get['current']);
		list($return['tags'], $return['page_count']) = output__admin::search_tags($get['query'],$return['current'],10);
		return $return;
	}
	
	function merge_tag() {
		global $get; global $check;
		$check->rights();
		
		if ($get['master'] != $get['slave']) {
			$master = obj::db()->sql('select * from tag where id='.$get['master'],1);
			$slave = obj::db()->sql('select * from tag where id='.$get['slave'],1);
			
			$old_count = $master['post_main'] + $master['post_flea_market'] + $master['video_main'] + $master['video_flea_market'] + $master['art_main'] + $master['art_flea_market'];
			
			obj::db()->sql('update post set tag = if (tag like "%|'.$master['alias'].'|%", replace(tag,"|'.$slave['alias'].'|","|"), replace(tag,"|'.$slave['alias'].'|","|'.$master['alias'].'|"))',0);
			obj::db()->sql('update video set tag = if (tag like "%|'.$master['alias'].'|%", replace(tag,"|'.$slave['alias'].'|","|"), replace(tag,"|'.$slave['alias'].'|","|'.$master['alias'].'|"))',0);
			obj::db()->sql('update art set tag = if (tag like "%|'.$master['alias'].'|%", replace(tag,"|'.$slave['alias'].'|","|"), replace(tag,"|'.$slave['alias'].'|","|'.$master['alias'].'|"))',0);
			
			$variants = explode('|',$master['variants'].$slave['variants']);
			if ($slave['name'] != $master['name']) $variants[] = $slave['name'];		
			
			$update = array(
				'post_main' => obj::db()->sql('select count(*) from post where locate("|'.$master['alias'].'|",tag) and area="main"',2),
				'post_flea_market' => obj::db()->sql('select count(*) from post where locate("|'.$master['alias'].'|",tag) and area="flea_market"',2),
				'video_main' => obj::db()->sql('select count(*) from video where locate("|'.$master['alias'].'|",tag) and area="main"',2),
				'video_flea_market' => obj::db()->sql('select count(*) from video where locate("|'.$master['alias'].'|",tag) and area="flea_market"',2),
				'art_main' => obj::db()->sql('select count(*) from art where locate("|'.$master['alias'].'|",tag) and area="main"',2),
				'art_flea_market' => obj::db()->sql('select count(*) from art where locate("|'.$master['alias'].'|",tag) and area="flea_market"',2),
				'variants' => '|'.implode('|',array_unique(array_filter($variants))).'|',
				'color' => empty($master['color']) ? $slave['color'] : $master['color']
			);
			
			obj::db()->update('tag',array_keys($update),array_values($update),$get['master']);
			obj::db()->sql('delete from tag where id='.$get['slave'],0);
			
			$add_count = array_sum(array_slice($update,0,6)) - $old_count;
			
			engine::add_res("Тег {$slave['name']} успешно влит в {$master['name']}. Счет {$master['name']} подрос на $add_count нахождений.", false, true);
		}
	}
}
