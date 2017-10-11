<?php
class Genius_Model_Membres {  	
    
	public static function getCommentByIdticket($id_ticket){
		global $db;
		$sql = "
		SELECT 
				SQL_CALC_FOUND_ROWS mc.id
				,mc.id_ticket
				,mb.nom
				,mb.prenom
				,concat(mb.nom,' ',mb.prenom) as nom_membre
				,mb.login
				,mb.id_categorie
				,mb.date_inscription
				,mm.id as id_message
				,mm.id_membre
				,mm.assign
				,mm.id_ticket
				,mm.subject
				,mm.message
				,DATE_FORMAT(mm.date_creation,'%d %M %y %H:%i') as date_creation
				,mc.commentaire
				,mc.id_ticket
				,mc.id_expediteur
				,DATE_FORMAT(mc.date_creation,'%d %M %y %H:%i') as date_creation_commentaire

			FROM ".TABLE_PREFIX."membres_messages_commentaires mc
			LEFT JOIN ".TABLE_PREFIX."membres_messages mm ON mm.id_ticket = mc.id_ticket
			LEFT JOIN ".TABLE_PREFIX."membres mb ON mm.id_membre=mb.id
			WHERE 
			mc.id_ticket = '$id_ticket'
			ORDER BY mc.date_creation ASC
		";
		$data = $db->fetchAll($sql);
 		return $data; 
	}


 	public static function getAllMembresInvestissementsById($id_membre)
 	{ 
 		global $db; 	
 		$sql = "
		SELECT
		  mi.id as id_membre_investissement,
		  mi.id_membre,
		  mi.id_investissement,
		  mi.montant_investissement,
		  DATE_FORMAT(mi.date_debut,'%d %M %y') as date_debut,
		  DATE_FORMAT(mi.date_fin,'%d %M %y') as date_fin,
		  DATE_FORMAT(mi.date_creation,'%d %M %y') as date_creation,
		  CONCAT (m.nom,' ',m.prenom) as nom_membre,
		  DATEDIFF(mi.date_fin,mi.date_debut) as delai,
		  i.titre,
		  i.ville,
		  i.id_pays,
		  i.description,
		  i.capital,
		  i.actif,
		  ti.type as type_investissement
		FROM 
			".TABLE_PREFIX."membres_investissements mi
			INNER JOIN ".TABLE_PREFIX."investissements i ON i.id = mi.id_investissement
			INNER JOIN ".TABLE_PREFIX."types_investissements ti ON ti.id = i.id_type_investissement
			INNER JOIN ".TABLE_PREFIX."membres m ON i.id = mi.id_investissement
		WHERE
		mi.id_membre = '$id_membre'
		GROUP BY mi.id
		";
 		$data = $db->fetchAll($sql);
 		return $data; 		  	 
 	} 
	
	public static function getMembresInvestissementsById($id_membre_investissement){
		global $db; 	
 		$sql = "
		SELECT
		  mi.id as id_membre_investissement,
		  mi.id_membre,
		  mi.id_investissement,
		  mi.montant_investissement,
		  mi.etat,
		  DATE_FORMAT(mi.date_debut,'%d %M %y') as date_debut,
		  DATE_FORMAT(mi.date_fin,'%d %M %y') as date_fin,
		  DATE_FORMAT(mi.date_creation,'%d %M %y') as date_creation,
		  CONCAT (m.nom,' ',m.prenom) as nom_membre,
		  DATEDIFF(mi.date_fin,mi.date_debut) as delai,
		  i.titre,
		  i.ville,
		  i.id_pays,
		  i.description,
		  i.capital,
		  i.actif,
		  ti.type as type_investissement
		FROM 
			".TABLE_PREFIX."membres_investissements mi
			INNER JOIN ".TABLE_PREFIX."investissements i ON i.id = mi.id_investissement
			INNER JOIN ".TABLE_PREFIX."types_investissements ti ON ti.id = i.id_type_investissement
			INNER JOIN ".TABLE_PREFIX."membres m ON i.id = mi.id_investissement
		WHERE
		mi.id = '$id_membre_investissement'
		GROUP BY mi.id
		";
 		$data = $db->fetchRow($sql);
 		return $data; 
	}
	
	public static function getAllInvestissements(){
		global $db; 	
 		$sql = "
		SELECT
		  i.id as id_investissement,
		  i.titre,
		  i.ville,
		  i.id_pays,
		  i.description,
		  i.capital,
		  i.actif,
		  ti.type as type_investissement,
		  DATE_FORMAT(i.date_creation,'%d %M %y') as date_creation
		FROM 
			".TABLE_PREFIX."investissements i 
			INNER JOIN ".TABLE_PREFIX."types_investissements ti ON ti.id = i.id_type_investissement
		WHERE
		i.id is not null
		AND i.actif = 1
		GROUP BY i.id
		";
 		$data = $db->fetchAll($sql);
 		return $data; 
	}
	
	public static function getInvestissementsById($id_investissement){
		global $db; 	
 		$sql = "
		SELECT
		  i.id as id_investissement,
		  i.titre,
		  i.ville,
		  i.id_pays,
		  i.description,
		  i.capital,
		  i.actif,
		  ti.type as type_investissement,
		  DATE_FORMAT(i.date_creation,'%d %M %y') as date_creation
		FROM 
			".TABLE_PREFIX."investissements i 
			INNER JOIN ".TABLE_PREFIX."types_investissements ti ON ti.id = i.id_type_investissement
		WHERE
		i.id = '$id_investissement'
		AND i.actif = 1
		GROUP BY i.id
		";
 		$data = $db->fetchRow($sql);
 		return $data; 
	}
}