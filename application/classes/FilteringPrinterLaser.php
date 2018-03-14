<?php

/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 11/10/2017
 * Time: 10:33
 */
class Genius_Class_FilteringPrinterLaser
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
        $this->session->inputPrinterLaser['interface'] = $int;

        $this->session->inputPrinterLaser['marque'] = $this->post['marque'];
        $this->session->inputPrinterLaser['format'] = $this->post['format'];
        $this->session->inputPrinterLaser['gamme'] = $this->post['gamme'];
        $this->session->inputPrinterLaser['use'] = $this->post['use'];

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
                unset($this->session->inputPrinterLaser['interface']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputPrinterLaser[$priority[$i]] = 'na';
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
        $priority[] = 'gamme';
        $priority[] = 'format';
        $priority[] = 'marque';

        return $priority;
    }

    public function filterDb()
    {
        $model = new Genius_Model_FiltrePrinterLaser();
        global $db;

        $model = $model ->select();

        if($this->post['marque'] == 'm_hp') $model = $model->where('m_hp = 1');
        if($this->post['marque'] == 'm_lexmark') $model = $model->where('m_lexmark = 1');

        if($this->post['format'] == 'a4') $model = $model->where('a4 = 1');
        if($this->post['format'] == 'a3') $model = $model->where('a3 = 1');

        if($this->post['gamme'] == 'groupe_petit') $model = $model->where('groupe_petit = 1');
        if($this->post['gamme'] == 'groupe_dep') $model = $model->where('groupe_dep = 1');

        if($this->post['use'] == 'mono') $model = $model->where('mono = 1');
        if($this->post['use'] == 'couleur') $model = $model->where('couleur = 1');


        if(isset($this->session->inputPrinterLaser['interface']['rectov']))  $model = $model->where('rectov = 1') ;
        if(isset($this->session->inputPrinterLaser['interface']['multi']))  $model = $model->where('multi = 1') ;

        if(isset($this->session->inputPrinterLaser['interface']['eth']))  $model = $model->where('eth = 1') ;
        if(isset($this->session->inputPrinterLaser['interface']['serie']))  $model = $model->where('serie = 1') ;
        if(isset($this->session->inputPrinterLaser['interface']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputPrinterLaser['interface']['parra']))  $model = $model->where('parra = 1') ;
        if(isset($this->session->inputPrinterLaser['interface']['usb']))  $model = $model->where('usb = 1') ;
        $model = $model->limit(10);

        return  $db->query($model)->fetchAll();

    }




    public function setResult()
    {
        $this->session->resultPrinterLaser = $this->result;
    }
}