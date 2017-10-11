<?php

/**
 * SEO add-on
 */
$this->attach('view_head', 1, function($view) {
    global $siteconfig;
    $current_url = $_SERVER['REQUEST_URI'];
    $front = Zend_Controller_Front::getInstance();
    $request = $front->getRequest();
    $controller = $request->getControllerName();
    $action = $request->getActionName();
    $t = Zend_Registry::get("Zend_Translate");

    $site_name = $siteconfig->title;
    $url = Genius_Plugin_Common::getFullBaseUrl();
    if ($controller == "index") {
        $menus = Genius_Class_Seo::getMetasMenu(1);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $menus['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $menus['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $menus['seo_meta_keyword'] . '">';
    } elseif ($controller == "error") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Page introuvable") . '-' . $site_name . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="">';
        echo '<meta name="keywords" content="">';
    } elseif ($controller == "results") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Résultats recherche") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Imprimantes, micro, pc, hp") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Imprimantes, micro, pc, hp") . '">';
    } elseif ($controller == "fiche") {
		$products_seo = Genius_Model_Product::getProductSeoById(1);
		$seo_title = $products_seo['seo_title_'.DEFAULT_LANG_ABBR];
		$seo_meta_description = $products_seo['seo_meta_description_'.DEFAULT_LANG_ABBR];
		$title_noscript = $products_seo['title_noscript_'.DEFAULT_LANG_ABBR];

        $id_product = $request->getParam('id_product');
        $product_metas = Genius_Class_Seo::getMetasProducts($id_product);
        $product = Genius_Model_Product::getProductById($id_product);
        $product_category = Genius_Model_Product::getProductCategoryById($id_product);
        $id_marque = $product_category['id_category_box'][13];
        $id_type = $product_category['id_category_box'][14];
        $marque = Genius_Model_Category::getCategoryById($id_marque);
        $type = Genius_Model_Category::getCategoryById($id_type);
        $categories = Genius_Model_Global::selectRow(TABLE_PREFIX . 'categories', "id_category_group", "id = '$id_type'");
        $id_category_group = $categories['id_category_group'];
        $group = Genius_Model_Group::getGroupById($id_category_group);
		$id_parent = $group['id_parent'];
        $group_parent = Genius_Model_Group::getGroupById($id_parent);
		
		//££Modele££
		$modele_str = $product['title_' . DEFAULT_LANG_ABBR];
		//££Marque££
		$marque_str = $marque['title_'.DEFAULT_LANG_ABBR];
		//££Souscategorie££
		$souscategorie_str = $type['title_'.DEFAULT_LANG_ABBR];
		//££Categorie££
		$categorie_str = $group_parent['title_'.DEFAULT_LANG_ABBR];
		
		$seo_title_str = str_replace("££Marque££",$marque_str,$seo_title);
		$seo_title_str = str_replace("££Modele££",$modele_str,$seo_title_str);
		$seo_title_str = str_replace("££Categorie££",$categorie_str,$seo_title_str);
		$seo_title_str = str_replace("££Souscategorie££",$souscategorie_str,$seo_title_str);
		
		$seo_meta_description_str = str_replace("££Marque££",$marque_str,$seo_meta_description);
		$seo_meta_description_str = str_replace("££Modele££",$modele_str,$seo_meta_description_str);
		$seo_meta_description_str = str_replace("££Categorie££",$categorie_str,$seo_meta_description_str);
		$seo_meta_description_str = str_replace("££Souscategorie££",$souscategorie_str,$seo_meta_description_str);
		
		$title_noscript_str = str_replace("££Marque££",$marque_str,$title_noscript);
		$title_noscript_str = str_replace("££Modele££",$modele_str,$title_noscript_str);
		$title_noscript_str = str_replace("££Categorie££",$categorie_str,$title_noscript_str);
		$title_noscript_str = str_replace("££Souscategorie££",$souscategorie_str,$title_noscript_str);
        
        if (!empty($product_metas['seo_title'])) {
            $seo_title = $product_metas['seo_title'];
        } else {
            $seo_title = $t->translate('Eurocomputer') . ' - ' . $group_parent['title_' . DEFAULT_LANG_ABBR] . ' - ' . $marque['title_' . DEFAULT_LANG_ABBR] . ' - ' . $product['title_' . DEFAULT_LANG_ABBR];
        }
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $seo_title_str . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $seo_meta_description_str . '">';
        echo '<meta name="keywords" content="' . $product_metas['seo_meta_keyword'] . '">';
    } elseif ($controller == "page" && ( $action == "v1" || $action == "p1" || $action == "m1")) {
        $id_category_group = $request->getParam('id_category_group');
        $id_category = $request->getParam('id_category');
        $id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
        $types = Genius_Model_Category::getCategoryBox($id_type);
        $group = Genius_Model_Group::getGroupById($id_category_group);
        $all_marques = true;
        if ($id_category_group && $id_category) {
            $all_marques = false;
        }
        if ($all_marques) {
            $marque_titre = $t->translate("Toutes marques");
            $seo_title = $group['seo_title_' . DEFAULT_LANG_ABBR];
            $seo_meta_description = $group['seo_meta_description_' . DEFAULT_LANG_ABBR];
            $seo_meta_keyword = $group['seo_meta_keyword_' . DEFAULT_LANG_ABBR];
        } else {
            $category = Genius_Model_Category::getCategoryById($id_category);
            $seo_title = $category['seo_title_' . DEFAULT_LANG_ABBR];
            $seo_meta_description = $category['seo_meta_description_' . DEFAULT_LANG_ABBR];
            $seo_meta_keyword = $category['seo_meta_keyword_' . DEFAULT_LANG_ABBR];
        }

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $seo_title . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $seo_meta_description . '">';
        echo '<meta name="keywords" content="' . $seo_meta_keyword . '">';
    } elseif ($controller == "page" && ( $action == "reparation" || $action == "vente" || $action == "maintenance" || $action == "location" || $action == "audit" || $action == "reprise" || $action == "smartprint")) {
        $datas['reparation'] = 1;
        $datas['vente'] = 2;
        $datas['maintenance'] = 4;
        $datas['location'] = 5;
        $datas['audit'] = 6;
        $datas['reprise'] = 7;
        $datas['smartprint'] = 8;
        $services = Genius_Class_Seo::getMetasServices($datas[$action]);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $services['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $services['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $services['seo_meta_keyword'] . '">';
    } elseif ($controller == "page" && ( $action == "articlereparation" || $action == "articlevente" || $action == "articlemaintenance" || $action == "articlelocation" || $action == "articleaudit" || $action == "articlereprise")) {
        $datas['articlereparation'] = $t->translate("Réparation");
        $datas['articlevente'] = $t->translate("Vente");
        $datas['articlemaintenance'] = $t->translate("Maintenance");
        $datas['articlelocation'] = $t->translate("Location");
        $datas['articleaudit'] = $t->translate("Audit");
        $datas['articlereprise'] = $t->translate("Reprise");
        $id_category_group = $request->getParam('id_category_group');
        $id_category = $request->getParam('id_category');
        $marque = Genius_Model_Category::getCategoryById($id_category);
        $group = Genius_Model_Group::getGroupById($id_category_group);
        $title_seo = $t->translate("Eurocomputer") . ' - ' . $datas[$action] . ' - ' . $marque['title_' . DEFAULT_LANG_ABBR] . ' - ' . $group['title_' . DEFAULT_LANG_ABBR];
        $seo_meta_description = "";
        $seo_meta_keyword = "";
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $title_seo . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $seo_meta_description . '">';
        echo '<meta name="keywords" content="' . $seo_meta_keyword . '">';
    } elseif ($controller == "marque" && ($action == "tracabilite" || $action == "imprimantes" || $action == "micro" )) {
        $datas['tracabilite'] = array("module" => $t->translate('Marque Traçabilité'), "id_module" => 10);
        $datas['imprimantes'] = array("module" => $t->translate('Marque Imprimantes'), "id_module" => 11);
        $datas['micro'] = array("module" => $t->translate('Marque Micro'), "id_module" => 15);
        $marque_module = $datas[$action];
        $id_module = $marque_module['id_module'];
        $module = Genius_Model_Global::selectRow(TABLE_PREFIX . 'modules', '*', "id = '$id_module' ");
        $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);
        foreach ($groups as $group) {
            $id_marque = Genius_Model_Group::getIdMarqueGroup($group['id_category_group']);
            $marques = Genius_Model_Category::getCategoryBox($id_marque);
            $i = 1;
            foreach ($marques['categories_list'] as $marque):
                if ($i == 1) {
                    $keywords .= $marque['title'];
                } else {
                    $keywords .= ', ' . $marque['title'];
                }
                $i++;
            endforeach;
        }

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Eurocomputer") . ' - ' . $marque_module['module'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $marque_module['module'] . '">';
        echo '<meta name="keywords" content="' . $marque_module['module'] . ', ' . $keywords . '">';
    } elseif ($controller == "devis") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Demander un devis - Eurocomputer") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Demander un devis sur Eurocomputer") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Demander, devis, imprimantes, micro, pc") . '">';
    } elseif ($controller == "comparaison") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Tableau comparatif - Eurocomputer") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Tableau comparatif sur Eurocomputer") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Tableau comparatif,produits,imprimantes,micro,pc") . '">';
    }
    if ($current_url == "/tracabilite.html") {
        $menus = Genius_Class_Seo::getMetasMenu(3);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $menus['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $menus['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $menus['seo_meta_keyword'] . '">';
    } elseif ($current_url == "/imprimantes.html") {
        $menus = Genius_Class_Seo::getMetasMenu(4);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $menus['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $menus['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $menus['seo_meta_keyword'] . '">';
    } elseif ($current_url == "/micro.html") {
        $menus = Genius_Class_Seo::getMetasMenu(5);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $menus['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $menus['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $menus['seo_meta_keyword'] . '">';
    } elseif ($current_url == "/reparationservices.html") {
        $menus = Genius_Class_Seo::getMetasMenu(6);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $menus['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $menus['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $menus['seo_meta_keyword'] . '">';
    } elseif ($current_url == "/marque.html") {
        $menus = Genius_Class_Seo::getMetasMenu(7);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $menus['seo_title'] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $menus['seo_meta_description'] . '">';
        echo '<meta name="keywords" content="' . $menus['seo_meta_keyword'] . '">';
    } elseif ($current_url == "/contact") {
        $page = Genius_Model_Page::getPageById(32);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $page['seo_title_' . DEFAULT_LANG_ABBR] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $page['seo_meta_description_' . DEFAULT_LANG_ABBR] . '">';
        echo '<meta name="keywords" content="' . $page['seo_meta_keyword_' . DEFAULT_LANG_ABBR] . '">';
    } elseif ($current_url == "/apropos/histoire") {
        $page = Genius_Model_Page::getPageById(29);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $page['seo_title_' . DEFAULT_LANG_ABBR] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $page['seo_meta_description_' . DEFAULT_LANG_ABBR] . '">';
        echo '<meta name="keywords" content="' . $page['seo_meta_keyword_' . DEFAULT_LANG_ABBR] . '">';
    } elseif ($current_url == "/apropos/mentionslegales") {
        $page = Genius_Model_Page::getPageById(27);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $page['seo_title_' . DEFAULT_LANG_ABBR] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $page['seo_meta_description_' . DEFAULT_LANG_ABBR] . '">';
        echo '<meta name="keywords" content="' . $page['seo_meta_keyword_' . DEFAULT_LANG_ABBR] . '">';
    } elseif ($current_url == "/societe") {
        $page = Genius_Model_Page::getPageById(2);
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $page['seo_title_' . DEFAULT_LANG_ABBR] . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $page['seo_meta_description_' . DEFAULT_LANG_ABBR] . '">';
        echo '<meta name="keywords" content="' . $page['seo_meta_keyword_' . DEFAULT_LANG_ABBR] . '">';
    } elseif ($current_url == "/marque/imprimantes") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Eurocomputer - Marque imprimantes") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Marque imprimantes") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Marque imprimantes,") . '">';
    } elseif ($current_url == "/marque/micro") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Eurocomputer - Marque micro") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Marque micro") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Marque micro,") . '">';
    } elseif ($current_url == "/page/confirmation") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Confirmation de votre demande de devis") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Devis") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Devis") . '">';
    }elseif ($current_url == "/confirmation-devis.html" || $current_url == "/confirmation-reparation.html") {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<title>' . $t->translate("Confirmation de votre demande de devis") . '</title>';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<meta name="author" content="' . $site_name . '">';
        echo '<meta name="description" content="' . $t->translate("Devis") . '">';
        echo '<meta name="keywords" content="' . $t->translate("Devis") . '">';
    }else {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    }
});

