<?php

class IndexController extends Genius_AbstractController {

    /**
     *
     */
    public function indexAction()
    {

            Zend_Layout::getMvcInstance()->setLayout('gv');
            $this->_helper->viewRenderer('gvindex');
            $this->view->slider = "statics/geo/slider.phtml";
            $this->view->filter = "statics/geo/filter.phtml";
            $this->view->search = "statics/geo/search_autocomplete.phtml";
            $this->view->searchmin = "statics/geo/search_autocomplete_min.phtml";
            $this->view->infotel = "statics/geo/infotel.phtml";
            $this->view->configurateur = "statics/geo/explication_configurator.phtml";

            $session = new Zend_Session_Namespace('session');
            $filtre = new Zend_Session_Namespace('filtre');
            $this->view->choice = count($filtre->choice);
            $this->view->session = $session;

            //$this->view->slider = "statics/slider.phtml";
            //$this->view->active = 'index';
    }

    /**
     *
     */
	public function getallmarquesAction()
    {
		$this->_helper->layout->disableLayout();
	}

}