<?php 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php"); 


unset($_SESSION['profileid']); 
unset($_SESSION['accessprofileid']);   
unset($_SESSION['u']);   

header("Location: index.html");
die(); 



?>