<?php

/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 11/10/2017
 * Time: 10:51
 */
class Genius_Model_FiltreEtiquettePortable
{

    public static function getAll() {
        global $db;
        $sql = " SELECT * FROM ec_filtre_portables  ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function find($id) {

        global $db;
        $sql = $db
            ->select()
            ->from('ec_filtre_portables')
            ->where("ec_filtre_portables.id = $id")
        ;
        return $sql;
    }

    public static function findArt($id) {

        global $db;
        $sql = $db
            ->select()
            ->from('ec_filtre_portables')
            ->where("ec_filtre_portables.product_id = $id")
        ;
        return $sql;
    }

    public static function all() {

        global $db;
        $sql = "SELECT * FROM ec_filtre_portables";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public function select(){

        global $db ;

        $sql = $db
            ->select()
            ->from('ec_filtre_portables')
            ->join('ec_products_multilingual', 'ec_filtre_portables.product_m_id = ec_products_multilingual.id',
                [
                    'title','text','id_product',
                    'carac_1','carac_2','carac_3','carac_4','carac_5','carac_6','search'
                ])
            ->joinLeft(
                'ec_images_relations',
                ' ec_products_multilingual.id_product = ec_images_relations.id_item ',['id_item']
            )
            ->joinLeft('ec_images', 'ec_images_relations.id_image = ec_images.id',['id_img' => 'id','filename','path_folder','format'])
            ->where('ec_images_relations.id_module=7')
            ->where('ec_images_relations.image_cover =1')
            ->where('ec_filtre_portables.visible = 1')
            ->order('ec_filtre_portables.stock DESC')
            ->order('ec_filtre_portables.top DESC')
            ->order('ec_filtre_portables.pertinence DESC')
        ;


        //print_r($sql->__ToString());
        //die();
        return  $sql;
    }
}