<?php

class Genius_Class_FilteringPrinterThermique
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
        $this->session->inputThermique['interface'] = $int;

        $conso = array_flip($this->post['conso']);
        $this->session->inputThermique['conso'] = $conso;

        $this->session->inputThermique['dimension'] = $this->post['dimension'];
        $this->session->inputThermique['dpi'] = $this->post['dpi'];
        $this->session->inputThermique['gamme'] = $this->post['gamme'];
        $this->session->inputThermique['width'] = $this->post['width'];
        $this->session->inputThermique['opt'] = $this->post['opt'];
        $this->session->inputThermique['use'] = $this->post['use'];
        $this->session->inputThermique['marque'] = $this->post['marque'];

        return $this;
    }
    public function setResult()
    {
        $this->session->resultThermique = $this->result;
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
                unset($this->session->inputThermique['interface']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputThermique[$priority[$i]] = 'na';
                    $this->result = $this->filterDb();
                }
            }

            if( $i == 8 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 8) $this->result=[];


       return  $this;
    }


    public function priority(){

        $priority[] = 'interface';
        $priority[] = 'opt';
        $priority[] = 'use';
        $priority[] = 'marque';
        $priority[] = 'gamme';
        $priority[] = 'width';
        $priority[] = 'dpi';

        return $priority;
    }

    public function filterDb()
    {
        $model = new Genius_Model_Filtre();

        global $db;

        $model = $model ->select();

        if($this->post['dpi'] == 200) $model = $model->where('200dpi = 1');
        if($this->post['dpi'] == 300) $model = $model->where('300dpi = 1');
        if($this->post['dpi'] == 600) $model = $model->where('600dpi = 1');
        if($this->post['dpi'] == 1200) $model = $model->where('1200dpi = 1');

        if($this->post['width'] == 2) $model = $model->where('2p = 1');
        if($this->post['width'] == 3) $model = $model->where('3p = 1');
        if($this->post['width'] == 4) $model = $model->where('4p = 1');
        if($this->post['width'] == 5) $model = $model->where('5p = 1');
        if($this->post['width'] == 6) $model = $model->where('6p = 1');
        if($this->post['width'] == 8) $model = $model->where('8p = 1');

        if($this->post['gamme'] == 'portative') $model = $model->where('portable = 1');
        if($this->post['gamme'] == 'bureau') $model = $model->where('bureau = 1');
        if($this->post['gamme'] == 'si') $model = $model->where('s_indu = 1');
        if($this->post['gamme'] == 'i') $model = $model->where('indu = 1');


        if($this->post['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->post['marque'] == 'm_datamax') $model = $model->where('m_datamax = 1');
        if($this->post['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->post['marque'] == 'm_toshiba') $model = $model->where('m_toshiba = 1');

        if($this->post['marque'] == 'm_honeywell'){
            $model = $model->where('m_honeywell = 1');
            $model = $model->where('m_intermec = 1');
            $model = $model->where('m_datamax = 1');
        }

        if($this->post['use'] == 'tt') $model = $model->where('tt = 1');
        if($this->post['use'] == 'dt') $model = $model->where('dt = 1');
        if($this->post['use'] == 'both') $model = $model->where('dt = 1')->where('tt = 1');

        if($this->post['opt'] == 'cut') $model = $model->where('cut = 1');
        if($this->post['opt'] == 'peel') $model = $model->where('peel = 1');
        if($this->post['opt'] == 'rewind') $model = $model->where('rewind = 1');
        if($this->post['opt'] == 'rfid') $model = $model->where('rfid = 1');

        if(isset($this->session->inputThermique['interface']['serie']))  $model = $model->where('serie = 1') ;
        if(isset($this->session->inputThermique['interface']['usb']))  $model = $model->where('usb = 1') ;
        if(isset($this->session->inputThermique['interface']['eth']))  $model = $model->where('eth = 1') ;
        if(isset($this->session->inputThermique['interface']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputThermique['interface']['parra']))  $model = $model->where('parra = 1') ;
        $model = $model->limit(10);

        //print_r($model->__ToString());
        //die();

        return  $db->query($model)->fetchAll();
    }


}