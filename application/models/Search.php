<?php
class Genius_Model_Search {

    public $words ;
    public $term ;

    public $model ;
    public $part ;
    public $partialPart ;
    public $marque ;

    public $exploded;
    public $sqls;

    public $isModel;
    public $isPart;
    public $isPartAppro;
    public $isMarque;
    public $isOther;

    public $result;




    /**
     * Genius_Model_Search constructor.
     * @param $words
     */
    public function __construct($words)
    {
        $this->words = $words;
        $this->sqls = [];
        $this->term = [];
        $this->model = [];
        $this->part = [];
        $this->partialPart = [];
        $this->marque = [];
        $this->result = [];

        $this->isMarque = false;
        $this->isModel = false;
        $this->isPart = false;
        $this->isPartAppro = false;
        $this->isOther = false;

        
        $this->exploded = explode(' ',$words);
        $this->deleteTwoSpace();
        $this->oneWord = $this->isOneWord();
        $this->twoWord = $this->isTwoWord();
    }

    /**
     * Logique de la recherche
     * @return array|string
     */
    public function search()
    {
        if( $this->oneWord == true )
        {
            $words = strtoupper($this->words);
            $model=[];

            // 1 recherche MODEL -------------------------------------------------------
            $model = $this->sqlTitle($words);
            if( ! empty( $model) ) {
                $this->isModel = true;
                
                return $model;
            }

            // 2  recherche PN-----------------------------------------------------------
            if( empty( $model ) )
            {
                $part = $this->sqlReference($words);

                if( ! empty( $part) ) {
                    $this->isPart = true;
                    return $part;
                }
            }

            // 3  recherche d'une ref partiel -------------------------------------------
            if(  empty($part) )
            {
                $this->isModel=false;
                $partialPart=[];
                $partialPart = $this->sqlPartialRef();

                if( ! empty( $partialPart)) {
                    $this->isPartAppro = true;
                    return $partialPart;
                }
            }

            // 4  recherche d'une marque -------------------------------------------
            if(  empty($partialPart) )
            {
                $marques = $this->sqlMarque($words);

                if( ! empty( $marques)) {
                    $this->isMarque = true;
                    return $marques;
                }
            }

            return [];
        }
        elseif($this->twoWord == true)
        {
            $testPlaneModel = strtoupper($this->words[0].$this->words[1]);
            $testPlaneModel_ = strtoupper($this->words[0].'-'.$this->words[1]);

            // 1 recherche MODEL (ex: oph 3001 => oph3001 ) -------------------------------------------------------
            $model = $this->sqlTitle($testPlaneModel);
            if( ! empty( $model) )
            {
                return $model;
            }

            // 2 recherche MODEL BIS  (ex: oph 3001 => oph-3001 ) -------------------------------------------------------
            $model_ = $this->sqlTitle($testPlaneModel_);
            if( ! empty( $model_) )
            {
                return $model_;
            }

            // 3  recherche PN  (ex: oph 3001 => oph3001 )------------------------------------------------------------------
            if( empty( $model )  && empty( $model_ ))
            {
                $part = $this->sqlReference($testPlaneModel);

                if( ! empty( $part) ) {
                    $this->isPart = true;
                    return $part;
                }
            }

            // 4  recherche PN  (ex: oph 3001 => oph-3001 )--------------------------------------------------------------------
            if( empty( $part ) )
            {
                $part_ = $this->sqlReference($testPlaneModel_);

                if( ! empty( $part) ) {
                    $this->isPart = true;
                    return $part;
                }
            }

            // 5  recherche PN  (ex: oph 3001 => oph-3001 )--------------------------------------------------------------------
            if( empty( $part_ ) )
            {
                $part_ = $this->sqlReference($testPlaneModel_);

                if( ! empty( $part) ) {
                    $this->isPart = true;
                    return $part;
                }
            }

            // 6  recherche d'une ref partiel -------------------------------------------
            if(  empty($part_) )
            {
                $this->isModel=false;
                $partialPart=[];
                $partialPart = $this->sqlPartialRef();

                if( ! empty( $partialPart)) {
                    $this->isPartAppro = true;
                    return $partialPart;
                }
            }

            die();

            // 6  recherche d'une marque -------------------------------------------
            if(  empty($partialPart) )
            {
                $marques = $this->sqlMarque($this->words);

                if( ! empty( $marques)) {
                    $this->isMarque = true;
                    return $marques;
                }
            }



            return [];
        }

    }
    /**
     * mise en tableau de totues les marques connue !
     */
    public function getAllMarques()
    {
        global $db;
        $marques = " SELECT marque FROM marques ";
        $data_marques = $db->fetchAll($marques);
        $marques=[];

        foreach ($data_marques as $index => $value_m) {
            $this->allMarques[$value_m['marque']]=$value_m['marque'];
        }

    }

