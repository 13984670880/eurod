<?php

class Genius_Class_Seo {
	
	public static function getLinkProduct($id_product){
		try{
		//groupe du produit
		$group_category = Genius_Model_Product::getGroup($id_product);
		$id_category = $group_category[1];
		$group = Genius_Model_Group::getGroupById($id_category);
		$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]);
		
		//type ou modèle du produit
		$product_category=Genius_Model_Product::getProductCategoryById($id_product);
		$id_marque = $product_category['id_category_box'][13];
		$marque = Genius_Model_Category::getCategoryById($id_marque);
		$title_marque = Genius_Class_String::cleanString($marque['title_'.DEFAULT_LANG_ABBR]);
		
		//marque du produit
		$id_type = $product_category['id_category_box'][14];
		$type = Genius_Model_Category::getCategoryById($id_type);
		$title_modele = Genius_Class_String::cleanString($type['title_'.DEFAULT_LANG_ABBR]);
		
		// nom du produit
		$product = Genius_Model_Product::getProductById($id_product);
		$title_product = Genius_Class_String::cleanString($product['title_'.DEFAULT_LANG_ABBR]);
		
		return "/".$title_group."/".$title_marque."/".$title_modele."/".$title_product."-".$id_product.".html";
		}catch(Zend_Exception $e){
			return NULL;
		}
	}
	
	public static function getLinkGroup($id_module,$id_category_group){
		try{
			//titre group
			$group = Genius_Model_Group::getGroupById($id_category_group);
			$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]);
			
			$modules = array(10=>"tracabilite",11=>"imprimantes",15=>"micro");
			
			return "/".$modules[$id_module]."/".$title_group."-".$id_category_group.".html";
		}catch(Zend_Exception $e){
			return NULL;
		}
	}
	
	public static function getLinkGroupMarque($id_module,$id_category_group,$id_category){
		try{
			//titre group
			$group = Genius_Model_Group::getGroupById($id_category_group);
			$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]);
			
			//titre marque
			$category = Genius_Model_Category::getCategoryById($id_category);
    		$marque_titre = Genius_Class_String::cleanString($category['title_'.DEFAULT_LANG_ABBR]);
			
			$modules = array(10=>"tracabilite",11=>"imprimantes",15=>"micro");
			
			return "/".$modules[$id_module]."/".$title_group."-".$id_category_group."/".$marque_titre."-".$id_category.".html";
		}catch(Zend_Exception $e){
			return NULL;
		}
	}
	
	public static function getLinkGroupMarqueService($id_service,$id_category_group,$id_category){
		try{
			//titre group
			$group = Genius_Model_Group::getGroupById($id_category_group);
			$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]);
			
			//titre marque
			$category = Genius_Model_Category::getCategoryById($id_category);
    		$marque_titre = Genius_Class_String::cleanString($category['title_'.DEFAULT_LANG_ABBR]);
			
			$services = array(1=>"reparation",2=>"vente",3=>"echange",4=>"maintenance",5=>"location",6=>"audit",7=>"reprise");
			
			return "/".$services[$id_service]."/".$title_group."-".$id_category_group."/".$marque_titre."-".$id_category.".html";
		}catch(Zend_Exception $e){
			return NULL;
		}
	}
	
	public static function getLinkGroupMarqueServiceProduct($id_service,$id_category_group,$id_category,$id_product){
		try{
			//titre group
			$group = Genius_Model_Group::getGroupById($id_category_group);
			$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]);
			
			//titre marque
			$category = Genius_Model_Category::getCategoryById($id_category);
    		$marque_titre = Genius_Class_String::cleanString($category['title_'.DEFAULT_LANG_ABBR]);
			
			// nom du produit
			$product = Genius_Model_Product::getProductById($id_product);
			$title_product = Genius_Class_String::cleanString($product['title_'.DEFAULT_LANG_ABBR]);
			
			$services = array(1=>"reparation",2=>"vente",3=>"echange",4=>"maintenance",5=>"location",6=>"audit",7=>"reprise");
			
			return "/".$services[$id_service]."/".$title_group."-".$id_category_group."/".$marque_titre."-".$id_category."/".$title_product."-".$id_product.".html";
		}catch(Zend_Exception $e){
			return NULL;
		}
	}

    public static function show() {
        $seo = '<title>{title}</title>';
        $seo .= '<meta name="description" content="{description}">' . "\n";
        $seo .= '<meta name="keywords" content="{keywords}">';
        global $siteconfig;
        $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        $title = "";
        $description = "";
        $keywords = "";

        // home page
        if ($controller == 'index' && $action = 'index') {
            $sess = new Zend_Session_Namespace();
            $id_language = $sess->translate_lang_id;

            $seo_home = Genius_Model_Global::getContenuRow(TABLE_PREFIX . 'pages', $id_language, 'id_page', " AND id_page=1 AND id_language='$id_language'");

            $title = $seo_home['seo_title'].', '.$siteconfig->title;
            $description = $seo_home['seo_meta_description'];
            $keywords = $seo_home['seo_meta_keyword'];
        }

        $seo = str_replace('{title}', $title, $seo);
        $seo = str_replace('{description}', $description, $seo);
        $seo = str_replace('{keywords}', $keywords, $seo);

        return $seo;
    }

    public static function noscript() {
        $seo = '<noscript title="{title_noscript}">';
        $seo .= '{h1_noscript}{h2_noscript}';
        $seo .= '</noscript>';

        $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        $title_noscript = "";
        $h1_noscript = "";
        $h2_noscript = "";

        // home page
        if ($controller == 'index' && $action = 'index') {
            $sess = new Zend_Session_Namespace();
            $id_language = $sess->translate_lang_id;

            $seo_home = Genius_Model_Global::getContenuRow(TABLE_PREFIX . 'pages', $id_language, 'id_page', " AND id_page=1 AND id_language='$id_language'");

            $title_noscript = $seo_home['title_noscript'];
            $h1_noscript = $seo_home['h1_noscript'];
            $h2_noscript = $seo_home['h2_noscript'];
        }

        // title_noscript
        $seo = str_replace('{title_noscript}', $title_noscript, $seo);

        // h1_noscript
        if (strlen(trim($h1_noscript)) > 0) {
            $seo = str_replace('{h1_noscript}', "<H1>" . $h1_noscript . "</H1>", $seo);
        } else {
            $seo = str_replace('{h1_noscript}', "", $seo);
        }

        // h2_noscript
        if (strlen(trim($h2_noscript)) > 0) {
            $seo = str_replace('{h2_noscript}', "<H2>" . $h2_noscript . "</H2>", $seo);
        } else {
            $seo = str_replace('{h2_noscript}', "", $seo);
        }
        return $seo;
    }
	
	
	public static function noscript_menus() {
        $seo = '<noscript title="{title_noscript}">';
        $seo .= '{h1_noscript}{h2_noscript}';
        $seo .= '</noscript>';

        $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        $title_noscript = "";
        $h1_noscript = "";
        $h2_noscript = "";

        // home page
        if ($controller == 'index' && $action = 'index') {
            $sess = new Zend_Session_Namespace();
            $id_language = $sess->translate_lang_id;

            $seo_home = Genius_Model_Global::getContenuRow(TABLE_PREFIX . 'menus', $id_language, 'id_page', " AND id_page=1 AND id_language='$id_language'");

            $title_noscript = $seo_home['title_noscript'];
            $h1_noscript = $seo_home['h1_noscript'];
            $h2_noscript = $seo_home['h2_noscript'];
        }

        // title_noscript
        $seo = str_replace('{title_noscript}', $title_noscript, $seo);

        // h1_noscript
        if (strlen(trim($h1_noscript)) > 0) {
            $seo = str_replace('{h1_noscript}', "<H1>" . $h1_noscript . "</H1>", $seo);
        } else {
            $seo = str_replace('{h1_noscript}', "", $seo);
        }

        // h2_noscript
        if (strlen(trim($h2_noscript)) > 0) {
            $seo = str_replace('{h2_noscript}', "<H2>" . $h2_noscript . "</H2>", $seo);
        } else {
            $seo = str_replace('{h2_noscript}', "", $seo);
        }
        return $seo;
    }
	
	public static function getMetasMenu($id_menu){
		$sess = new Zend_Session_Namespace();
		$id_language = $sess->translate_lang_id;
		$menus = Genius_Model_Global::selectRow(TABLE_PREFIX . 'menus_multilingual',"*","id_menu = '$id_menu' AND id_language='$id_language'");
		return $menus;
	}
	
	public static function getMetasServices($id_service){
		$sess = new Zend_Session_Namespace();
		$id_language = $sess->translate_lang_id;
		$services = Genius_Model_Global::selectRow(TABLE_PREFIX . 'services_multilingual',"*","id_service = '$id_service' AND id_language='$id_language'");
		return $services;
	}
	
	public static function getMetasProducts($id_product){
		$sess = new Zend_Session_Namespace();
		$id_language = $sess->translate_lang_id;
		$product = Genius_Model_Global::selectRow(TABLE_PREFIX . 'products_multilingual',"*","id_product = '$id_product' AND id_language='$id_language'");
		return $product;
	}

}