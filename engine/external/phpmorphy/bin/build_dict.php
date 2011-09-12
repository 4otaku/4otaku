#!/usr/bin/php
<?php
if($argc < 4) {
    echo "Usage " . $argv[0] . " XML_FILE OUT_DIR ENCODING";
    exit;
}

define('BIN_DIR', dirname(__FILE__));
define('MORPHY_DIR', getenv('MORPHY_DIR'));
define('MORPHY_BUILDER', MORPHY_DIR . '/bin/morphy_builder');
define('PHP_BIN', getenv('PHPRC') . '/php');

function doError($msg) {
	echo $msg;
	exit(1);
}

function doExec($title, $file, $args) {
	echo $title . "\n";
	
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	
	$cmd = '';
	switch(strtolower($ext)) {
		case 'php':
            		$cmd = PHP_BIN . ' -f ' . escapeshellarg($file) . ' --';
            		break;
		default:
			$cmd = escapeshellarg($file);
			
	}
	
	foreach($args as $k => $v) {
		if(is_null($v)) {
			if(is_string($k)) {
				$cmd .= ' ' . $k;
			}
		} else {
			if(is_string($k)) {
				$cmd .= ' ' . $k . '=' . escapeshellarg($v);
			} else {
				$cmd .= ' ' . escapeshellarg($v);
			}
		}
	}
	
	$desc = array(
		1 => array("pipe", "w"),  // stdout
		2 => array("pipe", "w") // stderr
	);
	
	$opts = array(
		'binary_pipes' => true,
		'bypass_shell' => true
	);
	
	$pipes = array();
	
	if(false === ($handle = proc_open($cmd, $desc, $pipes, null, null, $opts))) {
		doError('Can`t execute \'' . $cmd . '\' command');
	}
	
	if(1) {
		while(!feof($pipes[1])) {
			fputs(STDOUT, fgets($pipes[1]));
		}
	} else {
		stream_copy_to_stream($pipes[1], STDOUT);
	}
	
	$stderr = trim(stream_get_contents($pipes[2]));
	
	fclose($pipes[1]);
	fclose($pipes[2]);
	$errorcode = proc_close($handle);
	
	if($errorcode) {
		doError(
			"\n\nCommand '" . $cmd .'\' exit with code = ' . $errorcode . ', error = \'' . $stderr . '\''
		);
	}
	
	echo "OK.\n";
}

function get_locale($xml) {
	$reader = new XMLReader();
	if(false === $reader->open($xml)) {
		return false;
	}
	
	while($reader->read()) {
		if($reader->nodeType == XMLReader::ELEMENT) {
			if($reader->localName === 'locale') {
				$result = $reader->getAttribute('name');
				
				$result = strlen($result) ? $result : false;
				break;
			}
		}
	}
	
	$reader->close();
	
	return $result;
}

if(false === ($locale = get_locale($argv[1]))) {
	doError("Can`t retrieve locale name from '" . $argv[1] . "' file");
}

$morph_data_file = $argv[2] . '/morph_data.' . strtolower($locale) . '.bin';

echo "Found '$locale' locale in $argv[1]\n";

doExec(
	'Build dictionary',
	MORPHY_BUILDER,
	array(
		'--xml' => $argv[1],
		'--out-dir' => $argv[2],
		'--out-encoding' => $argv[3],
		'--force-encoding-single-byte' => null,
//		'--with-form-no' => 'no',
		'--verbose' => null,
		'--case' => 'upper',
	)
);

doExec('Extract gramtab', BIN_DIR . '/extract_gramtab.php', array($morph_data_file, $argv[2]));
doExec('Extract graminfo header', BIN_DIR . '/extract_graminfo_header.php', array($morph_data_file, $argv[2]));
