<?php

class ResultsController extends Zend_Controller_Action
{
    function init()
    {
        $this->view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');
    }

    function indexAction()
    {
        $this->view->headTitle()->append('Résultats recherche');
        $this->view->search = $search = $this->_getParam('search');
        //$key_words = explode(" ",$search);

        $seo = new Genius_Class_Seo();
        $results = new Genius_Model_Search($search);
        //$results = $results->protoSearch();
        $results = [];

        //var_dump($results);




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