<?php
class LangController extends Zend_Controller_Action {

	function switchAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iso_code = $this->_getParam('iso_code');
		$url = $this->_getParam('url');
		
		$url_exp = explode('/',$url);
		if ($url_exp[1] == 'en'){
			$url_return = substr($url,3);
		}elseif($url_exp[1] == 'po'){
			$url_return = '/po'.$url;
		}else{
			$url_return = '/en'.$url;
		}
		if ($iso_code == 'fr'){
			$translate_lang_id = 1;
		}elseif($iso_code == 'en'){
			$translate_lang_id = 2;
		}else{
			$translate_lang_id = 3;
		}
		
		$sess = new Zend_Session_Namespace();
		$sess->translate_lang_id = $translate_lang_id;
        $sess->locale = $iso_code;
		
		if ($iso_code == 'en' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
			$domain = 'http://'.$_SERVER['HTTP_HOST'];
		}else{
			$domain = 'http://'.$_SERVER['HTTP_HOST'];
		}
		echo $url_return;
	}
}