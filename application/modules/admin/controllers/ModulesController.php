<?php

class Admin_ModulesController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getmodules', 'html');
		$ajaxContext->addActionContext('delete', 'html');
		$ajaxContext->initContext();		
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Modules');	
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/modules-index.js', 'text/javascript');

		$module = array();
		$this->view->do = "Add a module";
		if($_POST){
			$id = (int)$_POST['Modules']['id'];
			if($id){ 
				// Update
				$title = $this->view->escape($_POST['Modules']['title']);
		 	 	if(trim($title)!=""){ 
		 	 		$where = " id=$id ";
		 	 		$data = array(
						'title'        => $title
						,'update_time' => date('Y-m-d H:i:s')
		 	 		);
		 	 		Genius_Model_Global::update(TABLE_PREFIX.'modules', $data, $where);

		 	 		$this->_redirect('/admin/modules'); 	 
		 	 	}
			}else{ 
				// Insert
		 	 	$title = $this->view->escape($_POST['Modules']['title']);
		 	 	if(trim($title)!=""){ 
		 	 		$data = array(
						'title'        => $title
						,'create_time' => date('Y-m-d H:i:s')
						,'update_time' => date('Y-m-d H:i:s')
		 	 		);
		 	 		Genius_Model_Global::insert(TABLE_PREFIX.'modules', $data); 	 
		 	 	}
	 	 	}
	 	 		
		} // endif	 

		$id = (int)$this->_getParam('id');
		if($id){ 
			// Pop data for update
			$where = " id=$id ";
			$module = Genius_Model_Global::select(TABLE_PREFIX.'modules', '', $where);
			$module = $module[0];

			$this->view->do     = "Edit a module";
		}

		$this->view->module = $module;

	}

	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'modules', $where);

	   	echo 1; 	 	 
	}

	public function getmodulesAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				'module.id'
				,'module.title'
				,'module.update_time'
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
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS module.id
				,module.title
				,IF(module.update_time='0000-00-00 00:00:00' OR module.update_time IS NULL, '', DATE_FORMAT(module.update_time,'%d %b %Y %Hh %imn')) AS update_time
			FROM ".TABLE_PREFIX."modules module
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

		 	 	$row[0] = $item['id'];
		 	 	$row[1] = $item['title'];
		 	 	$row[2] = $item['update_time'];

		 	 	$id = $item['id'];
		 	 	$url_edit = '/admin/modules/index/id/'.$item['id'];

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