<?php

class Genius_Model_Image { 
 	
 	public static function getImages($id_item, $id_image){ 
 		global $db;
 		
 		$sql = " 
 			SELECT t.*, tm.*, tr.*, t.filename as title
 			FROM ".TABLE_PREFIX."images t
 			JOIN ".TABLE_PREFIX."images_multilingual tm ON t.id=tm.id_image
 			JOIN ".TABLE_PREFIX."images_relations tr ON t.id=tr.id_image
 			WHERE tm.id_language=".DEFAULT_LANG_ID." AND tr.id_item='$id_item'
 			ORDER BY tr.order_item
 		";

 		$data = $db->fetchAll($sql);
 		return $data;   	 
 	} 

 	public static function getImageById($id){ 
 		global $db;

		$t  = TABLE_PREFIX."images";
		$tm = TABLE_PREFIX."images_multilingual";
		$tr = TABLE_PREFIX."images_relations";

 		$sql_t = "SELECT * FROM $t WHERE id=$id";
 		$t_data = $db->fetchRow($sql_t);

 		$sql_tm = " 
 			SELECT 
 				t.*
 				,tm.id_language
 				,tm.legend
 				,tm.alt
 				,tr.order_item
 			FROM $t t
 			JOIN $tm tm ON t.id=tm.id_image
 			JOIN $tr tr ON t.id=tr.id_image
 			WHERE t.id=$id
 		";

 		$tm_data = $db->fetchAll($sql_tm);

 		$multilingual_key = array('legend', 'alt');

 		if(!empty($tm_data)){ 
 			foreach ($tm_data as $k => $item) {
 				$abbr = Genius_Model_Language::getAbbr($item['id_language']);

 				foreach ($item as $key => $value) {
 					if(in_array($key, $multilingual_key)):
 						$t_data[$key.'_'.$abbr] = $value;
 					else:
 						$t_data[$key] = $value;
 					endif;
 				}
 			} 
 		}

 		return $t_data;			 
 	}
	
	public static function getHomeImageCoverById($id_module,$id_item)
    {
        global $db;

        $query = "
			SELECT
				 i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend
				,ir.id_item

			FROM  
			".TABLE_PREFIX."images_relations ir
			INNER JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			INNER JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id

			WHERE 
			ir.id_module= '$id_module'
			AND ir.id_item = '$id_item'
			AND ir.image_cover=1
			AND im.id_language=".DEFAULT_LANG_ID." 
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }

}