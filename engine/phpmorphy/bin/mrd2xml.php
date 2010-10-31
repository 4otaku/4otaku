#!/usr/bin/php
<?php
require_once(dirname(__FILE__) . '/../utils/dict_stuff/dict/source/mrd.php');
require_once(dirname(__FILE__) . '/../utils/dict_stuff/dict/writer/xml.php');

if($argc < 3) {
	echo "Usage " . $argv[0] . " MWZ_FILE OUT_DIR";
	exit;
}

$mwz_file = $argv[1];
$out_dir = $argv[2];

try {
	$source = new phpMorphy_Dict_Source_Mrd($mwz_file);
	$out = $out_dir . '/' . $source->getLanguage() . ".xml";
	
	$writer = new phpMorphy_Dict_Writer_Xml($out);
	$writer->write($source);
} catch (Exception $e) {
	echo 'ERROR: ' . $e->getMessage();
	exit(1);
}