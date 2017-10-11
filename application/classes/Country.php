<?php
class Genius_Class_Country{
	public static function generate(){
		$lang = DEFAULT_LANG_ABBR;
		if ($lang == 'fr'){
			$locale_lang = 'fr_FR';
		}elseif($lang == 'en'){
			$locale_lang = 'en_EN';
		}
		require_once 'Zend/Locale.php';
		$locale = new Zend_Locale($locale_lang);
		
		//pays	
		$countries = ($locale->getTranslationList('Territory',$lang, 2));
		foreach ($countries as $key=>$val):
			$pays[$key] = Genius_Class_String::wd_remove_accents($val);
		endforeach;
		
		asort($pays, SORT_LOCALE_STRING);
		
		return $pays;
	}
}