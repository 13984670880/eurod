<?php

class Genius_Model_Group {

    public static function getGroups() {
        return Genius_Model_Mutlilingual::get(TABLE_PREFIX . "categories_groups", TABLE_PREFIX . "categories_groups_multilingual", 'id', 'id_category_group');
    }

    public static function getGroupById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "categories_groups", TABLE_PREFIX . "categories_groups_multilingual", 'id', 'id_category_group', $id, 'tm.title,tm.accroche,tm.text,tm.text_reparation,tm.text_vente,tm.text_echange,tm.text_maintenance,tm.text_location,tm.text_audit,tm.text_reprise, tm.seo_title, tm.seo_meta_description, tm.seo_meta_keyword,tm.title_noscript,tm.h1_noscript,tm.h2_noscript');
    }
	
	public static function getGroupByIdOptimize($id){
		global $db;
		$sql = "SELECT title FROM ".TABLE_PREFIX."categories_groups_multilingual WHERE id_language = ".DEFAULT_LANG_ID." AND id_category_group = $id";
		$data = $db->fetchRow($sql);
        return $data;
	}

    public static function getParent($id) {
        global $db;
        $sql = " SELECT id FROM " . TABLE_PREFIX . "categories_groups WHERE id_parent=$id ";
        $data = $db->fetchAll($sql);
        return $data;
    }
	
	 public static function getGroupImageBannerById($id)
    {
        global $db;

        $query = "
			SELECT
				c.id
				,cm.title
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM ".TABLE_PREFIX."categories_groups c

			LEFT JOIN ".TABLE_PREFIX."categories_groups_multilingual cm ON c.id=cm.id_category_group
			LEFT JOIN ".TABLE_PREFIX."images_relations ir ON ( ir.id_item=c.id AND ir.id_module=16  AND ir.image_cover!=1 )
			LEFT JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			LEFT JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id

			WHERE i.filename LIKE '%banniere%' AND cm.id_language=".DEFAULT_LANG_ID." AND im.id_language=".DEFAULT_LANG_ID." AND c.id=$id
			ORDER BY c.order_item ASC
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }
	
	 public static function getGroupImageCoverById($id)
    {
        global $db;

        $query = "
			SELECT
				c.id
				,cm.title
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM ".TABLE_PREFIX."categories_groups c

			LEFT JOIN ".TABLE_PREFIX."categories_groups_multilingual cm ON c.id=cm.id_category_group
			LEFT JOIN ".TABLE_PREFIX."images_relations ir ON ( ir.id_item=c.id AND ir.id_module=16  AND ir.image_cover=1 )
			LEFT JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			LEFT JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id

			WHERE cm.id_language=".DEFAULT_LANG_ID." AND im.id_language=".DEFAULT_LANG_ID." AND c.id=$id
			ORDER BY c.order_item ASC
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }
	
	public static function getIdMarqueGroup($id_category_group) {
        global $db;
        $sql = " SELECT cg.id as id_category_group,mcg.id_module 
		FROM " . TABLE_PREFIX . "categories_groups cg
		INNER JOIN " . TABLE_PREFIX . "modules_categories_groups mcg ON  mcg.id_category_group =  cg.id
		WHERE 
		cg.id_parent=$id_category_group 
		AND mcg.id_module = 13";

        $data = $db->fetchRow($sql);
        return (int)$data['id_category_group'];
    }

    public static function getIdTypeGroup($id_category_group) {
        global $db;
        $sql = " SELECT cg.id as id_category_group,mcg.id_module 
        FROM " . TABLE_PREFIX . "categories_groups cg
        INNER JOIN " . TABLE_PREFIX . "modules_categories_groups mcg ON  mcg.id_category_group =  cg.id
        WHERE 
        cg.id_parent=$id_category_group 
        AND mcg.id_module = 14";
        
        $data = $db->fetchRow($sql);
        return (int)$data['id_category_group'];
    }
}
