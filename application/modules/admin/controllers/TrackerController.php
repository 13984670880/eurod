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

    public function statsAction()
    {
        global $db;

        $model = new Genius_Model_Tracker();

        $sql = $db->select()
            ->from('ec_searching',['count' => 'Count(ec_searching.`value`)','ec_searching.spec'])
            ->where('ec_searching.url IS NOT NULL')
            ->where('ec_searching.module = \'page_product\'')
            ->group('ec_searching.value')
            ->order('count DESC' )
            ->limit(30, 0);
        ;
        $this->view->produits = $db->query($sql)->fetchAll();

        $sql = $db->select()
            ->from('ec_searching',['count' => 'Count(ec_searching.`spec`)','ec_searching.value','ec_searching.spec'])
            ->where('ec_searching.url IS NOT NULL')
            ->where('ec_searching.module LIKE \'general\'')
            ->group('ec_searching.spec')
            ->order('count DESC' )
            ->limit(10, 0);
        ;
        //print_r($sql->__ToString());
        //die();
        $this->view->categories = $db->query($sql)->fetchAll();


        $sql = $db->select()
            ->from('ec_searching',['count' => 'Count(ec_searching.`spec`)','ec_searching.value','ec_searching.spec','ec_searching.url'])
            ->where('ec_searching.url IS NOT NULL')
            ->where('ec_searching.module NOT LIKE \'page_product\'')
            ->where('ec_searching.module NOT LIKE \'index\'')
            ->where('ec_searching.module NOT LIKE \'search\'')
            ->where('ec_searching.module NOT LIKE \'general\'')
            ->where('ec_searching.module NOT LIKE \'widget\'')
            ->where('ec_searching.module NOT LIKE \'menu\'')
            ->group('ec_searching.spec')
            ->order('count DESC' )
            ->limit(10, 0);
        ;
        //print_r($sql->__ToString());
        //die();
        $this->view->submarques = $db->query($sql)->fetchAll();

        $sql = $db->select()
            ->from('ec_searching',['count' => 'Count(ec_searching.`spec`)','ec_searching.value','ec_searching.spec','ec_searching.url'])
            ->where('ec_searching.url IS NOT NULL')
            ->where('ec_searching.module LIKE \'menu\'')
            ->group('ec_searching.spec')
            ->order('count DESC' )
            ->limit(10, 0);
        ;
        //print_r($sql->__ToString());
        //die();
        $this->view->menus = $db->query($sql)->fetchAll();

        $sql = $db->select()
            ->from('ec_searching',['count' => 'Count(ec_searching.`value`)','ec_searching.value','ec_searching.spec'])
            ->where('ec_searching.url IS NOT NULL')
            ->where('ec_searching.module LIKE \'search\'')
            ->group('ec_searching.value')
            ->order('count DESC' )
            ->limit(10, 0);
        ;
        //print_r($sql->__ToString());
        //die();
        $this->view->searchs = $db->query($sql)->fetchAll();

        $sql = $db->select()
            ->from('ec_searching',['count' => 'Count(ec_searching.`spec`)','ec_searching.value','ec_searching.spec'])
            ->where('ec_searching.url IS NOT NULL')
            ->where('ec_searching.module LIKE \'search\'')
            ->group('ec_searching.spec')
            ->order('count DESC' )
            ->limit(14, 0);
        ;
        //print_r($sql->__ToString());
        //die();
        $this->view->terms = $db->query($sql)->fetchAll();


    }

}