    /**
     * recherche un model existant dans les mot écrit
     */
    public function searchModel()
    {
        return true;
    }

    /**
     * Recherche un parts number
     */
    public function searchPartNumber()
    {
        return true;
    }


    /**
     * recherche une reference partiel
     */
    public function searchPartialPartNumber()
    {
        global $db;
        $formated = $this->formatPartial($this->words);


        $where = "";
        $where .= "AND (";

        foreach ($formated as $index => $word) {
            $where .= " cm.references_sans_html LIKE '%$word%' ";
       }
        $where .= ")";

        $request = self::requestsQl($where);
        return $db->fetchAll($request);
    }

    /**
     * Recherche d'une marque
     */
    public function searchMarque()
    {
        return true;
    }

    public function chiffreLettre($word)
    {
        return preg_match_all("/[a-z0-9A-Z]/", $word, $output_array);
    }
    public function chiffreLettreScore($word)
    {
        return preg_match_all("/[a-z0-9A-Z-]/", $word, $output_array);
    }
    public function chiffre($word)
    {
        return preg_match_all("/[0-9]/", $word, $output_array);
    }
    public function lettre($word)
    {
        return preg_match_all("/[a-zA-Z]/", $word, $output_array);
    }
    public function lAndC($word)
    {
        $chiffre = $this->chiffre($word);
        $lettre = $this->lettre($word);

        if($chiffre > 0 && $lettre >0)
        {
           $this->decomposeLAndC($word);
        }
    }
    public function decomposeLAndC($word)
    {
        preg_match_all('#[0-9]+#',$word,$integer);
        preg_match_all('#[A-Z]+#',strtoupper($word),$string);
        
        foreach ($integer[0] as $index => $int) {
            if(strlen($int ) >=2) $this->term[]=$int;
        }

        foreach ($string[0] as $index => $str) {
            if(strlen($str) >=2) $this->term[]=$str;
        }
    }


    public function searchForOneWord($word)
    {

        $score = strpos($word,'-');

        if($score){
            $words = explode('-',$word);

            foreach ($words as $index => $word) {
                $this->term[]=strtoupper($word);
                $this->lAndC($word);
            }
        }

        var_dump($this->term);
        die();

    }

    public function searchForTwoWord()
    {

    }
    public function searchOther()
    {

    }
    public function protoSearch()
    {
        var_dump($this);

        if(count($this->exploded) == 1)
        {
            foreach ( $this->exploded as $index => $word)
            {
                $this->searchForOneWord($word);
            }
        }


        if(count($this->exploded) == 2)
        {
            foreach ( $this->exploded as $index => $word)
            {
                $this->searchForOneWord($word);
            }
        }

            ;
        if(count($this->exploded) > 2)
        {
            foreach ( $this->exploded as $index => $word)
            {
                $this->searchForOneWord($word);
            }
        }


        die();


    }







    public static function get($keywords){
        $keywords_string = str_replace('-',' ',strtoupper($keywords));
        $keywords_string = str_replace('+',' ',$keywords_string);
        $keywords_array = explode(' ',$keywords_string);

        $where = '';

        $i = 0;

        foreach ($keywords_array as $key=>$value){
            $value = strtolower($value);
            if ($i == 0)
                $where .= "  (LOWER(pm.search) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%') ";
            else
                $where .= " AND ( LOWER(pm.search) LIKE '%$value%' OR LOWER(pm.references_sans_html) LIKE '%$value%' )";
            $i++;
        }


        global $db;
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
		AND ($where)
		AND id_language = 1
		GROUP BY p.id
		";

        $data_products = $db->fetchAll($sql_products);
        var_dump($sql_products);
        //die();
        return $data_products;
    }


