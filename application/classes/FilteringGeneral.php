<?php

class Genius_Class_FilteringGeneral
{

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
                    var_dump( $this->result == []);
                    var_dump($this->post);
                }
            }

            if( $i == 10 ) $this->result = $i ;
            $i++;
        }

        if($this->result == 10) $this->result=[];

        return  $this;
    }
}