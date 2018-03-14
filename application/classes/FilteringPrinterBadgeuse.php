<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 11/10/2017
 * Time: 10:33
 */
class Genius_Class_FilteringPrinterBadgeuse
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
        $options = array_flip($this->post['optd']);
        $conso = array_flip($this->post['conso']);
        $this->session->inputEtiquetteBadgeuse['conso'] = $conso;
        $this->session->inputEtiquetteBadgeuse['interface'] = $int;
        $this->session->inputEtiquetteBadgeuse['opt'] = $options;
        $this->session->inputEtiquetteBadgeuse['use'] = $this->post['use'];
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
                unset($this->session->inputEtiquetteBadgeuse['interface']);
                $this->result = $this->filterDb();
            }
            elseif( $i == 1 ) {
                unset($this->session->inputEtiquetteBadgeuse['opt']);
                $this->result = $this->filterDb();
            }
            else {
                if (isset($priority[$i])) {
                    $this->post[$priority[$i]] = 'na' ;
                    $this->session->inputEtiquetteBadgeuse[$priority[$i]] = 'na';
                    $this->result = $this->filterDb();
                }
            }

            if( $i == 4 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 4) $this->result=[];

        return  $this;
    }


    public function priority(){

        $priority[] = 'interface';
        $priority[] = 'opt';
        $priority[] = 'use';


        return $priority;
    }

    public function filterDb()
    {
        $model = new Genius_Model_FiltreEtiquetteBadgeuse();
        global $db;
        $model = $model ->select();
        if($this->post['use'] == 'one_face') $model = $model->where('one_face = 1');
        if($this->post['use'] == 'dual_face') $model = $model->where('dual_face = 1');
        if(isset($this->session->inputEtiquetteBadgeuse['interface']['usb']))  $model = $model->where('usb = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['interface']['eth']))  $model = $model->where('eth = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['interface']['wifi']))  $model = $model->where('wifi = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['opt']['magnetique']))  $model = $model->where('magnetique = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['opt']['puce']))  $model = $model->where('puce = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['opt']['nfc']))  $model = $model->where('nfc = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['opt']['rfid']))  $model = $model->where('rfid = 1') ;
        if(isset($this->session->inputEtiquetteBadgeuse['opt']['serrure']))  $model = $model->where('serrure = 1') ;
        $model = $model->limit(10);

        //print_r($model->__ToString());
        //die();

        return  $db->query($model)->fetchAll();
    }



    public function setResult()
    {
        $this->session->resultEtiquetteBadgeuse = $this->result;
    }

}