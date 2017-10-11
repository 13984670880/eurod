<?php

class Admin_ProductsController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getproducts', 'html');
        $ajaxContext->addActionContext('getgroups', 'html');
        $ajaxContext->addActionContext('getmarques', 'html');
        $ajaxContext->initContext();
    }

    public function indexAction() {
        $this->view->headTitle()->append('Produits');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/products-index.js', 'text/javascript');
    }

    public function fillAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $i = 0;
        $products = Genius_Model_Global::select(TABLE_PREFIX . 'products', "*", "id is not null");
        foreach ($products as $p):
            $i++;
            $order = array('order_item' => $i);
            $id_product = $p['id'];
            Genius_Model_Global::update(TABLE_PREFIX . 'products', $order, "id = '$id_product'");
        endforeach;
        die('4');
    }
	
	public function fillreferencesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$product = Genius_Model_Product::getAllProducts();
		foreach ($product as $p):
			$ref = strip_tags($p['references']);
			if (!empty($ref)){
				Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual',array('references_sans_html'=>$ref), "id_product = '$id_product'");
			}
		endforeach;
		die('done');
	}

    public function fillsearchAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $i = 0;
        $products = Genius_Model_Global::select(TABLE_PREFIX . 'products', "*", "id is not null");
        foreach ($products as $p):
            $id_product = $p['id'];
            $product = Genius_Model_Product::getProductById($id_product);
            $product_category = Genius_Model_Product::getProductCategoryById($id_product);
            $id_marque = $product_category['id_category_box'][13];
            if (!empty($id_marque)) {
                $marque = Genius_Model_Category::getCategoryById($id_marque);
            } else {
                $marque['title_' . DEFAULT_LANG_ABBR] = "";
            }
            $search = $marque['title_' . DEFAULT_LANG_ABBR] . ' ' . $product['title_' . DEFAULT_LANG_ABBR];
            Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual', array("search" => $search), "id_product = '$id_product'");
        endforeach;
        die('4');
    }

    public function seoAction() {
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/products-modify.js', 'text/javascript');
        $this->view->headTitle()->append('Edit Products');
        global $params;
        global $siteconfig;

        $products_seo = Genius_Model_Product::getProductSeoById(1);
        if ($_POST) {
            $languages = Genius_Model_Language::getLanguages();
            $id = 1;
            if (!empty($languages)) {
                foreach ($languages as $k => $item) {
                    $seo_title = Genius_Class_Utils::idml($_POST['Products'], 'seo_title_' . $item['abbreviation'], $_POST['Products']['seo_title_' . DEFAULT_LANG_ABBR]);
                    $seo_meta_description = Genius_Class_Utils::idml($_POST['Products'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Products']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                    $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Products'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Products']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                    $noscript = Genius_Class_Utils::idml($_POST['Products'], 'title_noscript_' . $item['abbreviation'], $_POST['Products']['title_noscript_' . DEFAULT_LANG_ABBR]);
                    $h1 = Genius_Class_Utils::idml($_POST['Products'], 'h1_noscript_' . $item['abbreviation'], $_POST['Products']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                    $h2 = Genius_Class_Utils::idml($_POST['Products'], 'h2_noscript_' . $item['abbreviation'], $_POST['Products']['h2_noscript_' . DEFAULT_LANG_ABBR]);
                    $id_language = $item['id'];
                    $data_products_multilingual = array(
                        'seo_title' => $seo_title
                        , 'seo_meta_description' => $seo_meta_description
                        , 'seo_meta_keyword' => $seo_meta_keyword
                        , 'title_noscript' => $noscript
                        , 'h1_noscript' => $h1
                        , 'h2_noscript' => $h2
                    );
                    Genius_Model_Global::update(TABLE_PREFIX . 'products_seo_multilingual', $data_products_multilingual, " id_product_seo=$id AND id_language=$id_language ");
                }
                $this->_redirect('/admin/products');
            }
        }

        $this->view->products_seo = $products_seo;
    }

    public function modifyAction() {
        //$this->view->headLink()->appendStylesheet('http://vjs.zencdn.net/4.10/video-js.css');
        //$this->view->inlineScript()->prependFile('http://vjs.zencdn.net/4.10/video.js', 'text/javascript');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/products-modify.js', 'text/javascript');
        $this->view->headTitle()->append('Edit Products');

        global $params;
        global $siteconfig;
        $languages = Genius_Model_Language::getLanguages();

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $product = array();

        $this->view->modules = Genius_Model_Module::getModulesByEtat();
        $this->view->services = Genius_Model_Services::getServices();
        $this->view->products_services = array();
        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $product = Genius_Model_Product::getProductById($id);
                $categories = Genius_Model_Product::getProductCategoryById($id);
                $this->view->categories = $categories['categories_groups_box'];
                $this->view->group_list = $categories['group_list'];
                $this->view->id_module_rubrique = $categories['id_module_rubrique'];
                $this->view->id_category_group_parent = $categories['id_category_group_parent'];
                $this->view->id_category_box = $categories['id_category_box'];
                $this->view->products_services = Genius_Model_Services::getProductsServicesById($id);

                if ($_POST) {
                    /*
                     * 3 steps :
                     * 1. update genius_pages
                     * 2. update genius_pages_categories
                     * 3. update genius_pages_multilingual
                     */

                    $id = (int) $_POST['Products']['id'];

                    $promotion = empty($_POST['promotion']) ? 0 : 1;

                    // update order item

                    if ($_POST['Products']['order_item']):
                        $order_item = (int) $_POST['Products']['order_item'];
                        Genius_Model_Global::updateOrderItemProduct(TABLE_PREFIX . "products", $order_item, $id);
                    else:
                        $order_item = (int) $_POST['Products']['old_order_item'];
                    endif;

                    // 1. update genius_pages
                    $data_products = array(
                        'update_time' => date('Y-m-d H:i:s'),
                        'promotion' => $promotion
                    );
                    Genius_Model_Global::update(TABLE_PREFIX . 'products', $data_products, " id=$id ");

                    // 2. update genius_pages_categories
                    Genius_Model_Global::delete(TABLE_PREFIX . 'products_categories', " id_product=$id ");
                    Genius_Model_Global::delete(TABLE_PREFIX . 'products_services', " id_product=$id ");
                    /* if (!empty($_POST['Products']['categories'])) {
                      foreach ($_POST['Products']['categories'] as $key => $value) {
                      $data_products_categories = array(
                      'id_product' => $id
                      , 'id_category' => $value
                      );
                      Genius_Model_Global::insert(TABLE_PREFIX . 'products_categories', $data_products_categories);
                      }
                      } */
                    if (!empty($_POST['Products']['categories_marque'])) {
                        $data_products_categories = array(
                            'id_product' => $id
                            , 'id_category' => $_POST['Products']['categories_marque']
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'products_categories', $data_products_categories);
                    }

                    if (!empty($_POST['Products']['categories_type'])) {
                        $data_products_categories = array(
                            'id_product' => $id
                            , 'id_category' => $_POST['Products']['categories_type']
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'products_categories', $data_products_categories);
                    }

                    if (!empty($_POST['Products']['services'])) {
                        foreach ($_POST['Products']['services'] as $key => $value):
                            $data_products_services = array(
                                'id_product' => $id
                                , 'id_service' => $value
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'products_services', $data_products_services);
                        endforeach;
                    }


                    // 3. update genius_pages_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Products'], 'title_' . $item['abbreviation'], $_POST['Products']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Products'], 'text_' . $item['abbreviation'], $_POST['Products']['text_' . DEFAULT_LANG_ABBR]);
							$text_seo = Genius_Class_Utils::idml($_POST['Products'], 'text_seo_' . $item['abbreviation'], $_POST['Products']['text_seo_' . DEFAULT_LANG_ABBR]);
                            $references = Genius_Class_Utils::idml($_POST['Products'], 'references_' . $item['abbreviation'], $_POST['Products']['references_' . DEFAULT_LANG_ABBR]);
							$references_sans_html = Genius_Class_Utils::idml($_POST['Products'], 'references_' . $item['abbreviation'], strip_tags($_POST['Products']['references_' . DEFAULT_LANG_ABBR]));

                            $seo_title = Genius_Class_Utils::idml($_POST['Products'], 'seo_title_' . $item['abbreviation'], $_POST['Products']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Products'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Products']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Products'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Products']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Products'], 'noscript_' . $item['abbreviation'], $_POST['Products']['noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Products'], 'h1_noscript_' . $item['abbreviation'], $_POST['Products']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Products'], 'h2_noscript_' . $item['abbreviation'], $_POST['Products']['h2_noscript_' . DEFAULT_LANG_ABBR]);

                            //caractéristiques
                            $carac_1 = Genius_Class_Utils::idml($_POST['Products'], 'carac_1_' . $item['abbreviation'], $_POST['Products']['carac_1_' . DEFAULT_LANG_ABBR]);
                            $carac_2 = Genius_Class_Utils::idml($_POST['Products'], 'carac_2_' . $item['abbreviation'], $_POST['Products']['carac_2_' . DEFAULT_LANG_ABBR]);
                            $carac_3 = Genius_Class_Utils::idml($_POST['Products'], 'carac_3_' . $item['abbreviation'], $_POST['Products']['carac_3_' . DEFAULT_LANG_ABBR]);
                            $carac_4 = Genius_Class_Utils::idml($_POST['Products'], 'carac_4_' . $item['abbreviation'], $_POST['Products']['carac_4_' . DEFAULT_LANG_ABBR]);
                            $carac_5 = Genius_Class_Utils::idml($_POST['Products'], 'carac_5_' . $item['abbreviation'], $_POST['Products']['carac_5_' . DEFAULT_LANG_ABBR]);
                            $carac_6 = Genius_Class_Utils::idml($_POST['Products'], 'carac_6_' . $item['abbreviation'], $_POST['Products']['carac_6_' . DEFAULT_LANG_ABBR]);

                            $id_language = $item['id'];

                            $data_products_multilingual = array(
                                'title' => $title
                                , 'text' => $text
								, 'text_seo' => $text_seo
                                , 'carac_1' => $carac_1
                                , 'carac_2' => $carac_2
                                , 'carac_3' => $carac_3
                                , 'carac_4' => $carac_4
                                , 'carac_5' => $carac_5
                                , 'carac_6' => $carac_6
                                , 'references' => $references
								, 'references_sans_html' => $references_sans_html
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                                , 'quantite' => $_POST['quantite']
                                , 'prix' => $_POST['prix']
                                , 'accessoires_produits_associes' => $_POST['accessoires_produits_associes']
                                , 'produits_similaires' => $_POST['produits_similaires']
                                , 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );
                            Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual', $data_products_multilingual, " id_product=$id AND id_language=$id_language ");
                        }

                        // upload fiche technique PDF
                        $valid_formats = array("pdf", "PDF");
                        if (isset($_FILES['pdf']['name'][0]) && !empty($_FILES['pdf']['name'][0])) {
                            $pdf = $_FILES['pdf']['name'];
                            $count = count($_FILES['pdf']['name']) - 1;
                            $tmp = $_FILES['pdf']['tmp_name'];
                            $fiche = '';
                            $path = UPLOAD_PATH . 'fiche_technique/';
                            foreach ($pdf as $key => $file) {
                                list($txt, $ext) = explode(".", $file);
                                if (in_array($ext, $valid_formats)) {
                                    move_uploaded_file($tmp[$key], UPLOAD_PATH . 'fiche_technique/' . $file);
                                }
                                if ($key != $count) {
                                    $fiche .= $file . ',';
                                } else {
                                    $fiche .= $file;
                                }
                            }
                            $fiche_pdf = (!empty($product['fiche_technique_' . DEFAULT_LANG_ABBR])) ? $product['fiche_technique_' . DEFAULT_LANG_ABBR] . ',' . $fiche : $fiche;
                            Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual', array('fiche_technique' => $fiche_pdf), 'id_product=' . $id);
                        }
                        // upload video
                        if (!empty($_FILES['video']['name'])) {
                            $video = $_FILES['video']['name'];
                            $upload = new Zend_File_Transfer();
                            //$upload->addValidator('Extension', false, array('mp4', 'flv', 'mov', 'avi', 'mpg', 'mpeg'));
                            //if ($upload->isValid()) {
                            $ext = pathinfo($upload->getFileName(), PATHINFO_EXTENSION);
                            if (($ext == 'mp4') || ($ext == 'flv') || ($ext == 'mov') || ($ext == 'avi') || ($ext == 'mpg') || ($ext == 'mpeg')) {
                                move_uploaded_file($_FILES['video']['tmp_name'], UPLOAD_PATH . 'video/' . $video);
                                Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual', array('video' => $video), 'id_product=' . $id);

                                // where ffmpeg is located, such as /usr/sbin/ffmpeg
                                $ffmpeg = 'ffmpeg';
                                // the input video file
                                $video = UPLOAD_PATH . 'video/' . $video;
                                // where you'll save the image
                                $image = UPLOAD_PATH . 'video/' . strtotime("now") . '_thumb.jpg';
                                // default time to get the image
                                $second = 1;
                                // get the duration and a random place within that
                                $cmd = "$ffmpeg -i $video -deinterlace -an -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $image 2>&1";
                                shell_exec($cmd);
                                // crop
                                $resizeObj = new Genius_Class_Resize($image);
                                $resizeObj->resizeImage(190, 190, 'auto');
                                $resizeObj->saveImage($image, 80);
                            }
                        }
                    }
                    Genius_Model_Product::fillSearch($id);
                    $this->_redirect('/admin/products');
                }
                break;

            case 'add':

                if ($_POST) {
                    $promotion = empty($_POST['promotion']) ? 0 : 1;
                    /*
                     * 3 steps :
                     * 1. insert in genius_pages
                     * 2. insert in genius_pages_categories
                     * 3. insert in genius_pages_multilingual
                     */

                    // 1. insert in genius_pages
                    $data_products = array(
                        'create_time' => date('Y-m-d H:i:s')
                        , 'update_time' => date('Y-m-d H:i:s')
                        , 'promotion' => $promotion);
                    Genius_Model_Global::insert(TABLE_PREFIX . 'products', $data_products);
                    $lastId = Genius_Model_Global::lastId();

                    // 2. insert in genius_pages_categories
                    if (!empty($_POST['Products']['categories_marque'])) {
                        $data_products_categories = array(
                            'id_product' => $lastId
                            , 'id_category' => $_POST['Products']['categories_marque']
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'products_categories', $data_products_categories);
                    }

                    if (!empty($_POST['Products']['categories_type'])) {
                        $data_products_categories = array(
                            'id_product' => $lastId
                            , 'id_category' => $_POST['Products']['categories_type']
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'products_categories', $data_products_categories);
                    }

                    if (!empty($_POST['Products']['services'])) {
                        foreach ($_POST['Products']['services'] as $key => $value):
                            $data_products_services = array(
                                'id_product' => $id
                                , 'id_service' => $value
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'products_services', $data_products_services);
                        endforeach;
                    }

                    // 3. insert in genius_pages_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Products'], 'title_' . $item['abbreviation'], $_POST['Products']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Products'], 'text_' . $item['abbreviation'], $_POST['Products']['text_' . DEFAULT_LANG_ABBR]);
							$text_seo = Genius_Class_Utils::idml($_POST['Products'], 'text_seo_' . $item['abbreviation'], $_POST['Products']['text_seo_' . DEFAULT_LANG_ABBR]);
                            $references = Genius_Class_Utils::idml($_POST['Products'], 'references_' . $item['abbreviation'], $_POST['Products']['references_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['Products'], 'seo_title_' . $item['abbreviation'], $_POST['Products']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Products'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Products']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Products'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Products']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Products'], 'title_noscript_' . $item['abbreviation'], $_POST['Products']['title_noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Products'], 'h1_noscript_' . $item['abbreviation'], $_POST['Products']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Products'], 'h2_noscript_' . $item['abbreviation'], $_POST['Products']['h2_noscript_' . DEFAULT_LANG_ABBR]);

                            $data_products_multilingual = array(
                                'id_product' => $lastId
                                , 'id_language' => $item['id']
                                , 'title' => $title
                                , 'text' => $text
								, 'text_seo' => $text_seo
                                , 'quantite' => $_POST['quantite']
                                , 'prix' => $_POST['prix']
                                , 'accessoires_produits_associes' => $_POST['accessoires_produits_associes']
                                , 'produits_similaires' => $_POST['produits_similaires']
                                , 'references' => $references
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                                , 'quantite' => $_POST['quantite']
                                , 'prix' => $_POST['prix']
                                , 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'products_multilingual', $data_products_multilingual);
                            $lastid = Genius_Model_Global::lastId();
                        }
                        //
                        if (!empty($_FILES['pdf']['name'])) {
                            $pdf = $_FILES['pdf']['name'];
                            $upload = new Zend_File_Transfer();
                            //$upload->addValidator('Extension', false, array('pdf', 'PDF'));
                            $ext = pathinfo($upload->getFileName(), PATHINFO_EXTENSION);
                            if (($ext == 'pdf') || ($ext == 'PDF')) {
                                move_uploaded_file($_FILES['pdf']['tmp_name'], UPLOAD_PATH . 'fiche_technique/' . $pdf);
                                Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual', array('fiche_technique' => $pdf), 'id_product=' . $lastid);
                            }
                        }
                        // upload video
                        /* if (!empty($_FILES['video']['name'])) {
                          $video = $_FILES['video']['name'];
                          $upload = new Zend_File_Transfer();
                          $upload->addValidator('Extension', false, array('mp4', 'flv', 'mov', 'avi', 'mpg', 'mpeg'));
                          if ($upload->isValid()) {
                          move_uploaded_file($_FILES['video']['tmp_name'], UPLOAD_PATH . 'video/' . $video);
                          Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual', array('video' => $video), 'id_product=' . $lastid);
                          }
                          } */
                    }
                    Genius_Model_Product::fillSearch($id);
                    $this->_redirect('/admin/products');
                }

                break;

            default:
                break;
        }

        $this->view->product = $product;
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'products', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'products', $where);
            }
        }

        echo 1;
    }

    public function getproductsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $env = APPLICATION_ENV;
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
        $con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password, $config->db->params->dbname);

        $aColumns = array(
            'p.id'
            , 'p.active'
            , 'pm.title'
            , 'pm.text'
            , 'p.update_time'
        );

        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_0'] === 'asc' ? 'ASC' : 'DESC') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        } else {
            $sOrder = "ORDER BY p.order_item ASC";
        }

        /*
         * Filtering
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($con, $_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        /* Get default id_language value */
        $sWhere .= (trim($sWhere) != "") ? " AND (pm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (pm.id_language=" . DEFAULT_LANG_ID . ") ";

        $sOrder = ( $sOrder == "" ) ? "ORDER BY p.order_item ASC" : $sOrder;
        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS p.id
				,IF(p.update_time='0000-00-00 00:00:00' OR p.update_time IS NULL, '', DATE_FORMAT(p.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,p.active
				,pm.title
				,pm.text
                                ,pm.quantite
                                ,pm.prix
			FROM " . TABLE_PREFIX . "products p
			LEFT JOIN " . TABLE_PREFIX . "products_multilingual pm ON p.id=pm.id_product
			$sWhere
			$sOrder
			$sLimit
		";

        $rResult = Genius_Model_Global::query($sQuery);
        $total = Genius_Model_Global::query("SELECT FOUND_ROWS()");
        $iFilteredTotal = $total[0]['FOUND_ROWS()'];
        $iTotal = $total[0]['FOUND_ROWS()'];

        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        if (!empty($rResult)) {
            foreach ($rResult as $k => $item) {
                $row = array();
                $checked_active = ($item['active'] == 1) ? 'checked="checked"' : '';
                $row[0] = '<input type="checkbox" class="styled" value="' . $item['id'] . '" name="checked[]" />';
                $row[1] = $item['id'];
                $row[2] = $item['title'];
                $row[3] = Genius_Class_Utils::chopText($item['text'], 200);
                $row[4] = $item['quantite'];
                $row[5] = $this->getProductsCategories($item['id']);
                $row[6] = '<input type="checkbox" class="active active_center" name="archive" value="' . $item['id'] . '" ' . $checked_active . '>';
                ;
                $row[7] = $item['update_time'];
                $id = $item['id'];
                $url_edit = '/admin/products/modify/do/edit/id/' . $item['id'];

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
                $actions .= '<li><a title="" class="tip delete" id="' . $id . '" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';

                $row[8] = $actions;

                $output['aaData'][] = $row;
            }
        }

        echo json_encode($output);
    }

    public function getProductsCategories($id_product) {
        $row = '';
        $sQuery = "
				SELECT 
                                    cm.title
				FROM 
				" . TABLE_PREFIX . "categories_multilingual cm
				LEFT JOIN " . TABLE_PREFIX . "products_categories pc ON pc.id_category = cm.id_category 
				WHERE
				cm.id_language=" . DEFAULT_LANG_ID . "
				AND
				pc.id_product =" . $id_product . "
				";
        $rResult = Genius_Model_Global::query($sQuery);
        $i = 1;
        if (!empty($rResult)) {
            foreach ($rResult as $item) {
                $commas = ($i < count($rResult)) ? ', ' : '';
                $row.= '<a href="#">' . $item['title'] . "</a>" . $commas;
                $i++;
            }
        }
        return $row;
    }

    // getGroup
    public function getgroupsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id_module = $this->_getParam('id');
        $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);
        $return = "<option value=''>Selectionnez le groupe</option>";
        foreach ($groups as $group) {
            $return .= "<option value='" . $group['id_category_group'] . "'>" . $group['title'];
        }
        echo json_encode(array('value' => $return));
    }

    public function getmarquesandtypesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = $this->_getParam('id');
        $return = '';
        $p = 0;
        $name_radion_button[0] = 'marque';
        $name_radion_button[1] = 'type';
        // id_groupe
        if (!empty($id)) {
            $id_group = Genius_Model_Group::getParent($id);
            foreach ($id_group as $idgrp) {
                $categories = Genius_Model_Category::getCategoryBox($idgrp['id']);
                $groups = Genius_Model_Group::getGroupById($idgrp['id']);
                $idgrp = $idgrp['id'];
                $title = '<h6>' . $groups['title_' . DEFAULT_LANG_ABBR] . '</h6>';
                foreach ($categories['categories_list'] as $item) {
                    $return .= '<label class="radio" style="cursor: pointer">';
                    $return .= '<input type="radio" name="Products[categories_' . $name_radion_button[$p] . ']" id="categories_' . $item['id_category'] . '" value="' . $item['id_category'] . '" class="styled">';
                    $return .= $item['title'];
                    $return .= '</label>';
                }
                $title_arr[] = $title;
                $return_arr[] = $return;
                $return = '';
                $p++;
            }
            echo json_encode(array('status' => 1, 'title_1' => $title_arr[0], 'value_1' => $return_arr[0], 'title_2' => $title_arr[1], 'value_2' => $return_arr[1]));
        } else {
            $title = '<h6>Marques</h6>';
            $return = '<label class="radio" style="cursor: pointer"></label>';
            echo json_encode(array('status' => 2, 'title_1' => '', 'value_1' => '', 'title_2' => '', 'value_2' => ''));
        }
    }

    public function activeAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $value = (int) $this->_getParam('value');

        $where = "id=$id ";
        Genius_Model_Global::update(TABLE_PREFIX . 'products', array('active' => $value), $where);

        echo 1;
    }

    public function deletepdfAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $datas = array('fiche_technique'=>'');
        $where = " id_product=$id ";
        $return = Genius_Model_Global::update(TABLE_PREFIX.'products_multilingual',$datas,$where);

        echo 1;
    }

}
