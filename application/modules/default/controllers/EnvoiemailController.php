<?php

class EnvoiemailController extends Genius_AbstractController
{
	public function indexAction()
	{
		
		$this->view->headTitle()->append($this->view->translate("Accueil, Secret Universe Network"));
		$lang = DEFAULT_LANG_ABBR;
		$message = "Les champs comportant une astérisque doivent obligatoirement être remplis. Vos coordonnées restent la 'propriété' de Codéo qui s'engage à ne pas les vendre ou à les céder à des Tiers, à l'exception des demandes qui nécessitent l'intervention de nos partenaires.";
		$html = new Zend_View();
		$html->setScriptPath(APPLICATION_PATH . '/modules/default/views/scripts/emails/');
		$template_mail = $html->render("mail.phtml");
		$body_mail = str_replace("{content}", $message, $template_mail);
		$email ='ravakasm@gmail.com';
		$headers = "From: noreplay@secretuniversenetwork.com" . "\r\n";
		$headers .= "Reply-To: ". strip_tags($email) . "\r\n";
		$headers .= "BCC: ravakagaw@yahoo.fr\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		mail($email,$this->view->translate("Confirmation de votre inscription"),$template_mail,$headers);
		echo'Lasa';
		die();
	}
}