<?php

class Admin_NewsController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getnews', 'html');
        $ajaxContext->initContext();
    }

    public function indexAction() {
        $this->view->headTitle()->append('News');
        $this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/news-index.js', 'text/javascript');
    }

    public function modifyAction() {
        $this->view->headTitle()->append('Edit News');

        global $params;
        global $siteconfig;
        $languages = Genius_Model_Language::getLanguages();

        $this->view->do = $do = $this->view->escape($this->_getParam('do'));
        $news = array();

        switch ($do) {
            case 'edit':
                $id = (int) $this->_getParam('id');
                $where = " id=$id ";
                $news = Genius_Model_News::getNewsById($id);

                if ($_POST) {
                    /*
                     * 3 steps :
                     * 1. update genius_news
                     * 2. update genius_news_categories
                     * 3. update genius_news_multilingual
                     */

                    $id = (int) $_POST['News']['id'];

                    // 1. update genius_pages
                    $data_news = array(
                        'news_date' => $this->view->escape($_POST['News']['news_date'])
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::update(TABLE_PREFIX . 'news', $data_news, " id=$id ");

                    // 2. update genius_news_categories
                    Genius_Model_Global::delete(TABLE_PREFIX . 'news_categories', " id_news=$id ");
                    if (!empty($_POST['News']['categories'])) {
                        foreach ($_POST['News']['categories'] as $key => $value) {
                            $data_news_categories = array(
                                'id_news' => $id
                                , 'id_category' => $value
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'news_categories', $data_news_categories);
                        }
                    }

                    // 3. update genius_news_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['News'], 'title_' . $item['abbreviation'], $_POST['News']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['News'], 'text_' . $item['abbreviation'], $_POST['News']['title_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['News'], 'seo_title_' . $item['abbreviation'], $_POST['News']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['News'], 'seo_meta_description_' . $item['abbreviation'], $_POST['News']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['News'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['News']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);

                            $id_language = $item['id'];

                            $data_news_multilingual = array(
                                'title' => $title
                                , 'text' => $text
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                            );
                            Genius_Model_Global::update(TABLE_PREFIX . 'news_multilingual', $data_news_multilingual, " id_news=$id AND id_language=$id_language ");
                        }
                    }

                    $this->_redirect('/admin/news');
                }

                break;

            case 'add':

                if ($_POST) {

                    /*
                     * 3 steps :
                     * 1. insert in genius_news
                     * 2. insert in genius_news_categories
                     * 3. insert in genius_news_multilingual
                     */

                    // 1. insert in genius_news
                    $data_news = array(
                        'news_date' => $this->view->escape($_POST['News']['news_date'])
                        , 'create_time' => date('Y-m-d H:i:s')
                        , 'update_time' => date('Y-m-d H:i:s')
                    );
                    Genius_Model_Global::insert(TABLE_PREFIX . 'news', $data_news);
                    $lastId = Genius_Model_Global::lastId();

                    // 2. insert in genius_news_categories
                    if (!empty($_POST['News']['categories'])) {
                        foreach ($_POST['News']['categories'] as $key => $value) {
                            $data_news_categories = array(
                                'id_news' => $lastId
                                , 'id_category' => $value
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'news_categories', $data_news_categories);
                        }
                    }

                    // 3. insert in genius_news_multilingual
                    if (!empty($languages)) {
                        foreach ($languages as $k => $item) {
                            $title = Genius_Class_Utils::idml($_POST['News'], 'title_' . $item['abbreviation'], $_POST['News']['title_' . DEFAULT_LANG_ABBR]);
                            $text = Genius_Class_Utils::idml($_POST['News'], 'text_' . $item['abbreviation'], $_POST['News']['title_' . DEFAULT_LANG_ABBR]);
                            $seo_title = Genius_Class_Utils::idml($_POST['News'], 'seo_title_' . $item['abbreviation'], $_POST['News']['seo_title_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_description = Genius_Class_Utils::idml($_POST['News'], 'seo_meta_description_' . $item['abbreviation'], $_POST['News']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
                            $seo_meta_keyword = Genius_Class_Utils::idml($_POST['News'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['News']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);

                            $data_news_multilingual = array(
                                'id_news' => $lastId
                                , 'id_language' => $item['id']
                                , 'title' => $title
                                , 'text' => $text
                                , 'seo_title' => $seo_title
                                , 'seo_meta_description' => $seo_meta_description
                                , 'seo_meta_keyword' => $seo_meta_keyword
                            );
                            Genius_Model_Global::insert(TABLE_PREFIX . 'news_multilingual', $data_news_multilingual);
                        }
                    }

                    $this->_redirect('/admin/news');
                }

                break;

            default:
                break;
        }

        $this->view->news = $news;
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $where = " id=$id ";
        $return = Genius_Model_Global::delete(TABLE_PREFIX . 'news', $where);

        echo 1;
    }

    public function massdeleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if (!empty($_POST['massdeleteitems'])) {
            foreach ($_POST['massdeleteitems'] as $k => $item) {
                $id = (int) $item['value'];
                $where = " id=$id ";
                $return = Genius_Model_Global::delete(TABLE_PREFIX . 'news', $where);
            }
        }

        echo 1;
    }

    public function getnewsAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    'news.id'
                    , 'nm.title'
                    , 'nm.text'
                    , 'news.update_time'
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
        $sWhere .= (trim($sWhere) != "") ? " AND (nm.id_language=" . DEFAULT_LANG_ID . ") " : " WHERE (nm.id_language=" . DEFAULT_LANG_ID . ") ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS news.id
				,IF(news.update_time='0000-00-00 00:00:00' OR news.update_time IS NULL, '', DATE_FORMAT(news.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,nm.title
				,nm.text
			FROM " . TABLE_PREFIX . "news news
			LEFT JOIN " . TABLE_PREFIX . "news_multilingual nm ON news.id=nm.id_news
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
                $row[2] = $item['text'];
                $row[3] = $item['update_time'];

                $id = $item['id'];
                $url_edit = '/admin/news/modify/do/edit/id/' . $item['id'];

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