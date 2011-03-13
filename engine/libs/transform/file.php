<?

class Transform_File
{
	function weight($filesize) {
		$filesize = (int) $filesize;
		if ($filesize > 1024*1024) {
			return round($filesize/(1024*1024),1).' мегабайт';
		} elseif ($filesize > 1024) {
			return round($filesize/1024,1).' килобайт';
		} else {
			return $filesize.' байт';
		}			
	}
}
