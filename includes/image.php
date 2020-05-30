<?php

// Exits
function die_with_image($file) {
	$exif_type = exif_imagetype($file);
	header("Content-type: " . image_type_to_mime_type($exif_type));
	$fp = fopen($file, 'rb');
	fpassthru($fp);
	exit;
}
function die_with_text_on_image($text) {
	$width = 800;
	$height = 600;

	$canvas = imagecreatetruecolor($width, $height);
	$black = imagecolorallocate($canvas, 0,0,0);
	$white = imagecolorallocate($canvas, 255,255,255);

	imagefill($canvas, 0, 0, $black);

	$font = realpath(ASSETS_DIR . "/fonts/appli-mincho.otf");

	$bbox = imagettfbbox(12, 0, $font, $text);
	$x = $bbox[0] + ($width / 2) - ($bbox[4] / 2);
	$y = $bbox[1] + ($height / 2) - ($bbox[5] / 2);

	imagettftext($canvas, 12, 0, $x, $y, $white, $font,$text);

	header('Content-Type: image/png');
	imagepng($canvas);
	imagedestroy($canvas);
	die;
}