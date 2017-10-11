<?php

class ComparaisonController extends Genius_AbstractController {

    public function indexAction() {
        $this->view->headTitle()->append('Comparer les produits');
        $this->view->headMeta()->appendName('description', "Comparer les produits");
        $this->view->headMeta()->appendName('keyword', "Comparer les produits");
        $this->view->headLink()->appendStylesheet(THEMES_DEFAULT_URL . 'css/index-comparaison.css?v=1');
        $this->view->headLink()->appendStylesheet(THEMES_DEFAULT_URL . 'css/popup.css?v=1');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/popup.js', 'text/javascript');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/comparaison.js', 'text/javascript');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/html2canvas.min.js', 'text/javascript');
        //$this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/jquery.1.8.3.min.js', 'text/javascript');
        $this->view->id_category_group = $this->_getParam('id_category_group');
    }

    function compareAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $str = $this->_getParam('t');
        $mail = $this->_getParam('mail');
        $pos = strpos($str, ',');
        $url = '?';
        if ($pos !== false):
            $str = explode(',', $str);
            foreach ($str as $key => $value):
                $key+=1;
                if ($key < count($str)):
                    $url .= 'a' . $key . '=' . $value . '&';
                else:
                    $url .= 'a' . $key . '=' . $value;
                endif;
            endforeach;
        else:
            $url .= 'a1=' . $str;
        endif;
        $link = BASE_URL . '/link' . $url;
        $link = '<a href='.$link.' style="color:blue;border-bottom:2px solid blue;text-decoration:none">'.$link.'</a>';
        $validatorEmail = new Zend_Validate_EmailAddress();
        if ($validatorEmail->isValid($mail)):
            global $siteconfig;
            $email_config = $siteconfig->email;
            //$email_config = "modeste.mbolatiana@gmail.com";
            $html = new Zend_View();
            $html->setScriptPath(APPLICATION_PATH . '/modules/default/views/scripts/emails/');
            $template_mail = $html->render("comparaison.phtml");
            $message = "<b>Voici le lien des produits que vous avez comparés: &nbsp;</b><p>" . $link . "</p><br/>";
            $body_mail = str_replace("{content}", $message, $template_mail);
            $headers = "From: $email_config" . "\r\n";
            $headers .= "Reply-To: " . strip_tags($email_config) . "\r\n";
            //$headers .= "BCC: $email_config\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($mail, $this->view->translate("Tableau comparatif"), $body_mail, $headers);
            echo json_encode(array('state' => 1, 'msg' => $this->view->translate('Mail envoyé avec succès')));
        else:
            echo json_encode(array('state' => 0, 'msg' => $this->view->translate('Adresse mail invalid')));
        endif;
    }

    public function linkAction() {
        $this->view->headTitle()->append('Comparer les produits');
        $this->view->headMeta()->appendName('description', "Comparer les produits");
        $this->view->headMeta()->appendName('keyword', "Comparer les produits");
        $this->view->headLink()->appendStylesheet(THEMES_DEFAULT_URL . 'css/index-comparaison.css?v=1');
        $this->view->headLink()->appendStylesheet(THEMES_DEFAULT_URL . 'css/popup.css?v=1');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/popup.js', 'text/javascript');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/fiche-index.js', 'text/javascript');
        $this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/html2canvas.min.js', 'text/javascript');
        //$this->view->inlineScript()->prependFile(THEMES_DEFAULT_URL . 'js/jquery.1.8.3.min.js', 'text/javascript');
        $tab = array();
        $a = '';
        for ($i = 1; $i <= 3; $i++):
            $a = $a . $i;
            $a = $this->_getParam('a' . $i);
            if (!empty($a)):
                array_push($tab, $a);
            endif;
        endfor;
        $this->view->tab = $tab;
    }

}
