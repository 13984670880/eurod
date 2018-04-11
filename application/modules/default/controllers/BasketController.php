<?php

class BasketController extends Genius_AbstractController {

    public function indexAction() {

        var_dump(Genius_Model_Tracker::load()->track());

        var_dump($_SERVER['SERVER_NAME'].$_SERVER['SERVER_NAME']);
        die();
        Genius_Model_Traceur::track($_SERVER['REMOTE_ADDR'],'/ertete','widget','configurateur','etape2');

        Genius_Model_Traceur::track($_SERVER['REMOTE_ADDR'],'module','configurateur','etape3');
        Zend_Layout::getMvcInstance()->setLayout('gv');
        $session = new Zend_Session_Namespace('session');

        //$session->success = true;
        //$session->sucessMsg = 'La demande de devis informatif a bien été envoyé. <br> N\'hesiter pas a utiliser notre configurateur , afin de rechercher du materiel. ';


        $session->setExpirationSeconds( 1);

        $filtre = new Zend_Session_Namespace('filtre');
        $counter = count($filtre->choice);

        $this->view->slider = "statics/geo/slider.phtml";
        $this->view->filter = "statics/geo/filter.phtml";
        $this->view->search = "statics/geo/search_autocomplete.phtml";
        $this->view->infotel = "statics/geo/infotel.phtml";
        $this->view->configurateur = "statics/geo/explication_configurator.phtml";
        $this->view->panier = "statics/geo/icone_panier.phtml";

        $this->view->active = 'index';
        $this->view->choice = $counter;
        $this->view->session = $session;
    }
}