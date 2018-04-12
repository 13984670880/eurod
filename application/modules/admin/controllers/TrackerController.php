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
        $search = isset($_GET['search']) ? $_GET['search'] : false;

        global $db;

        $model = new Genius_Model_Tracker();

        $results = $model->filter($search);

        $users = [];
        $daily = [];

        foreach ($results as $index => $res) {
            $dt = new DateTime($res['created_at']);
            $daily[$dt->format('d-m-Y')][$res['ip']][]=$res;
            $users[$res['ip']][]=$res;
        }

        $this->view->users = $users;
        $this->view->daily = $daily;

        $this->view->results = $results;
    }

    public function ipAction()
    {

        $ip = isset($_GET['ip']) ? $_GET['ip'] : false; ;

        $this->view->ip = $ip == false ? 'inconnue' : $ip;

        $this->view->activities = $ip == false ? [] : Genius_Model_Tracker::find($ip) ;

    }

}