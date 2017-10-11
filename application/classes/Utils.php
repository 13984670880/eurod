<?php

class Genius_Class_Utils {
	
public static function setLisible($str){
    $str=str_replace(' ','',$str);
	$arr1 = str_split($str);
	$max = sizeof($arr1);
	$m = 0;
	$i=0;
	$chaine = "";
	for ($i=1;$i<=$max;$i++){
		if (($i % 2) != 1){
			if ($i == $max){
				$chaine .= $arr1[$i-1];
			}else{
				$chaine .= $arr1[$i-1]." ";
			}
		}else{
			$chaine .= $arr1[$i-1];
		}
	}
	return $chaine;
}
public static function creerFichier($fichierChemin, $fichierNom, $fichierExtension, $fichierContenu, $droit=""){
	$fichierCheminComplet = $_SERVER["DOCUMENT_ROOT"].$fichierChemin."/".$fichierNom;
	if($fichierExtension!=""){
		$fichierCheminComplet = $fichierCheminComplet.".".$fichierExtension;
	}
 
	// création du fichier sur le serveur
	$leFichier = fopen($fichierCheminComplet, "wb");
	fwrite($leFichier,$fichierContenu);
	fclose($leFichier);
 
	// la permission
	if($droit==""){
		$droit="0777";
	}
 
	// on vérifie que le fichier a bien été créé
	$t_infoCreation['fichierCreer'] = false;
	if(file_exists($fichierCheminComplet)==true){
		$t_infoCreation['fichierCreer'] = true;
	}
 
	// on applique les permission au fichier créé
	$retour = chmod($fichierCheminComplet,intval($droit,8));
	$t_infoCreation['permissionAppliquer'] = $retour;
 
	return $t_infoCreation;
}

 
 

    public static function testsql($sql) {
        echo $sql;
        die();
    }

