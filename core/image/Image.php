<?php
namespace image;
class Image {
	public $path;
	
	public $width;
	public $height;
	public $format;
	public $size;
	
	public $image;
	
	public function __construct($path) {
		$this->path = $path;
		$this->size = filesize($this->path);
		
		$filearray = explode(".", $this->path);
		
		$ext = end($filearray);
		
		if($ext == "tif" || $ext == "tiff") {
			array_splice($filearray, count($filearray)-1);
			$jpgfile = implode(".", $filearray) .".jpg";
			
			if(!file_exists($jpgfile)) {
				echo "Creando jpg a partir de tiff<br />";
				
				$output = array();
				$command = "/usr/bin/convert '". $this->path ."' '". $jpgfile ."'";
				echo "Ejecutando ". $command ."<br />";
				exec($command, $output);
				
				foreach($output as $index => $line) {
					echo "Linea ". $index .": ". $line ."<br />";
				}
				
				echo "Terminado<br />";
			}
			
			$this->path = $jpgfile;
		}
		
				
		$info = getimagesize($this->path);
		$this->width = $info[0];
		$this->height = $info[1];
		$this->format = $info["mime"];
		
		$this->loadImage();
	}
	
	private function loadImage() {
		switch($this->format) {
			case 'image/jpeg':
				$this->image = imagecreatefromjpeg($this->path);
				break;
		
			case 'image/gif':
				$this->image = imagecreatefromgif($this->path);
				break;
		
			case 'image/png':
				$this->image = imagecreatefrompng($this->path);
				break;
		}
	}
	
	public function printImage() {
		header('Content-Type:'. $this->format);
		header('Content-Length: ' . $this->size);
		readfile($this->path);
	}
	
	public function resizeProp($max_width, $max_height, $dest = null) {
		if(!$this->image)
			$this->loadImage();
		
		if($dest === null)
			$dest = $this->path;
		
		$ratio = $this->width/$this->height;
		$max_ratio = $max_width/$max_height;
		
		if($max_ratio < $ratio) {
			$dest_width = $max_width;
			$dest_height = round($dest_width/$ratio);
		} else {
			$dest_height = $max_height;
			$dest_width = $dest_height*$ratio;
		}
		
		$dest_image = imagecreatetruecolor($dest_width, $dest_height);
		imagecopyresampled($dest_image, $this->image, 0,0,0,0, $dest_width, $dest_height, $this->width, $this->height);
		
		self::imageToFile($dest_image, $this->format, $dest);
		imagedestroy($dest_image);
	}
	
	public static function imageToFile($image, $format, $dest) {
		$res = false;
		switch($format) {
			case 'image/jpeg':
				$res = imagejpeg($image, $dest);
				break;
			case 'image/png':
				$res = imagepng($image, $dest);
				break;
			case 'image/gif':
				$res = imagegif($image, $dest);
				break;
		}
		
		return $res;
	}
	
	public static function createImageFromText($width, $height, $background, $color, $text, $font, $fontsize, $format, $path) {
		$im = imagecreate($width, $height);
		$backgroundobj = imagecolorallocate($im, $background[0], $background[1], $background[2]);
		$colorobj = imagecolorallocate($im, $color[0], $color[1], $color[2]);
		$x = ($width - ($fontsize-2.5) * strlen($text)) / 2;
		$y = ($height - ($fontsize-7)) / 2;
		
		imagettftext($im, $fontsize, 0, $x, $y, $colorobj, $font, $text);
		self::imageToFile($im, $format, $path);
		imagedestroy($im);
	}
	
	public function doThumbnail($width, $height, $dest, $options = array()) {
		if(!$this->image)
			$this->loadImage();
		
		try {
			$ratio = $width/$height;
			$src_ratio = $this->width/$this->height;
			if($ratio > $src_ratio) {
				$src_width = $this->width;
				$src_height = round($src_width/$ratio);
				$src_x = 0;
				
				// Procesando las opciones verticales
				if(in_array("bottom", $options)) {
					$src_y = $this->height - $src_height;
				} else if(in_array("top", $options)) {
					$src_y = 0;
				} else {
					$src_y = round(($this->height - $src_height) / 2 );
				}
			} else {
				$src_height = $this->height;
				$src_width = $src_height * $ratio;
				$src_y = 0;
				
				// Procesando las opciones horizontales
				if(in_array("right", $options)) {
					$src_x = $this->width - $src_width;
				} else if(in_array("left", $options)) {
					$src_x = 0;
				} else {
					$src_x = round(($this->width - $src_width) / 2 );
				}
			}
			
			$dest_img = imagecreatetruecolor($width, $height);
			
			if($this->format == "image/png") {
				imagealphablending($dest_img, false);
				imagesavealpha($dest_img, true);
			}
			imagecopyresampled($dest_img, $this->image, 0,0,$src_x,$src_y, $width, $height, $src_width, $src_height);
			self::imageToFile($dest_img, $this->format, $dest);
			
			imagedestroy($dest_img);
		} catch (Exception $e) {
			return false;
		}
		
		return true;
	}
}