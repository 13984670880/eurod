<?php
class Genius_Class_Validatorfile {
	
    public static function photo($file) 
    {
		$valid_formats = array("JPG","JPEG","jpg", "png", "gif", "bmp"); 
		list($txt, $ext) = explode(".", $file['photo']['name']);
		$size = $file['photo']['size'];
		if(in_array($ext,$valid_formats)){
			if($size<(1024*1024*10)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
    }
	
	public static function pdf($file) 
    {
		$valid_formats = array("pdf","PDF"); 
		list($txt, $ext) = explode(".", $file['questionnaire']['name']);
        if(in_array($ext,$valid_formats)){
			return true;
		}else{
			return false;
		}
    }
	
}