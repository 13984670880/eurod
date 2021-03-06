<?php

class Genius_Plugin_LayoutPicker extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = strtolower($request->getModuleName());
        $layout = Zend_Layout::getMvcInstance();
                        
        if ($layout->getMvcEnabled())                {
            $layout->setLayoutPath(APPLICATION_PATH . '/layouts/scripts/' . $module);
            $layout->setLayout($module);
        }
        
    }
}