<?php

class Admin_TopsController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getnews', 'html');
		$ajaxContext->initContext();
	}
	public function indexAction()
	{ 
		$this->view->headTitle()->append('Tops');	
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/tops-index.js', 'text/javascript');	
		$contenu = Genius_Class_Top::getTop(1);
		$results = Genius_Class_Utils::creerFichier('/application/modules/default/views/scripts/statics','tops','html',$contenu,'777');	 	 
	}
	
	public function modifyAction()
	{ 
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/tops-modify.js', 'text/javascript');
        $this->view->headTitle()->append('Edit Tops');
		global $params;
        global $siteconfig;

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
		$tops = array();
		switch ($do) {
            case 'edit':
			$id = (int) $this->_getParam('id');
			$tops = Genius_Model_Tops::getTopsById($id);
			if ($_POST) {
                    /*
                     * 2 steps :
                     * 1. update in ec_tops
                     */

                    // 1. update in ec_tops
					$id = (int) $_POST['Tops']['id'];
										
                    $data_tops = array(
						'title_top' => $_POST['Tops']['title_top']
						, 'title_top_1' => $_POST['Tops']['title_top_1']
						, 'title_top_2' => $_POST['Tops']['title_top_2']
						, 'title_top_3' => $_POST['Tops']['title_top_3']
						, 'id_order_marque' => $_POST['Tops']['id_order_marque']
						, 'format_menu_1' => $_POST['Tops']['format_menu_1']
						, 'format_menu_2' => $_POST['Tops']['format_menu_2']
						, 'format_menu_3' => $_POST['Tops']['format_menu_3']
						, 'id_order_service' => $_POST['Tops']['id_order_service']
						, 'id_order_marque_service' => $_POST['Tops']['id_order_marque_service']
						, 'format_url_menu_1' => $_POST['Tops']['format_url_menu_1']
						, 'format_url_menu_2' => $_POST['Tops']['format_url_menu_2']
						, 'format_url_menu_3' => $_POST['Tops']['format_url_menu_3']
						, 'id_order_product' => $_POST['Tops']['id_order_product']
						,'update_time' => date('Y-m-d H:i:s')
					);
                    Genius_Model_Global::update(TABLE_PREFIX . 'tops', $data_tops,"id = $id");
					$this->_redirect('/admin/tops');
			}
			
			break;

            case 'add':
			
			if ($_POST) {

                    /*
                     * 2 steps :
                     * 1. insert in ec_tops
                     */

                    // 1. insert in genius_pages
                    $data_tops = array(
						'title_top' => $_POST['Tops']['title_top']
						, 'title_top_1' => $_POST['Tops']['title_top_1']
						, 'title_top_2' => $_POST['Tops']['title_top_2']
						, 'title_top_3' => $_POST['Tops']['title_top_3']
						, 'id_order_marque' => $_POST['Tops']['id_order_marque']
						, 'format_menu_1' => $_POST['Tops']['format_menu_1']
						, 'format_menu_2' => $_POST['Tops']['format_menu_2']
						, 'format_menu_3' => $_POST['Tops']['format_menu_3']
						, 'id_order_service' => $_POST['Tops']['id_order_service']
						, 'id_order_marque_service' => $_POST['Tops']['id_order_marque_service']
						, 'format_url_menu_1' => $_POST['Tops']['format_url_menu_1']
						, 'format_url_menu_2' => $_POST['Tops']['format_url_menu_2']
						, 'format_url_menu_3' => $_POST['Tops']['format_url_menu_3']
						, 'id_order_product' => $_POST['Tops']['id_order_product']
						,'update_time' => date('Y-m-d H:i:s')
					);
                    Genius_Model_Global::insert(TABLE_PREFIX . 'tops', $data_tops);
                   
					$this->_redirect('/admin/tops');
			}
			
			break;

            default:
            break;
		}
		$this->view->tops = $tops;	 	 
	}
	
	public function gettopsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    't.id'
                    , 't.title_top'
                    , 't.update_time'
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
        //$sWhere .= (trim($sWhere) != "") ? " AND (sm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (sm.id_language=" . DEFAULT_LANG_ID . ") ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS t.id
				,IF(t.update_time='0000-00-00 00:00:00' OR t.update_time IS NULL, '', DATE_FORMAT(t.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,t.title_top
			FROM " . TABLE_PREFIX . "tops t
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

                $row[1] = $item['title_top'];
                $row[2] = $item['update_time'];

                $id = $item['id'];
                $url_edit = '/admin/tops/modify/do/edit/id/' . $item['id'];

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
                $actions .= '</ul>';

                $row[3] = $actions;

                $output['aaData'][] = $row;
            }
        }

        echo json_encode($output);
    }
}