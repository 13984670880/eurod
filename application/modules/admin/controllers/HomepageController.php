<?php
class Admin_HomepageController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getrequests', 'html');
		$ajaxContext->initContext();
	}
	public function indexAction()
	{		
		$this->view->headTitle()->append('Homepage');
		if($_POST){
			$lien = $this->_getParam('lien');
			$data = array ('lien'=>$lien);
			Genius_Model_Global::update(TABLE_PREFIX.'home', $data,"id = 1");
			$this->_redirect('/admin/homepage'); 
		}
		$this->view->home = Genius_Model_Global::selectRow(TABLE_PREFIX.'home','*',"id = 1");
	}
}