<?php
class Genius_Class_Random{

	public static function generate($car){

		$string = "";
		$chaine = "ABCDEFGHIJKLMN1234567890";
		srand((double)microtime()*1000000);
		for($i=0; $i<$car; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}
	
	public static function generatepwd($car){

		$string = "";
		$chaine = "ABCDEFGHIJKLMN1234567890abcdefghijklmnopqrstv";
		srand((double)microtime()*1000000);
		for($i=0; $i<$car; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}

	public static function numquestion($car){
		return time();
	}



	public static function setNewIdOffer($car){

		$string = "";
		$chaine = "ABCDEFGHIJKLMN1234567890";
		srand((double)microtime()*1000000);
		for($i=0; $i<$car; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}
}