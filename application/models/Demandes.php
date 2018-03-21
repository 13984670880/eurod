<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 22/08/2017
 * Time: 14:50
 */

class Genius_Model_Demandes
{


    public static function all() {

        global $db;

        //$sql = "
		//SELECT *
		//FROM ec_filtres_demandes
		//ORDERBY created_at DESC
		//";

        $sql = $db
            ->select()
            ->from('ec_filtres_demandes')
            ->order('ec_filtres_demandes.created_at DESC')
        ;

        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function find($id) {

        global $db;
        $sql = $db
            ->select()
            ->from('ec_filtres_demandes')
            ->where("ec_filtres_demandes .id = $id")
        ;
        return $sql;
    }

}