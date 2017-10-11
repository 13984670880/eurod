<?php
class LoginController extends Genius_AbstractController
{
	public function indexAction()
	{		
	error_reporting(E_ALL);
     ini_set('display_errors',1);
		$this->view->headTitle()->append('Easy Living | Login Form');
		
		$this->view->headMeta()->appendName('description',"Easy Living | Login Form");
		$this->view->headMeta()->appendName('keyword',"Easy Living | Login Form");
        $this->view->subheader = "statics/subheader.phtml"; 
	}

}