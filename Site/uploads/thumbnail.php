<?php

/*function my_autoloader($class) {
	include '../assets/lib/waveform/' . str_replace("\\", "/", $class) . '.php';
}

spl_autoload_extensions(".php");
spl_autoload_register("my_autoloader");
use BoyHagemann\Waveform\Waveform;
use BoyHagemann\Waveform\Generator;*/

include '../assets/lib/waveform/php-waveform-png.php';

function imagecreatefromfile( $filename ) {
    switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
        case 'jpeg':
        case 'jpg':
            return imagecreatefromjpeg($filename);
        break;

        case 'png':
            return imagecreatefrompng($filename);
        break;

        case 'gif':
            return imagecreatefromgif($filename);
        break;

        default:
            throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
        break;
    }
}

if(!isset($_GET['file'])) die("Param missing");
$file = $_GET['file'];
if(strpos($file, "..") !== FALSE
    || strpos($file, "/") !== FALSE
    || strpos($file, "\\") !== FALSE) die("Param missing"); // prevent hax
$filename = $file;
$file = "uploaded/" . $file;
if(!file_exists($file)) die("404");
$ending = strtolower( pathinfo( $file, PATHINFO_EXTENSION ));

if($ending == "wut" || $ending == "svg"){
  die(file_get_contents($file));
}

if(file_exists("thumb-cache/" . $filename . ".png")){
	header("Content-Type: image/png");
	die(file_get_contents("thumb-cache/" . $filename . ".png"));
}

function outputWaveform($path){
	global $filename;
	$img = renderWaveform($path);
	imagepng($img, "thumb-cache/" . $filename . ".png");
	imagepng($img);
	imagedestroy($img);
}

if($ending == "png" || $ending == "jpg" || $ending == "jpeg" || $ending == "gif"){
	$source_image = imagecreatefromfile($file);
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	$desired_width = 200;
	$desired_height = floor($height * ($desired_width / $width));
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	header("Content-Type: image/png");
	imagepng($virtual_image);
	imagepng($virtual_image, "thumb-cache/" . $filename . ".png");
	imagedestroy($virtual_image);
	imagedestroy($source_image);
} else if($ending == "wav" || $ending == "mp3"){
	header("Content-Type: image/png");
	if($ending == "wav"){
		outputWaveform($file);
	} else if($ending == "mp3"){
		$tmpname = substr(md5(time()), 0, 10);
		copy($file, "{$tmpname}_o.mp3");
		exec("lame {$tmpname}_o.mp3 -m m -S -f -b 16 --resample 8 {$tmpname}.mp3 && lame -S --decode {$tmpname}.mp3 {$tmpname}.wav");
		$newfile = "{$tmpname}.wav";
		
		unlink("{$tmpname}_o.mp3");
		unlink("{$tmpname}.mp3");
		
		outputWaveform($newfile);
		
		unlink("{$tmpname}.wav");
	} else if($ending == 'json') {
		$source_image = imagecreatefromfile('http://dev.opensprites.gwiddle.co.uk/assets/images/defaultscript.png');
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		$desired_width = 200;
		$desired_height = floor($height * ($desired_width / $width));
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		header("Content-Type: image/png");
		imagepng($virtual_image);
		imagepng($virtual_image, "thumb-cache/" . $filename . ".png");
		imagedestroy($virtual_image);
		imagedestroy($source_image);
	}
}
?>
