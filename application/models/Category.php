<?php

class Genius_Model_Category {

    public static function getCategories() {
        return Genius_Model_Mutlilingual::get(TABLE_PREFIX . "categories", TABLE_PREFIX . "categories_multilingual", 'id', 'id_category');
    }

    public static function getCategoryById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "categories", TABLE_PREFIX . "categories_multilingual", 'id', 'id_category', $id, 't.id_category_group,tm.title, tm.pastille,tm.accroche, tm.text,tm.text_reparation,tm.text_vente,tm.text_maintenance,tm.text_location,tm.text_audit,tm.text_reprise, tm.seo_title, tm.seo_meta_description, tm.seo_meta_keyword,tm.title_noscript,tm.h1_noscript,tm.h2_noscript');
    }

    public static function getCategoryByIdOptimize($id) {
        global $db;
        $sql = "SELECT title FROM " . TABLE_PREFIX . "categories_multilingual WHERE id_language = " . DEFAULT_LANG_ID . " AND id_category = $id";
        $data = $db->fetchRow($sql);
        return $data;
    }

    public static function getCategoryImageCoverById($id) {
        global $db;

        $query = "
			SELECT
				c.id
				,cm.title
				,cm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM " . TABLE_PREFIX . "categories c

			INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
			INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ( ir.id_item=c.id AND ir.id_module=9  AND ir.image_cover=1 )
			INNER JOIN " . TABLE_PREFIX . "images i ON ir.id_image=i.id
			INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image=i.id

			WHERE cm.id_language=" . DEFAULT_LANG_ID . " AND im.id_language=" . DEFAULT_LANG_ID . " AND c.id=$id
			ORDER BY c.order_item ASC
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }
	
	public static function getCategoryImageBannerById($id) {
        global $db;

        $query = "
			SELECT
				c.id
				,cm.title
				,cm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM " . TABLE_PREFIX . "categories c

			INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
			INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ( ir.id_item=c.id AND ir.id_module=9  AND ir.image_cover!=1 )
			INNER JOIN " . TABLE_PREFIX . "images i ON ir.id_image=i.id
			INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image=i.id

			WHERE i.filename LIKE '%banniere%' AND cm.id_language=" . DEFAULT_LANG_ID . " AND im.id_language=" . DEFAULT_LANG_ID . " AND c.id=$id
			ORDER BY c.order_item ASC
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }

    public static function getCategoryAllImageById($id) {
        global $db;

        $query = "
			SELECT
				c.id
				,cm.title
				,cm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM " . TABLE_PREFIX . "categories c

			INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
			INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ir.id_item=c.id AND ir.id_module=9
			INNER JOIN " . TABLE_PREFIX . "images i ON ir.id_image=i.id
			INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image=i.id

			WHERE cm.id_language=" . DEFAULT_LANG_ID . " AND im.id_language=" . DEFAULT_LANG_ID . " AND c.id=$id
			ORDER BY c.order_item ASC
		";

        $category = Genius_Model_Global::query($query);
        return $category;
    }

    public static function getCategoryByIdCategoryGroup($id_category_group) {
        global $db;
        $sql = "
 			SELECT c.id, c.id_category_group, c.order_item, cm.title
 			FROM " . TABLE_PREFIX . "categories c
 			INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
 			WHERE  c.id_category_group=$id_category_group AND cm.id_language=" . DEFAULT_LANG_ID . " 
 			ORDER BY c.order_item ASC
 		";

        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function isMarque($id_category) {
        $categories = Genius_Model_Global::selectRow(TABLE_PREFIX . "categories", "id_category_group", "id = '$id_category' ");
        $id_category_group = $categories['id_category_group'];
        $modules_categories_groups = Genius_Model_Global::selectRow(TABLE_PREFIX . "modules_categories_groups", "id_module", "id_category_group = '$id_category_group' ");
        $id_module = $modules_categories_groups['id_module'];
        if ($id_module == 13) {
            return true;
        } else {
            return false;
        }
    }

    public static function isType($id_category) {
        $categories = Genius_Model_Global::selectRow(TABLE_PREFIX . "categories", "id_category_group", "id = '$id_category' ");
        $id_category_group = $categories['id_category_group'];
        $modules_categories_groups = Genius_Model_Global::selectRow(TABLE_PREFIX . "modules_categories_groups", "id_module", "id_category_group = '$id_category_group' ");
        $id_module = $modules_categories_groups['id_module'];
        if ($id_module == 14) {
            return true;
        } else {
            return false;
        }
    }

    public static function getCategoryBox($id_category_group) {
        global $db;
        $categories_box = array();

        $name_sql = "
 			SELECT title
 			FROM " . TABLE_PREFIX . "categories_groups_multilingual 
 			WHERE id_category_group=$id_category_group AND id_language=" . DEFAULT_LANG_ID . "
 		";

        $name_run = $db->fetchRow($name_sql);
        $categories_box['categories_name'] = $name_run['title'];

        $list_sql = "
 			SELECT c.is_offre_speciale,c.modele_pastille,c.lien_offre_speciale,cm.title, cm.id_category, cm.pastille
 			FROM " . TABLE_PREFIX . "categories_multilingual cm 
 			JOIN " . TABLE_PREFIX . "categories c ON cm.id_category = c.id 
 			WHERE cm.id_language=" . DEFAULT_LANG_ID . " AND c.id_category_group=$id_category_group
 			ORDER BY c.order_item ASC
 		";
        $list_run = $db->fetchAll($list_sql);

        if (!empty($list_run)) {
            foreach ($list_run as $key => $value) {
                $categories_box['categories_list'][] = $value;
            }
        }

        return $categories_box;
    }

    public static function getCheckedCategories($id_category_group, $item_id, $table, $fk) {
        global $db;

        $categories = array();
        $categoriesquery = "
 			SELECT * 
 			FROM " . TABLE_PREFIX . "$table tc 
 			JOIN " . TABLE_PREFIX . "categories c ON tc.id_category = c.id  
 			WHERE tc.$fk = $item_id AND c.id_category_group=$id_category_group
 		";
        $categoriesresult = $db->fetchAll($categoriesquery);

        if (!empty($categoriesresult)) {
            foreach ($categoriesresult as $key => $value) {
                $categories[] = $value['id_category'];
            }
        }

        return $categories;
    }

    /* marques */

    public static function getAllMarquesPartenaires() {
        global $db;
        $sql = "
        SELECT 
        cm.title,
        i.filename,
        i.path_folder,
        i.format,
        im.alt,
        im.legend,
        ir.id_image
        
        FROM
        " . TABLE_PREFIX . "categories c
		INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
		INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ir.id_item = c.id
		INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image = ir.id_image
		INNER JOIN " . TABLE_PREFIX . "images i ON i.id = ir.id_image
		WHERE
		c.id_category_group IN(15,18,20,25,28,30,33)
		AND
		ir.id_module = 13
		AND 
		im.id_language = " . DEFAULT_LANG_ID . "
		  AND 
		cm.id_language = " . DEFAULT_LANG_ID . "
        ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function getModule($id_category_group) {
        global $db;
        $sql_1 = "
		SELECT
		id_parent
		FROM " . TABLE_PREFIX . "categories_groups
		WHERE
		id = '$id_category_group'
		";
        $data_1 = $db->fetchRow($sql_1);
        $id_parent = $data_1['id_parent'];
        $sql_2 = "
		SELECT
		id_module
		FROM " . TABLE_PREFIX . "modules_categories_groups
		WHERE
		id_category_group = '$id_parent'
		";
        $data_2 = $db->fetchRow($sql_2);
        return $data_2['id_module'];
    }

    public static function getTopMarques() {
        global $db;
		$tops = Genius_Model_Tops::getTopsById(1);
		$id_order_marque = $tops['id_order_marque'];
        $sql_1 = "
		 SELECT
		 c.id,
		 cm.title
		 FROM
        " . TABLE_PREFIX . "categories c
         INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
		 INNER JOIN " . TABLE_PREFIX . "modules_categories_groups mcg ON c.id_category_group = mcg.id_category_group
		 WHERE 	
		 mcg.id_module = 13
		 AND cm.id_language = 1
		 ";
        $data_1 = $db->fetchAll($sql_1);
        $m = array();
        foreach ($data_1 as $p) {
            $m[$p['id']] = $p['title'];
        }
        $results = array_unique($m);
        $str = "";
        foreach ($results as $key => $value) {
            $str .= "" . $key . ",";
        }
        $ids = substr($str, 0, -1);
        $sql_2 = "
		 SELECT
		 c.id as id_category,
		 c.id_category_group,
		 cm.title
		 FROM
        " . TABLE_PREFIX . "categories c
         INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
		 INNER JOIN " . TABLE_PREFIX . "modules_categories_groups mcg ON c.id_category_group = mcg.id_category_group
		 WHERE 	
		 mcg.id_module = 13
		 AND cm.id_language = 1
		 AND ( cm.title is not null AND cm.title <> '' AND cm.title <> '.' )
		 AND c.id IN ($id_order_marque)
		 ORDER BY FIELD(c.id,$id_order_marque)
		 ";
        $data = $db->fetchAll($sql_2);
        return $data;
    }
	
	public static function getTopMarquesServices(){
		$tops = Genius_Model_Tops::getTopsById(1);
		$id_order_marque_service = $tops['id_order_marque_service'];
		global $db;
        $sql_1 = "
		 SELECT
		 c.id,
		 cm.title
		 FROM
        " . TABLE_PREFIX . "categories c
         INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
		 INNER JOIN " . TABLE_PREFIX . "modules_categories_groups mcg ON c.id_category_group = mcg.id_category_group
		 WHERE 	
		 mcg.id_module = 13
		 AND cm.id_language = 1
		 AND c.id IN ($id_order_marque_service)
		 ORDER BY FIELD(c.id,$id_order_marque_service)
		 ";
        $data_1 = $db->fetchAll($sql_1);echo '<pre>';echo print_r($data_1);
        $m = array();
        foreach ($data_1 as $p) {
            $m[$p['id']] = $p['title'];
        }
        //$results = array_unique($m);
		$results = $m;
        $str = "";
        foreach ($results as $key => $value) {
            $str .= "" . $key . ",";
        }
        $ids = substr($str, 0, -1);
        $sql_2 = "
		 SELECT
		 c.id as id_category,
		 c.id_category_group,
		 cm.title
		 FROM
        " . TABLE_PREFIX . "categories c
         INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON cm.id_category = c.id
		 INNER JOIN " . TABLE_PREFIX . "modules_categories_groups mcg ON c.id_category_group = mcg.id_category_group
		 WHERE 	
		 mcg.id_module = 13
		 AND cm.id_language = 1
		 AND ( cm.title is not null AND cm.title <> ''  )
		 AND c.id IN ($id_order_marque_service)
		 ORDER BY FIELD(c.id,$id_order_marque_service)
		 ";
        $data = $db->fetchAll($sql_2);
        return $data;
	}

    public static function getAllMarques() {
        global $db;
        $sql = "
        SELECT 
        cg.id,
		cgm.id_category_group,
        i.filename,
        i.path_folder,
        i.format,
        im.alt,
        im.legend,
        ir.id_image
        
        FROM
		" . TABLE_PREFIX . "categories_groups cg
		INNER JOIN " . TABLE_PREFIX . "categories_groups_multilingual cgm ON cgm.id_category_group = cg.id
		INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ir.id_item = cg.id
		INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image = ir.id_image
		INNER JOIN " . TABLE_PREFIX . "images i ON i.id = ir.id_image
		WHERE
		cg.id = 35
		AND
		ir.id_module = 16
		AND 
		im.id_language = " . DEFAULT_LANG_ID . "
			AND 
		cgm.id_language = " . DEFAULT_LANG_ID . "
		ORDER BY ir.order_item ASC
        ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function getOffresSpeciales($id_category) {
        global $db;
        $sql = "
 			SELECT c.id,c.is_offre_speciale,c.etat_offre_speciale, c.id_category_group, c.order_item,c.lien_offre_speciale,c.modele_pastille, cm.title,cm.pastille
 			FROM " . TABLE_PREFIX . "categories c
 			INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
 			WHERE
            c.id_cat_offre_speciale_concerne=$id_category AND cm.id_language=" . DEFAULT_LANG_ID . " AND c.is_offre_speciale=1 AND c.etat_offre_speciale=1";
        $data = $db->fetchRow($sql);
        return $data;
    }
    
        public static function getOffresSpecialesTracabilite($id_category_group) {
        global $db;
        $sql = "
 			SELECT c.id,c.is_offre_speciale,c.etat_offre_speciale, c.id_category_group, c.order_item,c.lien_offre_speciale,c.modele_pastille, cm.title,cm.pastille
 			FROM " . TABLE_PREFIX . "categories c
 			INNER JOIN " . TABLE_PREFIX . "categories_multilingual cm ON c.id=cm.id_category
 			WHERE
            c.id_category_group=$id_category_group AND cm.id_language=" . DEFAULT_LANG_ID . " AND c.is_offre_speciale=1 AND c.etat_offre_speciale=1";
        $data = $db->fetchRow($sql);
        return $data;
    }

}
