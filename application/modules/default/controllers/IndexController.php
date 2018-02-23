<?php

class IndexController extends Genius_AbstractController {

    public function indexAction() {

        Zend_Layout::getMvcInstance()->setLayout('gv');



        $this->_helper->viewRenderer('gvindex');

        $this->view->slider = "statics/geo/slider.phtml";

        $this->view->filter = "statics/geo/filter.phtml";

        $this->view->search = "statics/geo/search_autocomplete.phtml";
        $this->view->searchmin = "statics/geo/search_autocomplete_min.phtml";

        $this->view->infotel = "statics/geo/infotel.phtml";

        $this->view->configurateur = "statics/geo/explication_configurator.phtml";

        $this->view->active = 'index';

        $session = new Zend_Session_Namespace('session');
        $filtre = new Zend_Session_Namespace('filtre');

        $search='zm';
        $results = Genius_Model_Search::get($search);
        $data=[];


        $i=0;


        foreach ($results[1] as $index => $result) {
            if($i < 6){
                $id[]=$result['id_product'];
                $data[]=[
                    "value" => $result['title'],
                    "label" => [
                        "label" => 'xx' ,
                        'h' => PROJECTS.'/xxx.html'
                    ],
                    "desc" => $result['photocrh_cover_p'],
                ];
            }

            $i++;
        }

        $searcherProduct =  Genius_Model_HelperProduct::getSearchProduct($id);

        //var_dump($searcherProduct);
        //die();
//
        //var_dump($id);
        //var_dump($results);
        //var_dump($data);
        //die();

        $this->view->choice = count($filtre->choice);
        $this->view->session = $session;
    }
	
	public function getallmarquesAction(){
		$this->_helper->layout->disableLayout();
	}

}