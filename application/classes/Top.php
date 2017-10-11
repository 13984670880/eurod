<?php
class Genius_Class_Top{
	public static function getTop($id){
		$html = '';
		$tops = Genius_Model_Tops::getTopsById($id);
		$title_top = $tops['title_top'];
		$title_top_1 = $tops['title_top_1'];
		$title_top_2 = $tops['title_top_2'];
		$title_top_3 = $tops['title_top_3'];
		$format_menu_1 = $tops['format_menu_1'];
		$format_menu_2 = $tops['format_menu_2'];
		$format_menu_3 = $tops['format_menu_3'];
		$format_url_menu_1 = $tops['format_url_menu_1'];
		$format_url_menu_2 = $tops['format_url_menu_2'];
		$format_url_menu_3 = $tops['format_url_menu_3'];

		
		$html .="<h3 class='heading3'>$title_top</h3>";
		
		$html .="<div class='accordianTab'>";
        $html .="<div class='accordianLink'><h3>$title_top_1</h3></div>";
		$html .="<div class='accordianContent'>"; 
		$html .="<ul>";
		$html .= Genius_Class_Top::treatment($format_menu_1);
		$html .="</ul>";
		$html .="</div>";
		$html .="</div>";
		
		$html .="<div class='accordianTab'>";
        $html .="<div class='accordianLink'><h3>$title_top_2</h3></div>";
		$html .="<div class='accordianContent'>"; 
		$html .="<ul>";
		$html .= Genius_Class_Top::treatment($format_menu_2);
		$html .="</ul>";
		$html .="</div>";
		$html .="</div>";
		
		$html .="<div class='accordianTab border_bottom'>";
        $html .="<div class='accordianLink'><h3>$title_top_3</h3></div>";
		$html .="<div class='accordianContent'>"; 
		$html .="<ul>";
		$html .= Genius_Class_Top::treatment($format_menu_3);
		$html .="</ul>";
		$html .="</div>";
		$html .="</div>";
		return $html;
	}
	
	public static function getTopProducts()
 	{ 
	    $tops = Genius_Model_Tops::getTopsById(1);
		$id_order_product = $tops['id_order_product'];
 		global $db;
 		$sql = "
 			SELECT p.id as id_product, p.order_item, pm.title
 			FROM ".TABLE_PREFIX."products p
 			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON p.id=pm.id_product
 			WHERE ( pm.id_language=".DEFAULT_LANG_ID." )
			AND p.id IN ($id_order_product)
 			ORDER BY FIELD(p.id,$id_order_product)
 		"; 

 		$data = $db->fetchAll($sql);
 		return $data;
 	}	
	
	public static function treatment($format_menu){
		$seo = new Genius_Class_Seo();
		$tops = Genius_Model_Tops::getTopsById(1);
		$html = "";
		if ($format_menu == "££marques££"){
			$marques = Genius_Model_Category::getTopMarques();
			foreach ($marques as $m){
				if (strtolower($m['title']) != "autres marques" && strtolower($m['title']) != "-"){
					$id_module = Genius_Model_Category::getModule($m['id_category_group']);
					$link = $seo->getLinkGroupMarque($id_module,$m['id_category_group'],$m['id_category']);
					$html .="<li><a href='".$link."'>".$m['title']."</a></li>";
				}
			}
			return $html;
		}
		if ($format_menu == "££services££"){
			$id_services = Genius_Class_Utils::StrToarray($tops['id_order_service']);
			$marques = Genius_Class_Utils::StrToarray($tops['id_order_marque_service']);
			$cnt = 0;
			foreach ($marques as $key=>$m){
					$id_marque = $m;
					$mark = Genius_Model_Category::getCategoryById($id_marque);
					$id_category_group = $mark['id_category_group'];
					$categories_groups = Genius_Model_Global::selectRow(TABLE_PREFIX.'categories_groups','id_parent',"id = '$id_category_group'");
					$group = Genius_Model_Group::getGroupByIdOptimize($categories_groups['id_parent']);	
					$indice = $cnt;		
					$services = Genius_Model_Services::getServicesByIdOptimize($id_services[$indice]);
					$link = $seo->getLinkGroupMarqueService($id_services[$indice],$categories_groups['id_parent'],$id_marque);
					$html .="<li><a href='".$link."'>".$services['title']." - ".$group['title']." - ".$mark['title_'.DEFAULT_LANG_ABBR]."</a></li>";
					$cnt++;
			}
			return $html;
		}
		if ($format_menu == "££produits££"){
			$products = Genius_Class_Top::getTopProducts();
			foreach ($products as $p){				
					$product_category=Genius_Model_Product::getProductCategoryById($p['id_product']);
		            $id_marque = $product_category['id_category_box'][13];
		            $id_type = $product_category['id_category_box'][14];
		            $marque = Genius_Model_Category::getCategoryByIdOptimize($id_marque);
					$type = Genius_Model_Category::getCategoryByIdOptimize($id_type);
					$link = $seo->getLinkProduct($p['id_product']);
				if (strtolower($p['title']) != "" && strtolower($marque["title"]) != "autres marques" ){	
					$html .="<li><a href='".$link."'>".$marque["title"]." ".$p['title']."</a></li>";
				}
			}
			return $html;
		}
	}
}
?>