<?php

class Admin_GroupsController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getgroups', 'html');
		$ajaxContext->addActionContext('delete', 'html');
		$ajaxContext->addActionContext('massdelete', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Groups');		 
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/groups-index.js', 'text/javascript');	 
	}

	public function modifyAction()
	{ 
		$this->view->headTitle()->append('Edit Group');

		global $params;
		global $siteconfig;
		$languages = Genius_Model_Language::getLanguages();

		$this->view->do = $do = $this->view->escape($this->_getParam('do'));
		$group = array();
	 	switch ($do) {
	 		case 'edit':
		 			$id = (int)$this->_getParam('id');
		 			$where = " id=$id ";
		 			$group = Genius_Model_Group::getGroupById($id);
	 				if($_POST){ 
		 				/*
		 				 * 3 steps :
		 				 * 1. update genius_categories_groups
		 				 * 2. update genius_categories_groups_multilingual
		 				 * 3. update genius_modules_categories_groups 	
		 				 */

		 				$id = (int)$_POST['Groups']['id'];

		 				// 1. update genius_categories_groups
		 				// update order item
		 				if($_POST['Groups']['order_item']):
		 					$order_item = (int)$_POST['Groups']['order_item'];
		 					Genius_Model_Global::updateOrderItem(TABLE_PREFIX."categories_groups", $order_item);
		 				else:
		 					$order_item = (int)$_POST['Groups']['old_order_item'];
		 				endif;

		 				$data_categories_groups = array(
		 					'order_item'	=> $order_item
		 					,'update_time'	=> date('Y-m-d H:i:s')
		 				);
		 				Genius_Model_Global::update(TABLE_PREFIX.'categories_groups', $data_categories_groups, " id=$id ");

		 				// 2. update genius_categories_groups_multilingual
		 				if(!empty($languages)){ 
		 					foreach ($languages as $k => $item) {
		 						$title = Genius_Class_Utils::idml($_POST['Groups'], 'title_'.$item['abbreviation'], $_POST['Groups']['title_'.DEFAULT_LANG_ABBR]);
								$accroche = Genius_Class_Utils::idml($_POST['Groups'], 'accroche_'.$item['abbreviation'], $_POST['Groups']['accroche_'.DEFAULT_LANG_ABBR]);
								$text = Genius_Class_Utils::idml($_POST['Groups'], 'text_' . $item['abbreviation'], $_POST['Groups']['text_' . DEFAULT_LANG_ABBR]);
								$text_reparation = Genius_Class_Utils::idml($_POST['Groups'], 'text_reparation_'.$item['abbreviation'], $_POST['Groups']['text_reparation_'.DEFAULT_LANG_ABBR]);
								$text_vente = Genius_Class_Utils::idml($_POST['Groups'], 'text_vente_'.$item['abbreviation'], $_POST['Groups']['text_vente_'.DEFAULT_LANG_ABBR]);
								$text_echange = Genius_Class_Utils::idml($_POST['Groups'], 'text_echange_'.$item['abbreviation'], $_POST['Groups']['text_echange_'.DEFAULT_LANG_ABBR]);
								$text_maintenance = Genius_Class_Utils::idml($_POST['Groups'], 'text_maintenance_'.$item['abbreviation'], $_POST['Groups']['text_maintenance_'.DEFAULT_LANG_ABBR]);
								$text_location = Genius_Class_Utils::idml($_POST['Groups'], 'text_location_'.$item['abbreviation'], $_POST['Groups']['text_location_'.DEFAULT_LANG_ABBR]);
								$text_audit = Genius_Class_Utils::idml($_POST['Groups'], 'text_audit_'.$item['abbreviation'], $_POST['Groups']['text_audit_'.DEFAULT_LANG_ABBR]);
								$text_reprise = Genius_Class_Utils::idml($_POST['Groups'], 'text_reprise_'.$item['abbreviation'], $_POST['Groups']['text_reprise_'.DEFAULT_LANG_ABBR]);
								
								//SEO
								$seo_title = Genius_Class_Utils::idml($_POST['Groups'], 'seo_title_' . $item['abbreviation'], $_POST['Groups']['seo_title_' . DEFAULT_LANG_ABBR]);
								$seo_meta_description = Genius_Class_Utils::idml($_POST['Groups'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Groups']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
								$seo_meta_keyword = Genius_Class_Utils::idml($_POST['Groups'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Groups']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
								$title_noscript_ = Genius_Class_Utils::idml($_POST['Groups'], 'title_noscript_' . $item['abbreviation'], $_POST['Groups']['title_noscript_' . DEFAULT_LANG_ABBR]);
								$h1_noscript = Genius_Class_Utils::idml($_POST['Groups'], 'h1_noscript_' . $item['abbreviation'], $_POST['Groups']['h1_noscript_' . DEFAULT_LANG_ABBR]);
								$h2_noscript = Genius_Class_Utils::idml($_POST['Groups'], 'h2_noscript_' . $item['abbreviation'], $_POST['Groups']['h2_noscript_' . DEFAULT_LANG_ABBR]);
								
		 						$id_language = $item['id'];
		 						$data_categories_groups_multilingual = array(
									'title' => $title
									,'accroche' => $accroche
									,'text' => $text
									,'text_reparation' => $text_reparation
									,'text_vente' => $text_vente
									,'text_echange' => $text_echange
									,'text_maintenance' => $text_maintenance
									,'text_location' => $text_location
									,'text_audit' => $text_audit
									,'text_reprise' => $text_reprise
									,'seo_title' => $seo_title
									,'seo_meta_description' => $seo_meta_description
									,'seo_meta_keyword' => $seo_meta_keyword
									,'title_noscript' => $title_noscript_
									,'h1_noscript' => $h1_noscript
									,'h2_noscript' => $h2_noscript
		 						);
		 						Genius_Model_Global::update(TABLE_PREFIX.'categories_groups_multilingual', $data_categories_groups_multilingual, " id_category_group=$id AND id_language=$id_language ");
		 					}
		 				}

		 				// 3. update genius_modules_categories_groups
						if ($id != 14){
						  $data_modules_categories_groups = array(
							  'id_module'			 => (int)$_POST['Groups']['id_module']
						  );
						  Genius_Model_Global::update(TABLE_PREFIX.'modules_categories_groups', $data_modules_categories_groups, " id_category_group=$id ");
						}

		 				$this->_redirect('/admin/groups');
	 				}		 			
	 				
	 			break;

	 		case 'add':

	 				if($_POST){ 
		 				/*
		 				 * 3 steps :
		 				 * 1. insert in genius_categories_groups
		 				 * 2. insert in genius_categories_groups_multilingual
		 				 * 3. insert in genius_modules_categories_groups 	
		 				 */

		 				// 1. insert in genius_categories_groups
		 				// update order item
		 				if($_POST['Groups']['order_item']):
		 					$order_item = (int)$_POST['Groups']['order_item'];
		 					Genius_Model_Global::updateOrderItem(TABLE_PREFIX."categories_groups", $order_item);
		 				else:
		 					$order_item = Genius_Model_Global::getMaxOrderItem(TABLE_PREFIX."categories_groups") + 1;
		 				endif;

		 				$data_categories_groups = array(
		 					'order_item'	=> $order_item
		 					,'create_time'	=> date('Y-m-d H:i:s')
		 					,'update_time'	=> date('Y-m-d H:i:s')
		 				);
		 				Genius_Model_Global::insert(TABLE_PREFIX.'categories_groups', $data_categories_groups);
		 				$lastId = Genius_Model_Global::lastId();

		 				// 2. insert in genius_categories_groups_multilingual
		 				if(!empty($languages)){ 
		 					foreach ($languages as $k => $item) {
		 						$title = Genius_Class_Utils::idml($_POST['Groups'], 'title_'.$item['abbreviation'], $_POST['Groups']['title_'.DEFAULT_LANG_ABBR]);
								$accroche = Genius_Class_Utils::idml($_POST['Groups'], 'accroche_'.$item['abbreviation'], $_POST['Groups']['accroche_'.DEFAULT_LANG_ABBR]);
								$text = Genius_Class_Utils::idml($_POST['Groups'], 'text_' . $item['abbreviation'], $_POST['Groups']['text_' . DEFAULT_LANG_ABBR]);
								//SEO
								$seo_title = Genius_Class_Utils::idml($_POST['Groups'], 'seo_title_' . $item['abbreviation'], $_POST['Groups']['seo_title_' . DEFAULT_LANG_ABBR]);
								$seo_meta_description = Genius_Class_Utils::idml($_POST['Groups'], 'seo_meta_description_' . $item['abbreviation'], $_POST['Groups']['seo_meta_description_' . DEFAULT_LANG_ABBR]);
								$seo_meta_keyword = Genius_Class_Utils::idml($_POST['Groups'], 'seo_meta_keyword_' . $item['abbreviation'], $_POST['Groups']['seo_meta_keyword_' . DEFAULT_LANG_ABBR]);
								$title_noscript_ = Genius_Class_Utils::idml($_POST['Groups'], 'title_noscript_' . $item['abbreviation'], $_POST['Groups']['title_noscript_' . DEFAULT_LANG_ABBR]);
								$h1_noscript = Genius_Class_Utils::idml($_POST['Groups'], 'h1_noscript_' . $item['abbreviation'], $_POST['Groups']['h1_noscript_' . DEFAULT_LANG_ABBR]);
								$h2_noscript = Genius_Class_Utils::idml($_POST['Groups'], 'h2_noscript_' . $item['abbreviation'], $_POST['Groups']['h2_noscript_' . DEFAULT_LANG_ABBR]);

		 						$data_categories_groups_multilingual = array(
									'id_category_group' => $lastId
									,'id_language'      => $item['id']
									,'title'            => $title
									,'accroche'         => $accroche
									,'text' => $text
									,'seo_title' => $seo_title
									,'seo_meta_description' => $seo_meta_description
									,'seo_meta_keyword' => $seo_meta_keyword
									,'title_noscript' => $title_noscript_
									,'h1_noscript' => $h1_noscript
									,'h2_noscript' => $h2_noscript
		 						);
		 						Genius_Model_Global::insert(TABLE_PREFIX.'categories_groups_multilingual', $data_categories_groups_multilingual);
		 					}
		 				}

		 				// 3. insert in genius_modules_categories_groups
		 				$data_modules_categories_groups = array(
		 					'id_module'			 => (int)$_POST['Groups']['id_module']
		 					,'id_category_group' => $lastId
		 				);
		 				Genius_Model_Global::insert(TABLE_PREFIX.'modules_categories_groups', $data_modules_categories_groups);

		 				$this->_redirect('/admin/groups');
	 				}

	 			break;
	 		
	 		default:
	 			break;
	 	}

	 	$this->view->group = $group;
	}

	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'categories_groups', $where);

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
		   		$return = Genius_Model_Global::delete(TABLE_PREFIX.'categories_groups', $where);		
	   		}			   	 	 
	   	}

	   	echo 1; 
	}

	public function getgroupsAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				'cg.id'
				,'cgm.title'
				,'cg.update_time'
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
				$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($con, $_GET['sSearch_'.$i])."%' ";
			}
		}

		/* Get default id_language value */
		$sWhere .= (trim($sWhere)!="") ? " AND (cgm.id_language=".DEFAULT_LANG_ID.") " : " WHERE (cgm.id_language=".DEFAULT_LANG_ID.") " ;
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS cg.id
				,cgm.title
				,IF(cg.update_time='0000-00-00 00:00:00' OR cg.update_time IS NULL, '', DATE_FORMAT(cg.update_time,'%d %b %Y %Hh %imn')) AS update_time
			FROM ".TABLE_PREFIX."categories_groups cg
			LEFT JOIN ".TABLE_PREFIX."categories_groups_multilingual cgm ON cg.id=cgm.id_category_group
			$sWhere
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
		 	 	
		 	 	$row[0] = '<input type="checkbox" class="styled" value="'.$item['id'].'" name="checked[]" />';

		 	 	$row[1] = $item['title'];
		 	 	$row[2] = $item['update_time'];

		 	 	$id = $item['id'];
		 	 	$url_edit = '/admin/groups/modify/id/'.$item['id'].'/do/edit';

		 	 	$actions = '<ul class="table-controls acenter">';
            	$actions .= '<li><a title="" class="tip" href="'.$url_edit.'" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
            	$actions .= '<li><a title="" class="tip delete" id="'.$id.'" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';    
                    
		 	 	$row[3] = $actions;

		 	 	$output['aaData'][] = $row;
		 	} 
		}
		
		echo json_encode( $output );
	}
}