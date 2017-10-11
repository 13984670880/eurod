<?php
class Genius_Class_Timthumb {
	
	public static function createThumb($image,$w,$h){
		list($width, $height, $type, $attr) = getimagesize($image); 
		$host_timthumb = BASE_URL.'/timthumb.php?';

		if($width>$w OR $height>$h):
			$new_image = $host_timthumb.'src='.$image.'&w='.$w.'&h='.$h.'&zc=1&q=80';
		else:
			$new_image = $host_timthumb.'src='.$image.'&w='.$width.'&h='.$height.'&zc=1&q=80';
		endif;
		return $new_image;
	}
	
}