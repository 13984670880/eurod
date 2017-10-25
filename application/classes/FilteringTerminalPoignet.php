<?php

class Genius_Class_FilteringTerminalPoignet
{
    public $result;
    public $inputTerminalPoignet;
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

        $option = array_flip($this->post['option']);
        $this->session->inputTerminalPoignet['option'] = $option;

        $this->session->inputTerminalPoignet['marque'] = $this->post['marque'];
        $this->session->inputTerminalPoignet['format'] = $this->post['format'];
        $this->session->inputTerminalPoignet['os'] = $this->post['os'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltreTerminalPoignet();
        $model = $model->select();


        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputTerminalPoignet['os'] == 'win_embeded_compact') $model = $model->where('win_embeded_compact = 1');
        if($this->session->inputTerminalPoignet['os'] == 'android') $model = $model->where('android = 1');

        /**
         * Filtre / marque du materiel
         */
        if($this->post['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->post['marque'] == 'm_motorola') $model = $model->where('m_motorola = 1');
        if($this->post['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->post['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');

        /**
         * Filtre CLAVIER materiel
         */
        if($this->session->inputTerminalPoignet['format'] == 'clavier') $model = $model->where('clavier = 1');
        if($this->session->inputTerminalPoignet['format'] == 'tactile') $model = $model->where('tactile = 1');
        if($this->session->inputTerminalPoignet['format'] == 'vocal') $model = $model->where('vocal = 1');


        /**
         * OPTION
         */
        if(isset($this->session->inputTerminalPoignet['option']['scanner_1d']))  $model = $model->where('scanner_1d = 1') ;
        if(isset($this->session->inputTerminalPoignet['option']['scanner_2d']))  $model = $model->where('scanner_2d = 1') ;

        $result = $db->query($model)->fetchAll();

        $this->result = $result;

        return $this;
    }

    public function setResult()
    {
        $this->session->resultTerminalPoignet = $this->result;
    }
}