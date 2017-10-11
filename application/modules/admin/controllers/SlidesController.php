<?php

class Admin_SlidesController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getslides', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Slides');		
		$this->view->inlineScript()->prependFile(THEMES_ADMIN_URL . 'js/slides-index.js', 'text/javascript');	 	 
	}

	public function modifyAction()
	{ 
		$this->view->headTitle()->append('Edit Slide');	

		global $params;
		global $siteconfig;
		$languages = Genius_Model_Language::getLanguages();

		$this->view->do = $do = $this->view->escape($this->_getParam('do'));
		$slide = array(); 

	 	switch ($do) {
	 		case 'edit':
		 			$this->view->id_slide = $id = (int)$this->_getParam('id');
		 			$where = " id=$id ";
		 			$slide = Genius_Model_Slide::getSlideById($id);
		 			
	 				if($_POST){ 

		 				/*
		 				 * 2 steps :
		 				 * 1. insert in genius_slides
		 				 * 2. insert in genius_pages_multilingual
		 				 */

		 				$id = (int)$_POST['Slides']['id'];

						// 1. update genius_slides
						$data_slides = array(
							'link'         => $this->view->escape($_POST['Slides']['link'])						
							,'update_time' => date('Y-m-d H:i:s')
						);
						Genius_Model_Global::update(TABLE_PREFIX.'slides', $data_slides, " id=$id ");
						
						// update order item
						if ($_POST['Slides']['order_item']):
							$order_item = (int) $_POST['Slides']['order_item'];
							Genius_Model_Global::updateOrderItemSlide(TABLE_PREFIX . "slides", $order_item, $id);
						else:
							$order_item = (int) $_POST['Products']['old_order_item'];
						endif;

		 				// 3. update genius_slides_multilingual
		 				if(!empty($languages)){ 
		 					foreach ($languages as $k => $item){
								$title = Genius_Class_Utils::idml($_POST['Slides'], 'title_'.$item['abbreviation'], $_POST['Slides']['title_'.DEFAULT_LANG_ABBR]);
								$text  = Genius_Class_Utils::idml($_POST['Slides'], 'text_'.$item['abbreviation'], $_POST['Slides']['title_'.DEFAULT_LANG_ABBR]);

								$id_language = $item['id'];

		 						$data_slides_multilingual = array(
									'title' => $title
									,'text' => $text
		 						);
		 						Genius_Model_Global::update(TABLE_PREFIX.'slides_multilingual', $data_slides_multilingual, " id_slide=$id AND id_language=$id_language ");
		 					}
		 				}

		 				$this->_redirect('/admin/slides');
	 				}		 			
	 				
	 			break;

	 		case 'add':

	 				if($_POST){ 

		 				/*
		 				 * 2 steps :
		 				 * 1. insert in genius_slides
		 				 * 2. insert in genius_pages_multilingual
		 				 */

						// 1. insert in genius_slides
						$data_slides = array(
							'link'         => $this->view->escape($_POST['Slides']['link'])						
							,'create_time' => date('Y-m-d H:i:s')
							,'update_time' => date('Y-m-d H:i:s')
						);
						Genius_Model_Global::insert(TABLE_PREFIX.'slides', $data_slides);
						$lastId = Genius_Model_Global::lastId();

		 				// 3. insert in genius_slides_multilingual
		 				if(!empty($languages)){ 
		 					foreach ($languages as $k => $item) {
								$title                = Genius_Class_Utils::idml($_POST['Slides'], 'title_'.$item['abbreviation'], $_POST['Slides']['title_'.DEFAULT_LANG_ABBR]);
								$text                 = Genius_Class_Utils::idml($_POST['Slides'], 'text_'.$item['abbreviation'], $_POST['Slides']['title_'.DEFAULT_LANG_ABBR]);

		 						$data_slides_multilingual = array(
									'id_slide'               => $lastId
									,'id_language'          => $item['id']
									,'title'                => $title
									,'text'                 => $text
		 						);
		 						Genius_Model_Global::insert(TABLE_PREFIX.'slides_multilingual', $data_slides_multilingual);
		 					}
		 				}

		 				$this->_redirect('/admin/slides');
	 				}

	 			break;
	 		
	 		default:
	 			break;
	 	}

		$this->view->slide = $slide;	 
	}

	public function deleteAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();	 	

	   	$id = (int)$this->_getParam('id');
	   	$where  = " id=$id ";
	   	$return = Genius_Model_Global::delete(TABLE_PREFIX.'slides', $where);

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
		   		$return = Genius_Model_Global::delete(TABLE_PREFIX.'slides', $where);		
	   		}			   	 	 
	   	}

	   	echo 1; 
	}

	public function getslidesAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
		$aColumns = 
			array(
				'slide.id'
				,'sm.title'
				,'sm.text'
				,'slide.link'
				,'slide.update_time'
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
		$sWhere .= (trim($sWhere)!="") ? " AND (sm.id_language=".DEFAULT_LANG_ID.") " : " WHERE (sm.id_language=".DEFAULT_LANG_ID.") " ;
		
		$sOrder = ( $sOrder == "" ) ? "ORDER BY slide.order_item ASC" : $sOrder;
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS slide.id
				,IF(slide.update_time='0000-00-00 00:00:00' OR slide.update_time IS NULL, '', DATE_FORMAT(slide.update_time,'%d %b %Y %Hh %imn')) AS update_time
				,sm.title
				,sm.text
				,slide.link
			FROM ".TABLE_PREFIX."slides slide
			LEFT JOIN ".TABLE_PREFIX."slides_multilingual sm ON slide.id=sm.id_slide
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
		 	 	$row[2] = $item['text'];
		 	 	$row[3] = $item['link'];
		 	 	$row[4] = $item['update_time'];

		 	 	$id = $item['id'];
		 	 	$url_edit = '/admin/slides/modify/do/edit/id/'.$item['id'];

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