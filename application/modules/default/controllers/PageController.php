<?php
class PageController extends Genius_AbstractController
{
	public function indexAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Catégorie Page');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id = $this->_getParam('id');
	}
	public function societeAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | La société');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		
	}
	public function tracabliteAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Catégorie tracablite');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
	}

	public function chariotmobileAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Chariot mobile');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
	}
	public function imprimanteAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Catégorie Imprimante');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
	}
	public function imprimantethermiqueAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Catégorie Imprimante thermique');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
	}
	public function v5Action()
	{		
		$this->view->headTitle()->append("Eurocomputer | Catégorie Imprimante laser Choix d'un marque");
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
	}
	public function v2Action()
	{		
		$this->view->headTitle()->append("Eurocomputer | Catégorie Imprimante laser Choix d'un marque");
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'tracablite';

        $this->view->id_category_group = $id_category_group = (int)$this->_getParam('id_category_group');
        $this->view->id_category = $id_category = (int)$this->_getParam('id_category');

        $id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
        $types   = Genius_Model_Category::getCategoryBox($id_type);

        $this->view->types = $types;
	}
	public function v8Action()
	{		
		$this->view->headTitle()->append("Eurocomputer | Catégorie Imprimante laser Choix d'un marque");
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
	}
	public function v4Action()
	{		
		$this->view->headTitle()->append("Eurocomputer | Catégorie Imprimante laser Choix d'un marque");
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
	}
	public function v6Action()
	{		
		$this->view->headTitle()->append("Eurocomputer | Catégorie Imprimante laser Choix d'un marque");
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
	}
	public function v7Action()
	{		
		$this->view->headTitle()->append("Eurocomputer | Catégorie Imprimante laser Choix d'un marque");
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
	}
	public function v1Action()
	{	//TRACABILITE	
		$this->view->headTitle()->append('Eurocomputer | Catégorie Page');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'tracablite';
		$this->view->id_category_group = $id_category_group = (int)$this->_getParam('id_category_group');
		$this->view->id_category = $id_category = (int)$this->_getParam('id_category');

		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$types   = Genius_Model_Category::getCategoryBox($id_type);

		$this->view->types = $types;
		
		$modules = Genius_Model_Module::getModuleNameByCategoryGroup($id_category_group);
		header('Access-Control-Allow-Origin: *');
		setcookie('rubrique','tracabilite', time() + (86400 * 1), '/');
	}
	public function p1Action()
	{	//IMPRIMANTES	
		$this->view->headTitle()->append('Eurocomputer | Catégorie Page');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'imprimante';
		$this->view->id_category_group = $id_category_group = (int)$this->_getParam('id_category_group');
		$this->view->id_category = $id_category = (int)$this->_getParam('id_category');

		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$types   = Genius_Model_Category::getCategoryBox($id_type);

		$this->view->types = $types;
		
		$modules = Genius_Model_Module::getModuleNameByCategoryGroup($id_category_group);
		header('Access-Control-Allow-Origin: *');
		setcookie('rubrique','imprimantes', time() + (86400 * 1), '/');

	}
	public function m1Action()
	{	
	    //MICRO	
		$this->view->headTitle()->append('Eurocomputer | Catégorie Micro');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'micro';
		$this->view->id_category_group = $id_category_group = (int)$this->_getParam('id_category_group');
		$this->view->id_category = $id_category = (int)$this->_getParam('id_category');
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$types   = Genius_Model_Category::getCategoryBox($id_type);
		$this->view->types = $types;
		$modules = Genius_Model_Module::getModuleNameByCategoryGroup($id_category_group);
		header('Access-Control-Allow-Origin: *');
		setcookie('rubrique','micro', time() + (86400 * 1), '/');
	}
	public function m2Action()
	{		
		$this->view->headTitle()->append('Eurocomputer | Catégorie Micro');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'micro';
	}
	
	public function souspageAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Sous Catégorie Page');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
	}
	
	public function marqueaAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Sous Catégorie Page');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_module = $id_module = $this->_getParam("id_module");
		$this->view->module = $module = Genius_Model_Global::selectRow(TABLE_PREFIX.'modules','*',"id = '$id_module' ");
		$this->view->groups = $groups = Genius_Model_Module::getCategoryGroupByModule($id_module);
	}
	public function reparationAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Réparation');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$id_category_group = 27;
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$this->view->types = $types   = Genius_Model_Category::getCategoryBox($id_type);
    }
	public function venteAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Vente reconditionné');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$id_category_group = 27;
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$this->view->types = $types   = Genius_Model_Category::getCategoryBox($id_type);
    }
	
	public function maintenanceAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Maintenance');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$id_category_group = 27;
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$this->view->types = $types   = Genius_Model_Category::getCategoryBox($id_type);
    }	
	
	public function locationAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Location');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$id_category_group = 27;
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$this->view->types = $types   = Genius_Model_Category::getCategoryBox($id_type);
    }	
	
	public function auditAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Audit');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$id_category_group = 27;
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$this->view->types = $types   = Genius_Model_Category::getCategoryBox($id_type);
    }	
	
	public function repriseAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Reprise');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }	
	
	public function smartprintAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Smartprint');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
		
	public function clichemicroAction()
	{	
	    $prest = $this->_getParam('prestation');
		$array_prest['vente'] = array("name"=>$this->view->translate("Vente & échange"),"link"=>"vente","id"=>2);
		$array_prest['echange'] = array("name"=>$this->view->translate("Echange Standard"),"link"=>"echange","id"=>3);
		$array_prest['maintenance'] = array("name"=>$this->view->translate("Maintenance"),"link"=>"maintenance","id"=>4);
		$array_prest['location'] = array("name"=>$this->view->translate("Location"),"link"=>"location","id"=>5);
		$array_prest['audit'] = array("name"=>$this->view->translate("Audit"),"link"=>"audit","id"=>6);
		$array_prest['reprise'] = array("name"=>$this->view->translate("Reprise"),"link"=>"reprise","id"=>7);
		
		$this->view->name = $name = $array_prest[$prest]['name'];
		$this->view->link = $link = $array_prest[$prest]['link'];
		$this->view->id_service = $link = $array_prest[$prest]['id'];
		
		$this->view->headTitle()->append('Eurocomputer | '.$name);
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'micro';
		$this->view->id_category_group = $id_category_group = 27;
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$types   = Genius_Model_Category::getCategoryBox($id_type);
		$this->view->types = $types;
    }
	public function accessoiresAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Accessoires');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }	
	
	public function articlereparationAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Réparation');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
		$this->view->form_name = $this->view->translate("FORMULAIRE DE DEMANDE DE RÉPARATION");
    }	
	
	public function articleventeAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Vente reconditionnée');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
    }
	
	public function articleechangeAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Echange standard');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
    }
	
	public function articlemaintenanceAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Maintenance');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
    }

    public function filtreAction()
    {
        $this->view->headTitle()->append('Eurocomputer | Location');
        $this->view->headMeta()->appendName('description',"");
        $this->view->headMeta()->appendName('keyword',"");

        $hdw = [
          'zm400' => [
              'dpi' => 300,
          ],
          'zm600' => [
              'dpi' => 600,
          ],
        ];

        $session = new Zend_Session_Namespace();
        $session->hdw = $hdw ;
        var_dump($session->input);
        var_dump($session->post);


        $this->view->input = $session->input;
        $this->view->hdw = $hdw;
    }
	
	public function articlelocationAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Location');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
    }
	
	public function articleauditAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Audit');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
    }
	
	public function articlerepriseAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Reprise');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
		$this->view->id_category = $id_category = $this->_getParam('id_category');
		$this->view->id_product = $id_product = $this->_getParam('id_product');
		$this->view->marque = Genius_Model_Category::getCategoryById($id_category);
		$this->view->group = Genius_Model_Group::getGroupById($id_category_group);
		//Image Marque
		$photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_category);
		$ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'].'/'.$photocover_marque['filename'].'-source-'.$photocover_marque['id_image'].'.'.$photocover_marque['format'] : '';
		$photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		$this->view->is_image_marque_avalaible = false;
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
			$this->view->is_image_marque_avalaible = true;
		}
		$this->view->photocrh_cover_marque = $photocrh_cover;
		
		//Image Cliché
		$photocover_group = Genius_Model_Group::getGroupImageCoverById($id_category_group);
		$ppath = (!empty($photocover_group)) ? $photocover_group['path_folder'].'/'.$photocover_group['filename'].'-source-'.$photocover_group['id_image'].'.'.$photocover_group['format'] : '';
		$photocrh_cover_group = THEMES_DEFAULT_URL . 'images/non_dispo.png';
		if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
			$photocrh_cover_group = UPLOAD_URL . 'images/' . $ppath;
		}
		$this->view->photocrh_cover_group = $photocrh_cover_group;
    }
	
	public function articlemicroAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Article');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	public function confirmationAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Confirmation');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	public function confirmationdevisAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Confirmation');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	public function confirmationreparationAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Confirmation');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	//bloc nos solutions
	public function marqueAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Partenaires');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	public function reparationservicesAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Réparation et services');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	public function microAction()
	{		
		//MICRO	
		$this->view->headTitle()->append('Eurocomputer | Micro');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
		$this->view->active = 'micro';
		$this->view->id_category_group = $id_category_group = 27;
		$this->view->id_category = $id_category = (int)$this->_getParam('id_category');
		$id_type = Genius_Model_Group::getIdTypeGroup($id_category_group);
		$types   = Genius_Model_Category::getCategoryBox($id_type);
		$this->view->types = $types;
		$modules = Genius_Model_Module::getModuleNameByCategoryGroup($id_category_group);
		header('Access-Control-Allow-Origin: *');
		setcookie('rubrique','micro', time() + (86400 * 1), '/');
    }
	
	public function imprimantesAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Imprimantes');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	
	public function tracabiliteAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Tracabilité');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
    }
	public function contactAction(){
		$this->view->headTitle()->append('Eurocomputer | Contact');
		$this->view->headMeta()->appendName('description',"");
		$this->view->headMeta()->appendName('keyword',"");
	}
}