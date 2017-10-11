<?php

/**
 * Common, application-wide methods
 * 
 */
class Genius_Plugin_Common extends Zend_Controller_Plugin_Abstract
{
	/**
	 * generate random unique string
	 */
	static public function getRandomString()
	{
		return sha1(uniqid('', true));
	}

	/**
	 * generate random number
	 */
	static public function getRandomNum($min = 100000000, $max = 999999999)
	{
		return (int) mt_rand($min, $max) + time();
	}

	/**
	 * get full base url (http://www.example.com/socialstrap)
	 */
	static public function getFullBaseUrl($include_base = true)
	{
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

	
	/**
	 * get safe uri (http://www.example.com/socialstrap/something/something)
	 */
	static public function getSafeUri()
	{
		$front = Zend_Controller_Front::getInstance();
		$request = $front->getRequest();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		
		$url = Genius_Plugin_Common::getFullBaseUrl().'/'.$controller.'/'.$action.'/';
		
		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		foreach ($params as $key => $val){
			if ($key === 'module' || $key === 'controller' || $key === 'action' || ! is_string($val) || ! is_string($val)) continue;
			$url .= htmlentities(strip_tags($key)) . '/' . htmlentities(strip_tags($val)) . '/';
		}

		return $url;
	}

	/**
	 * Returns mysql datetime for current time
	 */
	public static function now()
	{
		return date('Y-m-d H:i:s', time());
	}

	/**
	 * strip tags filter
	 */
	static function stripTags($content)
	{
		/*
		 * allow some tags and attributtes
		 * 
		$filter = new Zend_Filter_StripTags(array(
			'allowTags' => array(
				'a',
				'img',
				'pre'
			),
			'allowAttribs' => array(
				'src',
				'href'
			)
		));
		*/
		
		// strip everything
		$filter = new Zend_Filter_StripTags();
			
		return $filter->filter($content);
	}

	/**
	 * get location from ip address
	 */
	static function getLocatinFromIp()
	{
		$location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
 		return $location;
	}
}