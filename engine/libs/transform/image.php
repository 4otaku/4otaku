<?

class Transform_Image
{
	protected $target = false;
	protected $data;
	protected $worker;
	protected $composite = array();
	
	public function __construct ($file) {
		
		if (!class_exists('Imagick')) {
			
			$this->worker = new Imagick_Substitute($file);
			
			$this->composite['over'] = Imagick_Substitute::COMPOSITE_OVER;
			$this->composite['jpeg'] = Imagick_Substitute::COMPRESSION_JPEG;	
		} else {
			
			$this->worker = new Imagick($file);
			
			$this->composite['over'] = Imagick::COMPOSITE_OVER;
			$this->composite['jpeg'] = Imagick::COMPRESSION_JPEG;
		}
	}
	
	public function target ($target) {
		$this->target = $target;
		
		return $this;
	}
	
	public function scale ($new_size, $compression = 80, $thumbnail = true) {
		if (empty($this->target)) {
			Error::warning("Не задано место сохранения новой картинки");
			
			return false;
		}
		
		$format = $this->worker->getImageFormat();
		if (strtolower($format) == 'gif') {
			$this->worker = $this->worker->coalesceImages();
		}
		
		$old_x = $this->worker->getImageWidth(); $old_y = $this->worker->getImageHeight();
		
		if (!is_array($new_size)) {
			$new_size = array('0' => $new_size, '1' => $new_size);
		}
		
		if ($thumbnail) {
			$aspect = min ($new_size[0]/$old_x,$new_size[1]/$old_y);
			$x = round($old_x*$aspect); $y = round($old_y*$aspect);
			$func = 'thumbnailImage';
		} else {
			$aspect = $new_size[0]/$old_x;
			$x = round($old_x*$aspect); $y = round($old_y*$aspect);
			$func = 'scaleImage';
		}
		
		if (strtolower($format) == 'png') {
			$this->worker->setImageCompressionQuality($compression);	
			$this->worker->$func($x,$y);	
			$bg = $this->worker->clone();
			$bg->colorFloodFillImage('#ffffff',100,'#777777',0,0);
			$bg->compositeImage($this->worker,$this->composite['over'],0,0);
			$bg->setImageCompression($this->composite['jpeg']);
			$bg->setImageFormat('jpeg');
			$bg->writeImage($this->target);	
		} elseif (strtolower($format) == 'gif') {
			$this->worker->setImageCompressionQuality($compression);
			$this->worker->$func($x,$y);
			$this->worker->setImagePage($x,$y,0,0);
			$bg = $this->worker->clone();
			$bg->colorFloodFillImage('#ffffff',100,'#777777',0,0);
			$bg->compositeImage($this->worker,$this->composite['over'],0,0);
			$bg->setImageCompression($this->composite['jpeg']);
			$bg->setImageFormat('jpeg');				
			$bg->writeImage($this->target);		
		} else {	
			$this->worker->setImageCompressionQuality($compression);
			$this->worker->$func($x,$y);		
			$this->worker->setImageCompression($this->composite['jpeg']);
			$this->worker->setImageFormat('jpeg');
			$this->worker->writeImage($this->target);	
		}
		
		$this->worker->clear();
	}
}



if (!function_exists('exif_imagetype')) {
	function exif_imagetype($filename) {
		if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
			return $type;
		}
		return false;
	}
}

class Imagick_Substitute {

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
			default: die;
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
	
	function thumbnailImage($x, $y, $bestfit=false) 
	{
		if($bestfit)
		{
			if($this->height > $this->width) $x = $this->width/($this->height/$y);
			else $y = $this->height/($this->width/$x);
		}
		
		$thumbnail = imagecreatetruecolor($x, $y);
		imagecopyresampled($thumbnail,$this->data,0,0,0,0,$x,$y,$this->width,$this->height);
		$this->data = $thumbnail;
	}
	
	function scaleImage($x, $y, $bestfit=false) 
	{
		$this->thumbnailImage($x, $y, $bestfit);
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
