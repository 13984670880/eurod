<?php

class Genius_Model_Slide { 
 	
 	public static function getSlides() { 
 		return Genius_Model_Mutlilingual::get(TABLE_PREFIX."slides", TABLE_PREFIX."slides_multilingual", 'id', 'id_slide');		  	 
 	} 

 	public static function getSlideById($id){ 
 		return Genius_Model_Mutlilingual::byid(TABLE_PREFIX."slides", TABLE_PREFIX."slides_multilingual", 'id', 'id_slide', $id, 'tm.title, tm.text');			 
 	}
	
	public static function getSlideByIdOrder($id_slide) {
        global $db;
        $sql = "
 			SELECT c.id, c.order_item, cm.title
 			FROM " . TABLE_PREFIX . "slides c
 			INNER JOIN " . TABLE_PREFIX . "slides_multilingual cm ON c.id=cm.id_slide
 			WHERE cm.id_language=" . DEFAULT_LANG_ID . " 
 			ORDER BY c.order_item ASC
 		";

        $data = $db->fetchAll($sql);
        return $data;
    }
	
	public static function get()
	{
		global $db;
		
		$slidesquery = "
			SELECT 
				s.id
				,s.link
				,s.id_annonce
				,sm.title
				,sm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt 
				,im.legend

			FROM ".TABLE_PREFIX."slides s

			LEFT JOIN ".TABLE_PREFIX."slides_multilingual sm ON s.id=sm.id_slide
			LEFT JOIN ".TABLE_PREFIX."images_relations ir ON ( ir.id_item=s.id AND ir.id_module=5  AND ir.image_cover=1 )
			LEFT JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			LEFT JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id
			
			WHERE sm.id_language=1 AND im.id_language=1
			ORDER BY s.order_item ASC
		";

		$slides = Genius_Model_Global::query($slidesquery);
		return $slides;
	}
}