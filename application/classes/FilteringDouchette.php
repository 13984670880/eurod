<?php


/**
 * Class Genius_Class_FilteringDouchette
 */
class Genius_Class_FilteringDouchette
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
        $int = array_flip($this->post['interface']);
        $this->session->inputDouchette['interface'] = $int;

        $this->session->inputDouchette['type'] = $this->post['type'];
        $this->session->inputDouchette['laser'] = $this->post['laser'];
        $this->session->inputDouchette['gamme'] = $this->post['gamme'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltreDouchette();
        $model = $model->select();

        /**
         * Filtre scanner
         */
        if($this->session->inputDouchette['laser'] == '1std') $model = $model->where('1std = 1');
        if($this->session->inputDouchette['laser'] == '1ext') $model = $model->where('1ext = 1');
        if($this->session->inputDouchette['laser'] == '1lg') $model = $model->where('1lg = 1');
        if($this->session->inputDouchette['laser'] == '2std') $model = $model->where('2std = 1');
        if($this->session->inputDouchette['laser'] == '2lg') $model = $model->where('2lg = 1');

        /**
         * Filtre type materiel
         */
        if($this->session->inputDouchette['type'] == 'filaire') $model = $model->where('filaire = 1');
        if($this->session->inputDouchette['type'] == 'nofilaire') $model = $model->where('nofilaire = 1');
        if($this->session->inputDouchette['type'] == 'fixe') $model = $model->where('fixe = 1');
        if($this->session->inputDouchette['type'] == 'divers') $model = $model->where('divers = 1');

        /**
         * Filtre la gamme
         */
        if($this->session->inputDouchette['gamme'] == 'si') $model = $model->where('s_indu = 1');
        if($this->session->inputDouchette['gamme'] == 'i') $model = $model->where('indu = 1');

        /**
         * Filtre les interface de communication
         */
        if(isset($this->session->inputDouchette['interface']['bluetooh']))  $model = $model->where('bluetooh = 1') ;
        if(isset($this->session->inputDouchette['interface']['radio']))  $model = $model->where('radio = 1') ;

        $this->result = $db->query($model)->fetchAll();
        
        return $this;
    }

    public function setResult()
    {
        $this->session->resultDouchette = $this->result;
    }
}