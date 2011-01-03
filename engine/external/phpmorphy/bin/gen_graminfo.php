#!/usr/bin/php
<?php
require_once(dirname(__FILE__) . '/../utils/autogen/graminfo/gen.php');

$graminfo_dir = dirname(__FILE__) . '/../src/graminfo/access';

try {
	generate_graminfo_files($graminfo_dir);
} catch (Exception $e) {
	echo 'ERROR: ' . $e->getMessage();
	exit(1);
}
