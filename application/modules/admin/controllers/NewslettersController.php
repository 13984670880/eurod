<?php

class Admin_NewslettersController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getnews', 'html');
		$ajaxContext->initContext();
	}
	public function indexAction()
	{ 
		$this->view->headTitle()->append('Newsletters');	
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/newletters-index.js', 'text/javascript');		 	 
	}
	
	public function sendAction()
	{ 
		$this->view->headTitle()->append('Envoyer newletters');	
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/newletters-index.js', 'text/javascript');		
		if($_POST){ 
			$contacts = Genius_Model_Global::select(TABLE_PREFIX.'contacts_newsletters',"*","id is not null");
			foreach ($contacts as $c):
				$assignvalues = array(
				"phtml"=>$_POST['news'],
				"sender"=>"contact@lynxis.eu",
				"receiver"=>$c['email'],
				"subject"=>$_POST['subject'],
				"host"=>'http://'.$_SERVER['HTTP_HOST'],
				"translate"=>$this->view->translate
				);
				Genius_Class_Email::sendnews($assignvalues);
			endforeach;
			$this->_redirect('/admin/newsletters');
		}
	}
	
	public function addAction()
	{ 
		$this->view->headTitle()->append('Ajouter un contact');	
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/newletters-index.js', 'text/javascript');		
		if($_POST){ 
			$datas = array (
			"nom"=>$this->_getParam('nom'),
			"prenom"=>$this->_getParam('prenom'),
			"email"=>$this->_getParam('email'),
			"tel"=>$this->_getParam('tel'),
			"date"=>date('Y-m-d H:i:s')
			);
			Genius_Model_Global::insert(TABLE_PREFIX.'contacts_newsletters', $datas);
			$this->_redirect('/admin/newsletters');
		}
	}
	
	public function getcontactAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);

		$aColumns = 
			array(
				'id'
				,'nom'
				,'email'
				,'tel'
				,'date'
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
				$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($con,$_GET['sSearch'] )."%' OR ";
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
		//$sWhere .= (trim($sWhere)!="") ? "dsd" ;
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS id
				,IF(date='0000-00-00 00:00:00' OR date IS NULL, '', DATE_FORMAT(date,'%d %b %Y %Hh %imn')) AS update_time
				,CONCAT(nom,' ',prenom) as nom
				,prenom
				,email
				,tel
			FROM ".TABLE_PREFIX."contacts_newsletters
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

		 	 	$row[1] = $item['nom'];
		 	 	$row[2] = $item['email'];
				$row[3] = $item['tel'];
		 	 	$row[4] = $item['update_time'];

		 	 	$id = $item['id'];

		 	 	$actions = '<ul class="table-controls acenter">';
            	$actions .= '<li><a title="" class="tip delete" id="'.$id.'" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';    
                    
		 	 	$row[5] = $actions;

		 	 	$output['aaData'][] = $row;
		 	} 
		}
		
		echo json_encode( $output );
	}
	
	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'contacts_newsletters', $where);

	   	echo 1; 
	}
	
}