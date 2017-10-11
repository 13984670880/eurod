<?php
class Genius_Class_Menu {
    public static function getGroupName($id_module) {
		$module=Genius_Model_Module::getCategoryGroupByModule($id_module);
		return $module['title'];
	}
}