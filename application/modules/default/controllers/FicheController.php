<?php

class FicheController extends Genius_AbstractController {

    public function indexAction() {

        $this->view->headTitle()->append('Easy Living | Property Single');
        $this->view->headMeta()->appendName('description', "Easy Living | Property Single");
        $this->view->headMeta()->appendName('keyword', "Easy Living | Property Single");
        /* Modification page fiche */
        $this->view->headLink()->appendStylesheet(THEMES_DEFAULT_URL . 'css/index-fiche.css?v=1');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/fiche-index.js', 'text/javascript');
        /* Fin Modification page fiche */
        $this->view->subheader = "statics/subheader.phtml";
        $this->view->sidebar = "statics/sidebar.phtml";
        $this->view->active = 'fiche';
        $this->view->group = $this->_getParam('group');
        $this->view->id_product = $id_product = $this->_getParam('id_product');
        $this->view->product = Genius_Model_Product::getProductById($id_product);
        $product_category = Genius_Model_Product::getProductCategoryById($id_product);
        $this->view->id_marque = $id_marque = $product_category['id_category_box'][13];
        $this->view->id_type = $id_type = $product_category['id_category_box'][14];
        $this->view->marque = Genius_Model_Category::getCategoryById($id_marque);
        $this->view->type = Genius_Model_Category::getCategoryById($id_type);
        $categories = Genius_Model_Global::selectRow(TABLE_PREFIX . 'categories', "id_category_group", "id = '$id_type'");
        $id_category_group = $categories['id_category_group'];
        $group = Genius_Model_Group::getGroupById($id_category_group);
        $id_parent = $group['id_parent'];
        $group_parent = Genius_Model_Group::getGroupById($id_parent);
        $this->view->title_group = $group_parent['title_' . DEFAULT_LANG_ABBR];
        $this->view->id_category_group = $id_parent;
        if (!empty($_COOKIE["id_module"])) {
            $this->view->id_module = $id_module = $_COOKIE["id_module"];
        } else {
            $modules_categories_groups = Genius_Model_Global::selectRow(TABLE_PREFIX . 'modules_categories_groups', "id_module", "id_category_group = '$id_parent'");
            $this->view->id_module = $id_module = $modules_categories_groups['id_module'];
        }
        $module[10] = array("id_module" => 10, "title" => $this->view->translate("Traçabilité"), "link" => "/tracabilite.html", "color_table" => "violet");
        $module[11] = array("id_module" => 11, "title" => $this->view->translate("Imprimantes"), "link" => "/imprimantes.html", "color_table" => "bleu-claire");
        $module[15] = array("id_module" => 15, "title" => $this->view->translate("Micro"), "link" => "/micro.html", "color_table" => "bleu-fonce");
        $this->view->module = $module[$id_module];

        //Image Product

        $photocover_product = Genius_Model_Product::getProductImageCoverById($id_product);
        $ppath = (!empty($photocover_product)) ? $photocover_product['path_folder'] . '/' . $photocover_product['filename'] . '-small-' . $photocover_product['id_image'] . '.' . $photocover_product['format'] : '';
        $photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
        if (file_exists(UPLOAD_PATH . 'images/' . $ppath) && is_file(UPLOAD_PATH . 'images/' . $ppath)) {
            $photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
        }
        $this->view->photocrh_cover_product = $photocrh_cover;

        // Image not cover
        $this->view->photocrh_not_cover_product = Genius_Model_Product::getProductAllImageNotCoverById($id_product);

        //Image Marque
        $photocover_marque = Genius_Model_Category::getCategoryImageCoverById($id_marque);
        $ppath = (!empty($photocover_marque)) ? $photocover_marque['path_folder'] . '/' . $photocover_marque['filename'] . '-source-' . $photocover_marque['id_image'] . '.' . $photocover_marque['format'] : '';
        $photocrh_cover = THEMES_DEFAULT_URL . 'images/non_dispo.png';
        $this->view->is_image_marque_avalaible = false;
        if (file_exists(UPLOAD_PATH . 'images/' . $ppath) && is_file(UPLOAD_PATH . 'images/' . $ppath)) {
            $photocrh_cover = UPLOAD_URL . 'images/' . $ppath;
            $this->view->is_image_marque_avalaible = true;
        }
        $this->view->photocrh_cover_marque = $photocrh_cover;

        $this->view->similar_products = Genius_Model_Product::getSimilarProduct($id_product);
        $this->view->accessoires_produits_associes = Genius_Model_Product::getAccessoiresProductsAssocies($id_product);

        $this->view->products_services = Genius_Model_Services::getProductsServicesById($id_product);
    }

    public function addproductAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = (int) $this->_getParam('id');
        $id_cat = (int) $this->_getParam('id_cat');


        if (isset($_COOKIE["product"])) {
            $cookie_product = unserialize($_COOKIE["product"]);
            $cookie_product[$id_cat][] = $id;
        } else {
            $cookie_product = array();
            $temp = array();
            $temp[] = $id;
            $cookie_product[$id_cat] = $temp;
        }

        setcookie('product', serialize($cookie_product), time() + 7200, "/");
        echo 1;
    }

    public function deleteproductAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id = (int) $this->_getParam('id');
        $id_cat = (int) $this->_getParam('id_cat');

        if (isset($_COOKIE['product'])) {
            $cookie_product = unserialize($_COOKIE["product"]);
            $res = array_search($id, $cookie_product[$id_cat]);
            if ($res >= 0) {
                unset($cookie_product[$id_cat][$res]);
            }
            setcookie('product', serialize($cookie_product), time() + 7200, "/");
            echo 1;
        }
    }

    public function deleteallproductAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $id_cat = (int) $this->_getParam('id_cat');
        if (isset($_COOKIE['product'])) {
            $cookie_product = unserialize($_COOKIE["product"]);
            unset($cookie_product[$id_cat]);
            setcookie('product', serialize($cookie_product), time() + 7200, "/");
            echo 1;
        }
    }

}
