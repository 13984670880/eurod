<?php
class Genius_Class_Tree extends Genius_AbstractController{
	public function hasChild($id_membre){
		$result = Genius_Model_Global::select(TABLE_PREFIX.'membres',"*,id as id_membre,concat(prenom,' ',nom) as nom_partenaire,id_parrain","id_parrain = '$id_membre' AND etat = 1");
		if (!empty($result)){
			return true;
		}else{
			return false;
		}
	}
	
	public function getChild($id_membre){
		$result = Genius_Model_Global::select(TABLE_PREFIX.'membres',"*,id as id_membre,concat(prenom,' ',nom) as nom_child,id_parrain,id_pays","id_parrain = '$id_membre' AND etat = 1");
		return $result;
	}	
	
	public static function getMembersChild($id_parent){
		$members = self::fetchMember(self::getChild($id_parent));
		return '<ul>'.$members.'</ul>';
	}
	
	public static function getSousLicencies($nb_direct,$nb_niv1,$nb_niv2){
		$tree = "
			<li>
			<div class='cover'>zertz</div>
				<ul>
					<li><div class='cover'>erer</div></li>
					<li><div class='cover'>trert</div></li>
					<li><div class='cover'>erzte</div></li>
				</ul>
			</li>
			<li><div class='cover'>erer</div></li>
			<li><div class='cover'>trert</div></li>
			<li><div class='cover'>erzte</div></li>
		";
		return $tree;
	}
	
	public function detailsParent($id_membre){
		$member = Genius_Model_Global::selectRow(TABLE_PREFIX.'membres',"*,id as id_membre,concat(prenom,' ',nom) as nom_parent,id_pays,id_parrain","id = '$id_membre'");
		$member_details = "
			<div class='cover'>
			<div>
				<span class='name'>".$member['nom_parent']."<span>
				<img src='".THEMES_DEFAULT_URL."img/template/fr.jpg'/></span></span>
				<span class='numero'>numéro : ".$member['id']."</span>
				<span class='sponsor'>numéro sponsor : ".$member['id_parrain']."</span>
			</div>
			</div>
			";
		return 	$member_details;
	}
	
	public function fetchMember($result){
		$t = Zend_Registry::get('Zend_Translate');
		$frontController = Zend_Controller_Front::getInstance();
		foreach ($result as $member):
		    $id_membre = $member['id'];
			$view = new Zend_View(); 
			$url_parrain = $view->url(array("controller"=>"espacepro","action"=>"sponsor","id"=>$member['id_parrain']));
			$member_details = "
			<div class='cover'>
			<div>
				<span class='name'>".$member['nom_child']."<span>
				<img src='".THEMES_DEFAULT_URL."img/template/fr.jpg'/></span></span>
				<span class='numero'>".$t->translate('numéro')." : ".$member['id']."</span>
				<span class='sponsor'>".$t->translate('numéro sponsor')." : <a class='link_sponsor' href='".$url_parrain."'>".$member['id_parrain']."</a></span>
			</div>
			</div>
			";
			echo "<li>$member_details";
				if (self::hasChild ($id_membre)) {
					echo "<ul>";
						self::fetchMember (self::getChild($id_membre));
					echo "</ul>";
				}
			echo "</li>";	
		endforeach;
	}
}