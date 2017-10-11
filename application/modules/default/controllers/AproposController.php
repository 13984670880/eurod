<?php
class AproposController extends Genius_AbstractController
{
	public function mentionslegalesAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Mentions légales');
		$this->view->headMeta()->appendName('description',"Mentions légales");
		$this->view->headMeta()->appendName('keyword',"Easy Living | Login Form");
        $this->view->subheader = "statics/subheader.phtml"; 
	}
	
	public function histoireAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Notre histoire');
		$this->view->headMeta()->appendName('description',"Notre histoire");
		$this->view->headMeta()->appendName('keyword',"Easy Living | Login Form");
        $this->view->subheader = "statics/subheader.phtml"; 
	}
	
	public function marquespartenairesAction()
	{		
		$this->view->headTitle()->append('Eurocomputer | Marques partenaires');
		$this->view->headMeta()->appendName('description',"Marques partenaires");
		$this->view->headMeta()->appendName('keyword',"Easy Living | Login Form");
        $this->view->subheader = "statics/subheader.phtml"; 
	}

}