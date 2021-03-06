<?php

class Genius_Class_FilteringPosteClient
{
    public $result;
    public $inputPosteClient;
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
        $this->session->inputPosteClient['option'] = $option;

        $this->session->inputPosteClient['os'] = $this->post['os'];
        $this->session->inputPosteClient['marque'] = $this->post['marque'];
        $this->session->inputPosteClient['ecran'] = $this->post['ecran'];
        $this->session->inputPosteClient['ram'] = $this->post['ram'];
        $this->session->inputPosteClient['flash'] = $this->post['flash'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $this->result = $this->filterDb();

        $priority = $this->priority();

        $i = 0;

        while( $this->result == [] )
        {
            $error = new Zend_Session_Namespace('errormessage');
            $error->setExpirationSeconds( 1);
            $error->msg = 'Tous les critères n’ayant pu être respectés, voici les matériels <b>approchants</b>';

            if( $i == 0 ) {
                unset($this->session->inputPosteClient['option']);
                $this->result = $this->filterDb();
            }

            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputPosteClient[$priority[$i]] = 'na';
                    $this->result = $this->filterDb();
                }
            }

            if( $i == 6 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 4) $this->result=[];

        return  $this;
    }

    public function setResult()
    {
        $this->session->resultPosteClient = $this->result;
    }

    public function priority(){

        $priority[] = 'opt';
        $priority[] = 'ecran';
        $priority[] = 'ram';
        $priority[] = 'flash';
        $priority[] = 'os';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
    {
        global $db;
        $model = new Genius_Model_FiltrePosteClient();
        $model = $model->select();

        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputPosteClient['os'] == 'win') $model = $model->where('win = 1');
        if($this->session->inputPosteClient['os'] == 'linux') $model = $model->where('linux = 1');
        if($this->session->inputPosteClient['os'] == 'propri') $model = $model->where('propri = 1');
        if($this->session->inputPosteClient['os'] == 'none_os') $model = $model->where('none_os = 1');

        /**
         * Filtre MARQUE materiel
         */
        if($this->session->inputPosteClient['marque'] == 'dell') $model = $model->where('dell = 1');
        if($this->session->inputPosteClient['marque'] == 'hp') $model = $model->where('hp = 1');
        if($this->session->inputPosteClient['marque'] == 'axel') $model = $model->where('axel = 1');

        /**
         * Filtre RAM du materiel
         */
        if($this->post['ram'] == '2gb_ram') $model = $model->where('2gb_ram = 1');
        if($this->post['ram'] == '4gb_ram') $model = $model->where('4gb_ram = 1');
        if($this->post['ram'] == '8gb_ram') $model = $model->where('8gb_ram = 1');
        if($this->post['ram'] == '16gb_ram') $model = $model->where('16gb_ram = 1');
        if($this->post['ram'] == '32gb_ram') $model = $model->where('32gb_ram = 1');

        /**
         * Filtre FLASH materiel
         */
        if($this->session->inputPosteClient['flash'] == '2gb_flash') $model = $model->where('2gb_flash = 1');
        if($this->session->inputPosteClient['flash'] == '4gb_flash') $model = $model->where('4gb_flash = 1');
        if($this->session->inputPosteClient['flash'] == '8gb_flash') $model = $model->where('8gb_flash = 1');
        if($this->session->inputPosteClient['flash'] == '16gb_flash') $model = $model->where('16gb_flash = 1');
        if($this->session->inputPosteClient['flash'] == '32gb_flash') $model = $model->where('32gb_flash = 1');
        if($this->session->inputPosteClient['flash'] == '64gb_flash') $model = $model->where('64gb_flash = 1');
        if($this->session->inputPosteClient['flash'] == '128gb_flash') $model = $model->where('128gb_flash = 1');

        /**
         * Filtre FLASH materiel
         */
        if($this->session->inputPosteClient['ecran'] == '17p') $model = $model->where('17p = 1');
        if($this->session->inputPosteClient['ecran'] == '19p_4') $model = $model->where('19p_4 = 1');
        if($this->session->inputPosteClient['ecran'] == '19p_16') $model = $model->where('19p_16 = 1');
        if($this->session->inputPosteClient['ecran'] == '22p') $model = $model->where('22p = 1');
        if($this->session->inputPosteClient['ecran'] == '24p') $model = $model->where('24p = 1');

        /**
         * OPTION
         */
        if(isset($this->session->inputPosteClient['option']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputPosteClient['option']['dual_display']))  $model = $model->where('dual_display = 1') ;

        $model = $model->limit(10);

        //print_r($model->__ToString());
        //die();

        return  $db->query($model)->fetchAll();
    }
}