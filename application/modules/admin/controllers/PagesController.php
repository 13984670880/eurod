<?php

class Admin_PagesController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getpages', 'html');
        $ajaxContext->initContext();
    }

    public function indexAction() {
        $this->view->headTitle()->append('Pages');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/pages-index.js', 'text/javascript');
    }

    public function modifyAction() {
        $this->view->headTitle()->append('Edit Page');

        global $params;
        global $siteconfig;
        $languages = Genius_Model_Language::getLanguages();

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $page = array();

        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $page = Genius_Model_Page::getPageById($id);

                if ($_POST) {
                    /*
                     * 3 steps :
                     * 1. update genius_pages
                     * 2. update genius_pages_categories
                     * 3. update genius_pages_multilingual
                     */

                    $id = (int) $_POST['Pages']['id'];

                    // 1. update genius_pages
                    $data_pages = array(
                        'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::update(TABLE_PREFIX . 'pages', $data_pages, " id=$id ");

                    // 2. update genius_pages_categories
                    Genius_Model_Global::delete(TABLE_PREFIX . 'pages_categories', " id_page=$id ");
                    if (!empty($_POST['Pages']['categories'])) {
                        foreach ($_POST['Pages']['categories'] as $key => $value) {
                            $data_pages_categories = array(
                                'id_page' => $id
                                , 'id_category' => $value
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'pages_categories', $data_pages_categories);
                        }
                    }

                    // 3. update genius_pages_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Pages'], 'title_' . $item['abbreviation'], $_POST['Pages']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Pages'], 'text_' . $item['abbreviation'], $_POST['Pages']['text_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['Pages'], 'seo_title_' . $item['abbreviation'], $_POST['Pages']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Pages'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Pages']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Pages'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Pages']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Pages'], 'title_noscript_' . $item['abbreviation'], $_POST['Pages']['title_noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Pages'], 'h1_noscript_' . $item['abbreviation'], $_POST['Pages']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Pages'], 'h2_noscript_' . $item['abbreviation'], $_POST['Pages']['h2_noscript_' . DEFAULT_LANG_ABBR]);

                            $id_language = $item['id'];

                            $data_pages_multilingual = array(
                                'title' => $title
                                , 'text' => $text
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                                , 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );
                            Genius_Model_Global::update(TABLE_PREFIX . 'pages_multilingual', $data_pages_multilingual, " id_page=$id AND id_language=$id_language ");
                        }
                        // upload video
                        if (!empty($_FILES['video']['name'])) {
                            $video = $_FILES['video']['name'];
                            $upload = new Zend_File_Transfer();
                            //$upload->addValidator('Extension', false, array('mp4', 'flv', 'mov', 'avi', 'mpg', 'mpeg'));
                            //if ($upload->isValid()) {
                            $ext = pathinfo($upload->getFileName(), PATHINFO_EXTENSION);
                            $video = strtotime("now") . '_video.' . $ext;
                            if (($ext == 'mp4') || ($ext == 'flv') || ($ext == 'mov') || ($ext == 'avi') || ($ext == 'mpg') || ($ext == 'mpeg')) {
                                move_uploaded_file($_FILES['video']['tmp_name'], UPLOAD_PATH . 'video/' . $video);
                                // where ffmpeg is located, such as /usr/sbin/ffmpeg
                                $ffmpeg = 'ffmpeg';
                                // the input video file
                                $video_path = UPLOAD_PATH . 'video/' . $video;
                                // where you'll save the image
                                $image = strtotime("now") . '_thumb.jpg';
                                $image_path = UPLOAD_PATH . 'video/' . $image;
                                // default time to get the image
                                $second = 3;
                                // get the duration and a random place within that
                                $cmd = "$ffmpeg -i $video_path -deinterlace -an -ss $second -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $image_path 2>&1";
                                shell_exec($cmd);
                                // crop
                                $resizeObj = new Genius_Class_Resize($image_path);
                                $resizeObj->resizeImage(250, 200, 'auto');
                                $resizeObj->saveImage($image_path, 95);
                                Genius_Model_Global::update(TABLE_PREFIX . 'pages_multilingual', array('video' => $video, 'thumb' => $image, 'format' => $ext), 'id_page=' . $id);
                            }
                        }
                    }

                    $this->_redirect('/admin/pages');
                }

                break;

            case 'add':

                if ($_POST) {

                    /*
                     * 3 steps :
                     * 1. insert in genius_pages
                     * 2. insert in genius_pages_categories
                     * 3. insert in genius_pages_multilingual
                     */

                    // 1. insert in genius_pages
                    $data_pages = array(
                        'create_time' => date('Y-m-d H:i:s')
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::insert(TABLE_PREFIX . 'pages', $data_pages);
                    $lastId = Genius_Model_Global::lastId();

                    // 2. insert in genius_pages_categories
                    if (!empty($_POST['Pages']['categories'])) {
                        foreach ($_POST['Pages']['categories'] as $key => $value) {
                            $data_pages_categories = array(
                                'id_page' => $lastId
                                , 'id_category' => $value
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'pages_categories', $data_pages_categories);
                        }
                    }

                    // 3. insert in genius_pages_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['Pages'], 'title_' . $item['abbreviation'], $_POST['Pages']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['Pages'], 'text_' . $item['abbreviation'], $_POST['Pages']['text_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['Pages'], 'seo_title_' . $item['abbreviation'], $_POST['Pages']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['Pages'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Pages']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['Pages'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Pages']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
                            $noscript = Genius_Class_Utils::idml($_POST['Pages'], 'title_noscript_' . $item['abbreviation'], $_POST['Pages']['title_noscript_' . DEFAULT_LANG_ABBR]);
                            $h1 = Genius_Class_Utils::idml($_POST['Pages'], 'h1_noscript_' . $item['abbreviation'], $_POST['Pages']['h1_noscript_' . DEFAULT_LANG_ABBR]);
                            $h2 = Genius_Class_Utils::idml($_POST['Pages'], 'h2_noscript_' . $item['abbreviation'], $_POST['Pages']['h2_noscript_' . DEFAULT_LANG_ABBR]);

                            $data_pages_multilingual = array(
                                'id_page' => $lastId
                                , 'id_language' => $item['id']
                                , 'title' => $title
                                , 'text' => $text
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                                , 'title_noscript' => $noscript
                                , 'h1_noscript' => $h1
                                , 'h2_noscript' => $h2
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'pages_multilingual', $data_pages_multilingual);
                        }
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
                            Genius_Model_Global::update(TABLE_PREFIX . 'pages_multilingual', array('video' => $video, 'thumb' => $image, 'format' => $ext), 'id_page=' . $id);
                        }
                    }

                    $this->_redirect('/admin/pages');
                }

                break;

            default:
                break;
        }

        $this->view->page = $page;
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'pages', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'pages', $where);
            }
        }

        echo 1;
    }

    public function getpagesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    'page.id'
                    , 'pm.title'
                    , 'pm.text'
                    , 'page.update_time'
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($con,$_GET['sSearch']) . "%' OR ";
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($con,$_GET['sSearch_' . $i]) . "%' ";
            }
        }

        /* Get default id_language value */
        $sWhere .= (trim($sWhere) != "") ? " AND (pm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (pm.id_language=" . DEFAULT_LANG_ID . ") ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS page.id
				,IF(page.update_time='0000-00-00 00:00:00' OR page.update_time IS NULL, '', DATE_FORMAT(page.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,pm.title
				,pm.text
			FROM " . TABLE_PREFIX . "pages page
			LEFT JOIN " . TABLE_PREFIX . "pages_multilingual pm ON page.id=pm.id_page
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
                $row[3] = $this->getPagesCategories($item['id']);
                $row[4] = $item['update_time'];

                $id = $item['id'];
                $url_edit = '/admin/pages/modify/do/edit/id/' . $item['id'];

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

    public function getPagesCategories($id_page) {
        $row = '';
        $sQuery = "
				SELECT 
                                    cm.title
				FROM 
				" . TABLE_PREFIX . "categories_multilingual cm
				LEFT JOIN " . TABLE_PREFIX . "pages_categories pc ON pc.id_category = cm.id_category 
				WHERE
				cm.id_language=" . DEFAULT_LANG_ID . "
				AND
				pc.id_page =" . $id_page . "
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

}
