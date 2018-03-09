<?php


/**
 * Class Genius_Class_FilteringDouchette
 */
class Genius_Class_FilteringDouchetteRing
{
    public $result;
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

        $this->session->inputDouchetteRing['marque'] = $this->post['marque'];
        $this->session->inputDouchetteRing['laser'] = $this->post['laser'];
        $this->session->inputDouchetteRing['interface'] = $this->post['interface'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltreDouchetteRing();
        $model = $model->select();

        /**
         * filtre de la marque
         */
        if($this->session->inputDouchetteRing['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->session->inputDouchetteRing['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->session->inputDouchetteRing['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');

        /**
         * Filtre scanner
         */
        if($this->session->inputDouchetteRing['laser'] == '1d') $model = $model->where('1d = 1');
        if($this->session->inputDouchetteRing['laser'] == '2d') $model = $model->where('2d = 1');

        /**
         * Filtre type materiel
         */
        if($this->session->inputDouchetteRing['interface'] == 'filaire') $model = $model->where('filaire = 1');
        if($this->session->inputDouchetteRing['interface'] == 'bluetooh') $model = $model->where('bluetooh = 1');
        $model = $model->limit(10);
        $this->result = $db->query($model)->fetchAll();
        
        return $this;
    }

    public function setResult()
    {
        $this->session->resultDouchetteRing = $this->result;
    }
}