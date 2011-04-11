<?

class Art_Input extends Module_Output implements Plugins
{
	public static function create_pack_file ($pack_id) {
		$image_dir = IMAGES.SL.'art'.SL.'full'.SL;
		
		$search = array('+', $pack_id, 'cg_pack');
		$condition = Objects::db()->make_search_condition('meta', array($search));
		
		$arts = Objects::db()->get_table('art', array('id', 'md5','extension','meta'), $condition);

		if (empty($arts)) {
			Error::warning("Серъезная ошибка в данных CG-пака № $pack_id");
			return;
		}
		
		$filename = $pack_id.'.zip';
		$dirname = FILES.SL.'art'.SL.'cg_packs'.SL;

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
