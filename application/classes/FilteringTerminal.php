<?php

class Genius_Class_FilteringTerminal
{
    public $result;
    public $inputTerminalCount;
    public $inputTerminal;
    public $session;
    private $post;

    /**
     * Genius_Class_FilteringTerminal constructor.
     *
     * @param $input
     */
    public function __construct($post)
    {
        $this->post = $post;
        $this->inputTerminalCount = 0;
        $this->inputTerminalInit = 8;
        $this->result = [];
    }

    /**
     * @param $session
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return $this
     */
    public function handle()
    {
        $int = array_flip($this->post['com']);
        $this->session->inputTerminal['com'] = $int;

        $this->session->inputTerminal['marque'] = $this->post['marque'];
        $this->session->inputTerminal['format'] = $this->post['format'];
        $this->session->inputTerminal['os'] = $this->post['os'];
        $this->session->inputTerminal['clavier'] = $this->post['clavier'];
        $this->session->inputTerminal['scanner'] = $this->post['scanner'];

        //$arg = count($int) ? 1 : 0;
        //$this->inputTerminalCount = count($this->session->inputTerminal ) - 1 + $arg;
        //var_dump(count($this->session->inputTerminal ));
        //var_dump($this->session->inputTerminal);
        //var_dump(count($arg));
        //die();
        $this->session->inputTerminalCount = $this->inputTerminalCount ;
        $this->session->inputTerminalInit = $this->inputTerminalInit ;

        return $this;
    }


    public function search()
    {
        $this->result = $this->filterDb();

        $priority = $this->priority();

        $i = 0;

        while( $this->result == [] )
        {
            $error = new Zend_Session_Namespace('errormessage');
            $error->setExpirationSeconds( 1);
            $error->msg = 'Il n\'y a aucun résultats à votre recherche , nous éssayons de vous donné les resultats les plus pertinant. ';

            if( $i == 0 ) {
                unset($this->session->inputTerminal['com']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputTerminal[$priority[$i]] = 'na';
                    $this->result = $this->filterDb();
                }
            }

            if( $i == 7 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 7) $this->result=[];


        return  $this;
    }

    public function priority(){

        $priority[] = 'interface';
        $priority[] = 'clavier';
        $priority[] = 'laser';
        $priority[] = 'os';
        $priority[] = 'format';
        $priority[] = 'marque';


        return $priority;
    }

    public function filterDb()
    {
        global $db;
        $model = new Genius_Model_FiltreTerminal();
        $model = $model->select();

        /**
         * Filtre FORMAT materiel
         */
        if($this->session->inputTerminal['format'] == 'gun') $model = $model->where('gun = 1');
        if($this->session->inputTerminal['format'] == 'droit') $model = $model->where('droit = 1');

        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputTerminal['os'] == 'wince') $model = $model->where('wince = 1');
        if($this->session->inputTerminal['os'] == 'winmobile') $model = $model->where('winmobile = 1');
        if($this->session->inputTerminal['os'] == 'android') $model = $model->where('android = 1');

        /**
         * Filtre / marque du materiel
         */
        if($this->post['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->post['marque'] == 'm_motorola') $model = $model->where('m_motorola = 1');
        if($this->post['marque'] == 'm_symbol') $model = $model->where('m_symbol = 1');
        if($this->post['marque'] == 'm_datalogic') $model = $model->where('m_datalogic = 1');
        if($this->post['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->post['marque'] == 'm_lxe') $model = $model->where('m_lxe = 1');
        if($this->post['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');
        if($this->post['marque'] == 'm_psion') $model = $model->where('m_psion = 1');

        /**
         * Filtre CLAVIER materiel
         */
        if($this->session->inputTerminal['clavier'] == 'nume') $model = $model->where('nume = 1');
        if($this->session->inputTerminal['clavier'] == 'alpha') $model = $model->where('alpha_numeric = 1');
        if($this->session->inputTerminal['clavier'] == 'tactil') $model = $model->where('tactil = 1');

        /**
         * Filtre SCANNER materiel
         */
        if($this->session->inputTerminal['scanner'] == '1std') $model = $model->where('1std = 1');
        if($this->session->inputTerminal['scanner'] == '1lg') $model = $model->where('1lg = 1');
        if($this->session->inputTerminal['scanner'] == '1xlg') $model = $model->where('1xlg = 1');
        if($this->session->inputTerminal['scanner'] == '2std') $model = $model->where('2std = 1');
        if($this->session->inputTerminal['scanner'] == '2lg') $model = $model->where('2lg = 1');

        /**
         * Filtre les interface de communication
         */
        if(isset($this->session->inputTerminal['com']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputTerminal['com']['narrow']))  $model = $model->where('narrow = 1') ;
        if(isset($this->session->inputTerminal['com']['gprs']))  $model = $model->where('gprs = 1') ;
        if(isset($this->session->inputTerminal['com']['gsm']))  $model = $model->where('gsm = 1') ;
        if(isset($this->session->inputTerminal['com']['rfid']))  $model = $model->where('rfid = 1') ;
        if(isset($this->session->inputTerminal['com']['grand_froid']))  $model = $model->where('grand_froid = 1') ;
        $model = $model->limit(10);

        return  $db->query($model)->fetchAll();
    }



    public function setResult()
    {
        $this->session->resultTerminal = $this->result;
    }
}