<?php
/**
 * Open Graph Tags add-on
 */

$this->attach('view_head', 10, function($view) {
	
	$front      = Zend_Controller_Front::getInstance();
	$request    = $front->getRequest();
	$controller = $request->getControllerName();
	$action     = $request->getActionName();
		
	$url   = Genius_Plugin_Common::getFullBaseUrl();
	
	echo '<meta property="og:type" content="website"/>';
	echo '<meta property="og:url" content="'.htmlentities($url).'"/>';	
});