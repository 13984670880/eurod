<?php

class Genius_Model_Tickets { 
 	
 	public static function getTypes() { 
 		return Genius_Model_Mutlilingual::get(TABLE_PREFIX."news", TABLE_PREFIX."news_multilingual", 'id', 'id_news');		  	 
 	} 

 	public static function getTypesById($id){ 
 		return Genius_Model_Mutlilingual::byid(TABLE_PREFIX."requests_types", TABLE_PREFIX."requests_types_multilingual", 'id', 'id_request_type', $id, 'tm.libelle', false);			 
 	}
	 
}