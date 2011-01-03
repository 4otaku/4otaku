#!/usr/bin/php
<?php
require_once(dirname(__FILE__) . '/../utils/autogen/fsa/gen.php');

$fsa_dir = dirname(__FILE__) . '/../src/fsa/access';

try {
	generate_fsa_files($fsa_dir);
} catch (Exception $e) {
	echo 'ERROR: ' . $e->getMessage();
	exit(1);
}
