<?php
class Genius_Model_Tops {
	public static function getTopsById($id) {
        global $db;
        $sql = " SELECT * FROM " . TABLE_PREFIX . "tops WHERE id = '$id' ";
        $data = $db->fetchRow($sql);
        return $data;
    }
}
	