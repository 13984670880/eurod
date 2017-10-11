<?php
class Genius_Class_Products{
	public static function getFeatures($id_category_group,$column,$id_type = NULL,$id_marque = NULL){
		$feature = array();
		$t = Zend_Registry::get("Zend_Translate");
		//Imprimantes étiquettes
		$feature[14][1] = $t->translate("Résolution");
		$feature[14][2] = $t->translate("Rapidité (max)");
		$feature[14][3] = $t->translate("Largeur max impression");
		$feature[14][4] = $t->translate("Poids");
		$feature[14][5] = $t->translate("Encombrement LxHxP");
		$feature[14][6] = $t->translate("Type utilisation");
		//Terminaux code-barres 
		$feature[17][1] = $t->translate("Système exploitation");
		$feature[17][2] = $t->translate("Taille de l'écran");
		$feature[17][3] = $t->translate("Type scanner");
		$feature[17][4] = $t->translate("Type clavier ");
		$feature[17][5] = $t->translate("Taille (LXHXP)");
		$feature[17][6] = $t->translate("Poids");
		//Scanners code-barres
		$feature[21][1] = $t->translate("Communication");
		$feature[21][2] = $t->translate("Connectique");
		$feature[21][3] = $t->translate("Type scanner");
		$feature[21][4] = $t->translate("Distance lecture");
		$feature[21][5] = $t->translate("Encombrement LxHxP");
		$feature[21][6] = $t->translate("Poids");
		//Imprimantes laser
		$feature[24][1] = $t->translate("Vitesse impression");
		$feature[24][2] = $t->translate("Technologie d'impression");
		$feature[24][3] = $t->translate("Format impression");
		$feature[24][4] = $t->translate("Gestion papier (max)");
		$feature[24][5] = $t->translate("Encombrement LxHxP");
		$feature[24][6] = $t->translate("Type utilisation");
		//Imprimantes matricielles
		if($id_type == 110  &&  $id_marque == 104){
		$feature[32][1] = $t->translate("Type de caisson");
		$feature[32][2] = $t->translate("Dimensions (LxPxH)");
		$feature[32][3] = $t->translate("Nombre de compartiments");
		$feature[32][4] = $t->translate("Matériau caisson");
		$feature[32][5] = $t->translate("Piètement inox");
		$feature[32][6] = $t->translate("Options");
		}else{
		$feature[32][1] = $t->translate("Résolution (dot per inch)");
		$feature[32][2] = $t->translate("Vitesse d'impression");
		$feature[32][3] = $t->translate("Nombre de colonnes");
		$feature[32][4] = $t->translate("Technologie");
		$feature[32][5] = $t->translate("Encombrement LxHxP");
		$feature[32][6] = $t->translate("Poids");	
		}
		//Accessoires imprimantes
		$feature[36][1] = $t->translate("Type de caisson");
		$feature[36][2] = $t->translate("Dimensions (LxPxH)");
		$feature[36][3] = $t->translate("Nombre de compartiments");
		$feature[36][4] = $t->translate("Matériau caisson");
		$feature[36][5] = $t->translate("Piètement inox");
		$feature[36][6] = $t->translate("Options");
		if($id_type == 85  &&  $id_marque == 116){
		//pc destockage
		$feature[27][1] = $t->translate("Système exploitation (OS)");
		$feature[27][2] = $t->translate("Mémoire Ram / Max");
		$feature[27][3] = $t->translate("Processeur ");
		$feature[27][4] = $t->translate("Disque Dur");
		$feature[27][5] = $t->translate("Format");
		$feature[27][6] = $t->translate("Poids");
		}elseif($id_type == 121){		
		//coffret de protection
		$feature[27][1] = $t->translate("Type de caisson");
		$feature[27][2] = $t->translate("Dimensions (LxPxH)");
		$feature[27][3] = $t->translate("Nombre de compartiments");
		$feature[27][4] = $t->translate("Matériau caisson");
		$feature[27][5] = $t->translate("Piètement inox");
		$feature[27][6] = $t->translate("Options");
		}elseif($id_type == 123){		
		//onduleurs
		$feature[27][1] = $t->translate("Puissance");
		$feature[27][2] = $t->translate("Autonomie");
		$feature[27][3] = $t->translate("Garantie");
		$feature[27][4] = $t->translate("Poids");
		$feature[27][5] = $t->translate("Encombrement LxHxP");
		$feature[27][6] = $t->translate("Utilisation");
		}elseif($id_type == 89 /*&& $id_marque == 118*/){		
		//écrans destockage
		$feature[27][1] = $t->translate("Taille");
		$feature[27][2] = $t->translate("Résolution");
		$feature[27][3] = $t->translate("VESA");
		$feature[27][4] = $t->translate("Connectique");
		$feature[27][5] = $t->translate("Encombrement (LxHxP)");
		$feature[27][6] = $t->translate("Poids");
		}elseif($id_type == 86 && $id_marque == 117){
		//portable destockage
		$feature[27][1] = $t->translate("Système exploitation (OS)");
		$feature[27][2] = $t->translate("Mémoire Ram / Max");
		$feature[27][3] = $t->translate("Processeur ");
		$feature[27][4] = $t->translate("Disque Dur");
		$feature[27][5] = $t->translate("Format");
		$feature[27][6] = $t->translate("Poids");	
		}elseif ($id_type != 85 && $id_type != 86){
		//Clients légers
		$feature[27][1] = $t->translate("Mémoire Flash / Ram");
		$feature[27][2] = $t->translate("Ports disponibles");
		$feature[27][3] = $t->translate("Wifi disponible");
		$feature[27][4] = $t->translate("Système d'exploitation");
		$feature[27][5] = $t->translate("Encombrement LxHxP (position horizontale)");
		$feature[27][6] = $t->translate("Poids");
		}else{
		$feature[27][1] = $t->translate("Système exploitation (OS)");
		$feature[27][2] = $t->translate("Mémoire Ram / Max");
		$feature[27][3] = $t->translate("Processeur ");
		$feature[27][4] = $t->translate("Disque Dur");
		$feature[27][5] = $t->translate("Format");
		$feature[27][6] = $t->translate("Poids");
		}
		
		return $feature[$id_category_group][$column];
	}
	
