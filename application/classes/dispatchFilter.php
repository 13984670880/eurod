<?php

/**
 * Permet de savoir ce que l'utilisateur veut chercher : douchette / pda / imprimante couleur etc...
 * @property array result
 */
class Genius_Class_dispatchFilter
{

    /**
     * Genius_Class_dispatchFilter constructor.
     */
    public function __construct($session)
    {
        $this->session = $session;
        $this->setDefaultFiltre();
    }

    /**
     * Recherche du resultat article en fonction de la section
     */
    public function result()
    {
        if($this->session->search == 'search_douchette') $this->douchetteResult();
        if($this->session->search == 'search_douchette_ring') $this->ringResult();

        if($this->session->search == 'search_poste_client') $this->clientResult();
        if($this->session->search == 'search_poste_pc') $this->pcResult();
        if($this->session->search == 'search_poste_portable') $this->portableResult();

        if($this->session->search == 'search_terminal') $this->terminalResult();
        if($this->session->search == 'search_terminal_pda') $this->terminalPdaResult();
        if($this->session->search == 'search_terminal_embarque') $this->terminalEmbarqueResult();
        if($this->session->search == 'search_terminal_poignet') $this->terminalPoignetResult();

        if($this->session->search == 'search_thermique') $this->thermiqueResult();
        if($this->session->search == 'search_etiquette_couleur') $this->etiquetteCouleurResult();
        if($this->session->search == 'search_etiquette_portable') $this->etiquettePortableResult();
        if($this->session->search == 'search_etiquette_badgeuse') $this->etiquetteBadgeuseResult();
        if($this->session->search == 'search_printer_laser') $this->printerLaserReult();
        if($this->session->search == 'search_printer_matricielle') $this->printerMatricielleReult();
    }

    /**
     * Nous donne les input renseigner par l'utilisateur
     * @return mixed
     */
    public function getInput()
    {
        //Imprimante
        if ($this->session->search == 'search_thermique') return $this->session->inputThermique;
        if ($this->session->search == 'search_badgeuse') return $this->session->inputPrinter;
        if ($this->session->search == 'search_etiquette_couleur') return $this->session->inputEtiquetteCouleur;
        if ($this->session->search == 'search_etiquette_portable') return $this->session->inputEtiquettePortable;
        if ($this->session->search == 'search_etiquette_badgeuse') return $this->session->inputEtiquetteBadgeuse;
        if ($this->session->search == 'search_printer_laser') return $this->session->inputPrinterLaser;
        if ($this->session->search == 'search_printer_matricielle') return $this->session->inputPrinterMatricielle;

        //Terminal
        if ($this->session->search == 'search_terminal') return $this->session->inputTerminal;
        if ($this->session->search == 'search_terminal_pda') return $this->session->inputTerminalPda;
        if ($this->session->search == 'search_terminal_embarque') return $this->session->inputTerminalEmbarque;
        if ($this->session->search == 'search_terminal_poignet') return $this->session->inputTerminalPoignet;

        //Douchette
        if ($this->session->search == 'search_douchette') return $this->session->inputDouchette;
        if ($this->session->search == 'search_douchette_ring') return $this->session->inputDouchetteRing;
        if ($this->session->search == 'search_scanner_fixe') return $this->session->inputScannerFixe;

        //poste de travail
        if ($this->session->search == 'search_poste_pc') return $this->session->inputPostePc;
        if ($this->session->search == 'search_poste_portable') return $this->session->inputPostePortable;
        if ($this->session->search == 'search_poste_client') return $this->session->inputPosteClient;

    }

