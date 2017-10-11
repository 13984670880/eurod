<?php
class VilleController extends Genius_AbstractController
{
	public function indexAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Ville Page');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id = $this->_getParam('id');
	}

}