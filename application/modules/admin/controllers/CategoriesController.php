<?php

class Admin_CategoriesController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getcategories', 'html');
        $ajaxContext->addActionContext('delete', 'html');
        $ajaxContext->addActionContext('massdelete', 'html');
        $ajaxContext->addActionContext('getajaxorderitem', 'html');
        $ajaxContext->initContext();
    }

    public function indexAction() {
        $this->view->headTitle()->append('Categories');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/categories-index.js', 'text/javascript');
    }

    public function modifyAction() {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        $this->view->headTitle()->append('Edit Category');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/categories-modify.js', 'text/javascript');

        global $params;
        global $siteconfig;
        $languages = Genius_Model_Language::getLanguages();

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $category = array();
        $this->view->is_marque = false;
        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $category = Genius_Model_Category::getCategoryById($id);
                $this->view->is_marque = Genius_Model_Category::isMarque($id);
                $this->view->is_type = Genius_Model_Category::isType($id);
                if ($_POST) {
                    /*
                     * 2 steps :
                     * 1. update genius_categories
                     * 2. update genius_categories_multilingual
                     */

                    $id = (int) $_POST['Categories']['id'];

                    // 1. update genius_categories
                    // update order item
                    if ($_POST['Categories']['order_item']):
                        $order_item = (int) $_POST['Categories']['order_item'];
                        $id_category_group = (int) $_POST['Categories']['id_category_group'];
                        Genius_Model_Global::updateOrderItemCategory(TABLE_PREFIX . "categories", $order_item, $id_category_group);
                    else:
                        $order_item = (int) $_POST['Categories']['old_order_item'];
                    endif;
                    // offres speciales
                    $is_offre_speciale = $_POST['is_offre_speciale'];
                    $activation_offre_speciale = isset($_POST['activation_offre_speciale']) ? $_POST['activation_offre_speciale'] : "";
                    $modele_pastille = (int) $_POST['Categories']['modele_pastille'];
                    $lien_offre_speciale = $_POST['Categories']['lien_offre_speciale'];
                    $id_category_offre_speciale = (int) $_POST['Categories']['categories'];
                    $data_categories = array(
                        'id_category_group' => (int) $_POST['Categories']['id_category_group']
                        , 'is_offre_speciale' => $is_offre_speciale
                        , 'etat_offre_speciale' => $activation_offre_speciale
                        , 'modele_pastille' => $modele_pastille
                        , 'lien_offre_speciale' => $lien_offre_speciale
                        , 'id_cat_offre_speciale_concerne' => $id_category_offre_speciale
                        , 'order_item' => $order_item
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    //echo"<pre>";print_r($data_categories);echo"</pre>";die();
                    Genius_Model_Global::update(TABLE_PREFIX . 'categories', $data_categories, " id=$id ");
                    
                    
                    // logo marque
                    if (!empty($_FILES['logo']['name'])) {
                        
                        $tab = array(
                            'name' => $_FILES['logo']['name']
                            , 'path' => UPLOAD_PATH . 'logo_marque/'
                            , 'id' => $id
                            , 'size' => $_FILES['logo']['size']
                            , 'tmp' => $_FILES['logo']['tmp_name']
                            , 'champ' => 'categories'
                        );
                        Genius_Class_Upload::logo($tab);
                    }

                    // 2. update genius_categories_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Categories'], 'title_' . $item['abbreviation'], $_POST['Categories']['title_' . DEFAULT_LANG_ABBR]);
                            $pastille = Genius_Class_Utils::idml($_POST['Categories'], 'pastille_' . $item['abbreviation'], $_POST['Categories']['pastille_' . DEFAULT_LANG_ABBR]);
                            $accroche = Genius_Class_Utils::idml($_POST['Categories'], 'accroche_' . $item['abbreviation'], $_POST['Categories']['accroche_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Categories'], 'text_' . $item['abbreviation'], $_POST['Categories']['text_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['Categories'], 'seo_title_' . $item['abbreviation'], $_POST['Categories']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Categories'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Categories']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Categories'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Categories']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Categories'], 'title_noscript_' . $item['abbreviation'], $_POST['Categories']['title_noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Categories'], 'h1_noscript_' . $item['abbreviation'], $_POST['Categories']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Categories'], 'h2_noscript_' . $item['abbreviation'], $_POST['Categories']['h2_noscript_' . DEFAULT_LANG_ABBR]);


                            //services
                            $text_reparation = Genius_Class_Utils::idml($_POST['Categories'], 'text_reparation_' . $item['abbreviation'], $_POST['Categories']['text_reparation_' . DEFAULT_LANG_ABBR]);
                            $text_vente = Genius_Class_Utils::idml($_POST['Categories'], 'text_vente_' . $item['abbreviation'], $_POST['Categories']['text_vente_' . DEFAULT_LANG_ABBR]);
                            $text_maintenance = Genius_Class_Utils::idml($_POST['Categories'], 'text_maintenance_' . $item['abbreviation'], $_POST['Categories']['text_maintenance_' . DEFAULT_LANG_ABBR]);
                            $text_location = Genius_Class_Utils::idml($_POST['Categories'], 'text_location_' . $item['abbreviation'], $_POST['Categories']['text_location_' . DEFAULT_LANG_ABBR]);
                            $text_audit = Genius_Class_Utils::idml($_POST['Categories'], 'text_audit_' . $item['abbreviation'], $_POST['Categories']['text_audit_' . DEFAULT_LANG_ABBR]);
                            $text_reprise = Genius_Class_Utils::idml($_POST['Categories'], 'text_reprise_' . $item['abbreviation'], $_POST['Categories']['text_reprise_' . DEFAULT_LANG_ABBR]);
                            $id_language = $item['id'];


                            $data_categories_multilingual = array(
                                'title' => $title
                                , 'pastille' => $pastille
                                , 'accroche' => $accroche
                                , 'text' => $text
                                , 'text_reparation' => $text_reparation
                                , 'text_vente' => $text_vente
                                , 'text_maintenance' => $text_maintenance
                                , 'text_location' => $text_location
                                , 'text_audit' => $text_audit
                                , 'text_reprise' => $text_reprise
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                                , 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );


                            Genius_Model_Global::update(TABLE_PREFIX . 'categories_multilingual', $data_categories_multilingual, " id_category=$id AND id_language=$id_language ");
                        }
                    }

                    $this->_redirect('/admin/categories');
                }

                break;

            case 'add':
                //echo"<pre>";print_r($_POST);echo"</pre>";die();
                if ($_POST) {
                    /*
                     * 2 steps :
                     * 1. insert in genius_categories
                     * 2. insert in genius_categories_multilingual
                     */

                    // 1. insert in genius_categories
                    // update order item
                    //echo"<pre>";print_r($_POST);echo"</pre>";die();
                    $id_category_group = (int) $_POST['Categories']['id_category_group'];
                    if ($_POST['Categories']['order_item']):
                        $order_item = (int) $_POST['Categories']['order_item'];
                        Genius_Model_Global::updateOrderItem(TABLE_PREFIX . "categories", $order_item, $id_category_group);
                    else:
                        $order_item = Genius_Model_Global::getMaxOrderItemCategory(TABLE_PREFIX . "categories", $id_category_group) + 1;
                    endif;
                    // offres speciales
                    $is_offre_speciale = $_POST['is_offre_speciale'];
                    $activation_offre_speciale = isset($_POST['activation_offre_speciale']) ? $_POST['activation_offre_speciale'] : "";
                    $modele_pastille = (int) $_POST['Categories']['modele_pastille'];
                    $lien_offre_speciale = $_POST['Categories']['lien_offre_speciale'];
                    $id_category_offre_speciale = (int) $_POST['Categories']['categories'];
                    $data_categories = array(
                        'id_category_group' => (int) $_POST['Categories']['id_category_group']
                        , 'is_offre_speciale' => $is_offre_speciale
                        , 'etat_offre_speciale' => $activation_offre_speciale
                        , 'modele_pastille' => $modele_pastille
                        , 'lien_offre_speciale' => $lien_offre_speciale
                        , 'id_cat_offre_speciale_concerne' => $id_category_offre_speciale
                        , 'order_item' => $order_item
                        , 'create_time' => date('Y-m-d H:i:s')
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::insert(TABLE_PREFIX . 'categories', $data_categories);
                    $lastId = Genius_Model_Global::lastId();
                    // logo marque
                    if (!empty($_FILES['logo']['name'])) {
                        
                        $tab = array(
                            'name' => $_FILES['logo']['name']
                            , 'path' => UPLOAD_PATH . 'logo_marque/'
                            , 'id' => $lastId
                            , 'size' => $_FILES['logo']['size']
                            , 'tmp' => $_FILES['logo']['tmp_name']
                            , 'champ' => 'categories'
                        );
                        Genius_Class_Upload::logo($tab);
                    }

                    // 2. insert in genius_categories_multilingual

                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Categories'], 'title_' . $item['abbreviation'], $_POST['Categories']['title_' . DEFAULT_LANG_ABBR]);
                            $pastille = Genius_Class_Utils::idml($_POST['Categories'], 'pastille_' . $item['abbreviation'], $_POST['Categories']['pastille_' . DEFAULT_LANG_ABBR]);
                            $accroche = Genius_Class_Utils::idml($_POST['Categories'], 'accroche_' . $item['abbreviation'], $_POST['Categories']['accroche_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Categories'], 'text_' . $item['abbreviation'], $_POST['Categories']['text_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['Categories'], 'seo_title_' . $item['abbreviation'], $_POST['Categories']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Categories'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Categories']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Categories'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Categories']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Categories'], 'title_noscript_' . $item['abbreviation'], $_POST['Categories']['title_noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Categories'], 'h1_noscript_' . $item['abbreviation'], $_POST['Categories']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Categories'], 'h2_noscript_' . $item['abbreviation'], $_POST['Categories']['h2_noscript_' . DEFAULT_LANG_ABBR]);

                            $data_categories_multilingual = array(
                                'id_category' => $lastId
                                , 'id_language' => $item['id']
                                , 'title' => $title
                                , 'pastille' => $pastille
                                , 'accroche' => $accroche
                                , 'text' => $text
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                                , 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );

                            Genius_Model_Global::insert(TABLE_PREFIX . 'categories_multilingual', $data_categories_multilingual);
                        }
                    }

                    $this->_redirect('/admin/categories');
                }

                break;

            default:
                break;
        }

        $this->view->category = $category;
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'categories', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'categories', $where);
            }
        }

        echo 1;
    }

    public function getajaxorderitemAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id_category_group = (int) $this->_getParam('id_category_group');
        $categories = Genius_Model_Category::getCategoryByIdCategoryGroup($id_category_group);

        $options = array(
            'atfirst' => array(
                'value' => 1
                , 'title' => "At first"
            )
            , 'atend' => array(
                'title' => "At end"
            )
            , 'optgrouplabel' => "After"
            , 'options' => $categories
            , 'tablename' => TABLE_PREFIX . 'categories'
            , 'id_category_group' => $id_category_group
        );
        $options = Genius_Class_Forms::optionsOrderItemCategory($options, false);
        echo $options;
    }

    public function getcategoriesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $env = APPLICATION_ENV;
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
        $con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password, $config->db->params->dbname);

        $aColumns = array(
            'c.id'
            , 'cm.title'
            , 'c.update_time'
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($con, $_GET['sSearch_' . $i]) . "%' ";
            }
        }

        /* Get default id_language value */
        $sWhere .= (trim($sWhere) != "") ? " AND (cm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (cm.id_language=" . DEFAULT_LANG_ID . ") ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS c.id
				,c.id_category_group
				,cm.title
				,IF(c.update_time='0000-00-00 00:00:00' OR c.update_time IS NULL, '', DATE_FORMAT(c.update_time,'%d %b %Y %Hh %imn')) AS update_time
			FROM " . TABLE_PREFIX . "categories c
			LEFT JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
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
                $id_category_group = $item['id_category_group'];
                $groups = Genius_Model_Group::getGroupById($id_category_group);
                $row[0] = '<input type="checkbox" class="styled" value="' . $item['id'] . '" name="checked[]" />';

                $row[1] = $item['title'];
                $row[2] = $groups['title_' . DEFAULT_LANG_ABBR];
                $row[3] = $item['update_time'];

                $id = $item['id'];
                $url_edit = '/admin/categories/modify/id/' . $item['id'] . '/do/edit';

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
                $actions .= '<li><a title="" class="tip delete" id="' . $id . '" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';

                $row[4] = $actions;

                $output['aaData'][] = $row;
            }
        }

        echo json_encode($output);
    }

    public function getcategoriesbygroupAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id_category_group = (int) $this->_getParam('id_category_group');
        $categories = Genius_Model_Category::getCategoryByIdCategoryGroup($id_category_group);
        $options = array(
            'default' => array(
                'value' => ""
                , 'title' => "Choose a category"
            )
            , 'selected' => ''
            , 'options' => $categories
        );
        $options = Genius_Class_Forms::options($options);
        echo $options;
    }

}
