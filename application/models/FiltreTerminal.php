<?php
/**
 * Created by PhpStorm.
 * User: gvalero
 * Date: 25/08/2017
 * Time: 12:27
 */

class Genius_Model_FiltreTerminal
{

    public static function getTerminaux() {
        global $db;
        $sql = " SELECT * FROM ec_filtres_terminal  ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function find($id) {

        global $db;
        $sql = $db
            ->select()
            ->from('ec_filtres_terminal')
            ->where("ec_filtres_terminal.id = $id")
        ;
        return $sql;
    }

    public static function all() {

        global $db;
        $sql = " SELECT * FROM ec_filtres_terminal  ";
        $data = $db->fetchAll($sql);
        return $data;
    }
    public function select(){

        global $db ;

        $sql = $db
            ->select()
            ->from('ec_filtres_terminal')
            ->join('ec_products_multilingual', 'ec_filtres_terminal.product_m_id = ec_products_multilingual.id',
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
            ->where('ec_filtres_terminal.visible = 1')
            ->order('ec_filtres_terminal.pertinence DESC')
        ;

        return  $sql;
    }
}