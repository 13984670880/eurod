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
        $session->setExpirationSeconds( 1200);

        // Instance de la classe qui vas gerer a tout filtrer et faire
        // La recherche dans la base de donnée
        $filtering = new Genius_Class_FilteringPrinterThermique($_POST);
        if($session->search == 'search_etiquette_couleur') $filtering = new Genius_Class_FilteringPrinterCouleur($_POST);
        if($session->search == 'search_etiquette_portable') $filtering = new Genius_Class_FilteringPrinterPortable($_POST);
        if($session->search == 'search_etiquette_badgeuse') $filtering = new Genius_Class_FilteringPrinterBadgeuse($_POST);
        if($session->search == 'search_printer_laser') $filtering = new Genius_Class_FilteringPrinterLaser($_POST);
        if($session->search == 'search_printer_matricielle') $filtering = new Genius_Class_FilteringPrinterMatricielle($_POST);

        if($session->search == 'search_douchette') $filtering = new Genius_Class_FilteringDouchette($_POST);
        if($session->search == 'search_douchette_ring') $filtering = new Genius_Class_FilteringDouchetteRing($_POST);

        if($session->search == 'search_terminal') $filtering = new Genius_Class_FilteringTerminal($_POST);
        if($session->search == 'search_terminal_pda') $filtering = new Genius_Class_FilteringTerminalPda($_POST);
        if($session->search == 'search_terminal_embarque') $filtering = new Genius_Class_FilteringTerminalEmbarque($_POST);
        if($session->search == 'search_terminal_poignet') $filtering = new Genius_Class_FilteringTerminalPoignet($_POST);

        if($session->search == 'search_poste_client') $filtering = new Genius_Class_FilteringPosteClient($_POST);
        if($session->search == 'search_poste_pc') $filtering = new Genius_Class_FilteringPostePc($_POST);
        if($session->search == 'search_poste_portable') $filtering = new Genius_Class_FilteringPostePortable($_POST);


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
        $ip = $_SERVER['REMOTE_ADDR'];
        $search = $_GET['f'];

        $accessible = [
            'search_thermique',
            'search_etiquette_couleur',
            'search_etiquette_portable',
            'search_etiquette_badgeuse',
            'search_printer_laser',
            'search_printer_matricielle',
        ];

        $isAccessible = array_search($search,$accessible) <> null ? true : false;


        $session = new Zend_Session_Namespace('filtre');
        $session->search = $_GET['f'];

        if($ip <> '192.168.1.16'){
            $session->search = $isAccessible == true ? $_GET['f'] : 'search_thermique';
        }

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre');
    }

    /**
     * Reset des resultat
     */
    public function deletefiltreAction()
    {
        $session = new Zend_Session_Namespace('filtre');


        unset($session->resultTerminal);
        unset($session->resultTerminalPda);
        unset($session->resultTerminalEmbarque);
        unset($session->resultTerminalPoignet);

        unset($session->resultDouchette);

        unset($session->resultThermique);
        unset($session->resultEtiquetteCouleur);
        unset($session->resultEtiquettePortable);
        unset($session->resultEtiquetteBadgeuse);
        unset($session->resultPrinterLaser);
        unset($session->resultPrinterMatricielle);

        unset($session->resultPosteClient);
        unset($session->resultPostePc);
        unset($session->resultPostePotable);

        if ($session->search == 'search_thermique' ) unset($session->inputThermique) ;
        elseif($session->search == 'search_etiquette_couleur' )  unset($session->inputEtiquetteCouleur) ;
        elseif($session->search == 'search_etiquette_portable' )  unset($session->inputEtiquettePortable) ;
        elseif($session->search == 'search_etiquette_badgeuse' )  unset($session->inputEtiquetteBadgeuse) ;
        elseif($session->search == 'search_printer_laser' )  unset($session->inputPrinterLaser) ;
        elseif($session->search == 'search_printer_matricielle' )  unset($session->inputPrinterMatricielle) ;
        elseif($session->search == 'search_douchette' )  unset($session->inputDouchette) ;
        elseif($session->search == 'search_terminal' )  unset($session->inputTerminal) ;
        elseif($session->search == 'search_terminal_pda' )  unset($session->inputTerminalPda) ;
        elseif($session->search == 'search_terminal_embarque' )  unset($session->inputTerminalEmbarque) ;
        elseif($session->search == 'search_terminal_poignet' )  unset($session->inputTerminalPoignet) ;
        elseif($session->search == 'search_poste_pc' )  unset($session->inputPostePc) ;
        elseif($session->search == 'search_poste_client' )  unset($session->inputPosteClient) ;
        elseif($session->search == 'search_poste_portable' )  unset($session->inputPostePortable) ;
        else{
            unset($session->inputThermique) ;
            unset($session->inputDouchette);
            unset($session->inputEtiquetteCouleur);
            unset($session->resultEtiquettePortable);
            unset($session->resultEtiquetteBadgeuse);
            unset($session->resultPrinterLaser);
            unset($session->resultPrinterMatricielle);

            unset($session->inputTerminal);
            unset($session->inputTerminalPda) ;
            unset($session->inputTerminalEmbarque) ;
            unset($session->inputTerminalPoignet) ;

            unset($session->inputPosteClient);
            unset($session->inputPostePc);
            unset($session->inputPostePotable);
        }

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre');

    }

    /**
     * Choisit un materiel est le met dans le pannier.
     */
    public function choiceAction()
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

        $this->words = $this->setWordTranslation();
        $inputFormat = $this->formatInput($input);

        $id = $_GET['product'];

        $choice =[];
        //$choice['ids']=[];
        //array_push($choice['ids'], $id);

        $product = $this->modelFiltre->find($id);
        $result = $db->query($product)->fetch();


        $choice=
            [
                'input' => $inputFormat,
                'choice' => $result['name']
            ];

        $session->choice[$id] =$choice;

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre/pannier');

    }

    public function pannierAction()
    {
        $session = new Zend_Session_Namespace('filtre');

        if($session->choice == null){
            $baseUrl = new Zend_View_Helper_BaseUrl();
            $this->getResponse()->setRedirect($baseUrl->baseUrl().'/');
        }

        $this->view->pannier =  $session->choice;
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

        return new Genius_Model_Filtre();
    }

    /**
     * Tableau de translations des mots
     * @param $input
     */
    public function setWordTranslation()
    {
        return
            [
                'conso' => 'Consommable & soft',
                'dimension' => 'Dimension étiquette',
                'dpi' => 'Résolution',
                'gamme' => 'Gamme du matériel',
                'width' => 'Largeur d\'impression',
                'opt' => 'Options',
                'use' => 'Utilisation',
                'marque' => 'Marque',
                'interface' => 'Interface de connection',
                'printhead' => 'avec une tête supplémentaire',
                'software' => 'logiciel',
                'ruban' => 'ruban carbone',
                'na' => 'non-définie',

                'bureau' => 'bureau',
                'si' => 'semi-industriel',
                'i' => 'industriel',
                'indu' => 'industriel',

                'peel' => 'pré-décollage',
                'cut' => 'massicot',
                'rfid' => 'rfid',
                'rewind' => 'pré-imprimer',

                'feuille' => 'impression feuille a feuille',

                'ligne' => 'impression ligne',
                'aiguille' => 'impression aiguille',
                'tt' => 'transfert-thermique',
                'dt' => 'direct-thermique',
                'both' => 'les deux',

                'serie' => 'série',
                'usb' => 'usb',
                'eth' => 'réseau filaire',
                'wifi' => 'wifi',
                'parra' => 'parralléle',

                'm_zebra' => 'ZEBRA',
                'm_datamax' => 'DATAMAX',
                'm_intermec' => 'INTERMEC',
                'm_toshiba' => 'TEC / TOSHIBA',
                'm_honeywell' => 'HONEYWELL',
                'm_hp' => 'HEWLETT PACKARD',
                'm_lexmark' => 'LEXMARK',
                'm_ibm' => 'IBM',
                'm_epson' => 'EPSON',
                'm_oki' => 'OKI',

                '2' => '2"',
                '3' => '3"',
                '4' => '4"',
                '5' => '5"',
                '6' => '6"',
                '8' => '8"',

                'c_132' => '132 colonnes',
                'c_80' => '80 colonnes',

                '200' => '200 dpi',
                '300' => '300 dpi',
                '600' => '600 dpi',
                '1200' => '1200 dpi',

                'a3' => 'format A3',
                'a4' => 'format A4',

                'groupe_petit' => 'petit groupe de travail',
                'groupe_dep' => 'groupe de travail départemental',

                'mono' => 'impression noir & blanc',
                'couleur' => 'impression couleur',

                'rectov' => 'recto / verso',

                'one_face' => 'mode impression une face',
                'dual_face' => 'mode impression recto / verso',
                'magnetique' => 'lecteur carte magnétique',
                'puce' => 'lecteur carte puce',
                'nfc' => 'système NFC',
                'serrure' => 'système vérouillage par clé',
            ];
    }

    /**
     * Format les input selectionné par le client pour que se soit compréhensible a la lecture
     * @param $input
     * @return array|string
     */
    private function formatInput($input)
    {
        if($input == null )
            return 'Aucunes précisions sur les caractèristiques'
                ;
        $items=[];

        foreach ($input as $index => $item)
        {
            if(
                ($item !== '') AND ($item !== null) AND ($item !== 'na')
            )
            {
                $item_ = $item;
                $cle = $this->words[$index] == null ? $index : $this->words[$index] ;
                $valeur = $this->words[$item] == null ? $item : $this->words[$item] ;

                $selected_item=[];
                $listing=[];

                if(is_array($item_) )
                {
                    foreach ($item as $sub_key => $sub_item) {
                        $sub_cle = $this->words[$sub_key] == null ? $sub_key : $this->words[$sub_key] ;
                        $selected_item[] = $sub_cle;
                        $listing[$valeur] = $selected_item;
                    }
                    $items[$cle] = $selected_item;
                }

                if( ! is_array($item_) )  $selected[$cle] = $valeur;

            }
        }
        $array_merged = $selected;

        if( ! empty($items))
            $array_merged = array_merge($selected,$items)
            ;

        return $array_merged;
    }

    /**
     * Delete un materiel selectionné par le client.
     */
    public function deletechoiceAction()
    {
        $id = $_GET['product'];
        $session = new Zend_Session_Namespace('filtre');
        unset($session->choice[$id]);

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/filtre/pannier');
    }

}