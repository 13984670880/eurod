<?php

class Genius_Model_Menu {

    public static function getMenus() {
        return Genius_Model_Mutlilingual::get(TABLE_PREFIX . "menus", TABLE_PREFIX . "menus_multilingual", 'id', 'id_menu');
    }

    public static function getMenuById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "menus", TABLE_PREFIX . "menus_multilingual", 'id', 'id_menu', $id, 'tm.*', false);
    }

    public static function getAllMenus() {
        global $db;
        $sql = "
        SELECT 
        m.id as id_menu,
        ml.title,
        ml.text,
        ml.link,
        ml.seo_title,
        ml.controller        
        FROM        
        " . TABLE_PREFIX . "menus m
            INNER JOIN " . TABLE_PREFIX . "menus_multilingual ml ON ml.id_menu = m.id
                INNER JOIN " . TABLE_PREFIX . "menus_categories mc ON mc.id_menu = ml.id_menu
                    WHERE
                    ml.id_language = " . DEFAULT_LANG_ID . "
                    AND
                    mc.id_category = 9
            ORDER BY m.id ASC        
        ";
        $data = $db->fetchAll($sql);
        return $data;
    }

}