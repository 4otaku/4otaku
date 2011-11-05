<?php

include '../inc.common.php';
include_once ENGINE.SL.'upload'.SL.'functions.php';

class Process_Pack
{
	protected static $imagetypes = array(
		'image/jpeg',
		'image/gif',
		'image/png'
	);

	protected $id = 0;
	protected $archive = '';
	protected $unpacked = '';

	protected $count = 0;

	public function __construct($gallery_id) {
		$this->id = $gallery_id;
		$this->archive = FILES.SL.'pack_uploaded'.SL.$this->id.'.zip';
		$this->unpacked = FILES.SL.'pack_uploaded'.SL.$this->id.SL;
	}

	public function unpack () {

		Database::update('misc',
			array('data1' => 'unpacking'),
			'type = ? and data2 = ?',
			array('pack_status', $this->id)
		);

		require ENGINE.SL.'extract'.SL.'ArchiveExtractor.class.php';
		$archExtractor = new ArchiveExtractor();

		if (!file_exists($this->archive)) {
			Database::update('misc',
				array('data1' => 'error'),
				'type = ? and data2 = ?',
				array('pack_status', $this->id)
			);
			return;
		}

		mkdir($this->unpacked);
		$archExtractor->extractArchive($this->archive, $this->unpacked);

		Database::update('misc',
			array('data1' => 'unpacked'),
			'type = ? and data2 = ?',
			array('pack_status', $this->id)
		);
	}

	public function get_images () {

		$this->parse_dir($this->unpacked);

		Database::update('misc',
			array(
				'data1' => 'processing',
				'data3' => 0,
				'data4' => $this->count
			),
			'type = ? and data2 = ?',
			array('pack_status', $this->id)
		);
	}

	public function parse_images () {
		global $imagick; global $image_class;

		$params = array('pack_art', $this->id);
		$art = Database::get_full_table('misc', 'type = ? and data1 = ? order by data4 limit '.rand(4,9), $params);

		$total = Database::get_field('misc', 'data4', 'type = ? and data2 = ?', array('pack_status', $this->id));
		$current_order = Database::get_field('art_in_pack', 'max(`order`)', 'pack_id = ?', $this->id);
		$next_order = (!is_numeric($current_order)) ? 0 : $current_order + 1;

		if (is_array($art)) {
			$insert_in_pack = array();
			foreach ($art as $one) {

				$file = $one['data2'];

				Database::delete('misc', 'type = ? and id = ?', array('pack_art', $one['id']));

				$md5 = md5_file($file);
				$sizefile = filesize($file);
				$resized = false;

				$exists = Database::get_row('art', array('id', 'area'), 'md5 = ?', $md5);
				if (!$exists) {

					$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$thumb=md5(microtime(true));
					$newname = $md5.'.'.$extension;
					$newfile = IMAGES.SL.'booru'.SL.'full'.SL.$newname;
					$newresized = IMAGES.SL.'booru'.SL.'resized'.SL.$md5.'.jpg';
					$newthumb = IMAGES.SL.'booru'.SL.'thumbs'.SL.$thumb.'.jpg';
					$newlargethumb = IMAGES.SL.'booru'.SL.'thumbs'.SL.'large_'.$thumb.'.jpg';
					chmod($file, 0755);

					if (!move_uploaded_file($file, $newfile)) {
						file_put_contents($newfile, file_get_contents($file));
					}

					$imagick = new $image_class($path = $newfile);
					$animated = 0;

					$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
					if ($imagick->getImageWidth() > def::art('resizewidth')*def::art('resizestep')) {
						if (scale(def::art('resizewidth'), $newresized, 95, false)) {
							$resized = $sizes;
						}
					} elseif ($sizefile > def::art('resizeweight')) {
						if (scale(false, $newresized, 95, false)) {
							$resized = $sizes;
						}
					}

					if (!empty($resized)) {
						if ($sizefile > 1024*1024) {
							$sizefile = round($sizefile/(1024*1024),1).' мб';
						} elseif ($sizefile > 1024) {
							$sizefile = round($sizefile/1024,1).' кб';
						} else {
							$sizefile = $sizefile.' б';
						}
						$resized .= 'px; '.$sizefile;
					}

					scale(def::art('largethumbsize'), $newlargethumb);
					scale(def::art('thumbsize'), $newthumb);

					Database::insert('art',array(
						'md5' => $md5,
						'thumb' => $thumb,
						'extension' => $extension,
						'resized' => $resized,
						'animated' => $animated,
						'author' => '|',
						'category' => '|nsfw|game_cg|',
						'tag' => '|prostavte_tegi|',
						'pretty_date' => Transform_Time::ru_date(),
						'sortdate' => ceil(microtime(true)*1000),
						'area' => 'cg'
					));

					$art_id = Database::last_id();
				} else {
					$art_id = $exists['id'];

					if ($exists['area'] == 'deleted') {
						Database::update('art', array('area' => 'cg'), $exists['id']);
					}
				}

				$insert_in_pack[] = array(
					$art_id, $this->id,
					$next_order, pathinfo($file,PATHINFO_BASENAME)
				);

				$next_order++;
			}

			Database::bulk_insert('art_in_pack', $insert_in_pack,
				array('art_id','pack_id','order','filename'));
		}

		if ($count = Database::get_count('misc', 'type = ? and data1 = ?', array('pack_art', $this->id))) {

			Database::update('misc',
				array('data3' => $total - $count),
				'type = ? and data2 = ?',
				array('pack_status', $this->id));
		} else {

			Database::update('misc',
				array('data1' => 'done'),
				'type = ? and data2 = ?',
				array('pack_status', $this->id));

			Database::update('art_pack', array('weight' => 0), $this->id);
		}
	}

	protected function parse_dir ($directory) {

		$handle = dir($directory);
		$insert = array();
		while ($entry = $handle->read()) {

			if ($entry[0] == ".") {
				continue;
			}

			$filename = $entry;
			$entry = $directory.$entry;
			if (is_dir($entry)) {

				$this->parse_dir($entry.SL);
			} elseif(in_array(mime_content_type($entry), self::$imagetypes)) {

				$this->count++;
				$sort = str_repeat("0", 5 - strlen($this->count)).$this->count;
				$insert[] = array('pack_art', $this->id, $entry, $sort, $filename, 0);
			}
		}
		$handle->close();
		Database::bulk_insert('misc', $insert);
	}
}

$status = Database::get_full_row(
	'misc',
	'type = ? and data1 != ? and data1 != ?',
	array('pack_status', 'done', 'error')
);

if (!empty($status)) {
	$worker = new Process_Pack($status['data2']);

	switch ($status['data1']) {
		case 'starting':
		case 'unpacking':
			$worker->unpack();
			break;
		case 'unpacked':
			$worker->get_images();
			break;
		case 'processing':
			$worker->parse_images();
			break;
		default:
			break;
	}
}
