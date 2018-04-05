<?php

class ResultsController extends Zend_Controller_Action
{
    function init()
    {
        $this->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
    }




    function indexAction(){

        $this->view->headTitle()->append('Résultats recherche');

        $search = $this->_getParam('search');


        $interdit=array(">", "<",  ":", "*", "/", "|", "?", '"', '<', '>',"#","$","%","£","@","À","Á","Â","Ã","Ä","Å","à","á","â","ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø","ò","ó","ô","õ","ö","ø","È","É","Ê","Ë","è","é","ê","ë","Ç","ç","Ì","Í","Î","Ï","ì","í","î","ï","Ù","Ú","Û","Ü","ù","ú","û","ü","ÿ","Ñ","ñ");
        $search = str_replace($interdit, "", $search);
        Genius_Model_Traceur::track($_SERVER['REMOTE_ADDR'],'search_standard',$search);
        if (strtolower($search) == "reparation" ){
            $this->_redirect("/reparation");
        }elseif(strtolower($search) == "vente" ){
            $this->_redirect("/vente");
        }elseif(strtolower($search) == "maintenance"  || strtolower($search) == "garantie" ){
            $this->_redirect("/maintenance");
        }elseif(strtolower($search) == "location" ){
            $this->_redirect("/location");
        }elseif(strtolower($search) == "audit" ){
            $this->_redirect("/audit");
        }elseif(strtolower($search) == "reprise" ){
            $this->_redirect("/reprise");
        }elseif(strtolower($search) == "smartprint" ){
            $this->_redirect("/smartprint");
        }



        $this->view->search = $search;
        //$key_words = explode(" ",$search);
        $seo = new Genius_Class_Seo();


        // zend cache
        $frontendOptions = array(
            'lifetime' => 86400, // temps de vie du cache de 24 heures
            'automatic_serialization' => true
        );


        $backendOptions = array(
            // Répertoire où stocker les fichiers de cache
            'cache_dir' => './tmp/'
        );
        $results = Genius_Model_Search::get($search);

        //// créer un objet Zend_Cache_Core
        //$cache = Zend_Cache::factory('Core',
        //    'File',
        //    $frontendOptions,
        //    $backendOptions);
        //$cache->start();
        //
        //$id_cache = md5($search);
        //if(!$results = $cache->load($id_cache)){
        //    $results = Genius_Model_Search::get($search);
        //
        //    $cache->save($results,$id_cache);
        //}else{
        //    $results = $cache->load($id_cache);
        //}
        //

        if ( $results[0] == 0){
            global $siteconfig;
            $email_config = $siteconfig->email;
            $email_berenice = "berenice.haye@eurocomputer.fr";
            $html = new Zend_View();
            $html->setScriptPath(APPLICATION_PATH . '/modules/default/views/scripts/emails/');
            $template_mail = $html->render("mail.phtml");
            $message ="<b>Voici un mot clé qui n'a pas donné de résultat : &nbsp;</b><br/>";
            $message.="<i>Modèle: ".$search."</i><br/>";
            $body_mail = str_replace("{content}", $message, $template_mail);
            $headers = "From: $email_config" . "\r\n";
            $headers .= "Reply-To: ". strip_tags($email_config) . "\r\n";
            $headers .= "BCC: $email_config\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($email_berenice,$this->view->translate("Alerte bon fonctionnement du champ de recherche"),$body_mail,$headers);
            $this->_redirect("/devis?id_product=10&id_marque=0&id_type=0&search=$search");
        }else{
            if ($results[0] == 1){
                $results_id = $results[1];
                $id_product = $results_id[0]['id_product'];
                if ($id_product == 235){
                    $this->_redirect("/chariot-mobile-235.html");
                }else{
                    $this->_redirect($seo->getLinkProduct($id_product));
                }
            }elseif($results[0] == 2){
                $this->view->results = $results[1];
            }
        }

    }
    public function getAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $r = Genius_Model_Product::fillSearch(514);
    }

    function indexAction__()
    {
        $this->view->headTitle()->append('Résultats recherche');
        $this->view->search = $search = $this->_getParam('search');
        //$key_words = explode(" ",$search);

        $seo = new Genius_Class_Seo();
        $results = new Genius_Model_Search($search);
        //$results = $results->protoSearch();
        $results = [];

        var_dump($results);
        die();




        if (empty($results)) {
            global $siteconfig;
            $email_config = $siteconfig->email;
            $email_berenice = "berenice.haye@eurocomputer.fr";
            $html = new Zend_View();
            $html->setScriptPath(APPLICATION_PATH.'/modules/default/views/scripts/emails/');
            $template_mail = $html->render("mail.phtml");
            $message = "<b>Voici un mot clé qui n'a pas donné de résultat : &nbsp;</b><br/>";
            $message .= "<i>Modèle: ".$search."</i><br/>";
            $body_mail = str_replace("{content}", $message, $template_mail);
            $headers = "From: $email_config"."\r\n";
            $headers .= "Reply-To: ".strip_tags($email_config)."\r\n";
            $headers .= "BCC: $email_config\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($email_berenice, $this->view->translate("Alerte bon fonctionnement du champ de recherche"), $body_mail, $headers);
            $this->_redirect("/devis?id_product=10&id_marque=0&id_type=0&search=$search");
        }
        $this->view->results = $results;

        if (sizeof($results) == 1) {
            $id_product = $results[0]['id_product'];
            if ($id_product == 235) {
                $this->_redirect("/chariot-mobile-235.html");
            } else {
                $this->_redirect($seo->getLinkProduct($id_product));
            }
        }
    }
}