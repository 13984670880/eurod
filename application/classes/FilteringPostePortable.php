<?php

class Genius_Class_FilteringPostePortable
{
    public $result;
    public $inputPostePortable;
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
        $this->session->inputPostePortable['option'] = $option;

        $this->session->inputPostePortable['marque'] = $this->post['marque'];
        $this->session->inputPostePortable['use'] = $this->post['use'];
        $this->session->inputPostePortable['format'] = $this->post['format'];
        $this->session->inputPostePortable['os'] = $this->post['os'];

        $this->session->inputPostePortable['core'] = $this->post['core'];
        $this->session->inputPostePortable['ram'] = $this->post['ram'];
        $this->session->inputPostePortable['dd'] = $this->post['dd'];

        $this->session->inputPostePortable['ecran'] = $this->post['ecran'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltrePostePortable();
        $model = $model->selectGenerique();

        /**
         * FILRE PAR UTILISATION
         */
        if($this->session->inputPostePortable['use'] == 'bureau') $model = $model->where('bureau = 1');
        if($this->session->inputPostePortable['use'] == 'pao') $model = $model->where('pao = 1');
        if($this->session->inputPostePortable['use'] == 'cao') $model = $model->where('cao = 1');

        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputPostePortable['os'] == 'win_7_pro') $model = $model->where('win_7_pro = 1');
        if($this->session->inputPostePortable['os'] == 'win_10_pro') $model = $model->where('win_10_pro = 1');
        if($this->session->inputPostePortable['os'] == 'win_cp_pro') $model = $model->where('win_cp_pro = 1');

        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputPostePortable['core'] == 'core_i3') $model = $model->where('core_i3 = 1');
        if($this->session->inputPostePortable['core'] == 'core_i5') $model = $model->where('core_i5 = 1');
        if($this->session->inputPostePortable['core'] == 'core_i7') $model = $model->where('core_i7 = 1');

        /**
         * Filtre MARQUE materiel
         */
        if($this->session->inputPostePortable['marque'] == 'hp') $model = $model->where('hp = 1');
        if($this->session->inputPostePortable['marque'] == 'dell') $model = $model->where('dell = 1');
        if($this->session->inputPostePortable['marque'] == 'lenovo') $model = $model->where('lenovo = 1');



        /**
         * Filtre RAM du materiel
         */
        if($this->post['ram'] == '2gb_ram') $model = $model->where('2gb_ram = 1');
        if($this->post['ram'] == '4gb_ram') $model = $model->where('4gb_ram = 1');
        if($this->post['ram'] == '8gb_ram') $model = $model->where('8gb_ram = 1');

        /**
         * FILTRE DISQUE DUR
         */
        if($this->session->inputPostePortable['dd'] == '160gb') $model = $model->where('160gb = 1');
        if($this->session->inputPostePortable['dd'] == '250gb') $model = $model->where('250gb = 1');
        if($this->session->inputPostePortable['dd'] == '320gb') $model = $model->where('320gb = 1');
        if($this->session->inputPostePortable['dd'] == '500gb') $model = $model->where('500gb = 1');
        if($this->session->inputPostePortable['dd'] == '1000gb') $model = $model->where('1000gb = 1');


        /**
         * Filtre ECRAN materiel
         */
        if($this->session->inputPostePortable['ecran'] == '12p') $model = $model->where('12p = 1');
        if($this->session->inputPostePortable['ecran'] == '13p') $model = $model->where('13p = 1');
        if($this->session->inputPostePortable['ecran'] == '14p') $model = $model->where('14p = 1');
        if($this->session->inputPostePortable['ecran'] == '15p') $model = $model->where('15p = 1');
        if($this->session->inputPostePortable['ecran'] == '15_6p') $model = $model->where('15_6p = 1');
        if($this->session->inputPostePortable['ecran'] == '17p') $model = $model->where('17p = 1');

        /**
         * OPTION
         */
        if(isset($this->session->inputPostePortable['option']['ssd']))  $model = $model->where('ssd = 1') ;
        if(isset($this->session->inputPostePortable['option']['pave_nume']))  $model = $model->where('pave_nume = 1') ;
        $model = $model->limit(10);
        $result = $db->query($model)->fetchAll();

        $this->result = $result;

        return $this;
    }

    public function setResult()
    {
        $this->session->resultPostePortable = $this->result;
    }
}