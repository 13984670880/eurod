<?php

class Genius_Class_FilteringPostePc
{
    public $result;
    public $inputPostePc;
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
        $this->session->inputPostePc['option'] = $option;

        $this->session->inputPostePc['use'] = $this->post['use'];
        $this->session->inputPostePc['format'] = $this->post['format'];
        $this->session->inputPostePc['os'] = $this->post['os'];

        $this->session->inputPostePc['core'] = $this->post['core'];
        $this->session->inputPostePc['ram'] = $this->post['ram'];
        $this->session->inputPostePc['dd'] = $this->post['dd'];

        $this->session->inputPostePc['ecran'] = $this->post['ecran'];

        return $this;
    }

    /**
     * @return mixed
     */
    public function search()
    {
        global $db;
        $model = new Genius_Model_FiltrePostePc();
        $model = $model->select();

        /**
         * Filtre OS / SYSTEME materiel
         */
        if($this->session->inputPostePc['os'] == 'win_7_pro') $model = $model->where('win_7_pro = 1');
        if($this->session->inputPostePc['os'] == 'win_10_pro') $model = $model->where('win_10_pro = 1');
        if($this->session->inputPostePc['os'] == 'win_cp_pro') $model = $model->where('win_cp_pro = 1');

        /**
         * FILTRE POUR LE FORMAT
         */
        if($this->session->inputPostePc['format'] == 'tour') $model = $model->where('tour = 1');
        if($this->session->inputPostePc['format'] == 'sff') $model = $model->where('sff = 1');
        if($this->session->inputPostePc['format'] == 'usff') $model = $model->where('usff = 1');

        /**
         * FILRE PAR UTILISATION
         */
        if($this->session->inputPostePc['use'] == 'bureau') $model = $model->where('bureau = 1');
        if($this->session->inputPostePc['use'] == 'pao') $model = $model->where('pao = 1');
        if($this->session->inputPostePc['use'] == 'cao') $model = $model->where('cao = 1');

        /**
         * Filtre RAM du materiel
         */
        if($this->post['ram'] == '2gb_ram') $model = $model->where('2gb_ram = 1');
        if($this->post['ram'] == '4gb_ram') $model = $model->where('4gb_ram = 1');
        if($this->post['ram'] == '8gb_ram') $model = $model->where('8gb_ram = 1');

        /**
         * FILTRE DISQUE DUR
         */
        if($this->session->inputPostePc['dd'] == '160gb') $model = $model->where('160gb = 1');
        if($this->session->inputPostePc['dd'] == '250gb') $model = $model->where('250gb = 1');
        if($this->session->inputPostePc['dd'] == '320gb') $model = $model->where('320gb = 1');
        if($this->session->inputPostePc['dd'] == '500gb') $model = $model->where('500gb = 1');
        if($this->session->inputPostePc['dd'] == '1000gb') $model = $model->where('1000gb = 1');


        /**
         * Filtre FLASH materiel
         */
        if($this->session->inputPostePc['ecran'] == '17p') $model = $model->where('17p = 1');
        if($this->session->inputPostePc['ecran'] == '19p_4') $model = $model->where('19p_4 = 1');
        if($this->session->inputPostePc['ecran'] == '19p_16') $model = $model->where('19p_16 = 1');
        if($this->session->inputPostePc['ecran'] == '22p') $model = $model->where('22p = 1');
        if($this->session->inputPostePc['ecran'] == '24p') $model = $model->where('24p = 1');

        /**
         * OPTION
         */
        if(isset($this->session->inputPostePc['option']['ssd']))  $model = $model->where('ssd = 1') ;

        $result = $db->query($model)->fetchAll();

        $this->result = $result;

        return $this;
    }

    public function setResult()
    {
        $this->session->resultPostePc = $this->result;
    }
}