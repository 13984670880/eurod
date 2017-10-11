<?php

class NotremissionController extends Genius_AbstractController
{

	public function indexAction()
	{
		
		$this->view->headTitle()->append('Lynxis');
		
		$this->view->headMeta()->appendName('description',"Lynxis");
		$this->view->headMeta()->appendName('keyword',"Lynxis");
		$this->view->presentation = Genius_Model_Global::selectRow(TABLE_PREFIX.'pages_multilingual','*',"id_page = '7' AND id_language ='".DEFAULT_LANG_ID."' ");
	}

}