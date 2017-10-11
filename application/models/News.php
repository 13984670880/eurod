<?php

class Genius_Model_News { 
 	
 	public static function getNews() { 
 		return Genius_Model_Mutlilingual::get(TABLE_PREFIX."news", TABLE_PREFIX."news_multilingual", 'id', 'id_news');		  	 
 	} 

 	public static function getNewsById($id){ 
 		return Genius_Model_Mutlilingual::byid(TABLE_PREFIX."news", TABLE_PREFIX."news_multilingual", 'id', 'id_news', $id, 'tm.title, tm.text, tm.seo_title, tm.seo_meta_description, tm.seo_meta_keyword', false);			 
 	}
	 
}