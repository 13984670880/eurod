<?php

/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 11/10/2017
 * Time: 10:33
 */
class Genius_Class_FilteringPrinterCouleur
{
    public $session;
    public $result;
    private $post;

    /**
     * FilteringArticle constructor.
     */
    public function __construct($post)
    {
        $this->post = $post;
        $this->result = [];
    }

    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    public function handle()
    {
        $int = array_flip($this->post['interfacep']);
        $this->session->inputEtiquetteCouleur['interface'] = $int;

        $this->session->inputEtiquetteCouleur['dpi'] = $this->post['dpi'];
        $this->session->inputEtiquetteCouleur['width'] = $this->post['width'];
        $this->session->inputEtiquetteCouleur['opt'] = $this->post['opt'];
        $this->session->inputEtiquetteCouleur['marque'] = $this->post['marque'];

        return $this;
    }

    public function search()
    {
        $model = new Genius_Model_FiltreEtiquetteCouleur();
        global $db;

        $model = $model ->select();



        if($this->post['dpi'] == 300) $model = $model->where('300dpi = 1');
        if($this->post['dpi'] == 600) $model = $model->where('600dpi = 1');
        if($this->post['dpi'] == 1200) $model = $model->where('1200dpi = 1');

        if($this->post['width'] == 4) $model = $model->where('4p = 1');
        if($this->post['width'] == 6) $model = $model->where('6p = 1');
        if($this->post['width'] == 8) $model = $model->where('8p = 1');

        //if($this->post['marque'] == 'm_epson') $model = $model->where('m_epson = 1');
        //if($this->post['marque'] == 'm_citizen') $model = $model->where('m_citizen = 1');

        if(isset($this->session->inputEtiquetteCouleur['interface']['usb']))  $model = $model->where('usb = 1') ;
        if(isset($this->session->inputEtiquetteCouleur['interface']['eth']))  $model = $model->where('eth = 1') ;
        if(isset($this->session->inputEtiquetteCouleur['interface']['parra']))  $model = $model->where('parra = 1') ;

        //var_dump($db->query($model));

        $this->result = $db->query($model)->fetchAll();
        //var_dump($db->query($model)->fetchAll());
        return  $this;
    }

    public function setResult()
    {
        $this->session->resultEtiquetteCouleur = $this->result;
    }
}