<?

if (!function_exists('exif_imagetype')) {
    function exif_imagetype($filename) {
        if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
            return $type;
        }
		return false;
    }
}

class Imagick {

	private $path;
	private $data;
	private $width;
	private $height;
	private $format;
	private $compression = 80;
	
	const COMPOSITE_OVER = 'over';
	const COMPRESSION_JPEG = 'jpeg';
	
	function __construct ($file) {
		$this->path = $file;
		$sizes = getimagesize($this->path);
		$this->width = $sizes[0];
		$this->height = $sizes[1];
		$format = explode('/', image_type_to_mime_type(exif_imagetype($this->path)));
		$this->format = $format[1];
		switch ($this->format) {
			case 'png': $this->data = imagecreatefrompng($this->path); break;
			case 'jpeg': $this->data = imagecreatefromjpeg($this->path); break;
			case 'gif': $this->data = imagecreatefromgif($this->path); break;
			default: echo 'error-filetype'; die;
		}		
	}
	
	function __call($name, $arguments) {
		if($name == 'clone') return $this;
		else return false;
	}
	
	function getImageWidth() {		
		return $this->width;
	}
	
	function getImageHeight() {
		return $this->height;
	}
	
	function getImageFormat() {		
		return $this->format;
	}
	
	function setImageCompressionQuality($compression) {
		$this->compression = $compression;
	}
	
	function thumbnailImage($x, $y) {
		$thumbnail = imagecreatetruecolor($x, $y);
		imagecopyresampled($thumbnail,$this->data,0,0,0,0,$x,$y,$this->width,$this->height);
		$this->data = $thumbnail;
	}
	
	function scaleImage($x, $y) {
		$this->thumbnailImage($x, $y);
	}
	
	function colorFloodFillImage($fill, $fuzz, $bordercolor, $x, $y) {
		list($r, $g, $b) = str_split(substr($fill, 1),2);
		$color = imagecolorallocate(
					$this->data, 
					'0x' . strtoupper($r), 
					'0x' . strtoupper($g), 
					'0x' . strtoupper($b)
				);
		imagefill($this->data, $x, $y, $color);
	}
	
	function compositeImage($image, $type, $x, $y) {
		switch ($type) {
			case 'over':
				imagecopymerge(
					$this->data, 
					$image->data, 
					$this->width, 
					$this->height, 
					$x, 
					$y, 
					$image->width, 
					$image->height, 
					100
				);
				break;
			default: echo 'error-unexpected'; die;
		}
	}
	
	function setImageCompression($type) {
		$this->format = $type;
	}
	
	function setImageFormat($type) {
		$this->format = $type;		
	}
	
	function writeImage($path) {
		$function = 'image'.$this->format;
		$compression = $this->format == 'png' ? floor(($this->compression - 1) / 10) : $this->compression;
		$function($this->data, $path, $compression);
	}
	
	function coalesceImages() {
		return array($this);
	}	
	
	function clear() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $var) {
			unset($this->$key);
		}
	}

}
