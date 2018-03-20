<?php

class Genius_Class_FilteringTerminalEmbarque
{
    public $result;
    public $inputTerminalEmbarque;
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
        $this->session->inputTerminalEmbarque['interface'] = $int;

        $option = array_flip($this->post['option']);
        $this->session->inputTerminalEmbarque['option'] = $option;

        $this->session->inputTerminalEmbarque['marque'] = $this->post['marque'];
        $this->session->inputTerminalEmbarque['format'] = $this->post['format'];
        $this->session->inputTerminalEmbarque['os'] = $this->post['os'];

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
            $error->msg = 'Tous les critères n’ayant pu être respectés, voici les matériels approchants';

            if( $i == 0 ) {
                unset($this->session->inputTerminalEmbarque['interface']);
                $this->result = $this->filterDb();
            }
            elseif( $i == 2 ) {
                unset($this->session->inputTerminalEmbarque['option']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputTerminalEmbarque[$priority[$i]] = 'na';
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
        $priority[] = 'opt';
        $priority[] = 'os';
        $priority[] = 'format';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
    {
        global $db;
        $model = new Genius_Model_FiltreTerminalEmbarque();
        $model = $model->select();


        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputTerminalEmbarque['os'] == 'wince') $model = $model->where('wince = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'win_embeded_std') $model = $model->where('win_embeded_std = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'win_embeded_compact') $model = $model->where('win_embeded_compact = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'winmobile') $model = $model->where('winmobile = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'win_7') $model = $model->where('win_7 = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'win_10') $model = $model->where('win_10 = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'win_xp_pro') $model = $model->where('win_xp_pro = 1');
        if($this->session->inputTerminalEmbarque['os'] == 'win_xp_embeded') $model = $model->where('win_xp_embeded = 1');

        /**
         * Filtre / marque du materiel
         */
        if($this->post['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->post['marque'] == 'm_motorola') $model = $model->where('m_motorola = 1');
        if($this->post['marque'] == 'm_intermec') $model = $model->where('m_intermec = 1');
        if($this->post['marque'] == 'm_lxe') $model = $model->where('m_lxe = 1');
        if($this->post['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');
        if($this->post['marque'] == 'm_psion') $model = $model->where('m_psion = 1');

        /**
         * Filtre CLAVIER materiel
         */
        if($this->session->inputTerminalEmbarque['format'] == 'tactile') $model = $model->where('tactile = 1');
        if($this->session->inputTerminalEmbarque['format'] == 'clavier') $model = $model->where('clavier = 1');


        /**
         * Filtre les interface de communication
         */
        if(isset($this->session->inputTerminalEmbarque['interface']['usb']))  $model = $model->where('usb = 1') ;
        if(isset($this->session->inputTerminalEmbarque['interface']['eth']))  $model = $model->where('eth = 1') ;
        if(isset($this->session->inputTerminalEmbarque['interface']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputTerminalEmbarque['interface']['serie']))  $model = $model->where('serie = 1') ;
        if(isset($this->session->inputTerminalEmbarque['interface']['narow']))  $model = $model->where('narow = 1') ;

        /**
         * OPTION
         */
        if(isset($this->session->inputTerminalEmbarque['option']['grand_froid']))  $model = $model->where('grand_froid = 1') ;
        if(isset($this->session->inputTerminalEmbarque['option']['clavier_dur']))  $model = $model->where('clavier_dur = 1') ;
        if(isset($this->session->inputTerminalEmbarque['option']['douchette']))  $model = $model->where('douchette = 1') ;

        $model = $model->limit(10);

        return  $db->query($model)->fetchAll();
    }



    public function setResult()
    {
        $this->session->resultTerminalEmbarque = $this->result;
    }
}