<?php

class Admin_ServicesController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getnews', 'html');
        $ajaxContext->initContext();
    }

    public function indexAction() {
        $this->view->headTitle()->append('Services');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/services-index.js', 'text/javascript');
    }
	public function modifyAction() {
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/services-modify.js', 'text/javascript');
        $this->view->headTitle()->append('Edit Services');
		global $params;
        global $siteconfig;
        $languages = Genius_Model_Language::getLanguages();

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
		$services = array();
		switch ($do) {
            case 'edit':
			$id = (int) $this->_getParam('id');
			$services = Genius_Model_Services::getServicesById($id);
			if ($_POST) {
                    /*
                     * 2 steps :
                     * 1. update in ec_services
                     * 1. update in ec_services_multilingual
                     */

                    // 1. update in ec_services
					$id = (int) $_POST['Services']['id'];
										
                    $data_services = array(
                        'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::update(TABLE_PREFIX . 'services', $data_services,"id = $id");
					
					if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
							$id_language = $item['id'];
                            $seo_title = Genius_Class_Utils::idml($_POST['Services'], 'seo_title_' . $item['abbreviation'], $_POST['Services']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Services'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Services']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Services'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Services']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Services'], 'title_noscript_' . $item['abbreviation'], $_POST['Services']['title_noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Services'], 'h1_noscript_' . $item['abbreviation'], $_POST['Services']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Services'], 'h2_noscript_' . $item['abbreviation'], $_POST['Services']['h2_noscript_' . DEFAULT_LANG_ABBR]);
								
                            $title = Genius_Class_Utils::idml($_POST['Services'], 'title_' . $item['abbreviation'], $_POST['Services']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Services'], 'text_' . $item['abbreviation'], $_POST['Services']['text_' . DEFAULT_LANG_ABBR]);
							$link = Genius_Class_Utils::idml($_POST['Services'], 'link_' . $item['abbreviation'], $_POST['Services']['link_' . DEFAULT_LANG_ABBR]);
							$data_services_multilingual = array(
                                'title' => $title
                                , 'text' => $text
                                , 'link' => $link
								, 'seo_title' => $seo_title
								, 'seo_meta_description' => $seo_meta_description
								, 'seo_meta_keyword' => $seo_meta_keyword
								, 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );
                            Genius_Model_Global::update(TABLE_PREFIX . 'services_multilingual', $data_services_multilingual," id_service=$id AND id_language=$id_language ");
						}
					}
					$this->_redirect('/admin/services');
			}
			
			break;

            case 'add':
			
			if ($_POST) {

                    /*
                     * 2 steps :
                     * 1. insert in ec_services
                     * 1. insert in ec_services_multilingual
                     */

                    // 1. insert in genius_pages
                    $data_services = array(
                        'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::insert(TABLE_PREFIX . 'services', $data_services);
                    $lastId = Genius_Model_Global::lastId();
					
					if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Services'], 'title_' . $item['abbreviation'], $_POST['Services']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Services'], 'text_' . $item['abbreviation'], $_POST['Services']['text_' . DEFAULT_LANG_ABBR]);
							$link = Genius_Class_Utils::idml($_POST['Services'], 'link_' . $item['abbreviation'], $_POST['Services']['link_' . DEFAULT_LANG_ABBR]);
							$data_services_multilingual = array(
                                'id_service' => $lastId
                                , 'id_language' => $item['id']
                                , 'title' => $title
                                , 'text' => $text
                                , 'link' => $link
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'services_multilingual', $data_services_multilingual);
						}
					}
					$this->_redirect('/admin/services');
			}
			
			break;

            default:
            break;
		}
		$this->view->services = $services;
	}
	public function getservicesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    's.id'
                    , 'sm.title'
                    , 'sm.text'
                    , 's.update_time'
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
        $sWhere .= (trim($sWhere) != "") ? " AND (sm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (sm.id_language=" . DEFAULT_LANG_ID . ") ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS s.id
				,IF(s.update_time='0000-00-00 00:00:00' OR s.update_time IS NULL, '', DATE_FORMAT(s.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,sm.title
				,sm.text
				,sm.link
			FROM " . TABLE_PREFIX . "services s
			LEFT JOIN " . TABLE_PREFIX . "services_multilingual sm ON s.id=sm.id_service
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

                $row[0] = '<input type="checkbox" class="styled" value="' . $item['id'] . '" name="checked[]" />';

                $row[1] = $item['title'];
                $row[2] = Genius_Class_Utils::chopText($item['text'], 200);
                $row[3] = $item['update_time'];

                $id = $item['id'];
                $url_edit = '/admin/services/modify/do/edit/id/' . $item['id'];

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
}
