<?php

class Admin_AnnoncesController extends Genius_AbstractController {

    public function indexAction() {
        $this->view->headTitle()->append('Liste des annonces');
        $this->view->headMeta()->appendName('description', "Immo");
        $this->view->headMeta()->appendName('keyword', "Immo");
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/annonces-index.js', 'text/javascript');
    }

    public function modifyAction() {
        $this->view->headTitle()->append("Détail de l'annonce");
        $this->view->headMeta()->appendName('description', "Immo");
        $this->view->headMeta()->appendName('keyword', "Immo");
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/annonces-add.js', 'text/javascript');
        $this->view->vendeurs = Genius_Model_Global::select(TABLE_PREFIX . 'vendeurs', "*", "id is not null");
        $this->view->types_annonces = Genius_Model_Global::select(TABLE_PREFIX . 'types_annonces', "*", "id is not null");
        $this->view->categories_annonces = Genius_Model_Global::select(TABLE_PREFIX . 'categories_annonces', "*", "id is not null");
        $this->view->regions = Genius_Model_Global::select(TABLE_PREFIX . 'regions', "*", "id is not null");
        $this->view->departements = Genius_Model_Global::select(TABLE_PREFIX . 'departements', "*", "id is not null");
        $this->view->villes = Genius_Model_Global::select(TABLE_PREFIX . 'villes', "*", "id is not null");

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $annonces = array();
        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $annonces = Genius_Model_Global::selectRow(TABLE_PREFIX . "annonces", '*', $where);
                if ($_POST) {
                    if (!empty($_FILES['pdf']['name'])) {
                        $pdf = $_FILES['pdf']['name'];
                        $ext = explode('.', $pdf);
                        $ext = $ext[1];
                        if (($ext == 'pdf') || ($ext == 'PDF')) {
                            move_uploaded_file($_FILES['pdf']['tmp_name'], UPLOAD_PATH . 'pdf/' . $pdf);
                            $data = array(
                                "id_type_annonce" => $_POST['id_type_annonce']
                                , "id_category_annonce" => $_POST['id_category_annonce']
                                , "id_vendeur" => $_POST['id_vendeur']
                                , 'id_region' => $_POST['id_region']
                                , 'id_departement' => $_POST['id_departement']
                                , 'title_remp' => $_POST['title_remp']
                                , 'ville' => $_POST['ville']
                                , 'code_postal' => $_POST['code_postal']
                                , 'prix' => $_POST['prix']
                                , 'superficie_totale' => $_POST['superficie_totale']
                                , 'quartier' => $_POST['quartier']
                                , 'url_remp' => $_POST['url_remp']
                                , 'description_generale' => $_POST['description_generale']
                                , 'description_1' => $_POST['description_1']
                                , 'description_2' => $_POST['description_2']
                                , 'nb_pieces' => $_POST['nb_pieces']
                                , 'nb_chambres' => $_POST['nb_chambres']
                                , 'nb_cuisines' => $_POST['nb_cuisines']
                                , 'nb_salles_bains' => $_POST['nb_salles_bains']
                                , 'nb_couchages' => $_POST['nb_couchages']
                                , 'meta_title' => $_POST['meta_title']
                                , 'meta_description' => $_POST['meta_description']
                                , 'h1' => $_POST['h1']
                                , 'h2' => $_POST['h2']
                                , 'pdf_annonce' => $pdf
                            );
                            Genius_Model_Global::update(TABLE_PREFIX . 'annonces', $data, $where);
                        } else {
                            echo "<<<= Erreur d'extension du fichier";
                            die();
                        }
                    } else {
                        $data = array(
                            "id_type_annonce" => $_POST['id_type_annonce']
                            , "id_category_annonce" => $_POST['id_category_annonce']
                            , "id_vendeur" => $_POST['id_vendeur']
                            , 'id_region' => $_POST['id_region']
                            , 'id_departement' => $_POST['id_departement']
                            , 'title_remp' => $_POST['title_remp']
                            , 'ville' => $_POST['ville']
                            , 'code_postal' => $_POST['code_postal']
                            , 'prix' => $_POST['prix']
                            , 'superficie_totale' => $_POST['superficie_totale']
                            , 'quartier' => $_POST['quartier']
                            , 'url_remp' => $_POST['url_remp']
                            , 'description_generale' => $_POST['description_generale']
                            , 'description_1' => $_POST['description_1']
                            , 'description_2' => $_POST['description_2']
                            , 'nb_pieces' => $_POST['nb_pieces']
                            , 'nb_chambres' => $_POST['nb_chambres']
                            , 'nb_cuisines' => $_POST['nb_cuisines']
                            , 'nb_salles_bains' => $_POST['nb_salles_bains']
                            , 'nb_couchages' => $_POST['nb_couchages']
                            , 'meta_title' => $_POST['meta_title']
                            , 'meta_description' => $_POST['meta_description']
                            , 'h1' => $_POST['h1']
                            , 'h2' => $_POST['h2']
                        );
                        Genius_Model_Global::update(TABLE_PREFIX . 'annonces', $data, $where);
                    }
                    $this->_redirect('/admin/annonces');
                }
                break;
            case 'add':
                if ($_POST) {

                    if (!empty($_FILES['pdf']['name'])) {
                        $pdf = $_FILES['pdf']['name'];
                        $ext = explode('.', $pdf);
                        $ext = $ext[1];

                        if (($ext == 'pdf') || ($ext == 'PDF')) {
                            move_uploaded_file($_FILES['pdf']['tmp_name'], UPLOAD_PATH . 'pdf/' . $pdf);
                            $data = array(
                                "id_type_annonce" => $_POST['id_type_annonce']
                                , "id_category_annonce" => $_POST['id_category_annonce']
                                , "id_vendeur" => $_POST['id_vendeur']
                                , 'id_region' => $_POST['id_region']
                                , 'id_departement' => $_POST['id_departement']
                                , 'title_remp' => $_POST['title_remp']
                                , 'ville' => $_POST['ville']
                                , 'code_postal' => $_POST['code_postal']
                                , 'prix' => $_POST['prix']
                                , 'superficie_totale' => $_POST['superficie_totale']
                                , 'quartier' => $_POST['quartier']
                                , 'url_remp' => $_POST['url_remp']
                                , 'description_generale' => $_POST['description_generale']
                                , 'description_1' => $_POST['description_1']
                                , 'description_2' => $_POST['description_2']
                                , 'nb_pieces' => $_POST['nb_pieces']
                                , 'nb_chambres' => $_POST['nb_chambres']
                                , 'nb_cuisines' => $_POST['nb_cuisines']
                                , 'nb_salles_bains' => $_POST['nb_salles_bains']
                                , 'nb_couchages' => $_POST['nb_couchages']
                                , 'meta_title' => $_POST['meta_title']
                                , 'meta_description' => $_POST['meta_description']
                                , 'h1' => $_POST['h1']
                                , 'h2' => $_POST['h2']
                                , 'pdf_annonce' => $pdf
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'annonces', $data);
                        } else {
                            echo "<<<= Erreur d'extension du fichier";
                            die();
                        }
                    } else {
                        $data = array(
                            "id_type_annonce" => $_POST['id_type_annonce']
                            , "id_category_annonce" => $_POST['id_category_annonce']
                            , "id_vendeur" => $_POST['id_vendeur']
                            , 'id_region' => $_POST['id_region']
                            , 'id_departement' => $_POST['id_departement']
                            , 'title_remp' => $_POST['title_remp']
                            , 'ville' => $_POST['ville']
                            , 'code_postal' => $_POST['code_postal']
                            , 'prix' => $_POST['prix']
                            , 'superficie_totale' => $_POST['superficie_totale']
                            , 'quartier' => $_POST['quartier']
                            , 'url_remp' => $_POST['url_remp']
                            , 'description_generale' => $_POST['description_generale']
                            , 'description_1' => $_POST['description_1']
                            , 'description_2' => $_POST['description_2']
                            , 'nb_pieces' => $_POST['nb_pieces']
                            , 'nb_chambres' => $_POST['nb_chambres']
                            , 'nb_cuisines' => $_POST['nb_cuisines']
                            , 'nb_salles_bains' => $_POST['nb_salles_bains']
                            , 'nb_couchages' => $_POST['nb_couchages']
                            , 'meta_title' => $_POST['meta_title']
                            , 'meta_description' => $_POST['meta_description']
                            , 'h1' => $_POST['h1']
                            , 'h2' => $_POST['h2']
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'annonces', $data);
                    }


                    $this->_redirect('/admin/annonces');
                }
                break;
            default:
                break;
        }
        $this->view->annonces = $annonces;
    }

    public function getdepartementsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id_region = $this->_getParam('id_region');
        $departements = Genius_Model_Global::select(TABLE_PREFIX . 'departements', "*", "id_region = '$id_region' ");
        $d = "";
        foreach ($departements as $data) :
            $d .= "<option value=" . $data['id'] . ">" . $data['nom'] . "</option>";
        endforeach;
        echo $d;
    }

    public function getvillesAction() {
        /* $this->_helper->layout->disableLayout();
          $this->_helper->viewRenderer->setNoRender();
          $id_departement = $this->_getParam('id_departement');
          $villes = Genius_Model_Global::select(TABLE_PREFIX . 'villes', "*", "id_departement = '$id_departement' ");
          $v = "";
          foreach ($villes as $data) :
          $v .= "<option value=" . $data['ville'] . ">" . $data['ville'] . "</option>";
          endforeach;
          echo $v; */
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id_departement = $this->_getParam('id_departement');

        //$villes = Genius_Model_Global::select(TABLE_PREFIX.'villes',"*","id_departement = '$id_departement' ");

        $where_id_dept = '';
        if ((int) $id_departement) {
            $where_id_dept = " AND v.id_departement = '$id_departement' ";
        }

        $term = trim($this->getRequest()->getParam('term'));
        $sql = "
		    SELECT v.id, v.ville
		    FROM " . TABLE_PREFIX . "villes v
		    WHERE v.ville LIKE '%$term%' $where_id_dept
		    GROUP BY v.id
		";
        $villes = Genius_Model_Global::query($sql);

        $mod_array = array();
        foreach ($villes as $k => $data) :
            $row_array['id'] = $data['id'];
            $row_array['ville'] = stripslashes($data['ville']);
            array_push($mod_array, $row_array);
        endforeach;

        echo json_encode($mod_array);
    }

    public function getAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $aColumns =
                array(
                    'id'
                    , 'title_remp'
                    , 'ville'
                    , 'prix'
                    , 'pdf_annonce'
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
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
				,title_remp
				,ville
				,prix
				,pdf_annonce
			FROM " . TABLE_PREFIX . "annonces
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

                $row[1] = $item['title_remp'];
                $row[2] = $item['ville'];
                $row[3] = $item['prix'];
                $row[4] = $item['pdf_annonce'];

                $id = $item['id'];
                $url_edit = '/admin/annonces/modify/do/edit/id/' . $item['id'];

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit annonces"><i class="icon-pencil"></i></a> </li>';

                $bool = Genius_Model_Global::selectRow(TABLE_PREFIX . "annonces_pieces", 'id_annonce', 'id_annonce =' . $item['id']);
                if ($bool):
                    $url_edit_pieces = '/admin/annonces/pieces/do/edit/id/' . $item['id'];
                    $actions .= '<li><a title="" class="tip" href="' . $url_edit_pieces . '" data-original-title="Edit pieces"><i class="ico-edit"></i></a> </li>';
                else:
                    $url_add_pieces = '/admin/annonces/pieces/do/add/id/' . $item['id'];
                    $actions .= '<li><a title="" class="tip" href="' . $url_add_pieces . '" data-original-title="Ajout pieces"><i class="ico-plus-sign"></i></a> </li>';
                endif;

                $actions .= '<li><a title="" class="tip delete" id="' . $id . '" style="cursor: pointer" data-original-title="Supprimer cette annonce"><i class="ico-trash"></i></a> </li>';
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
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'annonces', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'annonces', $where);
            }
        }

        echo 1;
    }

    public function piecesAction() {
        $this->view->headTitle()->append('Detail pièces');
        $this->view->headMeta()->appendName('description', "Immo");
        $this->view->headMeta()->appendName('keyword', "Immo");
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/annonces-pieces.js', 'text/javascript');

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        switch ($do):
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $where_p = " id_annonce=$id ";
                $this->view->annonces = Genius_Model_Global::selectRow(TABLE_PREFIX . "annonces", '*', $where);
                $this->view->pieces = Genius_Model_Global::select(TABLE_PREFIX . "annonces_pieces", '*', $where_p);
                if ($_POST) {
                    $nb_pieces = $_POST['nb_pieces'];
                    for ($i = 1; $i <= $nb_pieces; $i++) {
                        $id = $_POST['id_' . $i];
                        $w = " id=$id ";
                        $data = array(
                            'id_annonce' => $_POST['id_annonce']
                            , 'description' => $_POST['description_' . $i]
                        );
                        Genius_Model_Global::update(TABLE_PREFIX . "annonces_pieces", $data, $w);
                    }
                    // add photos
                    $files_error = $_FILES['Photo']['error'];
                    $uploadpath = UPLOAD_PATH . 'images/';

                    if (!empty($files_error)) {
                        foreach ($files_error as $id_annonce_piece => $photo) {

                            if (is_array($photo) && !empty($photo)) {
                                foreach ($photo as $k => $error) {
                                    if (!$error) {
                                        // upload file
                                        $tempFile = $_FILES['Photo']['tmp_name'][$id_annonce_piece][$k];
                                        $targetPath = $uploadpath . 'pieces/';

                                        if (!is_dir($targetPath)) {
                                            if (!mkdir($targetPath)) {
                                                echo "Erreur: Veuillez créer manuellement le dossier " . $targetPath;
                                                exit(0);
                                            }
                                        }

                                        $path_parts = pathinfo($targetPath . $_FILES['Photo']['name'][$id_annonce_piece][$k]);
                                        $filename = strtolower($path_parts['filename']);
                                        $targetfilename = Genius_Class_String::formatFilename($filename);

                                        $targetfilename = $targetfilename . "-piece-" . $id_annonce_piece . "." . $path_parts['extension'];
                                        $targetFile = str_replace('//', '/', $targetPath) . $targetfilename;

                                        if (move_uploaded_file($tempFile, $targetFile)) {
                                            $data_acp = array(
                                                'id_annonce_piece' => $id_annonce_piece
                                                , 'photo' => $targetfilename
                                            );
                                            Genius_Model_Global::insert(TABLE_PREFIX . 'annonces_pieces_photos', $data_acp);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $this->_redirect('/admin/annonces');
                }
                break;
            case 'add':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $this->view->annonces = Genius_Model_Global::selectRow(TABLE_PREFIX . "annonces", '*', $where);
                if ($_POST) {
                    $nb_pieces = $_POST['nb_pieces'];
                    for ($i = 1; $i <= $nb_pieces; $i++) {
                        $data = array(
                            'id_annonce' => $_POST['id_annonce']
                            , 'description' => $_POST['description_' . $i]
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . "annonces_pieces", $data);
                    }
                    $this->_redirect('/admin/annonces');
                }
                break;
            default:
                break;
        endswitch;
    }

    public function addpiecesAction() {

        $nb_pieces = $_POST['nb_pieces'];
        for ($i = 1; $i <= $nb_pieces; $i++) {
            $data = array(
                'id_annonce' => $_POST['id_annonce']
                , 'description' => $_POST['description_' . $i]
            );
            Genius_Model_Global::insert(TABLE_PREFIX . "annonces_pieces", $data);
        }
        $this->_redirect('/admin/annonces');
    }

    public function deletephotopieceAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";

        $ac = Genius_Model_Global::selectRow(TABLE_PREFIX . 'annonces_pieces_photos', "*", $where);
        $uploadpath = UPLOAD_PATH . 'images/';
        $targetPath = $uploadpath . 'pieces/';

        $cabine_photo_path = $targetPath . $ac['photo'];
        if (file_exists($cabine_photo_path)) {
            unlink($cabine_photo_path);
        }

        Genius_Model_Global::delete(TABLE_PREFIX . 'annonces_pieces_photos', $where);

        echo 1;
    }

}

?>