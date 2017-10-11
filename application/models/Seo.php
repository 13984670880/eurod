<?php

class Genius_Model_Seo { 
 	
 	public static function getSeo() { 
 		return Genius_Model_Mutlilingual::get(TABLE_PREFIX."seo", TABLE_PREFIX."seo_multilingual", 'id', 'id_seo');		  	 
 	} 

 	public static function getSeoById($id){ 
 		return Genius_Model_Mutlilingual::byid(TABLE_PREFIX."seo", TABLE_PREFIX."seo_multilingual", 'id', 'id_seo', $id, 'tm.*', false);			 
 	}
	 
}
