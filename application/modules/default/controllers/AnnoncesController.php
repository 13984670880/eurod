<?php
class AnnoncesController extends Genius_AbstractController
{
	public function indexAction()
	{		
		$this->view->headTitle()->append('Easy Living | Property Listing Grid Sidebar');
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('_partials/pagination.phtml');
		$this->view->headMeta()->appendName('description',"Easy Living | Property Listing Grid Sidebar");
		$this->view->headMeta()->appendName('keyword',"Easy Living | Property Listing Grid Sidebar");
		$this->view->categories_annonces = Genius_Model_Global::select(TABLE_PREFIX.'categories_annonces','*',"id is not null ");
		$this->view->types_annonces = Genius_Model_Global::select(TABLE_PREFIX.'types_annonces','*',"id is not null ");
		
		$this->view->id_type_annonce = $this->_getParam('id_type_annonce');
		$this->view->id_category_annonce = $this->_getParam('id_category_annonce');
		
        $this->view->subheader = "statics/subheader.phtml"; 
		$this->view->sidebar = "statics/sidebar.phtml"; 
		$this->view->active = 'liste' ;
		$this->view->id_type_annonce = $this->_getParam('id_type_annonce');
		$results = Genius_Model_Annonces::getAllProperties('');
		
		$paginator = Zend_Paginator::factory($results);
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page', 1));
		$paginator->setItemCountPerPage($this->_getParam('par', 12));			
		$this->view->paginator = $paginator;
	}
}