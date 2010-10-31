#!/usr/bin/php
<?php
require_once(dirname(__FILE__) . '/../utils/autogen/gramtab/gen.php');

$gramtab_consts_file = dirname(__FILE__) . '/../src/gramtab_consts.php';

try {
	generate_gramtab_consts_file($gramtab_consts_file);
} catch (Exception $e) {
	echo 'ERROR: ' . $e->getMessage();
	exit(1);
}
