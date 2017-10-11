<?php

class Admin_ContactsController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getcontacts', 'html');
        $ajaxContext->initContext();
    }

    public function indexAction() {
        $this->view->headTitle()->append('Contacts');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/contacts-index.js', 'text/javascript');
    }

    public function modifyAction() {
        $this->view->headTitle()->append('Edit Contact');

        global $params;
        global $siteconfig;

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $contact = array();

        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $contact = Genius_Model_Global::selectRow(TABLE_PREFIX."contacts",'*','id='.$id);
                //$contact = Genius_Model_Contact::getContactById($id);

                if ($_POST) {

                    /*
                     * 2 steps :
                     * 1. insert in genius_slides
                     * 2. insert in genius_pages_multilingual
                     */

                    $id = (int) $_POST['Contacts']['id'];

                    // 1. update genius_slides
                    $data_contacts = array(
                        'service_commercial' => $this->view->escape($_POST['Contacts']['service_commercial'])
                        , 'service_commercial_mail' => $this->view->escape($_POST['Contacts']['service_commercial_mail'])
                        , 'service_technique' => $this->view->escape($_POST['Contacts']['service_technique'])
                        , 'service_technique_mail' => $this->view->escape($_POST['Contacts']['service_technique_mail'])
                        , 'service_comptabilite' => $this->view->escape($_POST['Contacts']['service_comptabilite'])
                        , 'service_comptabilite_mail' => $this->view->escape($_POST['Contacts']['service_comptabilite_mail'])
                        , 'service_logistique' => $this->view->escape($_POST['Contacts']['service_logistique'])
                        , 'service_logistique_mail' => $this->view->escape($_POST['Contacts']['service_logistique_mail'])
                        , 'telephone' => $this->view->escape($_POST['Contacts']['telephone'])
                        , 'fax' => $this->view->escape($_POST['Contacts']['fax'])
                        , 'email' => $this->view->escape($_POST['Contacts']['email'])
                        , 'adresse' => $this->view->escape($_POST['Contacts']['adresse'])
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::update(TABLE_PREFIX . 'contacts', $data_contacts, " id=$id ");

                    $this->_redirect('/admin/contacts');
                }

                break;

            case 'add':

                if ($_POST) {

                    /*
                     * 2 steps :
                     * 1. insert in genius_slides
                     * 2. insert in genius_pages_multilingual
                     */

                    // 1. insert in genius_slides
                    $data_contacts = array(
                        'service_commercial' => $this->view->escape($_POST['Contacts']['service_commercial'])
                        , 'service_commercial_mail' => $this->view->escape($_POST['Contacts']['service_commercial_mail'])
                        , 'service_technique' => $this->view->escape($_POST['Contacts']['service_technique'])
                        , 'service_technique_mail' => $this->view->escape($_POST['Contacts']['service_technique_mail'])
                        , 'service_comptabilite' => $this->view->escape($_POST['Contacts']['service_comptabilite'])
                        , 'service_comptabilite_mail' => $this->view->escape($_POST['Contacts']['service_comptabilite_mail'])
                        , 'service_logistique' => $this->view->escape($_POST['Contacts']['service_logistique'])
                        , 'service_logistique_mail' => $this->view->escape($_POST['Contacts']['service_logistique_mail'])
                        , 'telephone' => $this->view->escape($_POST['Contacts']['telephone'])
                        , 'fax' => $this->view->escape($_POST['Contacts']['fax'])
                        , 'email' => $this->view->escape($_POST['Contacts']['email'])
                        , 'adresse' => $this->view->escape($_POST['Contacts']['adresse'])
                        , 'create_time' => date('Y-m-d H:i:s')
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::insert(TABLE_PREFIX . 'contacts', $data_contacts);

                    $this->_redirect('/admin/contacts');
                }

                break;

            default:
                break;
        }

        $this->view->contact = $contact;
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'contacts', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'contacts', $where);
            }
        }

        echo 1;
    }

    public function getcontactsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    'c.id'
                    , 'c.telephone'
                    , 'c.email'
                    ,'c.adresse'
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
        //$sWhere .= (trim($sWhere) != "") ? " AND (lm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (lm.id_language=" . DEFAULT_LANG_ID . ") ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS c.id
				,IF(c.update_time='0000-00-00 00:00:00' OR c.update_time IS NULL, '', DATE_FORMAT(c.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,c.telephone
				,c.fax
                                ,c.email
                                ,c.adresse
			FROM " . TABLE_PREFIX . "contacts c
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

                $row[1] = 'Eurocomputer';
                $row[2] = $item['telephone'];
                $row[3] = $item['email'];
                $row[4] = $item['adresse'];
                $row[5] = $item['update_time'];

                $id = $item['id'];
                $url_edit = '/admin/contacts/modify/do/edit/id/' . $item['id'];

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
                //$actions .= '<li><a title="" class="tip delete" id="' . $id . '" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';

                $row[6] = $actions;

                $output['aaData'][] = $row;
            }
        }

        echo json_encode($output);
    }

}
