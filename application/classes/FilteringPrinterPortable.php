<?php

/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 11/10/2017
 * Time: 10:33
 */
class Genius_Class_FilteringPrinterPortable
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
        $options = array_flip($this->post['optp']);

        $conso = array_flip($this->post['conso']);
        $this->session->inputEtiquettePortable['conso'] = $conso;

        $this->session->inputEtiquettePortable['dimension'] = $this->post['dimension'];

        $this->session->inputEtiquettePortable['marque'] = $this->post['marque'];
        $this->session->inputEtiquettePortable['use'] = $this->post['use'];
        $this->session->inputEtiquettePortable['width'] = $this->post['width'];
        $this->session->inputEtiquettePortable['opt'] = $options;
        $this->session->inputEtiquettePortable['interface'] = $int;


        return $this;
    }



    public function setResult()
    {
        $this->session->resultEtiquettePortable = $this->result;
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
            $error->msg = 'Tous les critères n’ayant pu être respectés, voici les matériels approchants';

            if( $i == 0 ) {
                unset($this->session->inputEtiquettePortable['interface']);
                $this->result = $this->filterDb();
            }
            elseif( $i == 1 ) {
                unset($this->session->inputEtiquettePortable['opt']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputEtiquettePortable[$priority[$i]] = 'na';
                    $this->result = $this->filterDb();
                }
            }

            if( $i == 6 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 6) $this->result=[];


        return  $this;
    }


    public function priority(){

        $priority[] = 'opt';
        $priority[] = 'use';
        $priority[] = 'interface';
        $priority[] = 'width';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
    {
        $model = new Genius_Model_FiltreEtiquettePortable();
        global $db;

        $model = $model ->select();

        if($this->post['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->post['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->post['marque'] == 'm_datamax') $model = $model->where('m_datamax = 1');
        if($this->post['marque'] == 'm_toshiba') $model = $model->where('m_toshiba = 1');
        if($this->post['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');


        if($this->post['width'] == 2) $model = $model->where('2p = 1');
        if($this->post['width'] == 3) $model = $model->where('3p = 1');
        if($this->post['width'] == 4) $model = $model->where('4p = 1');

        if($this->post['use'] == 'ticket') $model = $model->where('ticket = 1');
        if($this->post['use'] == 'etiquette') $model = $model->where('etiquette = 1');

        if(isset($this->session->inputEtiquettePortable['opt']['linerless']))  $model = $model->where('linerless = 1') ;
        if(isset($this->session->inputEtiquettePortable['opt']['nfc']))  $model = $model->where('nfc = 1') ;
        if(isset($this->session->inputEtiquettePortable['opt']['magnetique']))  $model = $model->where('magnetique = 1') ;
        if(isset($this->session->inputEtiquettePortable['opt']['extbatterie']))  $model = $model->where('extbatterie = 1') ;

        if(isset($this->session->inputEtiquettePortable['interface']['usb']))  $model = $model->where('usb = 1') ;
        if(isset($this->session->inputEtiquettePortable['interface']['bluetooh']))  $model = $model->where('bluetooh = 1') ;
        if(isset($this->session->inputEtiquettePortable['interface']['wifi']))  $model = $model->where('wifi = 1') ;
        $model = $model->limit(10);

        return  $db->query($model)->fetchAll();





    }


}