<?php
class Genius_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract{
   
    private function initTranslator($locale){
        $translate = Zend_Registry::get('Zend_Translate');
        $translate->setLocale($locale);
    }

    public function routeStartup(Zend_Controller_Request_Abstract $request) {
        $locale = new Zend_Locale(DEFAULT_LANG_ABBR); //default locale

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $routes = $router->getRoutes();
        $defaultsRoute = array();
        $defaultsRoute[] = 'langRoute';
        $defaultsRoute[] = 'defaultRoute';
        $defaultsRoute[] = 'adminRoute';
       
        $uri = $request->getRequestUri(); 
        $uri = ltrim($uri, '/');
        $uri = explode('/', $uri);

        $final_uri = '/';
        if(!empty($uri)){
            foreach ($uri as $k => $item) {
                $final_uri .= $item.'/';
            }

            $final_uri = str_replace('//', '/', $final_uri);
        }

        $url_with_lang = preg_match('/\/([a-z]{2})([\/].*)/', $final_uri,$matches);    

        if ($url_with_lang){ //if locale is found in request
            $lang = $matches[1]; //obtain locale
            /* @var $locale Zend_Locale */
            switch ($lang){
                case 'en':
                    $locale->setLocale('en');
                    break;
				default :	
					$locale->setLocale('fr');
					break;
            }
            Zend_Registry::set('Zend_Locale',$locale);
            $this->initTranslator($locale);
            $router = Zend_Controller_Front::getInstance()->getRouter();
            /* @var $router Zend_Controller_Router_Rewrite */
            $langRoute = new Zend_Controller_Router_Route(
                ':lang', 
                array(
                    'lang' => $lang
                ),
                array(
                    'lang' => '[a-z]{0,2}'
                )
            );

            foreach ($routes as $name=>$route){
                if ($name != 'defaultRoute'){
                    /* @var $lang Zend_Controller_Router_Route */
                    $chain = new Zend_Controller_Router_Route_Chain();
                    
                    if(!in_array($name, $defaultsRoute)){
                        $router->addRoute(
                            $name . 'lang',
                            $chain->chain($langRoute)->chain($route)
                        );
                    }else{
                        $router->addRoute($name,$chain);
                    }
                }
            }
            
            $router->route($request);
        } else {

            Zend_Registry::set('Zend_Locale',$locale);
            $this->initTranslator($locale);
            /* @var $router Zend_Controller_Router_Rewrite */
            $langRoute = new Zend_Controller_Router_Route(
                ':lang',
                array(
                    'lang' => DEFAULT_LANG_ABBR
                ),
                array(
                    'lang' => '[a-z]{0,2}'
                )
            );

            foreach ($routes as $name=>$route){
                if ($name != 'defaultRoute'){
                    /* @var $route Zend_Controller_Router_Route */
                    //$router->removeRoute($name);
                    /* @var $lang Zend_Controller_Router_Route */
                    $chain = new Zend_Controller_Router_Route_Chain();
                    if(!in_array($name, $defaultsRoute)){
                        $router->addRoute(
                            $name . 'lang',
                            $chain->chain($langRoute)->chain($route)
                        );
                    }else{
                        $router->addRoute($name,$chain);
                    }
                }
            }

            $router->route($request);
        }

        parent::routeStartup($request);
    }
}