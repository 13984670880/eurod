<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 28/08/2017
 * Time: 16:26
 */

class Admin_DemandesController extends Genius_AbstractController
{
    public function init()
    {

    }

    public function indexAction()
    {

        $demandes = Genius_Model_Demandes::all();
        $this->view->demandes = $demandes;
    }

}