    /**
     * Nous retourne le resultat
     * @return mixed
     */
    public function getResult()
    {

        //IMPRIMANTE - ETIQUETTE
        if ($this->session->search == 'search_thermique')               return $this->session->resultThermique;
        if ($this->session->search == 'search_etiquette_badgeuse')      return $this->session->resultEtiquetteBadgeuse;
        if ($this->session->search == 'search_etiquette_couleur')       return $this->session->resultEtiquetteCouleur;
        if ($this->session->search == 'search_etiquette_portable')      return $this->session->resultEtiquettePortable;

        //IMPRIMANTE - LASER
        if ($this->session->search == 'search_printer_laser')           return $this->session->resultPrinterLaser;

        //IMPRIMANTE - MATRICIELLE
        if ($this->session->search == 'search_printer_matricielle')     return $this->session->resultPrinterMatricielle;

        // TERMINAL
        if ($this->session->search == 'search_terminal')                return $this->session->resultTerminal;
        if ($this->session->search == 'search_terminal_pda')            return $this->session->resultTerminalPda;
        if ($this->session->search == 'search_terminal_embarque')       return $this->session->resultTerminalEmbarque;
        if ($this->session->search == 'search_terminal_poignet')        return $this->session->resultTerminalPoignet;

        // DOUCHETTE
        if ($this->session->search == 'search_douchette')               return $this->session->resultDouchette;
        if ($this->session->search == 'search_douchette_ring')          return $this->session->resultDouchetteRing;
        if ($this->session->search == 'search_douchette_fixe')          return $this->session->resultDouchetteFixe;

        // POSTE DE TRAVAIL
        if ($this->session->search == 'search_poste_client')            return $this->session->resultPosteClient;
        if ($this->session->search == 'search_poste_pc')                return $this->session->resultPostePc;
        if ($this->session->search == 'search_poste_portable')          return $this->session->resultPostePortable;
    }

    /**
     * Recherche resultat filtrer pour les imprimante
     */
    private function thermiqueResult()
    {

        if($this->session->resultThermique === []) return $this->session->resultThermique;

        if($this->session->resultThermique === null) {
            global $db;
            $result = new Genius_Model_Filtre();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultThermique = $result;
        }

    }

    /**
     *  Recherche resultat filtrer pour les douchette
     */
    private function douchetteResult()
    {
        if($this->session->resultDouchette === []) return $this->session->resultDouchette;

        if($this->session->resultDouchette === null) {
            global $db;
            $result = new Genius_Model_FiltreDouchette();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultDouchette = $result;
        }
    }

    /**
     *  Recherche resultat filtrer pour les Terminaux
     * @return string
     */
    private function terminalResult()
    {
        if($this->session->resultTerminal === []) return $this->session->resultTerminal;

        if($this->session->resultTerminal === null) {
            global $db;
            $result = new Genius_Model_FiltreTerminal();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultTerminal = $result;
        }
    }

    /**
     * mise par defaut la section sur Imprimante
     */
    private function setDefaultFiltre()
    {
        isset($this->session->search)
            ?
            $this->session->search
            :
            $this->session->search = 'search_thermique';
    }

    /**
     * @return mixed
     */
    private function etiquetteCouleurResult()
    {

        if( $this->session->resultEtiquetteCouleur === [] ) return $this->session->resultEtiquetteCouleur;

        if($this->session->resultEtiquetteCouleur === null) {
            global $db;
            $result = new Genius_Model_FiltreEtiquetteCouleur();
            $result = $result->selectGenerique();

            $result = $db->query($result)->fetchAll();
            $this->session->resultEtiquetteCouleur = $result;
        }
    }

    /**
     * @return mixed
     */
    private function etiquettePortableResult()
    {
        //var_dump($this->session->resultEtiquetteCouleur);
        if($this->session->resultEtiquettePortable === []) return $this->session->resultEtiquettePortable;

        if($this->session->resultEtiquettePortable === null) {
            global $db;
            $result = new Genius_Model_FiltreEtiquettePortable();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultEtiquettePortable = $result;
        }
    }

