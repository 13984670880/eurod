<?php

class DevisController extends Genius_AbstractController
{
    public function indexAction()
    {
        $this->view->headTitle()->append('Demande de devis');
        $this->view->headMeta()->appendName('description', "");
        $this->view->headMeta()->appendName('keyword', "");
        $this->view->subheader = "statics/subheader.phtml";
        $this->view->sidebar = "statics/sidebar.phtml";
        $this->view->active = 'devis';
        $id_product = $this->view->id_product = $this->_getParam('id_product');
        $id_marque = $this->view->id_marque = $this->_getParam('id_marque');
        $id_type = $this->view->id_type = $this->_getParam('id_type');
        $search = $this->view->search = $this->_getParam('search');
        $prest = $this->_getParam('prest');

        if (! empty($prest)) {
            $this->view->prest = $prest;
        }

        if (isset($_COOKIE['rubrique']) && ! empty($_COOKIE['rubrique'])) {
            $this->view->background = $_COOKIE['rubrique'];
        } else {
            $this->view->background = "";
        }

        if (! empty($id_type)) {
            $this->view->type = Genius_Model_Category::getCategoryById($id_type);
        }

        if (! empty($id_product) && empty($search)) {
            $this->view->product = Genius_Model_Product::getProductById($id_product);
            $this->view->marque = Genius_Model_Category::getCategoryById($id_marque);

            $this->view->devis_phtml = "devis/devis_product.phtml";
        } elseif (! empty($search)) {
            $this->view->devis_phtml = "devis/devis_proposition.phtml";

            $this->view->prest = $prest;
        } else {
            if (! empty($id_type)) {
                $this->view->type = Genius_Model_Category::getCategoryById($id_type);
            } else {
                $this->view->marque = Genius_Model_Category::getCategoryById($id_marque);
            }
            $this->view->devis_phtml = "devis/devis_article.phtml";

            $this->view->prest = $prest;
        }

        $data_devis = [];
        //If the form is submitted
        if (isset($_POST['submitted'])) {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => '6Le4UwoUAAAAAP0FK9cJHa-cUyjuJyULze6Csijy',
                'response' => $_POST['g-recaptcha-response'],
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            ];
            // use key 'http' even if you send the request to https://...
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ],
            ];
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result);
            if ($result->success == 1) {
                $keys = '';
                foreach ($_POST['type_prestation'] AS $presta) {
                    $keys .= $presta.",";
                }
                // 1. insert in ec_devis
                $data_devis = [
                    'marque' => $_POST['marque'],
                    'modele' => $_POST['modele'],
                    'id_product' => $_POST['id_product'],
                    'id_prestation' => rtrim($keys, ","),
                    'reference' => $_POST['reference'],
                    'quantite' => $_POST['quantite'],
                    'message' => $_POST['message'],
                    'raison_sociale' => $_POST['raison_sociale'],
                    'prenom' => $_POST['prenom'],
                    'nom' => $_POST['nom'],
                    'email' => $_POST['email'],
                    'tel' => $_POST['tel'],
                    'create_time' => date('Y-m-d H:i:s'),
                ];
                global $siteconfig;
                $email_config = $siteconfig->email;
                $html = new Zend_View();
                $html->setScriptPath(APPLICATION_PATH.'/modules/default/views/scripts/emails/');
                $template_mail = $html->render("mail.phtml");
                $message = "<b>Marque: &nbsp;</b>".$_POST['marque']."<br/>";
                $message .= "<b>Modèle: &nbsp;</b>".$_POST['modele']."<br/>";
                $message .= "<b>Référence: &nbsp;</b>".$_POST['reference']."<br/>";
                $message .= "<b>Quantité: &nbsp;</b>".$_POST['quantite']."<br/>";
                $message .= "<b>Raison Sociale: &nbsp;</b>".$_POST['raison_sociale']."<br/>";
                $message .= "<b>Prenom: &nbsp;</b>".$_POST['prenom']."<br/>";
                $message .= "<b>Nom: &nbsp;</b>".$_POST['nom']."<br/>";
                $message .= "<b>Email: &nbsp;</b>".$_POST['email']."<br/>";
                $message .= "<b>Tel: &nbsp;</b>".Genius_Class_Utils::setLisible($_POST['tel'])."<br/>";
                $message .= "<b>Message: &nbsp;</b>".$_POST['message']."<br/>";
                $body_mail = str_replace("{content}", $message, $template_mail);
                $headers = "From: $email_config"."\r\n";
                $headers .= "Reply-To: ".strip_tags($email_config)."\r\n";
                $headers .= "BCC: $email_config\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                mail($email_config, $this->view->translate("Demande de devis"), $body_mail, $headers);
                Genius_Model_Global::insert(TABLE_PREFIX.'devis', $data_devis);
                $this->_redirect('/confirmation-devis.html');
            }
        }
    }

    public function reparationAction()
    {
        $this->view->headTitle()->append('Demande de devis');
        $this->view->headMeta()->appendName('description', "");
        $this->view->headMeta()->appendName('keyword', "");
        $this->view->subheader = "statics/subheader.phtml";
        $this->view->sidebar = "statics/sidebar.phtml";
        $this->view->inlineScript()->prependFile(PLUGINS_URL.'jqueryValidationEngine/js/jquery.validationEngine.js', 'text/javascript');
        $this->view->active = 'devis';
        $this->view->form_name = $this->view->translate("Formulaire de demande de réparation");
        $this->view->id_category_group = $id_category_group = $this->_getParam('id_category_group');
        $this->view->id_category = $id_category = $this->_getParam('id_category');
        $this->view->id_product = $id_product = $this->_getParam('id_product');
        if (! empty($id_product)) {
            $this->view->product = Genius_Model_Product::getProductById($id_product);
        } else {
            $this->view->product = [];
        }
        $this->view->marque = Genius_Model_Category::getCategoryById($id_category);
        $this->view->group = Genius_Model_Group::getGroupById($id_category_group);
        if (isset($_COOKIE['rubrique']) && ! empty($_COOKIE['rubrique'])) {
            $this->view->background = $_COOKIE['rubrique'];
        } else {
            $this->view->background = "";
        }
        global $siteconfig;
        $email_config = $siteconfig->email;
        //If the form is submitted
        if ($_POST) {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => '6Le4UwoUAAAAAP0FK9cJHa-cUyjuJyULze6Csijy',
                'response' => $_POST['g-recaptcha-response'],
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            ];
            // use key 'http' even if you send the request to https://...
            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ],
            ];
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result);
            if ($result->success == 1) {
                // 1. insert in ec_devis
                $data_reparations = [
                    'id_category_group' => $_POST['id_category_group'],
                    'marque' => $_POST['marque'],
                    'modele' => $_POST['modele'],
                    'panne' => $_POST['panne'],
                    'audit' => $_POST['audit'],
                    'devis' => $_POST['devis'],
                    'sav' => $_POST['sav'],
                    'scom' => $_POST['scom'],
                    'nom_entreprise' => $_POST['nom_entreprise'],
                    'email' => $_POST['email'],
                    'person_cont' => $_POST['person_cont'],
                    'tel' => $_POST['tel'],
                    'create_time' => date('Y-m-d H:i:s'),
                ];

                $html = new Zend_View();
                $html->setScriptPath(APPLICATION_PATH.'/modules/default/views/scripts/emails/');
                $template_mail = $html->render("mail.phtml");
                $message = "<b>Marque: &nbsp;</b>".$_POST['marque']."<br/>";
                $message .= "<b>Modèle: &nbsp;</b>".$_POST['modele']."<br/>";
                $message .= "<b>Un audit de parc sur site ?: &nbsp;</b>".Genius_Class_Prestations::convert($_POST['audit'])."<br/>";
                $message .= "<b>Enlèvement + devis gratuit du matériel ? : &nbsp;</b>".Genius_Class_Prestations::convert($_POST['devis'])."<br/>";
                $message .= "<b>Etre contacté par le SAV: &nbsp;</b>".Genius_Class_Prestations::convert($_POST['sav'])."<br/>";
                $message .= "<b>Etre contacté par le service commercial ? : &nbsp;</b>".Genius_Class_Prestations::convert($_POST['scom'])."<br/>";
                $message .= "<b>Nom de l'entreprise: &nbsp;</b>".$_POST['nom_entreprise']."<br/>";
                $message .= "<b>Email: &nbsp;</b>".$_POST['email']."<br/>";
                $message .= "<b>Personne à contacter: &nbsp;</b>".$_POST['person_cont']."<br/>";
                $message .= "<b>Téléphone: &nbsp;</b>".Genius_Class_Utils::setLisible($_POST['tel'])."<br/>";
                $body_mail = str_replace("{content}", $message, $template_mail);
                $headers = "From: $email_config"."\r\n";
                $headers .= "Reply-To: ".strip_tags($email_config)."\r\n";
                $headers .= "BCC: $email_config\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                mail($email_config, $this->view->translate("Demande de devis"), $body_mail, $headers);
                Genius_Model_Global::insert(TABLE_PREFIX.'reparations', $data_reparations);
                $this->_redirect('/confirmation-reparation.html');
            }
        }
    }

    public function index2Action()
    {
        $this->view->headTitle()->append('');
        $this->view->headMeta()->appendName('description', "");
        $this->view->headMeta()->appendName('keyword', "");
        $this->view->subheader = "statics/subheader.phtml";
        $this->view->sidebar = "statics/sidebar.phtml";
        $this->view->active = 'devis';
        $id_product = $this->_getParam('id_product');
        $id_marque = $this->_getParam('id_marque');
        $id_type = $this->_getParam('id_type');
        $this->view->product = Genius_Model_Product::getProductById($id_product);
        $this->view->marque = Genius_Model_Category::getCategoryById($id_marque);
        $this->view->type = Genius_Model_Category::getCategoryById($id_type);
    }
}