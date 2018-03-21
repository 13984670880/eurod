<?php

class TestgvController extends Genius_AbstractController
{

	public function indexAction()
	{
        $assignvalues = array(
            "phtml"=>"info-materiel.phtml",
            "sender"=>'geoffrey.valero@eurocomputer.Fr',
            "receiver"=>"valero.geoffrey@live.fr",
            "addcc"=>"valero.geoffrey@gmail.com",
            "subject"=>"Demande de devis - GV TEST ",
            "post"=>'test',
            "host"=>'Administrateur',
            "input" => 'test',
        );
        $state = Genius_Class_Email::send($assignvalues);
        $baseUrl = new Zend_View_Helper_BaseUrl();
        return $this->getResponse()->setRedirect($baseUrl->baseUrl().'/');
	}

}