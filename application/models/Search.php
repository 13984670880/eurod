<?php
class Genius_Model_Search {
	public static function get($keywords){
		global $db;
		$one_word = false;
		if(strpos(trim($keywords), ' ') !== false){
		  // multiple words
		  $one_word = false;
		  $keywords_string = str_replace('-',' ',strtoupper($keywords));
		  $keywords_string = str_replace('+',' ',$keywords_string);
		  $keywords_array = explode(' ',$keywords_string);
		  $keywords_array_2 = explode(' ',$keywords_string);
		  $keywords_array = array_filter($keywords_array);
		  foreach($keywords_array as $key => $value){
			  if(is_numeric($value)) unset($keywords_array[$key]);
		  }
		}else{
		  // one word
		  $one_word = true;
		  $keywords_array = array($keywords);
		  $keywords_array_2 = array($keywords);
		  
		  $keywords_string_one_word = str_replace('-',' ',strtoupper($keywords));
		  $keywords_string_one_word = str_replace('+',' ',$keywords_string_one_word);
		  $keywords_array_one_word = explode(' ',$keywords_string_one_word);
		  $keywords_array_one_word = array_filter($keywords_array_one_word);
	    }
		
		$where = '';
		
		$i = 0;
		
		
		
		/*new search first PN*/
		foreach ($keywords_array as $key=>$value){
			$value = strtolower($value);
			if (!empty($value) && $value != '0'  && $value != '00'  && $value != '000'  && $value != '0000'){
				if ($i == 0)
					$where .= "  (LOWER(pm.modele) LIKE '%$value%')";
				else
					$where .= " OR ( LOWER(pm.modele) LIKE '%$value%' )";	 
				$i++;
			}
		}
		
		// PRODUITS
		$sql_products = "
		SELECT
		p.id as id_product,
		pm.title
		FROM
		".TABLE_PREFIX."products p
		INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON pm.id_product = p.id
		WHERE
		p.id is not null
		AND ($where)
		AND id_language = 1
		GROUP BY p.id
		";					
		$data_products_1 = $db->fetchAll($sql_products);

		if(sizeof($data_products_1) == 1 && $one_word == true){
			return array(1,$data_products_1);
		}elseif(sizeof($data_products_1) == 0 && $one_word == true){
			$i = 0;
			$where = '';
			/*new search first PN*/
			foreach ($keywords_array_one_word as $key=>$value){
				$value = strtolower($value);
				if (!empty($value) && $value != '0'  && $value != '00'  && $value != '000'  && $value != '0000'){
					if ($i == 0)
						$where .= "  (LOWER(pm.modele) LIKE '%$value%')";
					else
						$where .= " OR ( LOWER(pm.modele) LIKE '%$value%' )";	 
					$i++;
				}
			}
			// PRODUITS
			$sql_products = "
			SELECT
			p.id as id_product,
			pm.title
			FROM
			".TABLE_PREFIX."products p
			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON pm.id_product = p.id
			WHERE
			p.id is not null
			AND ($where)
			AND id_language = 1
			GROUP BY p.id
			";					
			$data_products_1 = $db->fetchAll($sql_products);
			if (sizeof($data_products_1) == 1){
				return array(1,$data_products_1);
			}elseif(sizeof($data_products_1) > 1){
				$new_data_products_1 = array();
				$c = 0;
				foreach ($data_products_1 as $d){
					$id_product = $d['id_product'];
					if (!empty($id_product)){
					  $id_product = $d['id_product'];
					  $product_category = Genius_Model_Product::getProductCategoryById($id_product);
					  $id_marque = $product_category['id_category_box'][13];
					  if(!empty($id_marque)){
						  $marque = Genius_Model_Category::getCategoryById($id_marque);
						  $marque = $marque['title_'.DEFAULT_LANG_ABBR];
					  }else{
						  $marque = "";
					  }	
					  $photocover_product = Genius_Model_Product::getProductImageCoverById($id_product);		  
					  $ppath = (!empty($photocover_product)) ? $photocover_product['path_folder'].'/'.$photocover_product['filename'].'-small-'.$photocover_product['id_image'].'.'.$photocover_product['format'] : '';
					  $photocrh_cover_p = THEMES_DEFAULT_URL . 'images/non_dispo.png';
					  if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
						  $photocrh_cover_p = UPLOAD_URL . 'images/' . $ppath;
					  }
					  $new_data_products_1[$c]['id_product'] = $id_product;
					  $new_data_products_1[$c]['marque'] = $marque;
					  $new_data_products_1[$c]['title'] = $d['title'];
					  $new_data_products_1[$c]['photocrh_cover_p'] = $photocrh_cover_p;
					  $c++;
					}
				}
				return array(2,$new_data_products_1);
			}else{
				 /*new search first PN*/
				$where = "";
				$i = 0;
				foreach ($keywords_array_one_word as $key=>$value){
					$value = strtolower($value);
					if (!empty($value)){
					  if ($i == 0)
						  $where .= "  ( LOWER(pm.marque) LIKE '%$value%' OR LOWER(pm.groupe) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
					  else
						  $where .= " AND ( LOWER(pm.marque) LIKE '%$value%' OR LOWER(pm.groupe) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
					  $i++;
					}
				}
				
				
				// PRODUITS
				$sql_products = "
				SELECT
				p.id as id_product,
				pm.title
				FROM
				".TABLE_PREFIX."products p
				INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON pm.id_product = p.id
				WHERE
				p.id is not null
				AND ($where)
				AND id_language = 1
				GROUP BY p.id
				";					
				
				$data_products_1 = $db->fetchAll($sql_products);
				if (sizeof($data_products_1) == 1){
					return array(1,$data_products_1);
				}elseif(sizeof($data_products_1) > 1){
					$new_data_products_1 = array();
					$c = 0;
					foreach ($data_products_1 as $d){
						$id_product = $d['id_product'];
						if (!empty($id_product)){
						  $product_category = Genius_Model_Product::getProductCategoryById($id_product);
						  $id_marque = $product_category['id_category_box'][13];
						  if(!empty($id_marque)){
							  $marque = Genius_Model_Category::getCategoryById($id_marque);
							  $marque = $marque['title_'.DEFAULT_LANG_ABBR];
						  }else{
							  $marque = "";
						  }	
						  $photocover_product = Genius_Model_Product::getProductImageCoverById($id_product);		  
						  $ppath = (!empty($photocover_product)) ? $photocover_product['path_folder'].'/'.$photocover_product['filename'].'-small-'.$photocover_product['id_image'].'.'.$photocover_product['format'] : '';
						  $photocrh_cover_p = THEMES_DEFAULT_URL . 'images/non_dispo.png';
						  if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
							  $photocrh_cover_p = UPLOAD_URL . 'images/' . $ppath;
						  }
						  $new_data_products_1[$c]['id_product'] = $id_product;
						  $new_data_products_1[$c]['marque'] = $marque;
						  $new_data_products_1[$c]['title'] = $d['title'];
						  $new_data_products_1[$c]['photocrh_cover_p'] = $photocrh_cover_p;
						  $c++;
						}
					}
					return array(2,$new_data_products_1);
				}else{
					return array(0,$data_products_1);
				}				
			}
		}elseif(sizeof($data_products_1) == 1){
			return array(1,$data_products_1);
		}elseif(sizeof($data_products_1) > 1){
			$new_data_products_1 = array();
			$c = 0;
			foreach ($data_products_1 as $d){
				$id_product = $d['id_product'];
				if (!empty($id_product)){
				  $product_category = Genius_Model_Product::getProductCategoryById($id_product);
				  $id_marque = $product_category['id_category_box'][13];
				  if(!empty($id_marque)){
					  $marque = Genius_Model_Category::getCategoryById($id_marque);
					  $marque = $marque['title_'.DEFAULT_LANG_ABBR];
				  }else{
					  $marque = "";
				  }	
				  $photocover_product = Genius_Model_Product::getProductImageCoverById($id_product);		  
				  $ppath = (!empty($photocover_product)) ? $photocover_product['path_folder'].'/'.$photocover_product['filename'].'-small-'.$photocover_product['id_image'].'.'.$photocover_product['format'] : '';
				  $photocrh_cover_p = THEMES_DEFAULT_URL . 'images/non_dispo.png';
				  if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
					  $photocrh_cover_p = UPLOAD_URL . 'images/' . $ppath;
				  }
				  $new_data_products_1[$c]['id_product'] = $id_product;
				  $new_data_products_1[$c]['marque'] = $marque;
				  $new_data_products_1[$c]['title'] = $d['title'];
				  $new_data_products_1[$c]['photocrh_cover_p'] = $photocrh_cover_p;
				  $c++;
				}
			}
			return array(2,$new_data_products_1);
		}elseif(sizeof($data_products_1) == 0){
			$i = 0;
			/*new search first PN*/
			$where = "";
			$keywords_array_2 = array_filter($keywords_array_2);
			foreach ($keywords_array_2 as $key=>$value){
				$value = strtolower($value);
				if (!empty($value)){
				  if ($i == 0)
					  $where .= "  ( LOWER(pm.marque) LIKE '%$value%' OR LOWER(pm.groupe) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
				  else
					  $where .= " AND ( LOWER(pm.marque) LIKE '%$value%' OR LOWER(pm.groupe) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
				  $i++;
				}
			}
			// PRODUITS
			$sql_products = "
			SELECT
			p.id as id_product,
			pm.title
			FROM
			".TABLE_PREFIX."products p
			INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON pm.id_product = p.id
			WHERE
			p.id is not null
			AND ($where)
			AND id_language = 1
			GROUP BY p.id
			";	
			$data_products_2 = $db->fetchAll($sql_products);

			if(sizeof($data_products_2) == 1){
				return array(1,$data_products_2);
			}elseif (sizeof($data_products_2) > 1){
				$new_data_products_2 = array();
				$c = 0;
				foreach ($data_products_2 as $d){
					$id_product = $d['id_product'];
					if (!empty($id_product)){
					  $product_category = Genius_Model_Product::getProductCategoryById($id_product);
					  $id_marque = $product_category['id_category_box'][13];
					  if(!empty($id_marque)){
						  $marque = Genius_Model_Category::getCategoryById($id_marque);
						  $marque = $marque['title_'.DEFAULT_LANG_ABBR];
					  }else{
						  $marque = "";
					  }	
					  $photocover_product = Genius_Model_Product::getProductImageCoverById($id_product);		  
					  $ppath = (!empty($photocover_product)) ? $photocover_product['path_folder'].'/'.$photocover_product['filename'].'-small-'.$photocover_product['id_image'].'.'.$photocover_product['format'] : '';
					  $photocrh_cover_p = THEMES_DEFAULT_URL . 'images/non_dispo.png';
					  if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
						  $photocrh_cover_p = UPLOAD_URL . 'images/' . $ppath;
					  }
					  $new_data_products_2[$c]['id_product'] = $id_product;
					  $new_data_products_2[$c]['marque'] = $marque;
					  $new_data_products_2[$c]['title'] = $d['title'];
					  $new_data_products_2[$c]['photocrh_cover_p'] = $photocrh_cover_p;
					  $c++;
					}
				}
				return array(2,$new_data_products_2);
			}else{
				$i = 0;
				/*new search first PN*/
				$where = "";
				
				$keywords_array_2 = array_filter($keywords_array_2);

				foreach ($keywords_array_2 as $key=>$value){
					$value = strtolower($value);
					if (!empty($value) && $value != ' '  && $value != '  '){
					  if ($i == 0)
						  $where .= "  ( LOWER(pm.marque) LIKE '%$value%' OR LOWER(pm.groupe) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
					  else
						  $where .= " OR ( LOWER(pm.marque) LIKE '%$value%' OR LOWER(pm.groupe) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
					  $i++;
					}
				}
					
					
				// PRODUITS
				$sql_products = "
				SELECT
				p.id as id_product,
				pm.title
				FROM
				".TABLE_PREFIX."products p
				INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON pm.id_product = p.id
				WHERE
				p.id is not null
				AND ($where)
				AND id_language = 1
				GROUP BY p.id
				";	
				$data_products_3 = $db->fetchAll($sql_products);

				if(sizeof($data_products_3) == 1){
					return array(1,$data_products_3);
				}elseif (sizeof($data_products_3) > 1){
					$new_data_products_3 = array();
					$c = 0;
					foreach ($data_products_3 as $d){
						$id_product = $d['id_product'];
						if (!empty($id_product)){
						  $product_category = Genius_Model_Product::getProductCategoryById($id_product);
						  $id_marque = $product_category['id_category_box'][13];
						  if(!empty($id_marque)){
							  $marque = Genius_Model_Category::getCategoryById($id_marque);
							  $marque = $marque['title_'.DEFAULT_LANG_ABBR];
						  }else{
							  $marque = "";
						  }	
						  $photocover_product = Genius_Model_Product::getProductImageCoverById($id_product);		  
						  $ppath = (!empty($photocover_product)) ? $photocover_product['path_folder'].'/'.$photocover_product['filename'].'-small-'.$photocover_product['id_image'].'.'.$photocover_product['format'] : '';
						  $photocrh_cover_p = THEMES_DEFAULT_URL . 'images/non_dispo.png';
						  if(file_exists(UPLOAD_PATH.'images/'.$ppath) && is_file(UPLOAD_PATH.'images/'.$ppath)){
							  $photocrh_cover_p = UPLOAD_URL . 'images/' . $ppath;
						  }
						  $new_data_products_3[$c]['id_product'] = $id_product;
						  $new_data_products_3[$c]['marque'] = $marque;
						  $new_data_products_3[$c]['title'] = $d['title'];
						  $new_data_products_3[$c]['photocrh_cover_p'] = $photocrh_cover_p;
						  $c++;
						}
					}
					return array(2,$new_data_products_3);
				}else{
					return array(0,$data_products_3);
				}
			}
		}	
	}
	public static function getAll($key_words){
		global $db;
		// MARQUES
		$where_categories = "";
		$j=1;
		if (!empty($key_words)){
			$where_categories .= "AND (";
			foreach ($key_words as $key=>$value):
			if ($j==1){
				$where_categories .= " cm.title LIKE '%$value%' ";	
			}else{
				$where_categories .= " OR cm.title LIKE '%$value%' ";	
			}				
				$j++;
			endforeach;
			$where_categories .= ")";
		}
		$sql_marques = "
		SELECT
		cm.id_category,
		cm.title
		FROM
		".TABLE_PREFIX."categories c
		INNER JOIN ".TABLE_PREFIX."categories_multilingual cm ON c.id = cm.id_category
		INNER JOIN ".TABLE_PREFIX."modules_categories_groups mcg ON mcg.id_category_group = c.id_category_group
		WHERE
		mcg.id_module = 13
		$where_categories
		GROUP BY cm.id_category
		";
				
		$data_marques = $db->fetchAll($sql_marques);		
		$id_categories = array();
		if (!empty($data_marques)){
			foreach ($data_marques as $dm):
				$id_categories[] = $dm['id_category']; 
			endforeach;
		}
				
				
		$where_marques = "";
		$ids = "";
		$data_products_categories = array();
		if (!empty($id_categories)){
			$p = 1;
			$where_marques = "AND (";
			foreach ($id_categories as $key=>$val):
			if ($p==1){
				$ids .="$val";
			}else{
				$ids .=",$val";
			}
			$p++;
			endforeach;
			
			$where_marques .= "pc.id_category IN($ids)"; 
			$where_marques .= ")";
			// PRODUITS CATEGORIES
			$sql_products_categories = "
			SELECT
			pc.id_product,
			pc.id_category
			FROM
			".TABLE_PREFIX."products_categories pc
			WHERE
			pc.id_product is not null
			$where_marques
			";
			$data_products_categories = $db->fetchAll($sql_products_categories);	
					
		}
		
		$k = 1;
		$id_c = "";
		$where_products_categories = "";
		if (!empty($data_products_categories)){
			$where_products_categories .= "AND (";
			foreach ($data_products_categories as $pc):
				$id_product = $pc['id_product']; 
				if ($k==1){
					$id_c .="$id_product";
				}else{
					$id_c .=",$id_product";
				}
				$k++;
			endforeach;
			
			$where_products_categories .= "p.id IN($id_c)"; 
			$where_products_categories .= ")";
		}
		
		$where = "";
		$i = 1;
		if (!empty($key_words)){
			$where .= "AND (";
			foreach ($key_words as $key=>$value):
			if ($i==1){
				$where .= " pm.title LIKE '%$value%' OR pm.references LIKE '%$value%' ";	
			}else{
				$where .= " OR pm.title LIKE '%$value%' OR pm.references LIKE '%$value%' ";	
			}				
				$i++;
			endforeach;
			$where .= ")";
		}
		
		$where = "";
		// PRODUITS
		$sql_products = "
		SELECT
		p.id as id_product,
		pm.title,
		pm.text
		FROM
		".TABLE_PREFIX."products p
		INNER JOIN ".TABLE_PREFIX."products_multilingual pm ON pm.id_product = p.id
		WHERE
		p.id is not null
		$where
		$where_products_categories
		GROUP BY p.id
		";		
		$data_products = $db->fetchAll($sql_products);
 		return $data_products;
	}
}