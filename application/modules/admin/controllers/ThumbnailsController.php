<?php

class Admin_ThumbnailsController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->addActionContext('getsize', 'html');
		$ajaxContext->initContext();
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Thumbnails');
		//ALTER TABLE `".$table_prefix."posts_to_bookmark` ADD `ping_status` INT( 1 ) NOT NULL AFTER `onlywire_status`
		//ALTER TABLE `".$table_prefix."posts_to_bookmark` ADD `ping_status` INT( 1 ) NOT NULL FIRST

		global $image_thumbnail_params;

		$sess = new Zend_Session_Namespace();
		$sess->tab_active = 'create';

		if($_POST){ 
			switch ($_POST['type']) {
			 	case 'create':
			 			$name = $this->view->escape($_POST['Thumbnails']['name']);
			 			$valid = true;
 						if(trim($name)==""){ 
 			        		$sess->thumbs_error = '
 					            <div style="margin-top: 16px;" class="alert alert-error">
 			                        <button data-dismiss="alert" class="close" type="button">×</button>
 			                        Tous les champs sont obligatoires.
 			                    </div>';  	 
 			                $valid = false;
 						}

 						if($valid){ 
 							$field_name = 'size_'.strtolower($name);
 							$sql = "ALTER TABLE genius_images ADD $field_name varchar(25) NOT NULL"; 
 							$return = Genius_Model_Global::execute($sql);
 							
 							if($return){ 
 								$sql = "UPDATE ".TABLE_PREFIX."images SET ".$field_name."='0,0,0,0' WHERE 1"; 
 								Genius_Model_Global::execute($sql);
 							}

			        		$sess->thumbs_error = '
					            <div style="margin-top: 16px;" class="alert alert-success">
			                        <button data-dismiss="alert" class="close" type="button">×</button>
			                        Taille ajoutée avec succès.
			                    </div>'; 
			                $sess->tab_active = 'create';
 						}
			 		break;

			 	case 'generate':
						$module = $this->view->escape($_POST['Thumbnails']['module']);
						$size   = $this->view->escape($_POST['Thumbnails']['size']);

						$crop_size   = $image_thumbnail_params[$module][$size];
						$crop_width  = $crop_size['size_x'];
						$crop_height = $crop_size['size_y'];
						
						//generate thumbnails in folder $module and size $size
						$dir = UPLOAD_PATH.'images/'.$module.'/';
						$scan = scandir($dir);	
						
						for ($i = 0; $i<count($scan); $i++) {
							if ($scan[$i] != '.' && $scan[$i] != '..') {
								if (strpos($scan[$i], '-source-') !== false) {
									$filename = $dir . $scan[$i];
									if(file_exists($filename)){ 
										$newfilename = str_replace('-source-', '-'.$size.'-', $filename);
										list($w, $h) = getimagesize($filename);		
										$extension = Genius_Class_Utils::findexts($filename);

										$id = explode('-', $filename);
										$id = (int)$id[2];

										$width  = $crop_width;
										$height = $crop_height;

										//Original image don't exist
										if ((!$w) || (!$h)) { 
											$GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; 
							        		$sess->thumbs_error = '
									            <div style="margin-top: 16px;" class="alert alert-success">
							                        <button data-dismiss="alert" class="close" type="button">×</button>
							                        Image couldn\'t be resized because it wasn\'t a valid image.
							                    </div>';

											return false; 
										}

										//No resizing needed
										if (($w == $width) && ($h == $height)){} 

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
										
										if ($extension == 'jpg') {
											$img_r = imagecreateFROMjpeg($filename);
										}
										elseif ($extension == 'png') {
											$img_r = imagecreateFROMpng($filename);
										}
										elseif ($extension == 'gif') {
											$img_r = imagecreateFROMgif($filename);
										}
										elseif ($extension == 'bpm') {
											$img_r = imagecreateFROMwbmp($filename);
										}
										else {
											$img_r = imagecreateFROMjpeg($filename);
										}
										
										$image2 = imagecreatetruecolor ($new_w, $new_h);
										imagecopyresampled($image2,$img_r, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

									    //check to see if cropping needs to happen
										if (($new_h != $height) || ($new_w != $width)) {

											$image3 = imagecreatetruecolor ($width, $height);
									        if ($new_h > $height) { //crop vertically
									        	$extra = $new_h - $height;
									            $x = 0; //source x
									            $y = round($extra / 2); //source y
									            imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
									        } else {
									        	$extra = $new_w - $width;
									            $x = round($extra / 2); //source x
									            $y = 0; //source y
									            imagecopyresampled($image3,$image2, 0, 0, $x, $y, $width, $height, $width, $height);
									        }
									        imagedestroy($image2);
									        imagejpeg($image3, $newfilename);

									    } else {
									    	imagejpeg($image2, $newfilename);
									    }

									    //Update coordinates
									    $field_name = 'size_'.$size;
									    $sql = "UPDATE ".TABLE_PREFIX."images SET ".$field_name."='0,0,0,0' WHERE id='$id'"; 
									    Genius_Model_Global::execute($sql);
									}
								} // end if strpos
							} // end if scan
						} //end for

		        		$sess->thumbs_error = '
				            <div style="margin-top: 16px;" class="alert alert-success">
		                        <button data-dismiss="alert" class="close" type="button">×</button>
		                        Miniature(s) générée(s) avec succès.
		                    </div>'; 

						$sess->tab_active = 'generate';
			 		break;
			 	
			 	default:
			 		# code...
			 		break;
			 } 
		}
	}

	public function getsizeAction()
	{ 
		$this->_helper->layout->disableLayout(); 
	   	$this->_helper->viewRenderer->setNoRender();

	   	global $image_thumbnail_params;
		
		$module = $this->_getParam('m');
		$return = '<option value="">'.$this->view->translate("Choisissez une taille").'</option>';
		
		if(isset($image_thumbnail_params[$module])){ 
			foreach ($image_thumbnail_params[$module] as $key => $value) {
				$return .= '<option value="'.$key.'">'.$key.'</option>';	
			}		 
		}  		 

		echo $return;
	}

}