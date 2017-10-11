<?php

class ErrorController extends Genius_AbstractController
{

	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');
/*echo "<pre>";
  print_r($errors->exception->getMessage());
  print_r($errors->exception->getTraceAsString());
  echo "</pre>";
  die('kk')*/;
		if (!$errors || !$errors instanceof ArrayObject) {
			$this->view->message = 'You have reached the error page';
			return;
		}
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$httpCode = 404;
				$this->getResponse()->setHttpResponseCode(404);
				$priority = Zend_Log::NOTICE;
				$this->view->message = 'Page introuvable';
				$this->view->code_error = 404;
				$this->view->headTitle('Page not found');
			break;
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER: 
			  switch (get_class($errors->exception)) {
				case 'Zend_View_Exception' :
					$httpCode = 500;
					$this->getResponse()->setHttpResponseCode(500);
					$this->view->message = "Erreur de l'application";
					$this->view->code_error = 500;
					$this->view->headTitle('Application error');
				break;
				case 'Zend_Db_Exception' :
					$httpCode = 503;
					$this->getResponse()->setHttpResponseCode(500);
					$this->view->message = "Le serveur est actuellement incapable de traiter la demande en raison d’une surcharge temporaire ou bien en raison d’une maintenance du serveur en cours.";
					$this->view->code_error = 500;
					$this->view->headTitle('Erreur de traitement dans la base de données');
				break;
				default:
					$httpCode = 500;
					$this->getResponse()->setHttpResponseCode(500);
					$this->view->message = "Erreur de l'application";
					$this->view->code_error = 500;
					$this->view->headTitle('Application error');					
				break;
			  }
            break;
		}
		
		//envoi du mail
    	global $siteconfig;
	    $email_receiver = 'elsa.queri@eucoomputer.fr';
    	$sender ='no-reply@eurocomputer.fr';
		$email_admin = 'raliony@yahoo.fr';
    	
    	$subject = 'Erreur '.$httpCode.' sur le site';
    	
    	$message = '<p>Bonjour, </p>'; 
		$message .= '<p>Une erreur '.$httpCode.' viens de se produire sur le site.'."</p>";
    	$message .= '<p>Voici les informations relatives à cette erreur :'."</p>";
	 	$message .= '<p><b>Message erreur :</b> '.$errors->exception->getMessage()."</p>";		
    	$message .= '<p><b>Heure: </b>'.date("d/m/Y H:i")."</p>";
    	$message .= '<p><b>Page concernée: </b>'.$_SERVER['REQUEST_URI']."</p>";
    	$message .= '<p><b>Url complet: </b>'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."</p>";
    	$message .= '<p><b>Page précédente: </b>'.$_SERVER['HTTP_REFERER']."</p>";
    	$message .= '<p><b>Adresse IP du visiteur: </b>'.$_SERVER['REMOTE_ADDR'].' / '.$_SERVER['HTTP_X_FORWARDED_FOR']."</p>";
    	$message .= '<p><b>User agent: </b>'.$_SERVER['HTTP_USER_AGENT'].'</p>';
		
		$html = new Zend_View();
		$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/scripts/emails/');
		$template_mail = $html->render("error.phtml");
		$body_mail = str_replace("{content}", $message, $template_mail);
		$headers = "From: $sender" . "\r\n";
		$headers .= "Reply-To: ". strip_tags($sender) . "\r\n";
		//$headers .= "BCC: $email_admin\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		//mail($email_receiver,$subject,$body_mail,$headers);	
		
		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true) {
			$this->view->exception = $errors->exception;
		}
		$this->view->request = $errors->request;
	}

	private function _getLog()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');
		if (!$bootstrap->hasResource('Log')) {
			return false;
		}
		$log = $bootstrap->getResource('Log');
		return $log;
	}

}