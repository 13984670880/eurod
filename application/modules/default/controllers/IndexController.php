<?php

class IndexController extends Genius_AbstractController {

    public function indexAction() {
        Zend_Layout::getMvcInstance()->setLayout('gv');
        $this->_helper->viewRenderer('gvindex');

        $this->view->slider = "statics/geo/slider.phtml";
        $this->view->filter = "statics/geo/filter.phtml";
        $this->view->infotel = "statics/geo/infotel.phtml";
        $this->view->active = 'index';
    }
	
	public function getallmarquesAction(){
		$this->_helper->layout->disableLayout();
	}

}