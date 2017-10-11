<?php

class FiltreController extends Genius_AbstractController
{
    public function indexAction()
    {
        $session = new Zend_Session_Namespace('filtre');

        $dispatcher = new Genius_Class_dispatchFilter($session);

         $dispatcher->result();

        $this->view->result = $dispatcher->getResult();
        $this->view->input = $dispatcher->getInput();

        //var_dump($dispatcher->getResult());

        $this->view->search = $session->search;
        $this->view->subheader = "statics/subheader.phtml";

    }

    /**
     * route : filtre/apply
     * Applique le filtre en fonction de la section choisit et des inputs
     */
    public function makefiltreAction()
    {
        // Instance de la session
        $session = new Zend_Session_Namespace('filtre');
        $session->setExpirationSeconds( 600);

        // Instance de la classe qui vas gerer a tout filtrer et faire
        // La recherche dans la base de donnée
        $filtering = new Genius_Class_FilteringPrinterThermique($_POST);
        if($session->search == 'search_douchette') $filtering = new Genius_Class_FilteringDouchette($_POST);
        if($session->search == 'search_Terminal') $filtering = new Genius_Class_FilteringTerminal($_POST);
        if($session->search == 'search_etiquette_couleur') $filtering = new Genius_Class_FilteringPrinterCouleur($_POST);
        if($session->search == 'search_etiquette_portable') $filtering = new Genius_Class_FilteringPrinterPortable($_POST);
        if($session->search == 'search_etiquette_badgeuse') $filtering = new Genius_Class_FilteringPrinterBadgeuse($_POST);

        //Gestion du filtre ----
        $filtering
            ->setSession($session)
            ->handle()
            ->search()
            ->setResult()
        ;

        //var_dump($filtering);
        //die();
        
        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre');
    }

    /**
     * route : /filtre/form?f=xxxx
     * Change de section Imprimante / Douchette / Terminal
     */
    public function makefiltreformAction()
    {
        $session = new Zend_Session_Namespace('filtre');
        $session->search = $_GET['f'];

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre');
    }

    /**
     * Reset des resultat
     */
    public function deletefiltreAction()
    {
        $session = new Zend_Session_Namespace('filtre');

        unset($session->resultThermique);
        unset($session->resultDouchette);
        unset($session->resultTerminal);
        unset($session->resultEtiquetteCouleur);
        unset($session->resultEtiquettePortable);
        unset($session->resultEtiquetteBadgeuse);

        if ($session->search == 'search_thermique' ) unset($session->inputThermique) ;
        elseif($session->search == 'search_douchette' )  unset($session->inputDouchette) ;
        elseif($session->search == 'search_terminal' )  unset($session->inputTerminal) ;
        elseif($session->search == 'search_etiquette_couleur' )  unset($session->inputEtiquetteCouleur) ;
        elseif($session->search == 'search_etiquette_portable' )  unset($session->inputEtiquettePortable) ;
        elseif($session->search == 'search_etiquette_badgeuse' )  unset($session->inputEtiquetteBadgeuse) ;
        else{
            unset($session->inputThermique) ;
            unset($session->inputDouchette);
            unset($session->inputTerminal);
            unset($session->inputEtiquetteCouleur);
            unset($session->resultEtiquettePortable);
            unset($session->resultEtiquetteBadgeuse);
        }

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre');

    }

}