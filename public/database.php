<?php
if (mail('raliony@yahoo.fr', 'Mon Sujet',"test")){
	echo 'sent';
}else{
	echo 'not sent';
}
die('end');
$link = mysql_connect('eurocompeuro.mysql.db', 'eurocompeuro', 'Euro2500');
if (!$link) {
   die('Impossible de se connecter : ' . mysql_error());
}else{
	echo 'connexion established ok<br>';
}

// Rendre la base de données foo, la base courante
$db_selected = mysql_select_db('eurocompeuro', $link);
if (!$db_selected) {
   die ('Impossible de sélectionner la base de données : ' . mysql_error());
}else{
	echo 'database selected ok<br>';
}
?>