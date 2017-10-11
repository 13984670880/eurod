<?php
class Genius_Class_Prestations {
    public static function get($string_id) {
        $ids = explode(",", $string_id);
		$nb_ids = count($ids);
		$prestations = "";
		$i = 0;
		foreach($ids as $key=>$id){
			$i++;
			$prestation = Genius_Model_Global::selectRow(TABLE_PREFIX.'prestation',"*","id = '$id'");
			if ($i < $nb_ids){	
				$prestations .= $prestation['nom_prestation'].', ';
			}else{
				$prestations .= $prestation['nom_prestation'];
			}
		}
		return $prestations;
    }
	
	public static function convert($id){
	  if (!empty($id)){
		  if ($id== 1){
			  return "Oui";
		  }else{
			  return "Non";
		  }
	  }else{
		  return "Non";
	  }
	}
	
	public static function othermark(){
		$other_mark = array(98,102,103,104,105,106);
		return $other_mark;
	}
}
