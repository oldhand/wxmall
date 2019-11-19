<?php 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php"); 
 
 
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'login')
{
	 
	if(isset($_REQUEST['name']) && $_REQUEST['name'] != '' && 
	   isset($_REQUEST['password']) && $_REQUEST['password'] != '')
	{
		$profileid = 'hx5eyjjmlg6'; 
		$_SESSION['profileid'] = $profileid;
		$_SESSION['u'] = $profileid; 
		echo '1';
		die();
	} 
	die();
}  


 

require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;
 
$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
	$islogined = true;
} 
$smarty->assign("islogined",$islogined); 

$action = strtolower(basename(__FILE__,".php")); 
  
 
$smarty->assign("supplier_info",get_supplier_info()); 
$profileinfo = get_supplier_profile_info();
$smarty->assign("profile_info",$profileinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>