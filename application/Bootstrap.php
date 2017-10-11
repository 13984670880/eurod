<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $_docRoot;

    protected function _initPath()
    {
        $this->_docRoot = realpath(APPLICATION_PATH . '/../');
        Zend_Registry::set('docRoot', $this->_docRoot);
    }

    protected function _initLoaderResource()
    {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => $this->_docRoot . '/application',
            'namespace' => 'Genius'
        ));
        $resourceLoader->addResourceTypes(array(
            'model' => array(
                'namespace' => 'Model',
                'path' => 'models'
            )
        ));
        $resourceLoader->addResourceTypes(array(
            'forms' => array(
                'namespace' => 'Form',
                'path' => 'forms'
            )
        ));
        $resourceLoader->addResourceTypes(array(
            'class' => array(
                'namespace' => 'Class',
                'path' => 'classes'
            )
        ));
        $resourceLoader->addResourceTypes(array(
            'plugin' => array(
                'namespace' => 'Plugin',
                'path' => 'plugins'
            )
        ));
    }

    /* protected function _initLog() {
      $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/logs/error.log');
      return new Zend_Log($writer);
      } */

    protected function _initView()
    {
        $view = new Zend_View();
        $view->addHelperPath(
            'ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper'
        );

        $view->addHelperPath(
            APPLICATION_PATH . "/../library/Langs/View/Helper", "Langs_View_Helper"
        );

        return $view;
    }

    protected function _initSetupBaseUrl()
    {
        $this->bootstrap('frontcontroller');
        $controller = Zend_Controller_Front::getInstance();

        $lang = Genius_Class_Utils::getCurrentLang();
        if ($lang != "fr") {
            $controller->setBaseUrl('/' . $lang);
        }

        global $baseUrl;
        $baseUrl = $controller->getBaseUrl();
    }

    public function _initRoutes()
    {
        $this->bootstrap('FrontController');
        $this->_frontController = $this->getResource('FrontController');
        $router = $this->_frontController->getRouter();
        $controller = Zend_Controller_Front::getInstance();

        $lang = Genius_Class_Utils::getCurrentLang();
        $langRoute = new Zend_Controller_Router_Route(
            ':lang/', array(
            'lang' => $lang
        ), array(
                'lang' => '[a-z]{0,2}'
            )
        );

        $defaultRoute = new Zend_Controller_Router_Route(
            ':controller/:action/*', array(
                'module' => 'default',
                'controller' => 'index',
                'action' => 'index'
            )
        );
        $defaultRoute = $langRoute->chain($defaultRoute);

        $adminRoute = new Zend_Controller_Router_Route(
            'admin/:controller/:action/*', array(
                'module' => 'admin',
                'controller' => 'index',
                'action' => 'index'
            )
        );


        $product_route = new Zend_Controller_Router_Route_Regex(
            '([-\w]+)/([-\w]+)/([-\w]+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'fiche'
        , 'action' => 'index'
        ), array(
            1 => 'group',
            2 => 'marque',
            3 => 'modele',
            4 => 'nom_product',
            5 => 'id_product'
        ), '%s/%s/%s/%s-%d.html'
        );
        $societe_route = new Zend_Controller_Router_Route_Regex(
            'societe', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'societe'
        ), array(), 'societe'
        );
        $chariot_mobile_route = new Zend_Controller_Router_Route_Regex(
            'chariot-mobile-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'fiche'
        , 'action' => 'index'
        ), array(
            1 => 'id_product'
        ), 'chariot-mobile-%d.html'
        );
        $route_tracabilite = new Zend_Controller_Router_Route_Regex(
            'tracabilite.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'tracabilite'
        ), array(), 'tracabilite.html'
        );
        $route_micro = new Zend_Controller_Router_Route_Regex(
            'micro.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'micro'
        ), array(), 'micro.html'
        );
        $route_imprimantes = new Zend_Controller_Router_Route_Regex(
            'imprimantes.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'imprimantes'
        ), array(), 'imprimantes.html'
        );
        $route_reparationservices = new Zend_Controller_Router_Route_Regex(
            'reparationservices.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'reparationservices'
        ), array(), 'reparationservices.html'
        );
        $route_partenaires = new Zend_Controller_Router_Route_Regex(
            'marque.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'marque'
        ), array(), 'partenaires.html'
        );
        $route_extranet = new Zend_Controller_Router_Route_Regex(
            'extranet', array(
            'module' => 'default'
        , 'controller' => 'login'
        , 'action' => 'index'
        ), array(), 'extranet'
        );
        $group_route_imprimantes = new Zend_Controller_Router_Route_Regex(
            'imprimantes/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'p1'
        ), array(
            1 => 'group',
            2 => 'id_category_group'
        ), 'imprimantes/%s-%d'
        );
        $group_route_micro = new Zend_Controller_Router_Route_Regex(
            'micro/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'm1'
        ), array(
            1 => 'group',
            2 => 'id_category_group'
        ), 'micro/%s-%d'
        );
        $group_route_tracabilite = new Zend_Controller_Router_Route_Regex(
            'tracabilite/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'v1'
        ), array(
            1 => 'group',
            2 => 'id_category_group'
        ), 'tracabilite/%s-%d'
        );


        $group_route_imprimantes_marque = new Zend_Controller_Router_Route_Regex(
            'imprimantes/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'p1'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'imprimantes/%s-%d/%s-%d'
        );
        $group_route_micro_marque = new Zend_Controller_Router_Route_Regex(
            'micro/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'm1'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'micro/%s-%d/%s-%d'
        );
        $group_route_tracabilite_marque = new Zend_Controller_Router_Route_Regex(
            'tracabilite/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'v1'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'tracabilite/%s-%d/%s-%d'
        );

        $group_route_articlereparation = new Zend_Controller_Router_Route_Regex(
            'reparation/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlereparation'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'reparation/%s-%d/%s-%d'
        );
        $group_route_articlereparation_produit = new Zend_Controller_Router_Route_Regex(
            'reparation/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlereparation'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'reparation/%s-%d/%s-%d/%s-%d'
        );
        $group_route_articlevente = new Zend_Controller_Router_Route_Regex(
            'vente/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlevente'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'vente/%s-%d/%s-%d'
        );
        $group_route_articlevente_produit = new Zend_Controller_Router_Route_Regex(
            'vente/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlevente'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'vente/%s-%d/%s-%d/%s-%d'
        );
        $group_route_articleechange = new Zend_Controller_Router_Route_Regex(
            'echange/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articleechange'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'echange/%s-%d/%s-%d'
        );
        $group_route_articleechange_produit = new Zend_Controller_Router_Route_Regex(
            'echange/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articleechange'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'echange/%s-%d/%s-%d/%s-%d'
        );
        $group_route_articlemaintenance = new Zend_Controller_Router_Route_Regex(
            'maintenance/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlemaintenance'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'maintenance/%s-%d/%s-%d'
        );
        $group_route_articlemaintenance_produit = new Zend_Controller_Router_Route_Regex(
            'maintenance/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlemaintenance'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'maintenance/%s-%d/%s-%d/%s-%d'
        );
        $group_route_articlelocation = new Zend_Controller_Router_Route_Regex(
            'location/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlelocation'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'location/%s-%d/%s-%d'
        );
        $group_route_articlelocation_produit = new Zend_Controller_Router_Route_Regex(
            'location/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlelocation'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'location/%s-%d/%s-%d/%s-%d'
        );
        $group_route_articleaudit = new Zend_Controller_Router_Route_Regex(
            'audit/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articleaudit'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'audit/%s-%d/%s-%d'
        );
        $group_route_articleaudit_produit = new Zend_Controller_Router_Route_Regex(
            'audit/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articleaudit'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'audit/%s-%d/%s-%d/%s-%d'
        );
        $group_route_articlereprise = new Zend_Controller_Router_Route_Regex(
            'reprise/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlereprise'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category'
        ), 'reprise/%s-%d/%s-%d'
        );
        $group_route_articlereprise_produit = new Zend_Controller_Router_Route_Regex(
            'reprise/([-\w]+)-(\d+)/([-\w]+)-(\d+)/([-\w]+)-(\d+)\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'articlereprise'
        ), array(
            1 => 'group',
            2 => 'id_category_group',
            3 => 'marque',
            4 => 'id_category',
            5 => 'nom_produit',
            6 => 'id_product'
        ), 'reprise/%s-%d/%s-%d/%s-%d'
        );
        $route_reparation = new Zend_Controller_Router_Route_Regex(
            'reparation', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'reparation'
        ), array(), 'reparation'
        );
        $route_vente = new Zend_Controller_Router_Route_Regex(
            'vente', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'vente'
        ), array(), 'vente'
        );
        $route_echange = new Zend_Controller_Router_Route_Regex(
            'echange', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'echange'
        ), array(), 'echange'
        );
        $route_maintenance = new Zend_Controller_Router_Route_Regex(
            'maintenance', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'maintenance'
        ), array(), 'maintenance'
        );
        $route_location = new Zend_Controller_Router_Route_Regex(
            'location', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'location'
        ), array(), 'location'
        );
        $route_audit = new Zend_Controller_Router_Route_Regex(
            'audit', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'audit'
        ), array(), 'audit'
        );
        $route_reprise = new Zend_Controller_Router_Route_Regex(
            'reprise', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'reprise'
        ), array(), 'reprise'
        );
        $route_smartprint = new Zend_Controller_Router_Route_Regex(
            'smartprint', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'smartprint'
        ), array(), 'smartprint'
        );
        $route_clichemicro = new Zend_Controller_Router_Route_Regex(
            '([-\w]+)-micro\.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'clichemicro'
        ), array(
            1 => 'prestation',
        ), '%s-micro.html'
        );

        $comparaison_route = new Zend_Controller_Router_Route_Regex(
            '(\d+)-comparaison.html', array(
            'module' => 'default'
        , 'controller' => 'comparaison'
        , 'action' => 'index'
        ), array(
            1 => 'id_category_group'
        ), '%d-comparaison.html'
        );
        $route_comparaison_mail = new Zend_Controller_Router_Route_Regex(
            'link', array(
            'module' => 'default'
        , 'controller' => 'comparaison'
        , 'action' => 'link'
        ), array(), 'link'
        );
        $route_confirmation_devis = new Zend_Controller_Router_Route_Regex(
            'confirmation-devis.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'confirmationdevis'
        ), array(), 'confirmation-devis.html'
        );

        $route_confirmation_reparation = new Zend_Controller_Router_Route_Regex(
            'confirmation-reparation.html', array(
            'module' => 'default'
        , 'controller' => 'page'
        , 'action' => 'confirmationreparation'
        ), array(), 'confirmation-reparation.html'
        );



        $route_make_filtre_article = new Zend_Controller_Router_Route_Regex(
            'filtre/apply', array(
            'module' => 'default'
        , 'controller' => 'filtre'
        , 'action' => 'makefiltre'
        ), array(), 'filtreApply'
        );

        $route_make_filtre_form = new Zend_Controller_Router_Route_Regex(
            'filtre/form', array(
            'module' => 'default'
        , 'controller' => 'filtre'
        , 'action' => 'makefiltreform'
        ), array(), 'filtreform'
        );


        $route_delete_filtre_article = new Zend_Controller_Router_Route_Regex(
            'filtre/delete', array(
            'module' => 'default'
        , 'controller' => 'filtre'
        , 'action' => 'deletefiltre'
        ), array(), 'filtreDelete'
        );

        $route_home = new Zend_Controller_Router_Route_Regex(
            'reparation', array(
            'module' => 'gv'
        , 'controller' => 'home'
        , 'action' => 'index'
        ), array(), 'home'
        );

        $search_auto = new Zend_Controller_Router_Route_Regex(
            'search/auto', array(
            'module' => 'default'
        , 'controller' => 'searching'
        , 'action' => 'autocomplete'
        ), array(), 'searchauto'
        );
        $router->addRoute('searchauto', $search_auto);
        $router->addRoute('routeMakeHome', $route_home);
        $router->addRoute('routeDeleteFiltreArticle', $route_delete_filtre_article);
        $router->addRoute('routemakeFiltreForm', $route_make_filtre_form);
        $router->addRoute('routeMakeFiltreArticle', $route_make_filtre_article);

        $router->addRoute('langRoute', $langRoute);
        $router->addRoute('defaultRoute', $defaultRoute);
        $router->addRoute('adminRoute', $adminRoute);
        $router->addRoute('chariot_mobile_route', $chariot_mobile_route);
        $router->addRoute('comparaison_route', $comparaison_route);
        $router->addRoute('route_comparaison_mail', $route_comparaison_mail);
        $router->addRoute('product_route', $product_route);
        $router->addRoute('route_tracabilite', $route_tracabilite);
        $router->addRoute('route_imprimantes', $route_imprimantes);
        $router->addRoute('route_micro', $route_micro);
        $router->addRoute('route_reparationservices', $route_reparationservices);
        $router->addRoute('route_partenaires', $route_partenaires);
        $router->addRoute('group_route_imprimantes', $group_route_imprimantes);
        $router->addRoute('group_route_micro', $group_route_micro);
        $router->addRoute('group_route_tracabilite', $group_route_tracabilite);
        $router->addRoute('group_route_imprimantes_marque', $group_route_imprimantes_marque);
        $router->addRoute('group_route_micro_marque', $group_route_micro_marque);
        $router->addRoute('group_route_tracabilite_marque', $group_route_tracabilite_marque);
        $router->addRoute('group_route_articlereparation', $group_route_articlereparation);
        $router->addRoute('group_route_articlereparation_produit', $group_route_articlereparation_produit);
        $router->addRoute('group_route_articlevente', $group_route_articlevente);
        $router->addRoute('group_route_articlevente', $group_route_articlevente);
        $router->addRoute('group_route_articlevente_produit', $group_route_articlevente_produit);
        $router->addRoute('group_route_articleechange', $group_route_articleechange);
        $router->addRoute('group_route_articleechange_produit', $group_route_articleechange_produit);
        $router->addRoute('group_route_articlemaintenance', $group_route_articlemaintenance);
        $router->addRoute('group_route_articlemaintenance_produit', $group_route_articlemaintenance_produit);
        $router->addRoute('group_route_articlelocation', $group_route_articlelocation);
        $router->addRoute('group_route_articlelocation_produit', $group_route_articlelocation_produit);
        $router->addRoute('group_route_articleaudit', $group_route_articleaudit);
        $router->addRoute('group_route_articleaudit_produit', $group_route_articleaudit_produit);
        $router->addRoute('group_route_articlereprise', $group_route_articlereprise);
        $router->addRoute('group_route_articlereprise_produit', $group_route_articlereprise_produit);
        $router->addRoute('route_reparation', $route_reparation);
        $router->addRoute('route_vente', $route_vente);
        $router->addRoute('route_echange', $route_echange);
        $router->addRoute('route_maintenance', $route_maintenance);
        $router->addRoute('route_location', $route_location);
        $router->addRoute('route_audit', $route_audit);
        $router->addRoute('route_reprise', $route_reprise);
        $router->addRoute('route_smartprint', $route_smartprint);
        $router->addRoute('route_clichemicro', $route_clichemicro);
        $router->addRoute('route_societe', $societe_route);
        $router->addRoute('route_extranet', $route_extranet);
        $router->addRoute('route_confirmation_devis', $route_confirmation_devis);
        $router->addRoute('route_confirmation_reparation', $route_confirmation_reparation);
        $this->_frontController->registerPlugin(new Genius_Controller_Plugin_Language());
    }

    protected function _initAutoload()
    {
        $controller = Zend_Controller_Front::getInstance();

        // load hooks
        $controller->registerPlugin(new Genius_Plugin_Hooks());
    }

}
