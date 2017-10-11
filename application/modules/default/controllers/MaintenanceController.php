<?php

class MaintenanceController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->initContext();

		Zend_Layout::getMvcInstance()->setLayout('comingsoon');
	}

	public function indexAction()
	{
		$this->view->headTitle()->append('Coming soon');

		global $params;
		global $siteconfig;

		if($_POST){ 
			/*
			 * 2 steps :
			 * 1. insert in genius_requests
			 * 2. insert in genius_requests_multilingual
			 */

			// 1. insert in genius_requests
			$data_requests = array(
				'create_time' => date('Y-m-d H:i:s')
				,'newsletter' => 'Oui'
			);
			Genius_Model_Global::insert(TABLE_PREFIX.'requests', $data_requests);
		 	$lastId = Genius_Model_Global::lastId();

		 	// 2. insert in genius_requests_multilingual
		 	$data_requests_multilingual =  array(
				'id_request'   => $lastId
				,'id_language' => '1'
				,'name'        => $this->view->escape($_POST['name'])
				,'title'       => 'Formulaire de contact'
				,'object'      => $this->view->escape($_POST['subject'])
				,'email'       => $this->view->escape($_POST['email'])
				,'text'        => $this->view->escape($_POST['message'])
		 	);

		 	Genius_Model_Global::insert(TABLE_PREFIX.'requests_multilingual', $data_requests_multilingual);

		 	// notification mail
 	        $html = new Zend_View();
 	        $html->setScriptPath(TEMPLATES_PATH.'emails/');

 	        $html->assign('name', $this->view->escape($_POST['name']));
 	        $html->assign('email', $this->view->escape($_POST['email']));
 	        $html->assign('object', $this->view->escape($_POST['subject']));
 	        $html->assign('message', $this->view->escape($_POST['message']));

 			$mail    = new Zend_Mail('utf-8');
 			$message = $html->render('contactform.phtml');
 	        $mail->setBodyHtml($message);
 	        $mail->setFrom($this->view->escape($_POST['email']), $siteconfig->title);
 	        $mail->addTo('hurn_08@yahoo.fr');
 	        $mail->addBcc('swiitjaden@gmail.com');
 	        $mail->setSubject('Nouveau message - Formulaire de contact');

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
		}
	}

}