    /**
     * @return mixed
     */
    private function etiquetteBadgeuseResult()
    {
        //var_dump($this->session->resultEtiquetteCouleur);
        if($this->session->resultEtiquetteBadgeuse === []) return $this->session->resultEtiquetteBadgeuse;

        if($this->session->resultEtiquetteBadgeuse === null) {
            global $db;
            $result = new Genius_Model_FiltreEtiquetteBadgeuse();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultEtiquetteBadgeuse = $result;
        }
    }

    /**
     * @return mixed
     */
    private function printerLaserReult()
    {
        //var_dump($this->session->resultEtiquetteCouleur);
        if($this->session->resultPrinterLaser === []) return $this->session->resultPrinterLaser;

        if($this->session->resultPrinterLaser === null) {
            global $db;
            $result = new Genius_Model_FiltrePrinterLaser();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultPrinterLaser = $result;
        }
    }

    /**
     * @return mixed
     */
    private function printerMatricielleReult()
    {
        //var_dump($this->session->resultEtiquetteCouleur);
        if($this->session->resultPrinterMatricielle === []) return $this->session->resultPrinterMatricielle;

        if($this->session->resultPrinterMatricielle === null) {
            global $db;
            $result = new Genius_Model_FiltrePrinterMatricielle();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultPrinterMatricielle = $result;
        }
    }

    /**
     * @return mixed
     */
    private function terminalPdaResult()
    {
        if($this->session->resultTerminalPda === []) return $this->session->resultTerminalPda;

        if($this->session->resultTerminalPda === null) {
            global $db;
            $result = new Genius_Model_FiltreTerminalPda();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultTerminalPda = $result;
        }
    }

    /**
     * @return mixed
     */
    private function terminalEmbarqueResult()
    {
        if($this->session->resultTerminalEmbarque === []) return $this->session->resultTerminalEmbarque;

        if($this->session->resultTerminalEmbarque === null) {
            global $db;
            $result = new Genius_Model_FiltreTerminalEmbarque();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultTerminalEmbarque = $result;
        }
    }

    /**
     * @return mixed
     */
    private function terminalPoignetResult()
    {
        if($this->session->resultTerminalPoignet === []) return $this->session->resultTerminalPoignet;

        if($this->session->resultTerminalPoignet === null) {
            global $db;
            $result = new Genius_Model_FiltreTerminalPoignet();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultTerminalPoignet = $result;
        }
    }

    /**
     * @return mixed
     */
    private function clientResult()
    {
        if($this->session->resultPosteClient === []) return $this->session->resultPosteClient;

        if($this->session->resultPosteClient === null) {
            global $db;
            $result = new Genius_Model_FiltrePosteClient();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultPosteClient = $result;
        }
    }

    /**
     * @return mixed
     */
    private function pcResult()
    {
        if($this->session->resultPostePc === []) return $this->session->resultPostePc;

        if($this->session->resultPostePc === null) {
            global $db;
            $result = new Genius_Model_FiltrePostePc();
            $result = $result->selectGenerique();
            $result = $db->query($result)->fetchAll();
            $this->session->resultPostePc = $result;
        }
    }

    /**
     * @return mixed
     */
    private function portableResult()
    {
          if($this->session->resultPostePortable === [])
              return $this->session->resultPostePortable
                  ;

        if($this->session->resultPostePortable === null) {
            global $db;
            $result = new Genius_Model_FiltrePostePortable();
            $result = $result->selectGenerique();
            $result = $db->query($result)->fetchAll();
            $this->session->resultPostePortable = $result;
        }
    }

    /**
     * @return mixed
     */
    private function ringResult()
    {
        if($this->session->resultDouchetteRing === []) return $this->session->resultDouchetteRing;

        if($this->session->resultDouchetteRing === null) {
            global $db;
            $result = new Genius_Model_FiltreDouchetteRing();
            $result = $result->select();
            $result = $db->query($result)->fetchAll();
            $this->session->resultDouchetteRing = $result;
        }
    }
}