<?php

class Genius_Model_HelperProduct
{
    public static function getSearchProduct($products) {

        global $db;

        $sql = $db
            ->select()
            ->from('ec_products_multilingual',['id_product','title','marque','search'])
            ->where('id_product IN(?)', $products);
        ;

        $data = $db->fetchAll($sql);

        return $data;
    }

}