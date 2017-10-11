<?php
class DevisController extends Genius_AbstractController {
    public function indexAction() {
        $this->view->headTitle()->append('');
        $this->view->headMeta()->appendName('description', "");
        $this->view->headMeta()->appendName('keyword', "");
        $this->view->subheader = "statics/subheader.phtml";
        $this->view->sidebar = "statics/sidebar.phtml";
        $this->view->active = 'devis';
		$id_product = $this->_getParam('id_product');
		$id_marque = $this->_getParam('id_marque');
		$id_type = $this->_getParam('id_type');
		$prest = $this->_getParam('prest');
		if (isset($_COOKIE['rubrique']) && !empty($_COOKIE['rubrique'])){
			$this->view->background = $_COOKIE['rubrique'];
		}else{
			$this->view->background = "";
		}
		
		if (!empty($id_product)){
			$this->view->product = Genius_Model_Product::getProductById($id_product);
			$this->view->marque = Genius_Model_Category::getCategoryById($id_marque);
			$this->view->type = Genius_Model_Category::getCategoryById($id_type);
			$this->view->devis_phtml = "devis/devis_product.phtml";
		}else{
			if (!empty($id_type)){
				$this->view->type = Genius_Model_Category::getCategoryById($id_type);
			}else{
				$this->view->marque = Genius_Model_Category::getCategoryById($id_marque);
			}
			$this->view->devis_phtml = "devis/devis_article.phtml";
			
			$this->view->prest = $prest;
		}
		

    }
	
	public function index2Action() {
		$this->view->headTitle()->append('');
        $this->view->headMeta()->appendName('description', "");
        $this->view->headMeta()->appendName('keyword', "");
        $this->view->subheader = "statics/subheader.phtml";
        $this->view->sidebar = "statics/sidebar.phtml";
        $this->view->active = 'devis';
		$id_product = $this->_getParam('id_product');
		$id_marque = $this->_getParam('id_marque');
		$id_type = $this->_getParam('id_type');
		$this->view->product = Genius_Model_Product::getProductById($id_product);
		$this->view->marque = Genius_Model_Category::getCategoryById($id_marque);
		$this->view->type = Genius_Model_Category::getCategoryById($id_type);
	}

}