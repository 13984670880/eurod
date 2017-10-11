<?php

class Genius_Plugin_MaintenanceMode extends Zend_Controller_Plugin_Abstract
{  
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		global $siteconfig;

		// if we are on maintenance mode
		if($siteconfig->mode_maintenance==1){ 
			// mode maintenance default module only
			$module = strtolower($request->getModuleName());
			if($module=="default"){
				$request->setActionName('index');
				$request->setModuleName('default');
				$request->setControllerName('maintenance');						 				
			}	 
		}
	}

}