    public static function showArray($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public static function findexts($filename) {
        $filename = strtolower($filename);
        $exts = split("[/\\.]", $filename);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }

    public static function remove_file_extension($strName) {
        $ext = strrchr($strName, '.');

        if ($ext !== false) {
            $strName = substr($strName, 0, -strlen($ext));
        }

        return $strName;
    }

    public static function affiche_lien($str) {
        if ($str == "#")
            return '';
        else
            return substr($str, 7, strlen($str) - 7);
    }

    public static function curl_download($Url) {

        if (!function_exists('curl_init')) {
            die('Sorry cURL n\'est pas instalisé!');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    public static function xml2array($contents, $get_attributes = 1, $priority = 'tag') {
        if (!$contents)
            return array();

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values)
            return; //Hmm...





            
//Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference
        //Go through the tags.
        $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble
            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag')
                    $result = $value;
                else
                    $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag')
                        $attributes_data[$attr] = $val;
                    else
                        $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or ( !in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return($xml_array);
    }

    public static function array_to_json($array) {

        if (!is_array($array)) {
            return false;
        }
        $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
        if ($associative) {

            $construct = array();
            foreach ($array as $key => $value) {

                // We first copy each key/value pair into a staging array,
                // formatting each key and value properly as we go.
                // Format the key:
                if (is_numeric($key)) {
                    $key = "key_$key";
                }
                $key = "\"" . addslashes($key) . "\"";

                // Format the value:
                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "\"" . addslashes($value) . "\"";
                }

                // Add to staging array:
                $construct[] = "$key: $value";
            }

            // Then we collapse the staging array into the JSON form:
            $result = "{ " . implode(", ", $construct) . " }";
        } else { // If the array is a vector (not associative):
            $construct = array();
            foreach ($array as $value) {

                // Format the value:
                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = "'" . addslashes($value) . "'";
                }

                // Add to staging array:
                $construct[] = $value;
            }

            // Then we collapse the staging array into the JSON form:
            $result = "[ " . implode(", ", $construct) . " ]";
        }

        return $result;
    }

    /*
     * @return the value at $index in $array or $default if $index is not set.
     */

    public static function idx(array $array, $key, $default = null) {
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    public static function idml(array $array, $key, $default = null) {
        return (array_key_exists($key, $array) AND trim($array[$key]) != "" ) ? $array[$key] : $default;
    }

    /*
     * @return the id of the value in an array
     */

    public static function idv(array $array, $value, $default = false) {
        $key = array_search($value, $array);
        return ($key) ? $key : $default;
    }

    public static function he($str) {
        return htmlentities($str, ENT_QUOTES, "UTF-8");
    }

    public static function get_numerics($str) {
        preg_match_all('/(([0-9]+) )+/', $str, $matches);
        return $matches[0];
    }

    public static function trimStr($str) {
        return trim(strip_tags($str));
    }

    /*
     * Format number to decimal
     */

    public static function format_number($number) {
        $str = '';
        $str = number_format($number, 0, '.', ' ');
        return $str;
    }

    public static function text_cut($str, $no_words_ret) {
        static $tags = array('font', 'div', 'span', 'p', 'strong', 'b', 'u', 'i', 'a', 'ul', 'li');

        $word_count = 0;
        $pos = 0;
        $str_len = strlen($str);
        $str .= ' <';
        $open_tags = array();

        while ($word_count <= $no_words_ret && $pos < $str_len) {
            $pos = min(strpos($str, ' ', $pos), strpos($str, '<', $pos));
            if ($str[$pos] == '<') {
                if ($str[$pos + 1] == '/') {
                    array_pop($open_tags);
                } else {
                    $sub = substr($str, $pos + 1, min(strpos($str, ' ', $pos), strpos($str, '>', $pos)) - $pos - 1);
                    if (in_array($sub, $tags)) {
                        array_push($open_tags, $sub);
                    }
                }
                $pos = strpos($str, '>', $pos) + 1;
            } else {
                $pos++;
                if ($str[$pos] != ' ') //Code ajout
                    $word_count++;
            }
        }

        $str = substr($str, 0, $pos);
        if ($word_count > $no_words_ret)
            $str = trim($str) . "...";
        if (count($open_tags) > 0) {
            foreach ($open_tags as $value) {

                $str .= '</' . array_pop($open_tags) . '>';
            }
        }

        return($str);
    }

    public static function chopText($text, $length) {

        if (strlen($text) > $length) {
            $addpoints = 'true';
        } else {
            $addpoints = 'false';
        }

        $croped_value = substr(strip_tags($text), 0, $length);
        if (empty($text)) {
            $croped_value = '';
        } elseif ($addpoints == 'true') {
            $croped_value .='...';
        }

        return $croped_value;
    }

    public static function getCurrentLang($uri = null) {
        if (!$uri) {
            $uri = $_SERVER['REQUEST_URI'];
        }
        $uri = ltrim($uri, '/');
        $uri = explode('/', $uri);

        $final_uri = '/';
        if (!empty($uri)) {
            foreach ($uri as $k => $item) {
                $final_uri .= $item . '/';
            }

            $final_uri = str_replace('//', '/', $final_uri);
        }

        $alllangs = Genius_Model_Global::query("SELECT id,abbreviation FROM " . TABLE_PREFIX . "languages WHERE id IS NOT NULL");
        $langs = array();
        if (!empty($alllangs)) {
            foreach ($alllangs as $k => $lang) {
                $langs[] = $lang['abbreviation'];
            }
        }
        $lang = 'fr';
        $url_with_lang = preg_match('/\/([a-z]{2})([\/].*)/', $final_uri, $matches);
        if (isset($matches[1]) and in_array($matches[1], $langs)) {
            if (!isset($matches[1])) {
                $lang = 'fr';
            } else {
                $lang = $matches[1];
            }
        }

        return $lang;
    }

    public static function contains($substring, $string) {
        $pos = strpos($string, $substring);

        if ($pos === false) {
            // string needle NOT found in haystack
            return false;
        } else {
            // string needle found in haystack
            return true;
        }
    }

    public static function getFullBaseUrl($include_base = true) {
        $front = Zend_Controller_Front::getInstance();
        $view = new Zend_View();

        if ($include_base) {
            $base_url = $view->baseUrl();
        } else {
            $base_url = '';
        }

        $url = $front->getRequest()->getScheme() . '://' . $front->getRequest()->getHttpHost() . $base_url;

        return $url;
    }

    public static function isFileExists($file_path) {
        if (file_exists($file_path) && is_file($file_path)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getFileExt($file_path) {
        $path_parts = pathinfo($file_path);
        $fileext = strtolower($path_parts['extension']);
        return $fileext;
    }

    public static function getFileName($file_path) {
        $path_parts = pathinfo($file_path);
        $filename = strtolower($path_parts['filename']);
        return $filename;
    }

    public static function arrayToStr($cat_ids) {
        $cat_ids_str = '';

        if (is_array($cat_ids) && !empty($cat_ids)) {
            $cat_ids_str = null;
            foreach ($cat_ids as $id_cat) {
                $cat_ids_str .= $id_cat . ",";
            }
            $cat_ids_str = rtrim($cat_ids_str, ",");
            return $cat_ids_str;
        } else {
            return false;
        }
    }

    public static function StrToarray($cat_ids) {
        $cat = explode(',', $cat_ids);
        $cats = array();
        foreach ($cat as $key => $value) {
            $cats[] = $value;
        }
        return $cats;
    }

    public static function getUrlandPathImage($path, $filename, $type, $id_image, $format) {
        $url = UPLOAD_URL . 'images/' . $path . '/' . $filename . '-' . $type . '-' . $id_image . '.' . $format;
        $path = UPLOAD_PATH . 'images/' . $path . '/' . $filename . '-' . $type . '-' . $id_image . '.' . $format;
        if (file_exists($path) && is_file($path)) {
            return array('url' => $url, 'path' => $path);
        } else {
            return false;
        }
    }

    public static function getPastille($id) {
        $ext = $id != 3 ? 'png' : 'gif';
        $url = THEMES_DEFAULT_URL . 'images/pastille/pastille-' . $id . '.' . $ext;
        $path = THEMES_DEFAULT_PATH . 'images/pastille/pastille-' . $id . '.' . $ext;
        if (file_exists($path) && is_file($path)) {
            return '<div class="pastille"><img src="' . $url . '"></div>';
        } else {
            return '';
        }
    }

    public static function getLogoMarque($id) {
        $category = Genius_Model_Category::getCategoryById($id);
        $url = BASE_URL . 'timthumb.php?src=' . UPLOAD_URL . 'logo_marque/' . $category['logo_marque'];
        $path = UPLOAD_PATH . 'logo_marque/'. $category['logo_marque'];
        
        if (file_exists($path) && is_file($path)) {
            $url = Genius_Class_Timthumb::createThumb($path, 120,'');
            return '<div class="logomarque"><img src="' . $url . '"></div>';
        }else{
            return false;
        }
    }

}