$this->attach('view_footer', 1, function($view) {
    global $siteconfig;
    $current_url = $_SERVER['REQUEST_URI'];
    $front = Zend_Controller_Front::getInstance();
    $request = $front->getRequest();
    $controller = $request->getControllerName();
    $action = $request->getActionName();
    $t = Zend_Registry::get("Zend_Translate");

    $site_name = $siteconfig->title;
    $url = Genius_Plugin_Common::getFullBaseUrl();

    if ($controller == "index") {
        $menus = Genius_Class_Seo::getMetasMenu(1);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        echo '</noscript>';
    } elseif ($controller == "results") {
        
    } elseif ($controller == "fiche") {
		$products_seo = Genius_Model_Product::getProductSeoById(1);
		$title_noscript = $products_seo['title_noscript_'.DEFAULT_LANG_ABBR];
		$seo_title = $products_seo['seo_title_'.DEFAULT_LANG_ABBR];
		
        $id_product = $request->getParam('id_product');
        $product_metas = Genius_Class_Seo::getMetasProducts($id_product);
        $product = Genius_Model_Product::getProductById($id_product);
        $product_category = Genius_Model_Product::getProductCategoryById($id_product);
        $id_marque = $product_category['id_category_box'][13];
        $id_type = $product_category['id_category_box'][14];
        $marque = Genius_Model_Category::getCategoryById($id_marque);
        $type = Genius_Model_Category::getCategoryById($id_type);
        $categories = Genius_Model_Global::selectRow(TABLE_PREFIX . 'categories', "id_category_group", "id = '$id_type'");
        $id_category_group = $categories['id_category_group'];
        $group = Genius_Model_Group::getGroupById($id_category_group);
        $id_parent = $group['id_parent'];
        $group_parent = Genius_Model_Group::getGroupById($id_parent);

		//££Modele££
		$modele_str = $product['title_' . DEFAULT_LANG_ABBR];
		//££Marque££
		$marque_str = $marque['title_'.DEFAULT_LANG_ABBR];
		//££Souscategorie££
		$souscategorie_str = $type['title_'.DEFAULT_LANG_ABBR];
		//££Categorie££
		$categorie_str = $group_parent['title_'.DEFAULT_LANG_ABBR];
		
		$title_noscript_str = str_replace("££Marque££",$marque_str,$title_noscript);
		$title_noscript_str = str_replace("££Modele££",$modele_str,$title_noscript_str);
		$title_noscript_str = str_replace("££Categorie££",$categorie_str,$title_noscript_str);
		$title_noscript_str = str_replace("££Souscategorie££",$souscategorie_str,$title_noscript_str);
		
		$seo_title_str = str_replace("££Marque££",$marque_str,$seo_title);
		$seo_title_str = str_replace("££Modele££",$modele_str,$seo_title_str);
		$seo_title_str = str_replace("££Categorie££",$categorie_str,$seo_title_str); 
		$seo_title_str = str_replace("££Souscategorie££",$souscategorie_str,$seo_title_str);
		
        echo '<noscript title = "' . $seo_title_str . '" >';
		echo $title_noscript_str;
        echo '</noscript>';
    } elseif ($controller == "page" && ( $action == "v1" || $action == "p1" || $action == "m1")) {
        $id_category_group = $request->getParam('id_category_group');
        $id_category = $request->getParam('id_category');
        $id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
        $types = Genius_Model_Category::getCategoryBox($id_type);
        $group = Genius_Model_Group::getGroupById($id_category_group);
        $all_marques = true;
        if ($id_category_group && $id_category) {
            $all_marques = false;
        }
        if ($all_marques) {
            $marque_titre = $t->translate("Toutes marques");
        } else {
            $category = Genius_Model_Category::getCategoryById($id_category);
            $marque_titre = $category['title_' . DEFAULT_LANG_ABBR];
        }
        $seo_title = $t->translate('Eurocomputer') . ' - ' . $group['title_' . DEFAULT_LANG_ABBR] . ' - ' . $marque_titre;
        $seo_meta_description = "";
        $seo_meta_keyword = "";
        echo '<noscript title = "' . $seo_title . '" >';
        echo '</noscript>';
    } elseif ($controller == "page" && ( $action == "reparation" || $action == "vente" || $action == "maintenance" || $action == "location" || $action == "audit" || $action == "reprise" || $action == "smartprint")) {
        $datas['reparation'] = 1;
        $datas['vente'] = 2;
        $datas['maintenance'] = 4;
        $datas['location'] = 5;
        $datas['audit'] = 6;
        $datas['reprise'] = 7;
        $datas['smartprint'] = 8;
        $services = Genius_Class_Seo::getMetasServices($datas[$action]);
        echo '<noscript title = "' . $services['title_noscript'] . '" >';
        echo '</noscript>';
    } elseif ($controller == "page" && ( $action == "articlereparation" || $action == "articlevente" || $action == "articlemaintenance" || $action == "articlelocation" || $action == "articleaudit" || $action == "articlereprise")) {
        $datas['articlereparation'] = $t->translate("Réparation");
        $datas['articlevente'] = $t->translate("Vente");
        $datas['articlemaintenance'] = $t->translate("Maintenance");
        $datas['articlelocation'] = $t->translate("Location");
        $datas['articleaudit'] = $t->translate("Audit");
        $datas['articlereprise'] = $t->translate("Reprise");
        $id_category_group = $request->getParam('id_category_group');
        $id_category = $request->getParam('id_category');
        $marque = Genius_Model_Category::getCategoryById($id_category);
        $group = Genius_Model_Group::getGroupById($id_category_group);
        $title_seo = $t->translate("Eurocomputer") . ' - ' . $datas[$action] . ' - ' . $marque['title_' . DEFAULT_LANG_ABBR] . ' - ' . $group['title_' . DEFAULT_LANG_ABBR];
        $seo_meta_description = "";
        $seo_meta_keyword = "";
        echo '<noscript title = "' . $title_seo . '" >';
        echo '</noscript>';
    } elseif ($controller == "marque" && ($action == "tracabilite" || $action == "imprimantes" || $action == "micro" )) {
        $datas['tracabilite'] = array("module" => $t->translate('Marque Traçabilité'), "id_module" => 10);
        $datas['imprimantes'] = array("module" => $t->translate('Marque Imprimantes'), "id_module" => 11);
        $datas['micro'] = array("module" => $t->translate('Marque Micro'), "id_module" => 15);
        $marque_module = $datas[$action];
        $id_module = $marque_module['id_module'];
        $module = Genius_Model_Global::selectRow(TABLE_PREFIX . 'modules', '*', "id = '$id_module' ");
        $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);
        foreach ($groups as $group) {
            $id_marque = Genius_Model_Group::getIdMarqueGroup($group['id_category_group']);
            $marques = Genius_Model_Category::getCategoryBox($id_marque);
            $i = 1;
            foreach ($marques['categories_list'] as $marque):
                if ($i == 1) {
                    $keywords .= $marque['title'];
                } else {
                    $keywords .= ', ' . $marque['title'];
                }
                $i++;
            endforeach;
        }
        echo '<noscript title = "' . $marque_module['module'] . '" >';
        echo '</noscript>';
    } elseif ($controller == "devis") {
        
    }
    if ($current_url == "/tracabilite.html") {
        $menus = Genius_Class_Seo::getMetasMenu(3);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $menus['h1_noscript'] . '"</h1>';
        if (!empty($menus['h2_noscript']))
            echo '<h2>"' . $menus['h2_noscript'] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/imprimantes.html") {
        $menus = Genius_Class_Seo::getMetasMenu(4);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $menus['h1_noscript'] . '"</h1>';
        if (!empty($menus['h2_noscript']))
            echo '<h2>"' . $menus['h2_noscript'] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/micro.html") {
        $menus = Genius_Class_Seo::getMetasMenu(5);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $menus['h1_noscript'] . '"</h1>';
        if (!empty($menus['h2_noscript']))
            echo '<h2>"' . $menus['h2_noscript'] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/reparationservices.html") {
        $menus = Genius_Class_Seo::getMetasMenu(6);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $menus['h1_noscript'] . '"</h1>';
        if (!empty($menus['h2_noscript']))
            echo '<h2>"' . $menus['h2_noscript'] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/marque.html") {
        $menus = Genius_Class_Seo::getMetasMenu(7);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $menus['h1_noscript'] . '"</h1>';
        if (!empty($menus['h2_noscript']))
            echo '<h2>"' . $menus['h2_noscript'] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/contact") {
        $page = Genius_Model_Page::getPageById(32);
        echo '<noscript title = "' . $page['title_noscript_' . DEFAULT_LANG_ABBR] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $page['h1_noscript_' . DEFAULT_LANG_ABBR] . '"</h1>';
        if (!empty($menus['h1_noscript_' . DEFAULT_LANG_ABBR]))
            echo '<h2>"' . $page['h2_noscript_' . DEFAULT_LANG_ABBR] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/apropos/histoire") {
        $page = Genius_Model_Page::getPageById(29);
        echo '<noscript title = "' . $page['title_noscript_' . DEFAULT_LANG_ABBR] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $page['h1_noscript_' . DEFAULT_LANG_ABBR] . '"</h1>';
        if (!empty($menus['h1_noscript_' . DEFAULT_LANG_ABBR]))
            echo '<h2>"' . $page['h2_noscript_' . DEFAULT_LANG_ABBR] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/apropos/mentionslegales") {
        $page = Genius_Model_Page::getPageById(27);
        echo '<noscript title = "' . $page['title_noscript_' . DEFAULT_LANG_ABBR] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $page['h1_noscript_' . DEFAULT_LANG_ABBR] . '"</h1>';
        if (!empty($menus['h2_noscript_' . DEFAULT_LANG_ABBR]))
            echo '<h2>"' . $page['h2_noscript_' . DEFAULT_LANG_ABBR] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/societe") {
        $menus = Genius_Class_Seo::getMetasMenu(2);
        echo '<noscript title = "' . $menus['title_noscript'] . '" >';
        if (!empty($menus['h1_noscript']))
            echo '<h1>"' . $menus['h1_noscript'] . '"</h1>';
        if (!empty($menus['h2_noscript']))
            echo '<h2>"' . $menus['h2_noscript'] . '"</h2>';
        echo '</noscript>';
    }elseif ($current_url == "/marque/imprimantes") {
        
    } elseif ($current_url == "/marque/micro") {
        
    } elseif ($current_url == "/page/confirmation") {
        
    } else {
        
    }
});
