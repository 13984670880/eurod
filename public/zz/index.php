<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Intranet Eurocomputer - Info PHP</title>
</head>

<body>
  <p> </p>
  <h1>Informations sur le serveur PHP</h1>
  <p> </p>
<?php
	print $_SERVER['SERVER_NAME'];
	print "<br>";
	phpinfo(); 
?>
</body>

</html>


















