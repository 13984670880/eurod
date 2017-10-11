<?php
class AjaxController extends Zend_Controller_Action {
  function init() {
        $this->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('saveregister', 'html');
		$ajaxContext->addActionContext('authentification', 'html');
		$ajaxContext->addActionContext('saveregisteredit', 'html');
		$ajaxContext->addActionContext('getphoto', 'html');
        $ajaxContext->initContext();
    }
	
	function newsletterAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$newsletter = $this->_getParam('newsletter');
		$contacts_newsletters = Genius_Model_Global::select(TABLE_PREFIX.'contacts_newsletters',"*","email = '$newsletter' ");
		if (empty($contacts_newsletters)){
			$datas = array (
			"email"=>$newsletter,
			"date"=>date('Y-m-d H:i:s')
			);
			Genius_Model_Global::insert(TABLE_PREFIX.'contacts_newsletters', $datas);
			echo json_encode(array('state'=>1,"msg"=>$this->view->translate("Votre adresse mail a bien été enregistré")));
		}else{
			echo json_encode(array('state'=>1,"msg"=>$this->view->translate("Cette adresse email existe déjà")));
		}
		
	}
	
	function contactAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$nom = $this->_getParam('nom');
		$email = $this->_getParam('email');
		$message = $this->_getParam('message');
		$captcha = $this->_getParam('captcha');
		
		if ($_SESSION["code"] != $captcha){
			echo json_encode(array('state'=>0,"msg"=>'&laquo; '.$this->view->translate("Erreur code captcha").' &raquo;'));
			die();
		}
		
		
		Genius_Model_Global::insert(TABLE_PREFIX.'requests',array('create_time'=>date('Y-m-d H:i:s')));
		$registry = Zend_Registry::getInstance();
		$db = $registry['db'];
		$id_request = $db->lastInsertId();
		
		$sess = new Zend_Session_Namespace();	
		
		Genius_Model_Global::insert(TABLE_PREFIX.'requests_multilingual',array("id_request"=>$id_request,'id_language'=>$sess->translate_lang_id,'object'=>$this->view->translate("Demande infos"),'name'=>$nom,'email'=>$email,'text'=>$message));
		
		$assignvalues = array(
		"phtml"=>"contact.phtml",
		"sender"=>$email,
		"receiver"=>"info@lynxis.eu",
		"addcc"=>"jms@lynxis.eu",
		"subject"=>$this->view->translate("Demande d'informations"),
		"name"=>$nom,
		"message"=>$message,
		"host"=>'http://'.$_SERVER['HTTP_HOST'],
		"translate"=>$this->view->translate
		);
		$state = Genius_Class_Email::send($assignvalues);
		if ($state){
			echo json_encode(array("state"=>1,"msg"=>'&laquo; '.$this->view->translate("Envoi réussi").' &raquo;'));
		}else{
			echo json_encode(array("state"=>0,"msg"=>'&laquo; '.$this->view->translate("Echec de l'envoi, merci de réessayer svp").' &raquo;'));
		}
	}
}