<?php
class Genius_Model_Immobiliers {  	
    
	public static function getImmoById($id_immobilier){
		global $db;
		$sql = "
		SELECT 
				SQL_CALC_FOUND_ROWS i.id as id_immobilier
				,i.nom as nom_immobilier
				,i.adresse
				,i.montant
				,i.id_membre
				,i.fiche
				,i.ville
				,i.document_1
				,i.document_2
				,i.document_3
				,i.id_type_immobilier
				,i.date_creation
				,ti.id
				,til.libelle
				,mb.*
				,CONCAT(mb.nom,' ',mb.prenom) as nom_membre
				,DATE_FORMAT(i.date_creation,'%d %M %y %H:%m:%s') as date_creation
			FROM ".TABLE_PREFIX."immobiliers i
			LEFT JOIN ".TABLE_PREFIX."types_immobiliers ti ON ti.id=i.id_type_immobilier
			LEFT JOIN ".TABLE_PREFIX."types_immobiliers_multilingual til ON ti.id=til.id_type_immobilier
			LEFT JOIN ".TABLE_PREFIX."membres mb ON mb.id=i.id_membre
			WHERE
			i.id = '$id_immobilier'
		";
		$data = $db->fetchRow($sql);
 		return $data; 
	}
}
