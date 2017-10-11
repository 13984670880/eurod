<?php
class Genius_Class_Email{
	public function send($assignvalues = NULL){
		$html = new Zend_View();
		$html->setScriptPath('../application/modules/default/views/emails');

		if (sizeof($assignvalues)>0){
			foreach ($assignvalues as $key=>$val):
				$html->assign($key,$val);
			endforeach;
		}
		$mail = new Zend_Mail('utf-8');
		$message = $html->render($assignvalues['phtml']);
		$mail->setBodyHtml($message);
		$mail->setFrom($assignvalues['sender'],$_SERVER['HTTP_HOST']);
		$mail->addTo($assignvalues['receiver']);
		$mail->setSubject($assignvalues['subject']);

        if (!empty($assignvalues['addcc'])){
            $mail->addCc($assignvalues['addcc']);
        }

	   if ($_SERVER['SERVER_ADDR'] != '127.0.0.1') {
			 if ($mail->send()){
				 return true;
			 }else{
				 return false;
			 }
		}else{
		   $tr = new Zend_Mail_Transport_Smtp(SMTP);
		   Zend_Mail::setDefaultTransport($tr);
		   if ($mail->send()){
				 return true;
			 }else{
				 return false;
			 }
		}
	}
	
	public function sendnews($assignvalues = NULL){
		$html = new Zend_View();
		$html->setScriptPath('../application/modules/default/views/emails');

		if (sizeof($assignvalues)>0){
			foreach ($assignvalues as $key=>$val):
				$html->assign($key,$val);
			endforeach;
		}
		$mail = new Zend_Mail('utf-8');
		$message = $assignvalues['phtml'];
		$mail->setBodyHtml($message);
		$mail->setFrom($assignvalues['sender'],$_SERVER['HTTP_HOST']);
		$mail->addTo($assignvalues['receiver']);
		$mail->setSubject($assignvalues['subject']);

        if (!empty($assignvalues['addcc'])){
            $mail->addCc($assignvalues['addcc']);
        }

	   if ($_SERVER['SERVER_ADDR'] != '127.0.0.1') {
			 if ($mail->send()){
				 return true;
			 }else{
				 return false;
			 }
		}else{
		   $tr = new Zend_Mail_Transport_Smtp(SMTP);
		   Zend_Mail::setDefaultTransport($tr);
		   if ($mail->send()){
				 return true;
			 }else{
				 return false;
			 }
		}
	}
}
?>