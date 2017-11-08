<?php

/**
 * Class FiltredevisController
 */
class FiltredevisController extends Genius_AbstractController {

    protected $modelFiltre;
    protected $session;


    public function indexAction()
    {
        global $db;

        $exception=[
            'search_terminal_poignet',
            'search_terminal_poignet_',
        ];

        $session = new Zend_Session_Namespace('filtre');

        $isInException = array_search( $session->search,$exception) == null ? false : true;

        $input = $this->getInput($session);
        $this->modelFiltre = $this->getModel($session);
        $id = $_GET['product'];
        $product = $this->modelFiltre->find($id);

        $result = $db->query($product)->fetch();

        var_dump($id);
        var_dump($session->search);
        var_dump($this->getInput($session));
        var_dump($result);


        die();
    }

    /**
     * Nous donne les input renseigner par l'utilisateur
     * @return mixed
     */
    public function getInput($session )
    {
        //Imprimante
        if ($session->search == 'search_thermique') return $session->inputThermique;
        if ($session->search == 'search_badgeuse') return $session->inputPrinter;
        if ($session->search == 'search_etiquette_couleur') return $session->inputEtiquetteCouleur;
        if ($session->search == 'search_etiquette_portable') return $session->inputEtiquettePortable;
        if ($session->search == 'search_etiquette_badgeuse') return $session->inputEtiquetteBadgeuse;
        if ($session->search == 'search_printer_laser') return $session->inputPrinterLaser;
        if ($session->search == 'search_printer_matricielle') return $session->inputPrinterMatricielle;

        //Terminal
        if ($session->search == 'search_terminal') return $session->inputTerminal;
        if ($session->search == 'search_terminal_pda') return $session->inputTerminalPda;
        if ($session->search == 'search_terminal_embarque') return $session->inputTerminalEmbarque;
        if ($session->search == 'search_terminal_poignet') return $session->inputTerminalPoignet;

        //Douchette
        if ($session->search == 'search_douchette') return $session->inputDouchette;
        if ($session->search == 'search_douchette_ring') return $session->inputDouchetteRing;
        if ($session->search == 'search_scanner_fixe') return $session->inputScannerFixe;

        //poste de travail
        if ($session->search == 'search_poste_pc') return $session->inputPostePc;
        if ($session->search == 'search_poste_portable') return $session->inputPostePortable;
        if ($session->search == 'search_poste_client') return $session->inputPosteClient;

    }

    /**
     * Nous donne les input renseigner par l'utilisateur
     * @return mixed
     */
    public function getModel($session)
    {
        //Imprimante
        if ($session->search == 'search_thermique') return new Genius_Model_Filtre();
        if ($session->search == 'search_etiquette_badgeuse') return new Genius_Model_FiltreEtiquetteBadgeuse();
        if ($session->search == 'search_etiquette_couleur') return new Genius_Model_FiltreEtiquetteCouleur();
        if ($session->search == 'search_etiquette_portable') return new Genius_Model_FiltreEtiquettePortable();
        if ($session->search == 'search_printer_laser') return new Genius_Model_FiltrePrinterLaser();
        if ($session->search == 'search_printer_matricielle') return new Genius_Model_FiltrePrinterMatricielle();

        //Terminal
        if ($session->search == 'search_terminal') return $session->inputTerminal;
        if ($session->search == 'search_terminal_pda') return $session->inputTerminalPda;
        if ($session->search == 'search_terminal_embarque') return $session->inputTerminalEmbarque;
        if ($session->search == 'search_terminal_poignet') return $session->inputTerminalPoignet;

        //Douchette
        if ($session->search == 'search_douchette') return $session->inputDouchette;
        if ($session->search == 'search_douchette_ring') return $session->inputDouchetteRing;
        if ($session->search == 'search_scanner_fixe') return $session->inputScannerFixe;

        //poste de travail
        if ($session->search == 'search_poste_pc') return $session->inputPostePc;
        if ($session->search == 'search_poste_portable') return $session->inputPostePortable;
        if ($session->search == 'search_poste_client') return $session->inputPosteClient;

    }

}