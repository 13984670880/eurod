<?php

class Genius_Model_Translation {

	public static function getTranslations(){ 
		return Genius_Model_Mutlilingual::get(TABLE_PREFIX."translations", TABLE_PREFIX."translations_multilingual", 'id', 'id_translation');		  	 
	} 

	public static function getTranslationById($id){ 
		return Genius_Model_Mutlilingual::byid(TABLE_PREFIX."translations", TABLE_PREFIX."translations_multilingual", 'id', 'id_translation', $id, 'tm.key, tm.value', false);			 
	}

 	public static function getValueMultilingual($id_translation, $id_language)
 	{ 
 		global $db;
 		$sql = " SELECT id_translation,id_language,`key`,value FROM ".TABLE_PREFIX."translations_multilingual WHERE id_translation='$id_translation' AND id_language='$id_language' ";

 		$data = $db->fetchRow($sql);
 		return $data; 		  	 
 	} 

 	public static function isTranslated($str)
 	{ 
 		$str = mysql_real_escape_string($str);
 		global $db;
 		$sql = "SELECT * FROM `".TABLE_PREFIX."translations_multilingual` WHERE `id_language` =1 AND `key` = '$str'";
 		
 		$data = $db->fetchAll($sql);
 		if(!empty($data))
 			return true;
 		else 
 			return false;	 
 	}

}