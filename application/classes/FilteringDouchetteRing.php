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

                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputDouchetteRing[$priority[$i]] = 'na';
                    $this->result = $this->filterDb();
                }

            if( $i == 4 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 4) $this->result=[];


        return  $this;
    }

    public function priority()
    {
        $priority[] = 'interface';
        $priority[] = 'laser';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
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

        return  $db->query($model)->fetchAll();
    }



    public function setResult()
    {
        $this->session->resultDouchetteRing = $this->result;
    }
}