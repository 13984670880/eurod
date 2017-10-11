<?php

class Genius_Model_Language { 
 	
 	public static function getLanguages($limit = false)
 	{ 
 		global $db;
 	
 		if($limit)
 			$sql = " SELECT * FROM ".TABLE_PREFIX."languages WHERE 1 LIMIT 0,$limit";
 		else
 			$sql = " SELECT * FROM ".TABLE_PREFIX."languages WHERE 1 ";

 		$data = $db->fetchAll($sql);
 		return $data; 		  	 
 	} 

 	public static function getAbbr($id)
 	{ 
 		global $db;
 		$sql  = "SELECT abbreviation FROM ".TABLE_PREFIX."languages WHERE id=$id";
 		$data = $db->fetchRow($sql);
 		return $data['abbreviation'];
 	}

 	public static function getId($abbr)
 	{ 
 		global $db;
 		$sql  = "SELECT id FROM ".TABLE_PREFIX."languages WHERE abbreviation='$abbr'";
 		$data = $db->fetchRow($sql);
 		return $data['id']; 		 
 	}

}