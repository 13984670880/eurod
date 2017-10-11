<?php

class Admin_SiteconfigurationController extends Genius_AbstractController
{
	public function init()
	{
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Site configuration');

		// update Configuration
		if(!empty($_POST['SiteConfiguration'])){
			Genius_Model_Global::update(TABLE_PREFIX.'configs', $_POST['SiteConfiguration'], 'id='.intval($_POST['SiteConfiguration']['id']));
		}

		$this->view->configs = $configs = Genius_Model_Global::select(TABLE_PREFIX.'configs','siteweb,adresse,email,phone,fax,title,slogan,mode_maintenance,pause_slider,transition_slider','id=1');
	}

}