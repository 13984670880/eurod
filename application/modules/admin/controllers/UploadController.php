<?php

class Admin_UploadController extends Genius_AbstractController {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('multipleupload', 'html');
        $ajaxContext->addActionContext('crop', 'html');
        $ajaxContext->addActionContext('getimages', 'html');
        $ajaxContext->addActionContext('delete', 'html');
        $ajaxContext->addActionContext('changecover', 'html');
        $ajaxContext->initContext();
    }
	
	public function cleanAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$images_relations = Genius_Model_Global::select(TABLE_PREFIX . 'images_relations', "*","id is not null");
		foreach ($images_relations as $data){
			$id_image = $data['id_image'];
			$images = Genius_Model_Global::selectRow(TABLE_PREFIX . 'images', "*","id='$id_image'");
			if (empty($images)){
				Genius_Model_Global::delete(TABLE_PREFIX . 'images_multilingual', "id_image = '$id_image' ");
				Genius_Model_Global::delete(TABLE_PREFIX . 'images_relations', "id_image = '$id_image' ");
			}			
		}
		echo 'finished'; die();
	}

    public function multipleuploadAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        global $image_thumbnail_params;
        $languages = Genius_Model_Language::getLanguages();

        $uploaded_folder = $_REQUEST['folder'];
        $id_module = $_REQUEST['id_module'];
        $id_item = (int) $_REQUEST['id_item'];

        $uploadpath = UPLOAD_PATH . 'images/' . $uploaded_folder . '/';

        //If there is file
        if (!empty($_FILES)) {
            setlocale(LC_CTYPE, "fr_FR.utf8");

            //The tempfile
            $tempFile = $_FILES['file']['tmp_name'];

            //The target path
            $targetPath = $uploadpath;

            if (!is_dir($targetPath)) {
                if (!mkdir($targetPath)) {
                    echo "Erreur: Veuillez créer manuellement le dossier " . $targetPath;
                    exit(0);
                }
            }

            $path_parts = pathinfo($targetPath . $_FILES['file']['name']);
            $filename = $path_parts['filename'];

            $targetfilename = Genius_Class_String::formatFilename($filename);
            $targetfilename_formated = $targetfilename;

            $targetfilename = $targetfilename_formated . "-source." . $path_parts['extension'];
            $targetFile = str_replace('//', '/', $targetPath) . $targetfilename;

            if (move_uploaded_file($tempFile, $targetFile)) {
                // action to do when files are uploaded

                list($wfile, $hfile) = getimagesize($targetFile);
                $defaultcordinates = "0,0,0,0";
                $uploaded_file_name = $targetfilename_formated;
                $uploaded_file_format = $path_parts['extension'];

                /*
                 * 3 steps :
                 * 1. insert in genius_images
                 * 2. insert in genius_images_relations
                 * 3. insert in genius_images_multilingual
                 */

                // 1. insert in genius_images
                $data_images = array(
                    'filename' => $filename
                    , 'path_folder' => $uploaded_folder
                    , 'format' => $uploaded_file_format
                    , 'size_mini' => $defaultcordinates
                    , 'size_small' => $defaultcordinates
                    , 'size_medium' => $defaultcordinates
                    , 'create_time' => date('Y-m-d H:i:s')
                    , 'update_time' => date('Y-m-d H:i:s')
                );
                Genius_Model_Global::insert(TABLE_PREFIX . 'images', $data_images);
                $lastId = Genius_Model_Global::lastId();

                $newtargetfilename = $targetfilename_formated . "-source-" . $lastId . "." . $path_parts['extension'];
                $newrenametargetFile = str_replace('//', '/', $targetPath) . $newtargetfilename;
                rename($targetFile, $newrenametargetFile);

                // 2. insert in genius_images_relations
                // get item max order_item
                $maxorderitem = $this->getMaxOrderitem($id_module, $id_item);

                // test if item has image cover
                $photo_cover = $this->getPhotosCouverture($id_module, $id_item);
                $image_cover = (trim($photo_cover) == '') ? 1 : '';

                $data_images_relations = array(
                    'id_module' => $id_module
                    , 'id_item' => $id_item
                    , 'id_image' => $lastId
                    , 'image_cover' => $image_cover
                    , 'order_item' => (int) $maxorderitem + 1
                );
                Genius_Model_Global::insert(TABLE_PREFIX . 'images_relations', $data_images_relations);

                // 3. insert in genius_images_multilingual
                if (!empty($languages)) {
                    foreach ($languages as $key => $value) {
                        $data_images_multilingual = array(
                            'id_image' => $lastId
                            , 'id_language' => $value['id']
                            , 'legend' => ''
                            , 'alt' => ''
                        );
                        Genius_Model_Global::insert(TABLE_PREFIX . 'images_multilingual', $data_images_multilingual);
                    }
                }

                //crop image
                foreach ($image_thumbnail_params[$uploaded_folder] as $format => $value) {
                    $newfilename = $targetPath . $targetfilename_formated . '-' . $format . '-' . $lastId . '.' . $path_parts['extension'];

                    //Height and width of original image
                    list($w, $h) = getimagesize($newrenametargetFile);

                    $width = $value['size_x'];
                    $height = $value['size_y'];

                    //Original image don't exist
                    if ((!$w) || (!$h)) {
                        $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.';
                        return false;
                    }

                    //No resizing needed
                    if (($w == $width) && ($h == $height)) {
                        
                    }

                    //try max width first...
                    $ratio = $width / $w;
                    $new_w = $width;
                    $new_h = $h * $ratio;

                    //If that created an image smaller than what we wanted, try the other way
                    if ($new_h < $height) {
                        $ratio = $height / $h;
                        $new_h = $height;
                        $new_w = $w * $ratio;
                    }

                    if ($path_parts['extension'] == 'jpg') {
                        $img_r = imagecreateFROMjpeg($newrenametargetFile);
                    }elseif ($path_parts['extension'] == 'jpeg') {
                        $img_r = imagecreateFROMjpeg($newrenametargetFile);
                    } elseif ($path_parts['extension'] == 'png') {
                        $img_r = imagecreateFROMpng($newrenametargetFile);
                    } elseif ($path_parts['extension'] == 'gif') {
                        $img_r = imagecreateFROMgif($newrenametargetFile);
                    } elseif ($path_parts['extension'] == 'bpm') {
                        $img_r = imagecreateFROMwbmp($newrenametargetFile);
                    } else {
                        $img_r = imagecreateFROMjpeg($newrenametargetFile);
                    }

                    $image2 = imagecreatetruecolor($new_w, $new_h);
                    imagecopyresampled($image2, $img_r, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

                    //check to see if cropping needs to happen
                    if (($new_h != $height) || ($new_w != $width)) {

                        $image3 = imagecreatetruecolor($width, $height);
                        if ($new_h > $height) { //crop vertically
                            $extra = $new_h - $height;
                            $x = 0; //source x
                            $y = round($extra / 2); //source y
                            imagecopyresampled($image3, $image2, 0, 0, $x, $y, $width, $height, $width, $height);
                        } else {
                            $extra = $new_w - $width;
                            $x = round($extra / 2); //source x
                            $y = 0; //source y
                            imagecopyresampled($image3, $image2, 0, 0, $x, $y, $width, $height, $width, $height);
                        }
                        imagedestroy($image2);
                        imagejpeg($image3, $newfilename);
                    } else {
                        imagejpeg($image2, $newfilename);
                    }

                    //Update coordinates
                    $field_name = 'size_' . $format;
                    $sql = "UPDATE " . TABLE_PREFIX . "images SET " . $field_name . "='0,0,0,0' WHERE id='$lastId'";
                    Genius_Model_Global::execute($sql);
                }

                //echo 'File '.$targetPath.$uploaded_file_name.'.'.$uploaded_file_format.' uploaded.';
                $return = array('status' => 'ok');
                echo json_encode($return);
            } else {
                //echo "Erreur durant le téléchargement, veuillez recommencer!";
                $return = array('status' => 'error');
                echo json_encode($return);
                exit(0);
            }
        }
    }

    public function cropAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        global $image_thumbnail_params;

        //Get Image id
        $image_id = (int) $this->_getParam('id');
        $crop_format = $this->_getParam('size_format');
        $current_module = $this->_getParam('module_name');

        //Update coordinates
        $cx = $this->_getParam('cx_' . $crop_format);
        $cy = $this->_getParam('cy_' . $crop_format);
        $cxtwo = $this->_getParam('cxtwo_' . $crop_format);
        $cytwo = $this->_getParam('cytwo_' . $crop_format);

        $size_string = $cx . "," . $cy . "," . $cxtwo . "," . $cytwo;
        $data_images = array('size_' . $crop_format => $size_string, 'update_time' => date('Y-m-d H:i:s'));
        Genius_Model_Global::update(TABLE_PREFIX . "images", $data_images, " id=$image_id ");

        //Get images values
        $image_values = Genius_Model_Global::select(TABLE_PREFIX . "images", "*", " id=$image_id ");
        $image_values = $image_values[0];

        $size = $image_thumbnail_params[$current_module][$crop_format];
        $current_image = UPLOAD_PATH . 'images/' . $image_values['path_folder'] . '/' . $image_values['filename'] . "-" . $crop_format . "-" . $image_id . '.' . $image_values['format'];
        $source_image = UPLOAD_PATH . 'images/' . $image_values['path_folder'] . '/' . $image_values['filename'] . "-source-" . $image_id . '.' . $image_values['format'];

        if ($image_values['format'] == 'jpg') {
            $img_r = imagecreateFROMjpeg($source_image);
        } elseif ($image_values['format'] == 'png') {
            $img_r = imagecreateFROMpng($source_image);
        } elseif ($image_values['format'] == 'gif') {
            $img_r = imagecreateFROMgif($source_image);
        } elseif ($image_values['format'] == 'bpm') {
            $img_r = imagecreateFROMwbmp($source_image);
        } else {
            $img_r = imagecreateFROMjpeg($source_image);
        }

        list($wfile, $hfile) = getimagesize($source_image);

        if (isset($image_thumbnail_params[$current_module][$crop_format]['flexible'])) {
            $image2 = imagecreatetruecolor($this->_getParam('d_w_' . $crop_format), $this->_getParam('d_h_' . $crop_format));
        } else {
            $image2 = imagecreatetruecolor($size['size_x'], $size['size_y']);
        }
        imagecopyresampled($image2, $img_r, 0, 0, $this->_getParam('x_' . $crop_format), $this->_getParam('y_' . $crop_format), $this->_getParam('w_' . $crop_format), $this->_getParam('h_' . $crop_format), $wfile, $hfile);

        imagejpeg($image2, $current_image);

        $return = array('status' => 'ok');
        echo json_encode($return);
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        global $image_thumbnail_params;

        $id = (int) $this->_getParam('id');
        $module = $this->_getParam('module_name');
        $where = " id=$id ";
		$where_2 = " id_image=$id ";

        //get images values
        $image_values = Genius_Model_Global::select(TABLE_PREFIX . "images", "*", " id=$id ");
        $image_values = $image_values[0];

        //delete image
        Genius_Model_Global::delete(TABLE_PREFIX . 'images', $where);
		Genius_Model_Global::delete(TABLE_PREFIX . 'images_multilingual', $where_2);
		Genius_Model_Global::delete(TABLE_PREFIX . 'images_relations', $where_2);

        //remove source file
        $full_filename = $image_values['filename'] . "-source-" . $id . "." . $image_values['format'];
        $file_path = UPLOAD_PATH . 'images/' . $module . '/' . $full_filename;
        if (file_exists($file_path))
            @unlink($file_path);

        //remove another files
        /*$format = $image_thumbnail_params[$module];
        if (!empty($format)) {
            foreach ($format as $key => $value) {
                $full_filename = $image_values['filename'] . "-" . $key . "-" . $id . "." . $image_values['format'];
                $file_path = UPLOAD_PATH . 'images/' . $module . '/' . $full_filename;
                if (file_exists($file_path))
                    @unlink($file_path);
            }
        }*/
        
        echo 1;
    }

    public function changecoverAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $id = (int) $this->_getParam('id');
        $id_item = (int) $this->_getParam('id_item');
        $id_module = (int) $this->_getParam('id_module');

        // reset all image_cover for item
        $where_1 = " id_module=$id_module AND id_item=$id_item ";
        Genius_Model_Global::update(TABLE_PREFIX . 'images_relations', array('image_cover' => ''), $where_1);

        // set new cover
        $where_2 = " id_module=$id_module AND id_item=$id_item AND id_image=$id ";
        Genius_Model_Global::update(TABLE_PREFIX . 'images_relations', array('image_cover' => 1), $where_2);

        echo 1;
    }

    public function getimagesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
		$env = APPLICATION_ENV;
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', $env, true);
		$con = mysqli_connect($config->db->params->host, $config->db->params->username, $config->db->params->password,$config->db->params->dbname);
        $aColumns =
                array(
                    'images.filename'
                    , 'images_relations.image_cover'
                    , 'images.filename'
                    , 'images_multilingual.legend'
                    , 'images_multilingual.alt'
        );

        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . " " . ($_GET['sSortDir_0'] === 'asc' ? 'ASC' : 'DESC') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        /*
         * Filtering
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($con, $_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($con, $_GET['sSearch_' . $i]) . "%' ";
            }
        }

        $id_module = (int) $_GET['id_module'];
        $id_item = (int) $_GET['id_item'];

        $sWhere .= (trim($sWhere) != "") ? " AND (images_relations.id_module='$id_module' AND images_relations.id_item='$id_item') " : " WHERE (images_relations.id_module='$id_module' AND images_relations.id_item='$id_item') ";
        $sWhere .= (trim($sWhere) != "") ? " AND ( images_multilingual.id_language=" . DEFAULT_LANG_ID . " ) " : " WHERE ( images_multilingual.id_language=" . DEFAULT_LANG_ID . " ) ";

        $sOrder .= (trim($sOrder) != "") ? " AND ( ORDER BY images_relations.order_item ASC ) " : " ORDER BY images_relations.order_item ASC ";

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
			SELECT 
				SQL_CALC_FOUND_ROWS images_relations.id	
				,'" . md5('images_relations.id') . "' as md5_id
				,images_relations.*
				,images.filename
				,images.format
				,images_multilingual.legend
				,images_multilingual.alt
			FROM " . TABLE_PREFIX . "images_relations images_relations
			LEFT JOIN " . TABLE_PREFIX . "images_multilingual images_multilingual ON images_multilingual.id_image = images_relations.id_image 
			LEFT JOIN " . TABLE_PREFIX . "images images ON images.id = images_relations.id_image
			$sWhere
			$sOrder
			$sLimit
		";

        $rResult = Genius_Model_Global::query($sQuery);
        $total = Genius_Model_Global::query("SELECT FOUND_ROWS()");
        $iFilteredTotal = $total[0]['FOUND_ROWS()'];
        $iTotal = $total[0]['FOUND_ROWS()'];

        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        if (!empty($rResult)) {
            foreach ($rResult as $k => $item) {
                $row = array();

                $image = UPLOAD_PATH . 'images/' . $_GET['folder'] . '/' . $item['filename'] . '-mini-' . $item['id_image'] . '.' . $item['format'];
                $img = '';
                if (file_exists($image)) {
                    //$image_thumb = Genius_Class_Timthumb::createThumb($image, 80, 60);
                    $image_thumb = UPLOAD_URL . 'images/' . $_GET['folder'] . '/' . $item['filename'] . '-mini-' . $item['id_image'] . '.' . $item['format'];
                    $img = '<img alt="' . $item['alt'] . '" src="' . $image_thumb . '">';
                }

                $row[0] = $img;

                $checked = ($item['image_cover'] == 1) ? 'checked="checked"' : '';
                $row[1] = '<input type="radio" class="cover acenter" name="cover" value="' . $item['id_image'] . '" ' . $checked . '>';

                $row[2] = $item['filename'];
                $row[3] = $item['legend'];
                $row[4] = $item['alt'];

                $id = $item['id_image'];
                $url_edit = '/admin/images/modify/do/edit/module_name/' . $_GET['folder'] . '/id_item/' . $item['id_item'] . '/id_image/' . $item['id_image'];

                $actions = '<ul class="table-controls acenter">';
                $actions .= '<li><a title="" class="tip" href="' . $url_edit . '" data-original-title="Edit item"><i class="icon-pencil"></i></a> </li>';
                $actions .= '<li><a title="" class="tip delete" id="' . $id . '" style="cursor: pointer" data-original-title="Delete item"><i class="ico-trash"></i></a> </li>';
                $actions .= '</ul>';

                $row[5] = $actions;

                $output['aaData'][] = $row;
            }
        }

        echo json_encode($output);
    }

    protected function getPhotosCouverture($id_module, $id_item) {
        $coverquery = "SELECT id_image FROM " . TABLE_PREFIX . "images_relations WHERE id_item='$id_item' AND id_module='$id_module' AND image_cover=1 LIMIT 1";
        $coverresult = Genius_Model_Global::query($coverquery);

        if (!empty($coverresult))
            return $coverresult[0]['id_image'];
        else
            return '';
    }

    protected function getMaxOrderitem($id_module, $id_item) {
        $maxorderitem = 0;
        if ($id_item) {
            $maxorderitemquery = "SELECT max(ir.order_item) AS max FROM " . TABLE_PREFIX . "images_relations ir WHERE ir.id_module='$id_module' AND ir.id_item='$id_item' LIMIT 1";
            $maxorderitem = Genius_Model_Global::query($maxorderitemquery);
            $maxorderitem = (!empty($maxorderitem[0]['max'])) ? ($maxorderitem[0]['max']) : 0;
        }

        return $maxorderitem;
    }

}