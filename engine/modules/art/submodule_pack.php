<?

class Art_Submodule_Pack extends Art_Submodule_Group implements Plugins, Downloadable
{
	const PACK_FILE_SIZE_PREFIX = '_size_packfile_id_';
	
	protected $type = 'cg_pack';
	
	public static function description ($query) {

		$pack_id = (int) $query['alias'];
		
		$pack = Database::get_row('art_cg_pack', array('title', 'text'), $pack_id);

		list($weight, $weight_type) = self::get_pack_weight($pack_id);
		
		return array(
			'text' => $pack['text'], 
			'name' => $pack['title'], 
			'type' => 'pack', 
			'weight' => $weight, 
			'weight_type' => $weight_type, 
			'id' => $pack_id
		);			
	}	
		
	public static function get_pack_weight ($pack_id) {
		Cache::$prefix = self::PACK_FILE_SIZE_PREFIX;
		
		if (!($size = Cache::get($pack_id))) {
			
			$filename = self::get_pack_filename($pack_id, true);
			
			if (!file_exists($filename)) {
				self::create_pack_file($pack_id);
			}
			
			$size = filesize($filename);
			
			Cache::set($pack_id, $size, MONTH);
		}
		
		return Transform_String::round_bytes($size);
	}
	
	public function get_download_file ($query) {
		$pack_id = $query['alias'];

		return self::get_pack_filename($pack_id, true);
	}
	
	public function get_download_name ($query) {

		$pack_id = (int) $query['alias'];
		
		return Database::get_field('art_cg_pack', 'filename', $pack_id);
	}
	
	public static function get_pack_filename ($pack_id, $joined = false) {
		$filename = $pack_id.'.zip';
		$dirname = FILES.SL.'art'.SL.'cg_packs'.SL;
		
		if ($joined) {
			return $dirname.$filename;
		}
		
		return array($dirname, $filename);
	}
	
	public static function create_pack_file ($pack_id) {
		$image_dir = IMAGES.SL.'art'.SL.'full'.SL;
		
		$search = array('+', $pack_id, 'cg_pack');
		$condition = Database::make_search_condition('meta', array($search));
		
		$arts = Database::get_table('art', array('id', 'md5','extension','meta'), $condition);

		if (empty($arts)) {
			Error::warning("Серъезная ошибка в данных CG-пака № $pack_id");
			return;
		}
		
		list($dirname, $filename) = self::get_pack_filename($pack_id);

		if (!is_writable($dirname)) {
			Error::warning("Не хватает прав доступа для создания архива");
			return;
		}	
		
		$zip = new ZipArchive();
		if ($zip->open($dirname.$filename, ZipArchive::CREATE) === true) {
			foreach ($arts as $art) {
				
				$art_file = $image_dir.$art['md5'].'.'.$art['extension'];
				
				if (preg_match('/\sfilename__([^\s]+)/is', $art['meta'], $name)) {
					$art_filename = $name[1];
				} else {
					$art_filename = $art['id'].'.'.$art['extension'];
				}

				if (file_exists($art_file)) {
					$zip->addFile($art_file, $art_filename);
				}
				
			}
			$zip->close();
		}
		
		return $filename;
	}
}
