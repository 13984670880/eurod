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
        $interfaced = array_flip($this->post['interfaced']);
        $interfaceb = array_flip($this->post['interfaceb']);
        $optiond = array_flip($this->post['optiond']);

        $this->session->inputDouchette['interfaced'] = $interfaced;
        $this->session->inputDouchette['interfaceb'] = $interfaceb;
        $this->session->inputDouchette['optiond'] = $optiond;

        $this->session->inputDouchette['marque'] = $this->post['marque'];
        $this->session->inputDouchette['gamme'] = $this->post['gamme'];
        $this->session->inputDouchette['type'] = $this->post['type'];
        $this->session->inputDouchette['laser'] = $this->post['laser'];

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

            if( $i == 0 ) {
                unset($this->session->inputDouchette['optiond']);
                $this->result = $this->filterDb();
            }
            elseif( $i == 1 ) {
                unset($this->session->inputDouchette['interfaceb']);
                $this->result = $this->filterDb();
            }
            elseif( $i == 2 ) {
                unset($this->session->inputDouchette['interfaced']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputDouchette[$priority[$i]] = 'na';
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
        $priority[] = 'interface_socle';
        $priority[] = 'interface_com';
        $priority[] = 'laser';
        $priority[] = 'type';
        $priority[] = 'gamme';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
    {
        global $db;
        $model = new Genius_Model_FiltreDouchette();
        $model = $model->select();

        /**
         * filtre de la marque
         */
        if($this->session->inputDouchette['marque'] == 'm_zebra') $model = $model->where('m_zebra = 1');
        if($this->session->inputDouchette['marque'] == 'm_motorola') $model = $model->where('m_motorola = 1');
        if($this->session->inputDouchette['marque'] == 'm_symbol') $model = $model->where('m_symbol = 1');
        if($this->session->inputDouchette['marque'] == 'm_datalogic') $model = $model->where('m_datalogic = 1');
        if($this->session->inputDouchette['marque'] == 'm_honeywell') $model = $model->where('m_honeywell = 1');
        if($this->session->inputDouchette['marque'] == 'm_opticon') $model = $model->where('m_opticon = 1');


        /**
         * Filtre scanner
         */
        if($this->session->inputDouchette['laser'] == '1std') $model = $model->where('1std = 1');
        if($this->session->inputDouchette['laser'] == '2std') $model = $model->where('2std = 1');


        /**
         * Filtre type materiel
         */
        if($this->session->inputDouchette['type'] == 'filaire') $model = $model->where('filaire = 1');
        if($this->session->inputDouchette['type'] == 'nofilaire') $model = $model->where('nofilaire = 1');

        /**
         * Filtre la gamme
         */
        if($this->session->inputDouchette['gamme'] == 'bureau') $model = $model->where('bureau = 1');
        if($this->session->inputDouchette['gamme'] == 'i') $model = $model->where('indu = 1');

        /**
         * Filtre les interface de communication
         */
        if(isset($this->session->inputDouchette['interfaced']['bluetooh']))  $model = $model->where('bluetooh = 1') ;
        if(isset($this->session->inputDouchette['interfaced']['radio']))  $model = $model->where('radio = 1') ;

        /**
         * filtre des option
         */
        if(isset($this->session->inputDouchette['optiond']['ecran']))  $model = $model->where('ecran = 1') ;
        if(isset($this->session->inputDouchette['optiond']['clavier']))  $model = $model->where('clavier = 1') ;
        if(isset($this->session->inputDouchette['optiond']['batch']))  $model = $model->where('batch = 1') ;

        /**
         * Filtre connectique du socle
         */
        if(isset($this->session->inputDouchette['interfaceb']['usb']))  $model = $model->where('usb = 1') ;
        if(isset($this->session->inputDouchette['interfaceb']['serie']))  $model = $model->where('serie = 1') ;
        if(isset($this->session->inputDouchette['interfaceb']['wedge']))  $model = $model->where('wedge = 1') ;
        $model = $model->limit(10);

        return  $db->query($model)->fetchAll();
    }



    public function setResult()
    {
        $this->session->resultDouchette = $this->result;
    }
}