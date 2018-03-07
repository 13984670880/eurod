<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 25/08/2017
 * Time: 12:27
 */

class Genius_Model_FiltrePostePc
{

    public static function getTerminaux() {
        global $db;
        $sql = " SELECT * FROM ec_filtres_pc  ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function find($id) {

        global $db;
        $sql = $db
            ->select()
            ->from('ec_filtres_pc')
            ->where("ec_filtres_pc.id = $id")
        ;
        return $sql;
    }

    public static function findArt($id) {

        global $db;
        $sql = $db
            ->select()
            ->from('ec_filtres_pc')
            ->where("ec_filtres_pc.product_id = $id")
        ;
        return $sql;
    }

    public static function all() {

        global $db;
        $sql = " SELECT * FROM ec_filtres_pc  ";
        $data = $db->fetchAll($sql);
        return $data;
    }
    public function select($id = null ){

        global $db ;

        $sql = $db
            ->select()
            ->from('ec_filtres_pc')
            ->where('ec_filtres_pc.visible = 1')
            ->order('ec_filtres_pc.stock DESC')
            ->order('ec_filtres_pc.top DESC')
            ->order('ec_filtres_pc.pertinence DESC')
        ;

        if($id <> null )
        {
            $sql = $sql->where("ec_filtres_pc .product_id = $id");
        }
        //print_r($sql->__ToString());
        //die();
        return  $sql;
    }

    public function selectGenerique($id = null){

        global $db ;

        $sql = $db
            ->select()
            ->from('ec_filtres_pc')
            ->where('ec_filtres_pc.visible = 1')
            ->order('ec_filtres_pc.stock DESC')
            ->order('ec_filtres_pc.top DESC')
            ->order('ec_filtres_pc.pertinence DESC')
        ;

        if($id <> null )
        {
            $sql = $sql->where("ec_filtres_pc .id = $id");
        }
        //print_r($sql->__ToString());
        //die();
        return  $sql;
    }
}