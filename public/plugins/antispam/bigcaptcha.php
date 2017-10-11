<?php
  session_start();
  $code=rand(1000,9999);
  $_SESSION["code"]=$code;
  $im = imagecreatetruecolor(80, 40);
  $bg = imagecolorallocate($im, 204, 204, 204);
  $fg = imagecolorallocate($im, 3,3,3);
  imagefill($im, 0, 0, $bg);
  imagestring($im, 15, 23, 12,  $code, $fg);
  header("Cache-Control: no-cache, must-revalidate");
  header('Content-type: image/png');
  imagepng($im);
  imagedestroy($im);
?>  