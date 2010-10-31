#!/usr/bin/php
<?php
if($argc < 3) {
	echo "Usage " . $argv[0] . " MORPH_DATA_FILE OUT_DIR";
	exit;
}

require_once(dirname(__FILE__) . '/../src/common.php');

function replace_keys_with_name($map) {
	$result = array();
	
	foreach($map as $item) {
		$result[$item['name']] = $item;
	}
	
	if(count($map) != count($result)) {
		throw new Exception("Map contains non unique names");
	}
	
	return $result;
}

function extract_gramtab($graminfoFile, $outDir, $asText) {
	$factory = new phpMorphy_Storage_Factory();
	$graminfo = phpMorphy_GramInfo::create($factory->open(PHPMORPHY_STORAGE_FILE, $graminfoFile, false), false);
	
	$poses = $graminfo->readAllPartOfSpeech();
	$grammems = $graminfo->readAllGrammems(); 
	$ancodes = $graminfo->readAllAncodes();
	
	if($asText) {
		foreach($ancodes as &$ancode) {
			$pos_id = $ancode['pos_id'];
			
			if(!isset($poses[$pos_id])) {
				throw new Exception("Unknown pos_id '$pos_id' found");
			}
			
			$ancode['pos_id'] = $poses[$pos_id]['name'];
			
			foreach($ancode['grammem_ids'] as &$grammem_id) {
				if(!isset($grammems[$grammem_id])) {
					throw new Exception("Unknown grammem_id '$grammem_id' found");
				}
				
				$grammem_id = $grammems[$grammem_id]['name'];
			}
		}
		
		//$poses = replace_keys_with_name($poses);
		//$grammems = replace_keys_with_name($grammems);
	}
	
	$result = array(
		'poses' => $poses,
		'grammems' => $grammems,
		'ancodes' => $ancodes
	);
	
	$type = $asText ? '_txt' : '';
	$out_file = 'gramtab' . $type . '.' . strtolower($graminfo->getLocale()) . '.bin';
	$out_file = $outDir . '/' . $out_file;
	
	if(false === file_put_contents($out_file, serialize($result))) {
		throw new Exception("Can`t write '$out_file'");
	}
}

$file = $argv[1];
$out_dir = $argv[2];

try {
	extract_gramtab($file, $out_dir, true);
	extract_gramtab($file, $out_dir, false);
} catch (Exception $e) {
	echo 'ERROR: ' . $e->getMessage();
	exit(1);
}
