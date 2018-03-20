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
                unset($this->session->inputTerminalPoignet['option']);
                $this->result = $this->filterDb();
            }

            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputTerminalPoignet[$priority[$i]] = 'na';
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

        $priority[] = 'option';
        $priority[] = 'os';
        $priority[] = 'format';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
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
        $model = $model->limit(10);

        return  $db->query($model)->fetchAll();
    }




    public function setResult()
    {
        $this->session->resultTerminalPoignet = $this->result;
    }
}