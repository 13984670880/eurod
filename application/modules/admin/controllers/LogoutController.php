<?php
class Admin_LogoutController extends Genius_AbstractController {
	
	public function init() 
	{
    }
	
    function indexAction() 
    {
		$this->_helper->layout->disableLayout();   
		$this->_helper->viewRenderer->setNoRender();

		$storage = new Zend_Auth_Storage_Session('authSession', 'admin');
		$storage->clear();
		//Zend_Auth::getInstance()->clearIdentity();
		
		$this->_redirect('/admin');		
	}
}