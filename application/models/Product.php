<?php

class Genius_Model_Product {
	
	public static function getAccessoires(){
		$accessoires = array(49,60,71,108,109,110,111,112,124,126,127,128,129,130,131);
		return $accessoires;
	}
	
	public static function getAccessoiresOnglets(){
		$accessoires = array(49,60,71,108,109,110,111,112,121,123,124,126);
		return $accessoires;
	}
	
	public static function getAccessoiresANDOtherMark(){
		$accessoires = array(49,60,71,98,103,104,105,106,108,109,110,111,112);
		return $accessoires;
	}
	
	public static function getAccessoiresToString(){
		$accessoires = Genius_Model_Product::getAccessoiresANDOtherMark();
		$accessoires = Genius_Class_Utils::arrayToStr($accessoires);
		return $accessoires;
	}
	
	public static function isNextShow($nom_group,$order_item){
		$id_product_next = Genius_Model_Product::getNext($order_item);
		if (empty($id_product_next)){
			return false;die();
		}
		$group_category = Genius_Model_Product::getGroup($id_product_next);
		$id_category = $group_category[1];
		$group = Genius_Model_Group::getGroupById($id_category);
		$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]); 
		if ($nom_group == $title_group){
			return true;
		}else{
			return false;
		}
	}
	
	public static function isPreviewShow($nom_group,$order_item){
		$id_product_preview = Genius_Model_Product::getPreview($order_item);
		
		if (empty($id_product_preview)){
			return false;die();
		}
		$group_category = Genius_Model_Product::getGroup($id_product_preview);
		$id_category = $group_category[1];
		$group = Genius_Model_Group::getGroupById($id_category);
		$title_group = Genius_Class_String::cleanString($group['title_'.DEFAULT_LANG_ABBR]);
		if ($nom_group == $title_group){
			return true;
		}else{
			return false;
		}
	}
	
	public static function getNext($order_item){
		
		$accessoires = Genius_Model_Product::getAccessoiresToString();		
		global $db;
		$sql_p = "
			SELECT p.id as id_product, p.order_item, pm.title
 			FROM ".TABLE_PREFIX."products p
			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id = pm.id_product
			INNER JOIN ".TABLE_PREFIX."products_categories pc ON pc.id_product = p.id 			
 			WHERE ( pm.id_language=".DEFAULT_LANG_ID." )
			AND pc.id_category IN ($accessoires) 
			AND p.active = 1
			AND pm.title <> ''
		";
		$data_p = $db->fetchAll($sql_p);
		$not_products = array();
		foreach ($data_p as $p){
			$not_products[]=$p['id_product'];
		}
		$not_products = Genius_Class_Utils::arrayToStr($not_products);
		$sql = "
 			SELECT p.id as id_product, p.order_item, pm.title
 			FROM ".TABLE_PREFIX."products p
			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id = pm.id_product			
 			WHERE ( pm.id_language=".DEFAULT_LANG_ID." )
			AND p.id NOT IN ($not_products) 
			AND pm.title <> ''
			AND p.active = 1
			AND p.order_item > '$order_item'  ORDER BY p.order_item ASC LIMIT 1 
 		"; 
		$data = $db->fetchRow($sql);
 		return $data['id_product'];
	}
	
	public static function getPreview($order_item){		
		$accessoires = Genius_Model_Product::getAccessoiresToString();
		global $db;
		$sql_p = "
 			SELECT p.id as id_product, p.order_item, pm.title
 			FROM ".TABLE_PREFIX."products p
			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product
			INNER JOIN ".TABLE_PREFIX."products_categories pc ON pc.id_product = p.id 						
 			WHERE ( pm.id_language=".DEFAULT_LANG_ID." )
			AND pc.id_category IN ($accessoires) 
			AND p.active = 1
			AND pm.title <> ''
 		"; 
		$data_p = $db->fetchAll($sql_p);
		$not_products = array();
		foreach ($data_p as $p){
			$not_products[]=$p['id_product'];
		}
		$not_products = Genius_Class_Utils::arrayToStr($not_products);
		$sql = "
 			SELECT p.id as id_product, p.order_item, pm.title
 			FROM ".TABLE_PREFIX."products p
			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product						
 			WHERE ( pm.id_language=".DEFAULT_LANG_ID." )
			AND p.id NOT IN ($not_products) 
			AND pm.title <> ''
			AND p.active = 1
			AND p.order_item < '$order_item' ORDER BY p.order_item DESC LIMIT 1 
 		"; 
		$data = $db->fetchRow($sql);
 		return $data['id_product'];
	}
	
	public static function getAllProducts()
 	{ 
 		global $db;
 		$sql = "
 			SELECT p.id as id_product, p.order_item, pm.references
 			FROM ".TABLE_PREFIX."products p
 			LEFT JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product
 			WHERE  pm.id_language = 1
 			ORDER BY p.order_item ASC
 		"; 

 		$data = $db->fetchAll($sql);
 		return $data;
 	}	
	
	public static function getProducts()
 	{ 
 		global $db;
 		$sql = "
 			SELECT p.id, p.order_item, pm.title
 			FROM ".TABLE_PREFIX."products p
 			LEFT JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product
 			WHERE ( pm.id_language=".DEFAULT_LANG_ID." )
 			ORDER BY p.order_item ASC
 		"; 

 		$data = $db->fetchAll($sql);
 		return $data;
 	}	
	
	public static function fillSearch($id_product){
		$product = Genius_Model_Product::getProductById($id_product);
		$product_category=Genius_Model_Product::getProductCategoryById($id_product);
		$id_marque = $product_category['id_category_box'][13];
		$search_value = "";
		if(!empty($id_marque)){
			$marque = Genius_Model_Category::getCategoryById($id_marque);
			$search_value = $marque['title_'.DEFAULT_LANG_ABBR].' '.$product['title_'.DEFAULT_LANG_ABBR];
		}else{
			$search_value = $product['title_'.DEFAULT_LANG_ABBR];
		}		
		Genius_Model_Global::update(TABLE_PREFIX . 'products_multilingual',array("search"=>$search_value),"id_product = '$id_product'");
	}

    public static function getProductById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "products", TABLE_PREFIX . "products_multilingual", 'id', 'id_product', $id, 'tm.*', false);
    }
	
	public static function getProductSeoById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "products_seo", TABLE_PREFIX . "products_seo_multilingual", 'id', 'id_product_seo', $id, 'tm.*', false);
    }
	
	public static function getGroup($id_product) {
		global $db;
		$sql_category = "
		SELECT 
        p.id as id_product, pc.id , pc.id_category

        FROM ".TABLE_PREFIX."products p
        INNER JOIN ".TABLE_PREFIX."products_categories pc ON (p.id=pc.id_product)
		WHERE p.id= '$id_product'
		";
		$data_category = $db->fetchRow($sql_category);
		$id_category = $data_category['id_category'];
		$sql_group = "
		SELECT
		c.id,
		c.id_category_group
		FROM ".TABLE_PREFIX."categories c
		WHERE c.id = '$id_category'
		";
		$data_group = $db->fetchRow($sql_group);
		$id_category_group = $data_group['id_category_group'];
		$sql_category_group = "
		SELECT
		cg.id,
		cg.id_parent
		FROM ".TABLE_PREFIX."categories_groups cg
		WHERE cg.id = '$id_category_group'
		";
		$data_category_group = $db->fetchRow($sql_category_group);
		return array($id_category,$data_category_group['id_parent']);
	}
	
	 public static function getSimilarProduct($id_product) {
		 $products = Genius_Model_Global::selectRow(TABLE_PREFIX . "products_multilingual","id_product,produits_similaires","id_product = '$id_product' AND id_language = '".DEFAULT_LANG_ID."'");
		 $p = array();
		 if (!empty($products['produits_similaires'])){
			 $ids = explode(";",$products['produits_similaires']);
			 if (!empty($ids)){
				 foreach ($ids as $key=>$value):
				 	$p[] = $value;
				 endforeach;
			 }
		 }
		 return $p;
	 }
	 
	 public static function getAccessoiresProductsAssocies($id_product) {
		 $products = Genius_Model_Global::selectRow(TABLE_PREFIX . "products_multilingual","id_product,accessoires_produits_associes","id_product = '$id_product' AND id_language = '".DEFAULT_LANG_ID."'");
		 $p = array();
		 if (!empty($products['accessoires_produits_associes'])){
			 $ids = explode(";",$products['accessoires_produits_associes']);
			 if (!empty($ids)){
				 foreach ($ids as $key=>$value):
				 	$p[] = $value;
				 endforeach;
			 }
		 }
		 return $p;
	 }
	
	public static function getProductImageCoverById($id)
    {
        global $db;

        $query = "
			SELECT
				p.id
				,pm.title
				,pm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM ".TABLE_PREFIX."products p

			LEFT JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product
			LEFT JOIN ".TABLE_PREFIX."images_relations ir ON ( ir.id_item=p.id AND ir.id_module=7 AND ir.image_cover =1 )
			LEFT JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			LEFT JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id

			WHERE pm.id_language=".DEFAULT_LANG_ID." AND im.id_language=".DEFAULT_LANG_ID." AND p.id=$id
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }
    
    	public static function getProductAllImageNotCoverById($id)
    {
        global $db;

        $query = "
			SELECT
				p.id
				,pm.title
				,pm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM ".TABLE_PREFIX."products p

			LEFT JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product
			LEFT JOIN ".TABLE_PREFIX."images_relations ir ON ( ir.id_item=p.id AND ir.id_module=7 AND ir.image_cover!=1 )
			LEFT JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			LEFT JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id

			WHERE pm.id_language=".DEFAULT_LANG_ID." AND im.id_language=".DEFAULT_LANG_ID." AND p.id=$id
		";

        $category = Genius_Model_Global::query($query);
        return $category;
    }
    
	
	 public static function getProductCategoryById($id) {
		 $categories_groups = array ();
		 $id_category_box = array ();
		 $products_categories = Genius_Model_Global::select(TABLE_PREFIX . "products_categories","*","id_product = '$id' ");
		 foreach ($products_categories as $p_c):
		 	//get group
			$id_category = $p_c['id_category']; 
			$categories = Genius_Model_Global::selectRow(TABLE_PREFIX . "categories","*","id = '$id_category' ");
			$id_category_group = $categories['id_category_group'];
			$modules_categories_groups = Genius_Model_Global::selectRow(TABLE_PREFIX . "modules_categories_groups","*","id_category_group = '$id_category_group' ");
			$categories_groups = Genius_Model_Global::selectRow(TABLE_PREFIX . "categories_groups","*","id = '$id_category_group' ");
			$id_category_group_parent = $categories_groups['id_parent'];
			$id_module_rubrique = Genius_Model_Module::getIdModuleByCategoryGroup($id_category_group_parent);
			$id_module_group = $modules_categories_groups['id_module'];
			$categories_box = Genius_Model_Category::getCategoryBox($id_category_group);
			$categories_groups_box[$id_module_group] = $categories_box;
			$id_category_box[$id_module_group] = $id_category;
		 endforeach;
		 
		 $categories_groups_all = Genius_Model_Global::select(TABLE_PREFIX . "modules_categories_groups","*","id_module = '$id_module_rubrique' ");
		
		 foreach ($categories_groups_all as $groups):
		 	$id_category_group = $groups['id_category_group'];
			$all_groups = Genius_Model_Group::getGroupById($id_category_group);			
			$group_list[$all_groups['id']] = $all_groups['title_fr'];
		 endforeach;
		 return 
		 array(
		 'id_module_rubrique'=>$id_module_rubrique,
		 'id_category_group_parent'=>$id_category_group_parent,
		 'categories_groups_box'=>$categories_groups_box,
		 'group_list'=>$group_list,
		 'id_category_box'=>$id_category_box
		 );
	 }

    public static function getProductsPromotions() {
        global $db;
        $sql = "
            SELECT
			    p.id as id_product,
                pm.title,
                pm.text,
				pm.prix,
                i.filename,
                i.path_folder,
                i.format,
                ir.id_image,
                im.legend,
                im.alt
            FROM
			  " . TABLE_PREFIX . "products p
			  INNER JOIN " . TABLE_PREFIX . "products_multilingual pm ON pm.id_product = p.id
			  INNER JOIN " . TABLE_PREFIX . "images_relations ir ON ir.id_item = p.id
			  INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image = ir.id_image
			  INNER JOIN " . TABLE_PREFIX . "images i ON i.id = im.id_image
			  WHERE
			  p.promotion = 1												
			  AND
			  im.id_language = " . DEFAULT_LANG_ID . "
				AND
				pm.id_language = " . DEFAULT_LANG_ID . "
				AND p.active = 1
				GROUP BY p.id
				ORDER BY RAND() LIMIT 4 
        ";
        $data = $db->fetchAll($sql);
        return $data;
    }

    public static function getProductsWithinCategories($cat_ids){
        global $db;

        $cat_ids_str = Genius_Class_Utils::arrayToStr($cat_ids);
        if($cat_ids_str){
            $sql = "
                SELECT pc.*

                FROM ".TABLE_PREFIX."products_categories pc 
                WHERE pc.id_category IN ($cat_ids_str)

                GROUP BY pc.id_product
            ";
            
            $data = $db->fetchAll($sql);
            return $data;

        }else{
            return array();
        }
    }

    public static function getProductsMarques($id_category_group, $ids_products){
        global $db;

        $products_ids_str = Genius_Class_Utils::arrayToStr($ids_products);

        $id_marque = Genius_Model_Group::getIdMarqueGroup($id_category_group);
        $marques = Genius_Model_Category::getCategoryBox($id_marque);

        $marque_ids_str = '';
        if(!empty($marques['categories_list'])){
            foreach ($marques['categories_list'] as $k => $marque) {
                $marque_ids_str .= $marque['id_category'].",";
            }
            $marque_ids_str = rtrim($marque_ids_str,",");
        }

        if($products_ids_str && $marque_ids_str){
            $sql = "
                SELECT 
                    p.id, p.order_item, pc.id_category, cm.title

                FROM ".TABLE_PREFIX."products p
                LEFT JOIN ".TABLE_PREFIX."products_categories pc ON (p.id=pc.id_product)
                LEFT JOIN ".TABLE_PREFIX."categories_multilingual cm ON (cm.id_category=pc.id_category AND cm.id_language=" . DEFAULT_LANG_ID . ")
                
                WHERE p.id IN ($products_ids_str) AND pc.id_category IN ($marque_ids_str)
                GROUP BY pc.id_category
            ";

            $data = $db->fetchAll($sql);

            return $data;
        }
    }

    /**
     * Obtenir les produits  
     *
     * @param int $id_marque_category
     * @param int $id_type_category 
     * @return array
     */
    public static function getProductsByCategoryIds($id_marque_category, $id_type_category){
        global $db;

        $sql = "
            SELECT * FROM (
                (
                    SELECT 
                        p.id as id_product,p.active, p.order_item, pc.id_category, pm.title

                    FROM ".TABLE_PREFIX."products p
                    INNER JOIN ".TABLE_PREFIX."products_categories pc ON (p.id=pc.id_product)
                    INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON (pm.id_product=p.id AND pm.id_language=" . DEFAULT_LANG_ID . ")
                    
                    WHERE pc.id_category=$id_marque_category AND p.active = 1
					
                )
                Union All
                (
                    SELECT 
                        p.id as id_product,p.active, p.order_item, pc.id_category, pm.title

                    FROM ".TABLE_PREFIX."products p
                    INNER JOIN ".TABLE_PREFIX."products_categories pc ON (p.id=pc.id_product)
                    INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON (pm.id_product=p.id AND pm.id_language=" . DEFAULT_LANG_ID . ")
                    
                    WHERE pc.id_category=$id_type_category AND p.active = 1
					
                )
            ) 
            As tbl  
			
			GROUP BY tbl.id_product  HAVING COUNT(*)>=2 
			ORDER BY tbl.order_item ASC
			
        ";

        $data = $db->fetchAll($sql);

        return $data;
    }
}