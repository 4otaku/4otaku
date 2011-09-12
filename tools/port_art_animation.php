<?
die;
include '../inc.common.php';
include '../engine/upload/functions.php';

$arts = obj::db()->sql('select id, md5 from art where extension = "gif" and animated=0', 'id');

var_dump(count($arts));

foreach ($arts as $id => $art) {

	$image = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$art.'.gif';
	$sizefile = filesize($image);
		
	$imagick =  new $image_class($path = $image);
	$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
	
	$update = false;
	
	try {
		if ($imagick->getImageWidth() > $def['booru']['resizewidth']*$def['booru']['resizestep']) {
			
			scale($def['booru']['resizewidth'],ROOT_DIR.SL.'images/booru/resized/'.$art.'.jpg',95,false);
			$update = true;
		} elseif ($sizefile > $def['booru']['resizeweight']) {
			scale(ceil($imagick->getImageWidth()/2),ROOT_DIR.SL.'images/booru/resized/'.$art.'.jpg',95,false);
			$update = true;
		}	

		if ($update) {
			obj::db()->update('art',array('animated'),array(1),$id);
		}
	
	} catch (Exception $e) {}
	echo ".";
}

