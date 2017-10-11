<?php

class Admin_VendeursController extends Genius_AbstractController {

    public function indexAction() {
        $this->view->headTitle()->append('Liste des vendeurs');
        $this->view->headMeta()->appendName('description', "Immo");
        $this->view->headMeta()->appendName('keyword', "Immo");
        $this->view->vendeurs = Genius_Model_Global::select(TABLE_PREFIX . 'vendeurs', "*", "id is not null");
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/vendeurs-index.js', 'text/javascript');
    }

    public function modifyAction() {
        $this->view->headTitle()->append('Edit Vendeur');
        //$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/vendeur-modify.js', 'text/javascript');

        global $params;
        global $siteconfig;

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $vendeurs = array();
        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $vendeurs = Genius_Model_Global::selectRow(TABLE_PREFIX . "vendeurs", '*', $where);
                if ($_POST) {
                    $id = (int) $_POST['Vendeurs']['id'];
                    $nom = $_POST['Vendeurs']['nom'];
                    $telephone = $_POST['Vendeurs']['phone'];
                    $email = $_POST['Vendeurs']['email'];

                    if (!empty($_FILES['photo']['name'])) {
                        $photo = $_FILES['photo']['name'];
                        $ext = explode('.', $photo);
                        $ext = $ext[1];
                        $time = time();
                        $new_filename = $time . '.' . $ext;
                        $avatar_filename = $time . '-avatar.' . $ext;

                        if (($ext == 'jpeg') || ($ext == 'jpg') || ($ext == 'png')|| ($ext == 'gif')) {
                            move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_PATH . 'vendeurs/' . $new_filename);
                            $data = array(
                                'nom' => $nom
                                , 'telephone' => $telephone
                                , 'email' => $email
                                , 'photo' => $new_filename
                            );
                            Genius_Model_Global::update(TABLE_PREFIX . 'vendeurs', $data, 'id=' . $id);
                            // resize image
                            $resizeObj = new Genius_Class_Resize(UPLOAD_PATH . 'vendeurs/' . $new_filename);
                            $resizeObj->resizeImage(90, 90, 'auto');
                            $resizeObj->saveImage(UPLOAD_PATH . 'vendeurs/' . $avatar_filename, 80);
                        } else {
                            echo "<<<= Erreur d'extension du fichier";
                            die();
                        }
                    } else {
                        $data = array(
                            'nom' => $nom
                            , 'telephone' => $telephone
                            , 'email' => $email
                        );
                        Genius_Model_Global::update(TABLE_PREFIX . 'vendeurs', $data, 'id=' . $id);
                    }
                    $this->_redirect('/admin/vendeurs');
                }
                break;
            case 'add':
                if ($_POST) {
                    $nom = $_POST['Vendeurs']['nom'];
                    $telephone = $_POST['Vendeurs']['phone'];
                    $email = $_POST['Vendeurs']['email'];

                    if (!empty($_FILES['photo']['name'])) {
                        $photo = $_FILES['photo']['name'];
                        $ext = explode('.', $photo);
                        $ext = $ext[1];
                        $time = time();
                        $new_filename = $time . '.' . $ext;
                        $avatar_filename = $time . '-avatar.' . $ext;

                        if (($ext == 'jpeg') || ($ext == 'jpg') || ($ext == 'png')) {
                            move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_PATH . 'vendeurs/' . $new_filename);
                            $data = array(
                                'nom' => $nom
                                , 'telephone' => $telephone
                                , 'email' => $email
                                , 'photo' => $new_filename
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'vendeurs', $data);
                            // resize image
                            $resizeObj = new Genius_Class_Resize(UPLOAD_PATH . 'vendeurs/' . $new_filename);
                            $resizeObj->resizeImage(90, 90, 'auto');
                            $resizeObj->saveImage(UPLOAD_PATH . 'vendeurs/' . $avatar_filename, 80);
                        } else {
                            echo "<<<= Erreur d'extension du fichier";
                            die();
                        }
                    } else {
                        $data = array(
                            'nom' => $nom
                            , 'telephone' => $telephone
                            , 'email' => $email
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'vendeurs', $data);
                    }
                    $this->_redirect('/admin/vendeurs');
                }
                break;
            default:
                break;
        }
        $this->view->vendeurs = $vendeurs;
    }

    public function getAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    'id'
                    , 'nom'
                    , 'email'
                    , 'telephone'
                    , 'photo'
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
        //$sWhere .= (trim($sWhere)!="") ? "dsd" ;

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS id
				,nom
				,email
				,telephone
				,photo
			FROM " . TABLE_PREFIX . "vendeurs
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
        $path = UPLOAD_URL . 'vendeurs/';
        if (!empty($rResult)) {
            foreach ($rResult as $k => $item) {
                $row = array();
                // traitement avatar 
                $p = explode('.', $item['photo']);
                $p_name = $p[0] . '-avatar.' . $p[1];
                $row[0] = '<input type="checkbox" class="styled" value="' . $item['id'] . '" name="checked[]" />';
                $row[1] = '<img src="' . $path . $p_name . '">';
                $row[2] = $item['nom'];
                $row[3] = $item['email'];
                $row[4] = $item['telephone'];


                $id = $item['id'];
                $url_edit = '/admin/vendeurs/modify/do/edit/id/' . $item['id'];

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
                $actions .= '<li><a title="" class="tip delete" id="' . $id . '" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';

                $row[5] = $actions;

                $output['aaData'][] = $row;
            }
        }

        echo json_encode($output);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'vendeurs', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'vendeurs', $where);
            }
        }

        echo 1;
    }

}
?>