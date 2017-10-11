<?php

class Admin_ResetpasswordController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->initContext();
		Zend_Layout::getMvcInstance()->setLayout('login');
	}

	public function indexAction()
	{ 
		$this->view->headTitle()->append('Reset password'); 	
		$email = $this->view->email = Genius_Class_Utils::idx($_GET, 'email', '');

		$sess = new Zend_Session_Namespace('adminlogin');
		if($_POST){ 
			$email     = $this->view->escape($_POST['email']);
			$password  = $this->view->escape($_POST['password']);
			$cpassword = $this->view->escape($_POST['cpassword']); 

			if(trim($password)=="" or trim($cpassword)==""){ 
        		$sess->login_error = '
		            <div style="margin-top: 16px;" class="alert alert-error">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        Les 2 champs sont obligatoires.
                    </div>';  	 
			}
			elseif($password!=$cpassword){ 
        		$sess->login_error = '
		            <div style="margin-top: 16px;" class="alert alert-error">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        Les 2 mots de passe ne sont pas identiques.
                    </div>'; 	 
			}else{ 
				$return = Genius_Model_Global::update(TABLE_PREFIX."users", array('password'=>md5($password)), "email='$email'");
				if($return){ 
	        		$sess->login_error = '
			            <div style="margin-top: 16px;" class="alert alert-succes">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                        Votre mot de passe a été changé avec succès.
	                    </div>'; 	 
				}else{ 
	        		$sess->login_error = '
			            <div style="margin-top: 16px;" class="alert alert-error">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                        Il y a eu une error lors de la modification, veuillez recommencer.
	                    </div>';  	 
				} 
			} // endif if($password!=$cpassword)
		} // endif if($_POST)
	} 
}