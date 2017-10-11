<?php
class Genius_Model_Services {
	
	public static function getServices() {
		$sess = new Zend_Session_Namespace();
		$id_language = $sess->translate_lang_id;
        return Genius_Model_Mutlilingual::get(TABLE_PREFIX . "services", TABLE_PREFIX . "services_multilingual", 'id', 'id_service');
    }
	
	public static function getServicesById($id) {
        return Genius_Model_Mutlilingual::byid(TABLE_PREFIX . "services", TABLE_PREFIX . "services_multilingual", 'id', 'id_service', $id, 'tm.title, tm.text,tm.link,tm.seo_title,tm.*', false);
    }
	
	public static function getServiceImageBannerById($id_item) {
        global $db;

        $query = "
			SELECT	
				i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM " . TABLE_PREFIX . "images i

			
			INNER JOIN " . TABLE_PREFIX . "images_relations ir ON  ir.id_module=17 AND i.id = ir.id_image
			INNER JOIN " . TABLE_PREFIX . "images_multilingual im ON im.id_image=i.id

			WHERE i.filename LIKE '%banniere%' AND im.id_language=" . DEFAULT_LANG_ID . " AND ir.id_item = $id_item
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }
	
	public static function getServicesByIdOptimize($id){
		global $db;
		$sql = "SELECT title FROM ".TABLE_PREFIX."services_multilingual WHERE id_language = ".DEFAULT_LANG_ID." AND id_service = '$id' ";
		$data = $db->fetchRow($sql);
        return $data;
	}
	
	 public static function getProductsServicesById($id) {
        global $db;
        $sql = " SELECT * FROM " . TABLE_PREFIX . "products_services WHERE id_product = '$id' ";
        $data = $db->fetchAll($sql);
		$ids = array();
		foreach ($data as $d):
			$ids[] = $d['id_service'];
		endforeach;
        return $ids;
    }
	public static function getServicesImageCoverById($id)
    {
        global $db;

        $query = "
			SELECT
				s.id
				,sm.title
				,sm.text
				,i.id as id_image
				,i.filename
				,i.path_folder
				,i.format
				,im.alt
				,im.legend

			FROM ".TABLE_PREFIX."services s

			LEFT JOIN ".TABLE_PREFIX."services_multilingual sm ON s.id=sm.id_service
			LEFT JOIN ".TABLE_PREFIX."images_relations ir ON ( ir.id_item=s.id AND ir.id_module=17 )
			LEFT JOIN ".TABLE_PREFIX."images i ON ir.id_image=i.id
			LEFT JOIN ".TABLE_PREFIX."images_multilingual im ON im.id_image=i.id

			WHERE sm.id_language=".DEFAULT_LANG_ID." AND im.id_language=".DEFAULT_LANG_ID." AND s.id=$id
		";

        $category = Genius_Model_Global::queryRow($query);
        return $category;
    }
}
?>