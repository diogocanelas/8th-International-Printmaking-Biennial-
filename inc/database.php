<?php 
ini_set("display_errors","On");

try{
  $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME .";port=" . DB_PORT,DB_USER,DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
}catch(Exception $e) {
  echo $e->getMessage();
  die();
}
?>