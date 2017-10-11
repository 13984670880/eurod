<?php
class MarqueController extends Genius_AbstractController
{
	public function tracabiliteAction(){	
		$this->view->id_module = $id_module = 10;
		$this->view->module = $module = Genius_Model_Global::selectRow(TABLE_PREFIX.'modules','*',"id = '$id_module' ");
		$this->view->groups = $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);
	}
	public function imprimantesAction(){
		$this->view->id_module = $id_module = 11;
		$this->view->module = $module = Genius_Model_Global::selectRow(TABLE_PREFIX.'modules','*',"id = '$id_module' ");
		$this->view->groups = $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);	
	}
	public function microAction(){	
		$this->view->id_module = $id_module = 15;
		$this->view->module = $module = Genius_Model_Global::selectRow(TABLE_PREFIX.'modules','*',"id = '$id_module' ");
		$this->view->groups = $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);
	}
}