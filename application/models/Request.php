<?php

class Genius_Model_Request {

	public static function getRequests(){ 
		return Genius_Model_Mutlilingual::get(TABLE_PREFIX."requests", TABLE_PREFIX."requests_multilingual", 'id', 'id_request');		  	 
	} 

	public static function getTranslationById($id){ 
		return Genius_Model_Mutlilingual::byid(TABLE_PREFIX."requests", TABLE_PREFIX."requests_multilingual", 'id', 'id_request', $id, 'tm.title, tm.object, tm.email, tm.phone, tm.text', false);			 
	}

}