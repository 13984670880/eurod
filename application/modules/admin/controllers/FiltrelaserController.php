<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 28/08/2017
 * Time: 16:26
 */

class Admin_FiltrelaserController extends Genius_AbstractController
{



    public function init()
    {

    }

    public function indexAction()
    {
        $baseUrl = new Zend_View_Helper_BaseUrl();
        $this->getResponse()->setRedirect($baseUrl->baseUrl().'/admin/filtrelaser/all');
    }



    public function allAction()
    {

        $douchettes = Genius_Model_FiltrePrinterLaser::all();
        $paginate_= 10;
        $paginator = Zend_Paginator::factory($douchettes)->setItemCountPerPage($paginate_);
        $paginator->setCurrentPageNumber($this->_getParam('page'));


        $current = $paginator->getCurrentPageNumber();
        $total = ceil($paginator->getTotalItemCount()/$paginate_);
        $next = $current + 1;
        $prev = $current - 1;


        $this->view->current = $current;
        $this->view->total = $total;
        $this->view->next = $next;
        $this->view->previous = $prev;

        $this->view->douchettes = $douchettes;
        $this->view->paginator = $paginator;
    }

    public function postAction()
    {
        global $db;

        if( isset($_POST['id'])) $id = $_POST['id'];
        if( isset($_POST['page'])) $page = $_POST['page'];

        if( isset($_POST['id_'])) $id = $_POST['id_'];
        if( isset($_POST['page_'])) $page = $_POST['page_'];

        unset($_POST['id']);
        unset($_POST['id_']);
        unset($_POST['page']);
        unset($_POST['page_']);


        //on recherche la ligne a update avec une condition where
        $where['ec_filtre_lasers.id = ?'] = $id;

        $result = $db->update('ec_filtre_lasers',$_POST,$where);

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $url = '/admin/filtrelaser/all/page/'.$page;
        $this->getResponse()->setRedirect($baseUrl->baseUrl().$url);

    }

    public function editAction()
    {
        global $db;
        $id = $this->_getParam('id');

        $request = Genius_Model_FiltrePrinterLaser::find($id);
        $douchette = $db->query($request)->fetch();

        $this->view->douchette = $douchette;
        $this->view->page = $this->_getParam('page');;
    }

    public function updateAction()
    {
        global $db;

        $champ = $this->_getParam('n');
        $id = $this->_getParam('id');
        $val = $this->_getParam('val');
        $page = $this->_getParam('p');

        //On inverse la valeur enregistre en bdd actuel soit 1 ou 0
        $value = $val == TRUE ? 0 : 1;

        //on recherche la ligne a update avec une condition where
        $where['ec_filtre_lasers.id = ?'] = $id;

        //on passe en tableau le / les parametre a update
        $data[ $champ ] = $value;

        $result = $db->update('ec_filtre_lasers',$data,$where);

        $baseUrl = new Zend_View_Helper_BaseUrl();
        $url = '/admin/filtrelaser/all/page/'.$page;
        $this->getResponse()->setRedirect($baseUrl->baseUrl().$url);
    }

}