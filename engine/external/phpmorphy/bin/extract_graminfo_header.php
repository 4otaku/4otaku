#!/usr/bin/php
<?php
if($argc < 3) {
    echo "Usage " . $argv[0] . " MORPH_DATA_FILE OUT_DIR";
    exit;
}

require_once(dirname(__FILE__) . '/../src/common.php');

$file = $argv[1];
$out_dir = $argv[2];

try {
    $factory = new phpMorphy_Storage_Factory();
    $graminfo = phpMorphy_GramInfo::create($factory->open(PHPMORPHY_STORAGE_FILE, $file, false), false);
    
    $out_file = $out_dir . '/morph_data_header_cache.' . strtolower($graminfo->getLocale()) . '.bin';
    
    file_put_contents(
        $out_file,
        '<' . "?php\nreturn " .
            var_export(
                $graminfo->getHeader(),
                true
            ) .
        ";\n"
    );
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
    exit(1);
}
