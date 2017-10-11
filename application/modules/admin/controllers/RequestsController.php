<?php

class Admin_RequestsController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getrequests', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Requests');
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/requests-index.js', 'text/javascript');		 	 
	}

	public function viewAction()
	{ 
		$this->view->headTitle()->append('View request');	
		
		$id = (int)$this->_getParam('id');
		$this->view->request = Genius_Model_Request::getTranslationById($id);
	}

	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'requests', $where);

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
		   		$return = Genius_Model_Global::delete(TABLE_PREFIX.'requests', $where);		
	   		}			   	 	 
	   	}

	   	echo 1; 
	}

	public function getrequestsAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				'request.id'
				,'rm.object'
				,'rm.email'
				,'rm.phone'
				,'rm.text'
				,'request.newsletter'
				,'request.create_time'
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
		$sWhere .= (trim($sWhere)!="") ? " AND (rm.id_language=".DEFAULT_LANG_ID.") " : " WHERE (rm.id_language=".DEFAULT_LANG_ID.") " ;
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS request.id
				,request.newsletter
				,IF(request.create_time='0000-00-00 00:00:00' OR request.create_time IS NULL, '', DATE_FORMAT(request.create_time,'%d %b %Y %Hh %imn')) AS create_time
				,rm.object
				,rm.email
				,rm.phone
				,rm.text
			FROM ".TABLE_PREFIX."requests request
			LEFT JOIN ".TABLE_PREFIX."requests_multilingual rm ON request.id=rm.id_request
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

		 	 	$row[1] = $item['object'];
		 	 	$row[2] = $item['email'];
		 	 	$row[3] = $item['phone'];
		 	 	$row[4] = $item['text'];
		 	 	$row[5] = $item['newsletter'];
		 	 	$row[6] = $item['create_time'];

		 	 	$id = $item['id'];
		 	 	$url_edit = '/admin/requests/view/id/'.$item['id'];

		 	 	$actions = '<ul class="table-controls acenter">';
            	$actions .= '<li><a title="" class="tip" href="'.$url_edit.'" data-original-title="View item"><i class="ico-zoom-in"></i></a> </li>';
            	$actions .= '<li><a title="" class="tip delete" id="'.$id.'" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';    
                    
		 	 	$row[7] = $actions;

		 	 	$output['aaData'][] = $row;
		 	} 
		}
		
		echo json_encode( $output );
	}
}