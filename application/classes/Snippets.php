<?php
class Genius_Class_Snippets {

	public static function multilingualTabs()
	{ 
		$tr = Zend_Registry::get('Zend_Translate');
		$languages = Genius_Model_Language::getLanguages();

	 	$return = '';
	 	$return .= '<ul class="nav-multilingual nav-tabs">';
 
 		if(!empty($languages)){ 
 		 	foreach ($languages as $k => $item) {
 		 		$active = ($k==0) ? 'class="active"' : '' ;
 		 		$return .= '<li '.$active.'><a data-toggle="tab" style="cursor:pointer" abbr="'.$item['abbreviation'].'" >'.$item['title'].'</a></li>';	 	
 		 	} 
 		} 	 	

	 	$return .= '</ul>';
	 	echo $return;
	}

	public static function breadcrumb($module, $module_url)
	{ 
		$tr = Zend_Registry::get('Zend_Translate');

		$label_locale = '';
		$sess = new Zend_Session_Namespace();
		switch ($sess->locale) {
			case 'fr':
					$label_locale = $tr->translate("English");
				break;

			case 'en':
					$label_locale = $tr->translate("Français");
				break;
			
			default:
				# code...
				break;
		}
		
		$return = '';
		$return .= '
		<div class="crumbs">
		    <ul id="breadcrumbs" class="breadcrumb"> 
		        <li><a href="/admin">'.$tr->translate("Tableau de bord").'</a></li>
		        <li class="active"><a href="'.$module_url.'" title="">'.$module.'</a></li>
		    </ul>
		    
		    <ul class="breadcrumb-right">
		    	<li><a style="cursor: pointer" class="change-lang" title="'.$label_locale.'"><i class="icon-flag"></i><span>'.$label_locale.'</span></a></li>
		        <li><a href="/admin/users" title="'.$tr->translate("Utilisateurs").'"><i class="icon-user"></i><span>'.$tr->translate("Utilisateurs").'</span></a></li>
		        <li><a href="/admin/siteconfiguration" title="'.$tr->translate("Configuration du site").'"><i class="icon-cogs"></i><span>'.$tr->translate("Configuration du site").'</span></a></li>
		    </ul>
		    
		</div>';

		return $return;
	}

}