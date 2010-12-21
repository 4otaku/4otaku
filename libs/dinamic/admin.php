<?

include_once('engine/engine.php');
class dinamic__admin extends engine
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
}
