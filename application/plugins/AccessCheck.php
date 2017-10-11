<?php

class Genius_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract
{  
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$module   = $request->getModuleName();
		$resource = $request->getControllerName();
		$action   = $request->getActionName();

		$storage = new Zend_Auth_Storage_Session('authSession', 'admin');
		
		if( ($resource!="index") && ($resource!="resetpassword") && ($storage->isEmpty()))
		{
			if($module=='admin'):
				$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
				$redirector->gotoUrl('/admin')
				           ->redirectAndExit();
			endif;
		}
	}
}