	public function allowtabs($id_category_group,$id_marque,$id_type){
		$tabs_value['tab1'] = false;
		if ($id_type == 57 /*Terminal portable main libre*/){
			$tabs_value['tab2'] = true;
		}else{
			$tabs_value['tab2'] = true;
		}
		
		$tabs_value['tab3'] = false;
		$tabs_value['tab4'] = false;
		if (
		  ($id_category_group != 22 ) /*chariot mobile*/
		&& 
		/*($id_category_group != 27 /*Rubrique micro/
		&& */(
		   $id_type != 85 /*MICRO PC*/
		/*&& $id_marque != 79 /*MICRO HP 
		&& $id_marque != 82 /*IBM / Lenovo*/
		&& $id_type != 86 /*MICRO PORTABLES*/
		&& $id_type != 88 /*MICRO SERVEURS*/
		&& $id_type != 87 /*MICRO clients légers*/
		&& $id_type != 89 /*MICRO ECRANS*/
		//&& $id_type != 57 /*Terminal portable main libre*/
		)
		
		){
				$tabs_value['tab1'] = true;
		}
		
		if (
		  ($id_category_group != 22 ) /*chariot mobile*/
		&& 
		/*($id_category_group != 27 /*Rubrique micro/
		&&*/ (
		   $id_type != 85 /*MICRO PC*/
		/*&& $id_marque != 79 /*MICRO HP 
		&& $id_marque != 82 /*IBM / Lenovo*/
		&& $id_type != 86 /*MICRO PORTABLES*/
		&& $id_type != 88 /*MICRO SERVEURS*/
		&& $id_type != 89 /*MICRO ECRANS*/
		//&& $id_type != 57 /*Terminal portable main libre*/
		)
		
		){
				$tabs_value['tab3'] = true;
		}
		
		if (
		  ($id_category_group != 22 ) /*chariot mobile*/
		/*&& 
		($id_category_group != 27 /*Rubrique micro*/
		&& (
		   $id_type != 85 /*MICRO PC*/
		/*&& $id_marque != 79 /*MICRO HP
		&& $id_marque != 82 /*IBM / Lenovo*/
		&& $id_type != 86 /*MICRO PORTABLES*/
		&& $id_type != 88 /*MICRO SERVEURS*/
		&& $id_type != 89 /*MICRO ECRANS*/
		//&& $id_type != 57 /*Terminal portable main libre*/
		)
		){
				$tabs_value['tab4'] = true;
		}
		
		return $tabs_value;
	}
}