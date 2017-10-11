<?php

class Genius_Model_Page {

    public static function getPages() {
        return Genius_Model_Mutlilingual::get(TABLE_PREFIX . "pages", TABLE_PREFIX . "pages_multilingual", 'id', 'id_page');
    }

    public static function getPageById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "pages", TABLE_PREFIX . "pages_multilingual", 'id', 'id_page', $id, 'tm.title, tm.text,tm.video,tm.format,tm.thumb, tm.seo_title, tm.seo_meta_description, tm.seo_meta_keyword', false);
    }

    public static function getServiceById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "services", TABLE_PREFIX . "services_multilingual", 'id', 'id_service', $id, 'tm.title, tm.text,tm.link', false);
    }

    public static function getPageByCategoryAndId($id_category, $id_page) {
        global $db;
        $sql = "
        SELECT
            pm.title,
            pm.text,
            i.filename,
            i.path_folder,
            i.format,
            ir.id_image,
            im.legend,
            im.alt,
            pm.seo_meta_keyword,
            pm.seo_title,
            pm.seo_meta_description
        FROM " . TABLE_PREFIX . "pages p
        INNER JOIN " . TABLE_PREFIX . "pages_multilingual pm ON pm.id_page = p.id
        INNER JOIN " . TABLE_PREFIX . "pages_categories pc ON pc.id_page = pm.id_page
        INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ir.id_item = pm.id_page
        INNER JOIN " . TABLE_PREFIX . "images i ON i.id = ir.id
        INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image = ir.id_image
        WHERE
             pc.id_category = $id_category
        AND
            pc.id_page = $id_page
        AND
            pm.id_language = " . DEFAULT_LANG_ID . "
        AND
            im.id_language = " . DEFAULT_LANG_ID . "
        AND
            ir.id_module = 3
        ";
        $data = $db->fetchRow($sql);
        return $data;
    }

    public static function getPageByCategoryAndIdOptimize($id_page) {
        global $db;
        $sql = "
        SELECT
            pm.title,
            pm.text
        FROM " . TABLE_PREFIX . "pages p
        INNER JOIN " . TABLE_PREFIX . "pages_multilingual pm ON pm.id_page = p.id
        WHERE
            pm.id_page = $id_page
        AND
            pm.id_language = " . DEFAULT_LANG_ID . "
      
        ";
        $data = $db->fetchRow($sql);
        return $data;
    }

    public static function getPageByCategory($id_category) {
        global $db;
        $sql = "SELECT
                    p.id,
                    pm.title,
                    pm.text
                FROM
                    " . TABLE_PREFIX . "pages_multilingual pm
                LEFT JOIN " . TABLE_PREFIX . "pages p ON p.id = pm.id_page
                LEFT JOIN " . TABLE_PREFIX . "pages_categories pc ON pc.id_page = p.id
                LEFT JOIN " . TABLE_PREFIX . "categories c ON c.id = pc.id_category
                LEFT JOIN " . TABLE_PREFIX . "categories_groups cg ON cg.id = c.id_category_group
                LEFT JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
                LEFT JOIN " . TABLE_PREFIX . "images_relations ir ON ir.id_item = p.id AND ir.id_module=2
                LEFT JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image = ir.id_image                
                LEFT JOIN " . TABLE_PREFIX . "images i ON i.id = im.id_image
                
                WHERE 
                c.id='" . $id_category . "'
                AND
                pm.id_language = " . DEFAULT_LANG_ID . "
                AND
                cm.id_language = " . DEFAULT_LANG_ID . "
                ORDER BY p.id ASC
	";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function getVideothequeRandom($id_category) {
        global $db;
        $sql = "
                SELECT
                    p.id,
                    pm.title,
                    pm.text,
                    pm.seo_title,
                    pm.thumb
                FROM
                    " . TABLE_PREFIX . "pages_multilingual pm
                INNER JOIN " . TABLE_PREFIX . "pages p ON p.id = pm.id_page
                INNER JOIN " . TABLE_PREFIX . "pages_categories pc ON pc.id_page = p.id
                INNER JOIN " . TABLE_PREFIX . "categories c ON c.id = pc.id_category
                INNER JOIN " . TABLE_PREFIX . "categories_groups cg ON cg.id = c.id_category_group
                INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
                WHERE 
                c.id='" . $id_category . "'
                AND
                pm.id_language = " . DEFAULT_LANG_ID . "
                ORDER BY RAND() LIMIT 1            
        ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function getImageNotCover($id_item, $id_module) {
        global $db;

        $sql = " 
 			SELECT t.*, tm.*, tr.*, t.filename as title
 			FROM " . TABLE_PREFIX . "images t
 			JOIN " . TABLE_PREFIX . "images_multilingual tm ON t.id=tm.id_image
 			JOIN " . TABLE_PREFIX . "images_relations tr ON t.id=tr.id_image AND tr.image_cover <> 1 AND tr.id_module=$id_module
 			WHERE tm.id_language=" . DEFAULT_LANG_ID . " AND tr.id_item='$id_item'
 			ORDER BY tr.order_item
 		";
        $data = $db->fetchAll($sql);
        return $data;
    }

}
