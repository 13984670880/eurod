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

        $this->session->inputTerminal['type'] = $this->post['type'];
        $this->session->inputTerminal['gamme'] = $this->post['gamme'];
        $this->session->inputTerminal['format'] = $this->post['format'];
        $this->session->inputTerminal['os'] = $this->post['os'];
        $this->session->inputTerminal['lcd'] = $this->post['lcd'];
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

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltreTerminal();
        $model = $model->select();

        /**
         * Filtre TYPE materiel
         */
        if($this->session->inputTerminal['type'] == 'tm') $model = $model->where('termi_m = 1');
        if($this->session->inputTerminal['type'] == 'em') $model = $model->where('termi_e = 1');
        if($this->session->inputTerminal['type'] == 'ml') $model = $model->where('termi_ml = 1');
        if($this->session->inputTerminal['type'] == 'pda') $model = $model->where('pda = 1');
        if($this->session->inputTerminal['type'] == 'divers') $model = $model->where('divers = 1');

        /**
         * Filtre GAMME materiel
         */
        if($this->session->inputTerminal['gamme'] == 'si') $model = $model->where('s_indu = 1');
        if($this->session->inputTerminal['gamme'] == 'i') $model = $model->where('indu = 1');
        if($this->session->inputTerminal['gamme'] == 'gf') $model = $model->where('grand_froid = 1');

        /**
         * Filtre FORMAT materiel
         */
        if($this->session->inputTerminal['format'] == 'gun') $model = $model->where('gun = 1');
        if($this->session->inputTerminal['format'] == 'rotatif') $model = $model->where('rotatif = 1');
        if($this->session->inputTerminal['format'] == 'droit') $model = $model->where('droit = 1');

        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputTerminal['os'] == 'ce') $model = $model->where('wince = 1');
        if($this->session->inputTerminal['os'] == 'mo') $model = $model->where('winmobile = 1');
        if($this->session->inputTerminal['os'] == 'android') $model = $model->where('android = 1');

        /**
         * Filtre LCD materiel
         */
        if($this->session->inputTerminal['lcd'] == '3p') $model = $model->where('3p = 1');
        if($this->session->inputTerminal['lcd'] == '4p') $model = $model->where('4p = 1');
        if($this->session->inputTerminal['lcd'] == '5p') $model = $model->where('5p = 1');

        /**
         * Filtre CLAVIER materiel
         */
        if($this->session->inputTerminal['clavier'] == 'nume') $model = $model->where('nume = 1');
        if($this->session->inputTerminal['clavier'] == 'alpha') $model = $model->where('alpha_numeric = 1');
        if($this->session->inputTerminal['clavier'] == 'hybrid') $model = $model->where('hybrid = 1');

        /**
         * Filtre SCANNER materiel
         */
        if($this->session->inputTerminal['scanner'] == '1std') $model = $model->where('1std = 1');
        if($this->session->inputTerminal['scanner'] == '1ext') $model = $model->where('1ext = 1');
        if($this->session->inputTerminal['scanner'] == '1lg') $model = $model->where('1lg = 1');
        if($this->session->inputTerminal['scanner'] == '2std') $model = $model->where('2std = 1');
        if($this->session->inputTerminal['scanner'] == '2lg') $model = $model->where('2lg = 1');

        /**
         * Filtre les interface de communication
         */
        if(isset($this->session->inputTerminal['com']['bluetooh']))  $model = $model->where('bluetooh = 1') ;
        if(isset($this->session->inputTerminal['com']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputTerminal['com']['narrow']))  $model = $model->where('narrow = 1') ;

        $result = $db->query($model)->fetchAll();

        $this->result = $result;

        return $this;
    }

    public function setResult()
    {
        $this->session->resultTerminal = $this->result;
    }
}