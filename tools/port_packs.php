<?
die;
$start = $_GET['start'];
include '../inc.common.php';
include '../engine/upload/functions.php';
set_time_limit(0);
$arts = obj::db()->sql('select a.id, a.md5, a.extension from art as a left join art_in_pack as p on a.id = p.art_id where p.art_id > 0 limit '.((int) $start).', 10', 'id');

foreach ($arts as $id => $art) {
	$mainfile = ROOT_DIR.SL.'images/booru/full/'.$art['md5'].'.'.$art['extension'];
	if (!file_exists($mainfile)) {
		var_dump($id);
	} else {
		$thumb = ROOT_DIR.SL.'images/booru/thumbs/'.$art['md5'].'.jpg';
		$large_thumb = ROOT_DIR.SL.'images/booru/thumbs/large_'.$art['md5'].'.jpg';
		
		@unlink($thumb);
		@unlink($large_thumb);
		
		$imagick =  new $image_class($path = $mainfile);	
		scale($def['booru']['largethumbsize'],$large_thumb);
		
		$imagick =  new $image_class($path = $mainfile);
		scale($def['booru']['thumbsize'],$thumb);
	}
}
if (!empty($arts)) {
	echo '<meta http-equiv="refresh" content="1; url=http://4otaku.ru/tools/port_packs.php?start='.($start+10).'">';
	var_dump($arts);
}
die($start);

$arts = obj::db()->sql('select a.id, a.md5 from art as a left join art_in_pack as p on a.id = p.art_id where p.art_id > 0', 'id');

foreach ($arts as $id => $md5) {
	$filename = obj::db('sub')->sql('select filename from w8m_art where md5="'.$md5.'"', 2);
	obj::db()->update('art_in_pack', 'filename', $filename, $id, 'art_id');
}


die;
$w8m_packs = obj::db('sub')->sql('select * from w8m_galleries');
	
foreach ($w8m_packs as $pack) {
	$count = 0;
	
	$arts = obj::db('sub')->sql('select * from w8m_art where gallery_id='.$pack['id'].' order by folder, filename desc');
	
	if ($pack['id'] < 159 || $pack['id'] > 201) {
		$arts = array_reverse($arts);
	}
	
	$date = strtotime($pack['date']);
	
	foreach ($arts as $art) {
			
		if ($row = obj::db()->sql('select * from art where md5 = "'.$art['md5'].'"', 1)) {
			
			if (($row['area'] != 'main' && $row['area'] != 'flea_market') || $row['comment_count'] == 0) {
				
				obj::db()->update('art', array('area'), array('cg'), $row['id']);
				$art_id = $row['id'];
			} else {
				continue;
			}
		} else {
			$mainfile = '../images/booru/full/'.$art['md5'].'.'.$art['ext'];
			$resfile = '../images/booru/resized/'.$art['md5'].'.jpg';
			
			if (@filesize($mainfile) < 100) {
				@unlink($mainfile);
				@unlink('../images/booru/thumbs/'.$art['md5'].'.jpg');
				@unlink('../images/booru/thumbs/large_'.$art['md5'].'.jpg');
			}
			
			if (!file_exists($mainfile)) {					
				$image = @file_get_contents("/var/www/nameless/data/www/w8m.4otaku.ru/image/{$pack['md5']}/full/{$art['md5']}.{$art['ext']}");
				if (empty($image)) continue;
				$thumb = @file_get_contents("/var/www/nameless/data/www/w8m.4otaku.ru/image/{$pack['md5']}/thumb/{$art['md5']}.jpg");
				$large_thumb = @file_get_contents("/var/www/nameless/data/www/w8m.4otaku.ru/image/{$pack['md5']}/large/{$art['md5']}.jpg");
				
				file_put_contents($mainfile,$image);			
				file_put_contents('../images/booru/thumbs/'.$art['md5'].'.jpg',$thumb);
				file_put_contents('../images/booru/thumbs/large_'.$art['md5'].'.jpg',$large_thumb);
			}
			
			if (!file_exists($resfile)) {
				$resized = @file_get_contents("/var/www/nameless/data/www/w8m.4otaku.ru/image/{$pack['md5']}/resized/{$art['md5']}.jpg");
				if ($resized) file_put_contents($resfile,$resized);
			}
			
			$sizes = getimagesize($mainfile);
			$resizes = file_exists($resfile) ? getimagesize($resfile) : 0;	
			$sizefile = filesize($mainfile);	
			
			if (!empty($resizes)) {					
				if ($sizefile > 1024*1024) {
					$sizefile = round($sizefile/(1024*1024),1).' мб';
				} elseif ($sizefile > 1024) {
					$sizefile = round($sizefile/1024,1).' кб';
				} else {
					$sizefile = $sizefile.' б';
				}
				$resizes = "$sizes[0]x$sizes[1]px; $sizefile";
			}
			
			obj::db()->insert('art',array(
				$art['md5'],
				$art['md5'],
				$art['ext'],
				($resizes ? $resizes: ''),
				0,
				'|',
				'|nsfw|game_cg|',
				'|tagme|',
				'|',
				0,
				'',
				'',
				$art['comment_count'],
				$art['last_comment'],
				obj::transform('text')->rumonth(date('m', $date)).date(' j, Y', $date),
				$date*1000 + $count,
				'cg'
			));

			$art_id = obj::db()->sql('select @@identity from art',2);
		}
		
		obj::db()->insert('art_in_pack',array($art_id, $pack['id'], $count),false);
		$count++;		
	}

	obj::db()->insert('art_pack',array(
		$pack['id'],
		$pack['name'].'.zip',
		$pack['filesize'],
		$pack['image'],
		$pack['name'],
		$pack['text'],
		$pack['pretty_text'],
		0,
		'date' => date ("Y-m-d H:i:s",$date),
	),false);
	
	var_dump($count);
}
	