    /**
     * execution requette avec condition where
     * @param $where
     * @return string
     */
    public static function requestsQl($where,$multiple=false){
        if($multiple == false)
        return "
                SELECT 
                  p.id as id_product,
		          pm.title,
		          pm.text
		        FROM
		          " . TABLE_PREFIX . "products p
		        INNER JOIN " . TABLE_PREFIX . "products_multilingual pm ON pm.id_product = p.id
		        WHERE
		            p.id is not null 
		            AND ($where)
                    AND id_language = 1
		        GROUP BY p.id
		         "
            ;
        if($multiple == true)
            return "
                SELECT 
                  p.id as id_product,
		          pm.title,
		          pm.text
		        FROM
		          " . TABLE_PREFIX . "products p
		        INNER JOIN " . TABLE_PREFIX . "products_multilingual pm ON pm.id_product = p.id
		        WHERE
		            p.id is not null 
		             $where
                    AND id_language = 1
		        GROUP BY p.id
		         "
                ;
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

    /**
     * recherche un model
     * @param $word
     * @return string
     */
    private function sqlTitle($model)
    {
        global $db;
        $where = "LOWER(pm.title) LIKE '%$model%'";
        $request = self::requestsQl($where);

        $this->sqls[]=$request;
        return $db->fetchAll($request);

    }

    /**
     * requette sql pour recherche reference precise
     * @param $ref
     * @return mixed
     */
    private function sqlReference($ref)
    {
        global $db;
        $where = "LOWER(pm.references_sans_html) LIKE '%$ref%'";
        $request = self::requestsQl($where);
        $this->sqls[]=$request;
        return $db->fetchAll($request);
    }

    /**
     * requette sql pour recherche reference partial
     * @param $partials
     * @return array
     */
    private function sqlPartialRef()
    {
        global $db;
        $formated = $this->formatPartial($this->words);
        $fo_ = [];
        $foo_ =[];
        foreach ($this->words as $index => $word) {
            $fo_[] = $this->formatPartial($word);
        }
        foreach ($fo_ as $i => $k) {
            foreach ($k as $v) {
                $foo_[] =$v;
            }
        }
        var_dump($this->words);
        $where = "";
        $where .= "AND (";
        $i=0;

        $variable = is_array($this->words) ? $foo_ : $formated;
        foreach ($variable as $key=>$value):
            
            $i == 0 ?
                $where .= "pm.references_sans_html LIKE '%$value%' "
                :
                $where .= "OR pm.references_sans_html LIKE '%$value%' "
            ;
        $i++;
        endforeach;

        $where .= ")";
        $sql =self::requestsQl($where,true);
        $this->sqls[]=$sql;

        return $db->fetchAll($sql);
    }

    /**
     * requette sql pour recherche d'une marque
     * @param $marque
     * @return mixed
     */
    private function sqlMarque($marque,$multiple = false)
    {
        global $db;

        if($multiple == false){
            $where = "LOWER(pm.search) LIKE '%$marque%'";
            $request = self::requestsQl($where);
        }
        else{
            $where = "";
            $where .= "AND (";
            $i=0;
            foreach ($marque as $key=>$value):
                $i == 0 ?
                    $where .= "pm.search LIKE '%$value%' "
                    :
                    $where .= "OR pm.search LIKE '%$value%' "
                ;
                $i++;
            endforeach;

            $where .= ")";
            $request =self::requestsQl($where,true);
        }
        $this->sqls[]=$request;
        return $db->fetchAll($request);
    }

    /**
     * formate un mot composer de chiffre et de lettre
     * @param $words
     */
    private function formatPartial($words)
    {
        preg_match_all('#[0-9]+#',$words,$integer);
        preg_match_all('#[A-Z]+#',strtoupper($words),$string);

        var_dump($words);
        $int_ = [];
        $str_ = [];

         if(isset($string[0][0])){
             foreach ($string[0] as $k_l => $lettre)
             {
                 if(strlen($lettre) > 1)$str_[]=$lettre;
             }
         }
        if (isset($integer[0][0])) {
            foreach ($integer[0] as $k_i => $chiffre)
            {
                if (strlen($chiffre) > 1) $int_[] = $chiffre;
            }
        }
        return array_merge($int_,$str_);
    }

    /**
     * Nous renseigne si il n'y a qu'un seul mot chercher
     * @return bool
     */
    private function isOneWord()
    {
        return count($this->exploded) == 1 ? true : false;
    }

    /**
     * On supprime les espace en trop et on ré organise le tableau de mot avec index propre
     */
    private function deleteTwoSpace()
    {
        var_dump($this->exploded);
        foreach ($this->exploded as $index => $item) {
            if ($item === "") unset ($this->exploded[$index]);
        }

        if($this->isOneWord()){
            foreach ($this->exploded as $item) {
                $this->words = $item;
            }
        }
        else{
            $this->words=[];
            foreach ($this->exploded as $item) {
                $this->words[] = $item;
            }
        }

    }

    private function isTwoWord()
    {
        return count($this->exploded) == 2 ? true : false;
    }
}