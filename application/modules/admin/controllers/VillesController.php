<?php

class Admin_VillesController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getall', 'html');
		$ajaxContext->initContext();
	}
	public function indexAction()
	{ 
		$this->view->headTitle()->append('Villes');	
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/villes-index.js', 'text/javascript');		 	 
	}
	public function createpermalinksAction()
	{ 
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$villes = Genius_Model_Global::select(TABLE_PREFIX . 'villes', '*', 'id is not null');
		foreach ($villes as $v) :
			$permalinks = Genius_Class_String::cleanString($v['ville']);
			$id = $v['id'];
			$data_ville = array("permalinks"=>$permalinks);
			Genius_Model_Global::update(TABLE_PREFIX . 'villes', $data_ville, "id = '$id' ");
		endforeach;
		echo 'done'; die();
	}
	public function referencementAction()
	{ 
		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
		$value = (int) $this->_getParam('value');

        // set new cover
        $where_2 = "id=$id ";
        Genius_Model_Global::update(TABLE_PREFIX . 'villes', array('is_referencement' => $value), $where_2);

        echo 1;
		
	}
	
	public function getallAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				'id'
				,'ville'
				,'cp'
				,'is_referencement'
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
		//$sWhere .= (trim($sWhere)!="") ? "dsd" ;
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS id				
				,ville
				,cp
				,is_referencement
			FROM ".TABLE_PREFIX."villes
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
		 	 	$row[0] = '<input type="checkbox" class="" value="'.$item['id'].'" name="checked[]" />';
		 	 	$row[1] = $item['ville'];
		 	 	$row[2] = $item['cp'];
				$checked = ($item['is_referencement']==1)?"checked='checked'":""; 
				$row[3] = '<input type="checkbox" class="ref" id="'.$item['id'].'" value="'.$item['id'].'" name="checked[]" '.$checked.' />';
		 	 	$output['aaData'][] = $row;
		 	} 
		}
		
		echo json_encode( $output );
	}
}