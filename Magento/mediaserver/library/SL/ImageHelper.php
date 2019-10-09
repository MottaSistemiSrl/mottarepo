<?php
class SL_ImageHelper {
	public function createThumb($originalImage) {
		list($width, $height) = getimagesize($originalImage);
		// recupero la proporzione dell'immagine
		$resolution = $width / $height;
		$newWidth = $resolution * SL_THUMB_HEIGHT;
		$newHeight = SL_THUMB_HEIGHT;
		
		$tn = imagecreatetruecolor($newWidth, $newHeight);  
		$source = imagecreatefromjpeg($originalImage);
		imagecopyresized($tn, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		$thumbName = str_replace(".jpg", "-thumb.jpg", $originalImage);
		imagejpeg($tn, $thumbName);
	}
}