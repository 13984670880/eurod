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

        $search='z4';
        $results = Genius_Model_Search::get($search);

        $interdit=array(" ","(",")",">", "<",  ":", "*", "/", "|", "?", '"', '<', '>',"#","$","%","£","@","À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");

        $results = Genius_Model_Search::get($search);

        $data=[];
        $i=0;
        foreach ($results[1] as $index => $result) {
            if($i < 6){

                $tt = strtolower($result['title']);
                $title = str_replace($interdit, "-", $tt);


                $lnk = $title.'-'.$result['id_product'];
                $data[]=[
                    "value" => $result['title'],
                    "label" => [
                        "label" => '',
                        'h' => PROJECTS.'/materiel/1/1/'.$lnk.'.html'
                    ],
                    "desc" => $result['photocrh_cover_p'],
                ];
            }

            $i++;
        }

        if(count($data) == 1){
            $photo = Genius_Model_Product::getProductImageCoverById($result['id_product']);
            $xx = UPLOAD_URL.'images/'.$photo['path_folder'].'/'.$photo['filename'].'-small-'.$photo['id_image'].'.'.$photo['format'];
            $data[0]['label']['h']=$xx;
        }


        //$searcherProduct =  Genius_Model_HelperProduct::getSearchProduct($id);

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