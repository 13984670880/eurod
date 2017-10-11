<?php
class ListeController extends Genius_AbstractController
{
	public function indexAction()
	{		
		$this->view->headTitle()->append('Easy Living | Property Listing Grid Sidebar');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('_partials/pagination.phtml');
		$this->view->headMeta()->appendName('description',"Easy Living | Property Listing Grid Sidebar");
		$this->view->headMeta()->appendName('keyword',"Easy Living | Property Listing Grid Sidebar");
        $this->view->subheader = "statics/subheader.phtml"; 
		$this->view->sidebar = "statics/sidebar.phtml"; 
		$this->view->active = 'liste' ;
	}
	

}