<?php
$file = fopen("paths.php", "r");
var_dump($file);
echo filesize("paths.php");
fclose($file);
?>