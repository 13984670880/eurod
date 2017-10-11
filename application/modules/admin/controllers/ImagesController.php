<?php

class Admin_ImagesController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('xxx', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Images');		 
	}

	public function modifyAction()
	{ 
		$this->view->headTitle()->append('Edit Images');

		global $params;
		global $siteconfig;
		global $image_thumbnail_params;
		$languages = Genius_Model_Language::getLanguages();

		$id = (int)$this->_getParam('id_image');
		$id_item = (int)$this->_getParam('id_item');
		$module = $this->_getParam('module_name');

		$image = Genius_Model_Image::getImageById($id);
		if(empty($image))
			$image = array();

		$this->view->image = $image;
		$this->view->id = $id;
		$this->view->id_item = $id_item;
		$this->view->module = $module;
		$this->view->image_thumbnail_params = $image_thumbnail_params;

		if($_POST){ 
			/*
			 * 1 step :
			 * 1. update genius_images_multilingual
			 */

			$id = (int)$_POST['Images']['id'];
			$id_item = (int)$_POST['Images']['id_item'];
			$module = $_POST['Images']['module'];

			// 1. update genius_images_multilingual
			// update order item
			if($_POST['Images']['order_item']):
				$order_item = (int)$_POST['Images']['order_item'];
				$id_item = (int)$_POST['Images']['id_item'];
				Genius_Model_Global::updateOrderItemImage(TABLE_PREFIX."images_relations", $order_item, $id_item);
			else:
				$order_item = (int)$_POST['Images']['old_order_item'];
			endif;

			Genius_Model_Global::update(TABLE_PREFIX."images_relations", array('order_item'=>$order_item), " id_item='$id_item' AND id_image='$id' ");

			if(!empty($languages)){ 
				foreach ($languages as $k => $item) {
					$legend = Genius_Class_Utils::idml($_POST['Images'], 'legend_'.$item['abbreviation'], $_POST['Images']['legend_'.DEFAULT_LANG_ABBR]);
					$alt    = Genius_Class_Utils::idml($_POST['Images'], 'alt_'.$item['abbreviation'], $_POST['Images']['alt_'.DEFAULT_LANG_ABBR]);

					$id_language = $item['id'];

					$data_images_multilingual = array(
						'legend' => $legend
						,'alt'   => $alt
					);
					Genius_Model_Global::update(TABLE_PREFIX.'images_multilingual', $data_images_multilingual, " id_image=$id AND id_language=$id_language ");
				}
			}

			$redirect = '/admin/'.$module.'/modify/do/edit/id/'.$id_item;
			$this->_redirect($redirect);				 
		}			
	}

}