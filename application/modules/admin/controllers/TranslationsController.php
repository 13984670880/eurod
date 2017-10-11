<?php

class Admin_TranslationsController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('gettranslations', 'html');
		$ajaxContext->addActionContext('delete', 'html');
		$ajaxContext->addActionContext('massdelete', 'html');
		$ajaxContext->addActionContext('scan', 'html');
		$ajaxContext->addActionContext('changelang', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Translations');
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/translations-index.js', 'text/javascript');

		$id = (int)$this->_getParam('id');
		$this->view->id_language = $id;
		$sess = new Zend_Session_Namespace();	
		$sess->translate_lang_id = $id;
		if (empty($_SESSION['id_lang'])){
			$_SESSION['id_lang'] = 1;
		}else{
			$_SESSION['id_lang'] = $id;
		}
	}

	public function modifyAction()
	{ 
		$sess = new Zend_Session_Namespace();
		$this->view->headTitle()->append('Edit Translation');

		global $params;
		global $siteconfig;
		$languages = Genius_Model_Language::getLanguages();

		$this->view->do = $do = $this->view->escape($this->_getParam('do'));
		$translation = array();
	 	switch ($do) {
	 		case 'edit':
					$id          = (int)$this->_getParam('id');
					$where       = " id=$id ";
					$id_language = (int)$this->_getParam('lang');

		 			$translation = Genius_Model_Translation::getValueMultilingual($id, $id_language);
		 			
	 				if($_POST){ 
		 				/*
		 				 * 2 steps :
		 				 * 1. update genius_translations
		 				 * 2. update genius_translations_multilingual
		 				 */

		 				$id = (int)$_POST['Translations']['id'];
		 				$id_language = (int)$_POST['Translations']['id_language'];
		 				$value = $this->view->escape($_POST['Translations']['value']);

		 				// 1. update genius_translations
		 				$data_translations = array(
							'update_time' => date('Y-m-d H:i:s')
		 				);
		 				Genius_Model_Global::update(TABLE_PREFIX.'translations', $data_translations, " id=$id ");

		 				// 2. update genius_categories_multilingual
 						$data_translations_multilingual = array(
							'value' => $value
 						);
 						$where = " id_translation=$id AND id_language=$id_language ";
 						Genius_Model_Global::update(TABLE_PREFIX.'translations_multilingual', $data_translations_multilingual, $where);
		 			
		 				$this->_redirect('/admin/translations/index/id/'.$_SESSION['id_lang']);
	 				}		 			
	 				
	 			break;

	 		case 'add':

	 				if($_POST){ 
		 				/*
		 				 * 2 steps :
		 				 * 1. insert in genius_translations
		 				 * 2. insert in genius_translations_multilingual
		 				 */

		 				// 1. insert in genius_translations
		 				$genius_translations = array(
							'create_time'  => date('Y-m-d H:i:s')
							,'update_time' => date('Y-m-d H:i:s')
		 				);

		 				Genius_Model_Global::insert(TABLE_PREFIX.'translations', $genius_translations);
		 				$lastId = Genius_Model_Global::lastId();

		 				// 2. insert in genius_categories_multilingual
		 				if(!empty($languages)){ 
		 					$key = $value = $this->view->escape($_POST['Translations']['key']);

		 					foreach ($languages as $k => $item) {
		 						$data_translations_multilingual = array(
									'id_translation'         => $lastId
									,'id_language'           => $item['id']
									,'language_abbreviation' => $item['abbreviation']
									,'key'                   => $key
									,'value'                 => $value
		 						);
		 						Genius_Model_Global::insert(TABLE_PREFIX.'translations_multilingual', $data_translations_multilingual);
		 					}
		 				}

		 				$this->_redirect('/admin/translations/index/id/'.$_SESSION['id_lang']);
	 				}

	 			break;
	 		
	 		default:
	 			break;
	 	}

	 	$this->view->translation = $translation;
	}

	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'translations', $where);

	   	echo 1; 
	}

	public function massdeleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	if(!empty($_POST['massdeleteitems'])){ 
	   		foreach ($_POST['massdeleteitems'] as $k => $item) {
				$id    = (int)$item['value'];
				$where = " id=$id ";
		   		$return = Genius_Model_Global::delete(TABLE_PREFIX.'translations', $where);		
	   		}			   	 	 
	   	}

	   	echo 1; 
	}

	public function gettranslationsAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				't.id'
				,'tm.key'
				,'tm.value'
				,'t.update_time'
			);
		   		   		
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ". intval( $_GET['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ". ($_GET['sSortDir_0']==='asc' ? 'ASC' : 'DESC') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}

		/* 
		 * Filtering
		 */
		$sWhere = "";
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($con, $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($con,$_GET['sSearch_'.$i])."%' ";
			}
		}

		/* Get default id_language value */
		$sWhere .= (trim($sWhere)!="") ? " AND (tm.id_language=".$_SESSION['id_lang'].") " : " WHERE (tm.id_language=".$_SESSION['id_lang'].") " ;
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS t.id
				,IF(t.update_time='0000-00-00 00:00:00' OR t.update_time IS NULL, '', DATE_FORMAT(t.update_time,'%d %b %Y %H:%i')) AS update_time
				,tm.id_translation
			FROM ".TABLE_PREFIX."translations t
			LEFT JOIN ".TABLE_PREFIX."translations_multilingual tm ON t.id=tm.id_translation
			$sWhere
			GROUP BY t.id
			$sOrder
			$sLimit
		";

		$rResult        = Genius_Model_Global::query($sQuery);
		$total          = Genius_Model_Global::query("SELECT FOUND_ROWS()");
		$iFilteredTotal = $total[0]['FOUND_ROWS()'];
		$iTotal         = $total[0]['FOUND_ROWS()'];
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho"                => intval($_GET['sEcho']),
			"iTotalRecords"        => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData"               => array()
		);

		if(!empty($rResult)){ 
		 	foreach ($rResult as $k => $item) {
		 	 	$row = array();
		 	 	
		 	 	$count = -1;
		 	 	$row[++$count] = '<input type="checkbox" class="styled" value="'.$item['id'].'" name="checked[]" />';

		 	 	$sess = new Zend_Session_Namespace();	
				//$id_language = $sess->translate_lang_id;
				$id_language = $_SESSION['id_lang'];

 				$translations = Genius_Model_Translation::getValueMultilingual($item['id_translation'], $id_language);
 				$row[++$count] = $translations['key'];
 				$row[++$count] = $translations['value'];

		 	 	$row[++$count] = $item['update_time'];

		 	 	$id = $item['id_translation'];
		 	 	$url_edit = 'http://'.$_SERVER['HTTP_HOST'].'/admin/translations/modify/id/'.$item['id_translation'].'/lang/'.$id_language.'/do/edit';

		 	 	$actions = '<ul class="table-controls acenter">';
            	$actions .= '<li><a title="" class="tip" href="'.$url_edit.'" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
            	$actions .= '<li><a title="" class="tip delete" id="'.$id.'" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';    
                    
		 	 	$row[++$count] = $actions;

		 	 	$output['aaData'][] = $row;
		 	} 
		}
		
		echo json_encode( $output );
	}

	public function scanAction()
	{
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();

		global $dirs_to_translate;
		$languages = Genius_Model_Language::getLanguages();

		$directories = array(APPLICATION_PATH.'/layouts/scripts/default', APPLICATION_PATH.'/modules/default');
		if(!empty($directories)){
			foreach ($directories as $k => $directory) {

				//Genius_Class_Folder::listDirectory(APPLICATION_PATH);
				Genius_Class_Folder::listDirectory($directory);
					
				$content = "";
				$translations = array();

				if(!empty($dirs_to_translate)){ 
					foreach ($dirs_to_translate as $k => $folder) {
			 		    $files = array_filter(glob("$folder/*"), 'is_file');
			 		    $contents = array_map('file_get_contents', $files);
			 		    foreach ($contents as $content) {        
			 	    	    preg_match_all("'translate\(\"(.*?)\"'si", $content, $matchfirst);
			 	    	    if(!empty($matchfirst[1])):
			 	    	    	foreach ($matchfirst[1] as $k => $item) {
			 	    	    		$translations[] = $item;
			 	    	    	}
			 	    	    endif;
			 	    	    
			 	    	    preg_match_all("'translate\(\'(.*?)\''si", $content, $matchsecond);
			 	    	   	if(!empty($matchsecond[1])):
			 	    	   		foreach ($matchsecond[1] as $k => $item) {
			 	    	   			$translations[] = $item;
			 	    	   		}
			 	    	   	endif;
			 		    }
					} 
				}
				
			    if(!empty($translations)){ 
			    	foreach ($translations as $key => $translation) {
			    		if(!Genius_Model_Translation::isTranslated($translation)):
			 				/*
			 				 * 2 steps :
			 				 * 1. insert in genius_translations
			 				 * 2. insert in genius_translations_multilingual
			 				 */

			 				// 1. insert in genius_translations
			 				$genius_translations = array(
								'create_time'  => date('Y-m-d H:i:s')
								,'update_time' => date('Y-m-d H:i:s')
			 				);
			 				Genius_Model_Global::insert(TABLE_PREFIX.'translations', $genius_translations);
			 				$lastId = Genius_Model_Global::lastId();

			 				// 2. insert in genius_categories_multilingual
			 				if(!empty($languages)){ 
			 					foreach ($languages as $k => $item) {
			 						$data_translations_multilingual = array(
										'id_translation'         => $lastId
										,'id_language'           => $item['id']
										,'language_abbreviation' => $item['abbreviation']
										,'key'                   => $translation
										,'value'                 => $translation
			 						);

			 						Genius_Model_Global::insert(TABLE_PREFIX.'translations_multilingual', $data_translations_multilingual);
			 					}
			 				}
			 			endif;
			    	}	 
			    }
				
			}
		}

	    echo 1;
	}

	public function changelangAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	

	   	$sess = new Zend_Session_Namespace();
	   	$sess->locale  = (!empty($sess->locale) AND $sess->locale=="fr") ? "en" : "fr"; 

	   	$id_language = Genius_Model_Language::getId($sess->locale);
	   	$sess->translate_lang_id = $id_language;	 

	   	echo 1;
	}
}