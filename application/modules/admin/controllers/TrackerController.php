<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 28/08/2017
 * Time: 16:26
 */

class Admin_TrackerController extends Genius_AbstractController
{
    public function init()
    {

    }

    public function indexAction()
    {
        global $db;

        $model = new Genius_Model_Tracker();

        $model = $model->all();

        $result = $db->query($model)->fetchAll();

        $this->view->results = $result;

    }

}