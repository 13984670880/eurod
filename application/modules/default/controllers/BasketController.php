<?php

class BasketController extends Genius_AbstractController {

    public function indexAction() {

        Zend_Layout::getMvcInstance()->setLayout('gv');
        $session = new Zend_Session_Namespace('session');

        $filtre = new Zend_Session_Namespace('filtre');
        $counter = count($filtre->choice);

        $this->view->slider = "statics/geo/slider.phtml";
        $this->view->filter = "statics/geo/filter.phtml";
        $this->view->search = "statics/geo/search_autocomplete.phtml";
        $this->view->infotel = "statics/geo/infotel.phtml";
        $this->view->active = 'index';
        $this->view->counter = $counter;

        $this->view->session = $session;



    }
}