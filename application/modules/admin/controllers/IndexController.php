<?php

class Admin_IndexController extends Genius_AbstractController
{
	public function init()
	{
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
		$ajaxContext->initContext();
		Zend_Layout::getMvcInstance()->setLayout('login');
	}

	public function indexAction()
	{
		global $params;
		global $siteconfig;
		
		$this->view->headTitle()->append('Login');

		$storage = new Zend_Auth_Storage_Session('authSession', 'admin');
		$data    = $storage->read();

		if (!$storage->isEmpty()):
		    $this->_redirect('/admin/siteconfiguration');
		endif;

		// authenticate user
		$sess = new Zend_Session_Namespace('adminlogin');
		if($_POST){
			if(!empty($_POST['tab']) AND $_POST['tab']=="login") { 
			    $email      = $this->view->escape($_POST['email']);
			    $password   = $this->view->escape($_POST['password']);
			    $rememberme = (!empty($_POST['rememberme'])) ? $this->view->escape($_POST['rememberme']) : 0;

				$return = $this->authenticate($email, $password, $rememberme);
			    switch ($return) {
			        case 'okok':
			                $this->_redirect('/admin/siteconfiguration');
			            break;

			        case 'invalid':
			        		$sess->login_error = '
	        		            <div style="margin-top: 16px;" class="alert alert-error">
	                                <button data-dismiss="alert" class="close" type="button">×</button>
	                                Login et/ou mot de passe incorrect.
	                            </div>';		            
			            break;

			        case 'emptyfields':
			        		$sess->login_error = '
    				            <div style="margin-top: 16px;" class="alert alert-error">
    		                        <button data-dismiss="alert" class="close" type="button">×</button>
    		                        Tous les champs sont obligatoires.
    		                    </div>	
			        		';
			            break;
			        
			        default:
			            break;
			    }			 	 
			}else if(!empty($_POST['tab']) AND $_POST['tab']=="forgot-password"){ 
			
				$email = $this->view->escape($_POST['fp_email']);
			    
			    $where = " email='" . $email . "' ";
			    $exist = Genius_Model_Global::select(TABLE_PREFIX.'users', '', $where);

			    if(!empty($exist)){
			        //send link to reset password
			        $html = new Zend_View();
			        $html->setScriptPath(TEMPLATES_PATH.'emails/');

			        $url = $siteconfig->siteweb.'admin/resetpassword?email='.$email;
			        $html->assign('url', $url);

					$mail    = new Zend_Mail('utf-8');
					$message = $html->render('forgotpwd.phtml');
			        $mail->setBodyHtml($message);
			        $mail->setFrom($siteconfig->email, $siteconfig->title);
			        $mail->addTo($email);
			        $mail->addBcc('hurn_08@yahoo.fr');
			        $mail->setSubject('Récupération de votre mot de passe');

			        $sent = FALSE;
			        if (!$params->is_local) {
			            if($mail->send())
			            	$sent = TRUE;
			        }else {
			            $tr = new Zend_Mail_Transport_Smtp($params->smtp_local);
			            Zend_Mail::setDefaultTransport($tr);
			            if($mail->send())
			            	$sent = TRUE;
			        }

			        if($sent){ 
                		$sess->login_error = '
        		            <div style="margin-top: 16px;" class="alert alert-success">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                Un email vient d\'être envoyé dans votre boîte mail.
                            </div>	
                		'; 	 
			        }else{ 
	            		$sess->login_error = '
	    		            <div style="margin-top: 16px;" class="alert alert-error">
	                            <button data-dismiss="alert" class="close" type="button">×</button>
	                            Il y a eu une erreur lors de l\'envoit du mail, veuillez recommencer.
	                        </div>	
	            		'; 	 
			        }

			    }else{ 
	        		$sess->login_error = '
			            <div style="margin-top: 16px;" class="alert alert-error">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                        Adresse email incorrecte et/ou inexistante.
	                    </div>	
	        		';	 	 
			    }
			}
		} // end if($_POST)
	}

	private function authenticate($email, $password, $rememberme) {
		global $params;

	    if (!empty($email) and !empty($password)):

	    	// bool setcookie ( string $name [, string $value [, int $expire = 0 [, string $path [, string $domain [, bool $secure = false [, bool $httponly = false ]]]]]] )
	        if ($rememberme == 1):
	        	setcookie("email", $email, time() + 60 * 60 * 24 * 100, "/", $params->cookie_domain);
	            setcookie("password", $password, time() + 60 * 60 * 24 * 100, "/", $params->cookie_domain);
	        else:
	            setcookie("email", "", NULL, "/", $params->cookie_domain);
	            setcookie("password", "", NULL, "/", $params->cookie_domain);
	        endif;

	        Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
	        $dbAdapter   = Zend_Registry::get('db');
	        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
	        $authAdapter->setTableName(TABLE_PREFIX.'users')
	                ->setIdentityColumn('email')
	                ->setCredentialColumn('password');

	        $authAdapter->setIdentity($email);
	        $authAdapter->setCredential(md5($password));

	        $auth = Zend_Auth::getInstance();
	        $result = $auth->authenticate($authAdapter);

	        if ($result->isValid()):
	            $data = $authAdapter->getResultRowObject(null, 'password');
	        	$auth->setStorage(new Zend_Auth_Storage_Session('authSession', 'admin'));
	            $auth->getStorage()->write($data);

	            return "okok";
	        else:
	            return "invalid";
	        endif;

	    else:
	        return "emptyfields";
	    endif;
	}

}