<?php

class Admin_UsersController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getusers', 'html');
		$ajaxContext->addActionContext('delete', 'html');
		$ajaxContext->addActionContext('massdelete', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Users');		 
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/users-index.js', 'text/javascript');	 
	}

	public function modifyAction()
	{ 
		$this->view->headTitle()->append('Edit User');

		global $params;
		global $siteconfig;

		$this->view->do = $do = $this->view->escape($this->_getParam('do'));
		$user = array();
	 	switch ($do) {
	 		case 'edit':
	 				$id = (int)$this->_getParam('id');
	 				$where = " id=$id ";
	 				$user = Genius_Model_Global::select(TABLE_PREFIX.'users', '', $where);
	 				$user = $user[0];

	 				if($_POST){ 
		 				$data_user = array(
							'first_name'   => $this->view->escape($_POST['Users']['first_name'])
							,'last_name'   => $this->view->escape($_POST['Users']['last_name'])
							,'email'       => $this->view->escape($_POST['Users']['email'])
							,'update_time' => date('Y-m-d H:i:s')
		 				);

						$id    = (int)$_POST['Users']['id'];
						$where = " id=$id ";
		 				Genius_Model_Global::update(TABLE_PREFIX.'users', $data_user, $where);

		 				$this->_redirect('/admin/users'); 	 
	 				}
	 			break;

	 		case 'add':

	 				if($_POST){ 
		 				$data_user = array(
							'first_name'   => $this->view->escape($_POST['Users']['first_name'])
							,'last_name'   => $this->view->escape($_POST['Users']['last_name'])
							,'email'       => $this->view->escape($_POST['Users']['email'])
							,'create_time' => date('Y-m-d H:i:s')
							,'update_time' => date('Y-m-d H:i:s')
		 				);

		 				Genius_Model_Global::insert(TABLE_PREFIX.'users', $data_user);

		 				// send mail with a link to set password
				        $html = new Zend_View();
				        $html->setScriptPath(TEMPLATES_PATH.'emails/');

						$email = $this->view->escape($_POST['Users']['email']);
						$url   = $siteconfig->siteweb.'admin/resetpassword?email='.$email;
				        $html->assign('url', $url);

						$mail    = new Zend_Mail('utf-8');
						$message = $html->render('createpwd.phtml');
				        $mail->setBodyHtml($message);
				        $mail->setFrom($siteconfig->email, $siteconfig->title);
				        $mail->addTo($email);
				        $mail->addBcc('hurn_08@yahoo.fr');
				        $mail->setSubject('Mise en place de votre mot de passe');

				        $sent = FALSE;
				        if (!$params->is_local) {
				            if($mail->send())
				            	$sent = TRUE;
				        }else {
				            $tr = new Zend_Mail_Transport_Smtp($params->smtp_local);
				            Zend_Mail::setDefaultTransport($tr);
				            if($mail->send())
				            	$sent = TRUE;
				        }

		 				$this->_redirect('/admin/users');
	 				}
	 			break;
	 		
	 		default:
	 			break;
	 	}

	 	$this->view->user = $user;
	}

	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'users', $where);

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
		   		$return = Genius_Model_Global::delete(TABLE_PREFIX.'users', $where);		
	   		}			   	 	 
	   	}

	   	echo 1; 
	}	

	public function getusersAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				'user.id'
				,'user.first_name'
				,'user.last_name'
				,'user.email'
				,'user.update_time'
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
				SQL_CALC_FOUND_ROWS user.id
				,user.first_name
				,user.last_name
				,user.email
				,IF(user.update_time='0000-00-00 00:00:00' OR user.update_time IS NULL, '', DATE_FORMAT(user.update_time,'%d %b %Y %Hh %imn')) AS update_time
			FROM ".TABLE_PREFIX."users user
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

		 	 	$row[1] = $item['first_name'];
		 	 	$row[2] = $item['last_name'];
		 	 	$row[3] = $item['email'];
		 	 	$row[4] = $item['update_time'];

		 	 	$id = $item['id'];
		 	 	$url_edit = '/admin/users/modify/do/edit/id/'.$item['id'];

		 	 	$actions = '<ul class="table-controls acenter">';
            	$actions .= '<li><a title="" class="tip" href="'.$url_edit.'" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
            	$actions .= '<li><a title="" class="tip delete" id="'.$id.'" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';    
                    
		 	 	$row[5] = $actions;

		 	 	$output['aaData'][] = $row;
		 	} 
		}
		
		echo json_encode( $output );
	}
}