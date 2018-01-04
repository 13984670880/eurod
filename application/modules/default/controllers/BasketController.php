<?php

class BasketController extends Genius_AbstractController {

    public function indexAction() {
        Zend_Layout::getMvcInstance()->setLayout('gv');

        $this->view->slider = "statics/geo/slider.phtml";
        $this->view->filter = "statics/geo/filter.phtml";
        $this->view->search = "statics/geo/search_autocomplete.phtml";
        $this->view->infotel = "statics/geo/infotel.phtml";
        $this->view->active = 'index';
    }

}