<?php

class Genius_Class_FilteringTerminalPda
{
    public $result;
    public $inputTerminalPdaCount;
    public $inputTerminalPda;
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
        $this->session->inputTerminalPda['com'] = $int;

        $option = array_flip($this->post['option']);
        $this->session->inputTerminalPda['option'] = $option;

        $this->session->inputTerminalPda['marque'] = $this->post['marque'];
        $this->session->inputTerminalPda['scanner'] = $this->post['scanner'];
        $this->session->inputTerminalPda['clavier'] = $this->post['clavier'];
        $this->session->inputTerminalPda['os'] = $this->post['os'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltreTerminalPda();
        $model = $model->select();


        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputTerminalPda['os'] == 'ce') $model = $model->where('wince = 1');
        if($this->session->inputTerminalPda['os'] == 'mo') $model = $model->where('winmobile = 1');
        if($this->session->inputTerminalPda['os'] == 'android') $model = $model->where('android = 1');
        if($this->session->inputTerminalPda['os'] == 'os_propri') $model = $model->where('os_propri = 1');

        /**
         * Filtre / marque du materiel
         */
        if($this->post['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->post['marque'] == 'm_motorola') $model = $model->where('m_motorola = 1');
        if($this->post['marque'] == 'm_datalogic') $model = $model->where('m_datalogic = 1');
        if($this->post['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->post['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');
        if($this->post['marque'] == 'm_opticon') $model = $model->where('m_opticon = 1');

        /**
         * Filtre CLAVIER materiel
         */
        if($this->session->inputTerminalPda['clavier'] == 'nume') $model = $model->where('nume = 1');
        if($this->session->inputTerminalPda['clavier'] == 'tactile') $model = $model->where('tactile = 1');
        if($this->session->inputTerminalPda['clavier'] == 'alpha') $model = $model->where('alpha_numeric = 1');

        /**
         * Filtre SCANNER materiel
         */
        if($this->session->inputTerminalPda['scanner'] == '1std') $model = $model->where('1std = 1');
        if($this->session->inputTerminalPda['scanner'] == '2std') $model = $model->where('2std = 1');


        /**
         * Filtre les interface de communication
         */
        if(isset($this->session->inputTerminalPda['com']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputTerminalPda['com']['bluetooh']))  $model = $model->where('bluetooh = 1') ;

        /**
         * OPTION
         */
        if(isset($this->session->inputTerminalPda['option']['gf']))  $model = $model->where('grand_froid = 1') ;
        if(isset($this->session->inputTerminalPda['option']['3g']))  $model = $model->where('3g = 1') ;
        if(isset($this->session->inputTerminalPda['option']['4g']))  $model = $model->where('4g = 1') ;
        if(isset($this->session->inputTerminalPda['option']['picture']))  $model = $model->where('picture = 1') ;
        if(isset($this->session->inputTerminalPda['option']['gps']))  $model = $model->where('gps = 1') ;
        if(isset($this->session->inputTerminalPda['option']['gsl']))  $model = $model->where('gsl = 1') ;
        $model = $model->limit(10);
        $result = $db->query($model)->fetchAll();

        $this->result = $result;

        return $this;
    }

    public function setResult()
    {
        $this->session->resultTerminalPda = $this->result;
    }
}