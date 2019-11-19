<?php 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php"); 
 
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'submit')
{
	echo '{"result":"fail","message":{"password1":["\u8bf7\u8f93\u5165\u5bc6\u7801","<strong> password1 <\/strong>\u4e0d\u80fd\u4e3a\u7a7a\u3002"],"account":["<strong> \u7528\u6237\u540d <\/strong>\u4e0d\u80fd\u4e3a\u7a7a\u3002","<strong> \u7528\u6237\u540d <\/strong>\u5e94\u5f53\u4e3a\u5b57\u6bcd\u548c\u6570\u5b57\u7684\u7ec4\u5408\uff0c\u81f3\u5c11\u4e09\u4f4d"],"realname":["<strong> \u771f\u5b9e\u59d3\u540d <\/strong>\u4e0d\u80fd\u4e3a\u7a7a\u3002"],"email":["<strong> \u90ae\u7bb1 <\/strong>\u4e0d\u80fd\u4e3a\u7a7a\u3002","<strong> \u90ae\u7bb1 <\/strong>\u5e94\u5f53\u4e3a\u5408\u6cd5\u7684EMAIL\u3002","<strong> \u90ae\u7bb1 <\/strong>\u5df2\u7ecf\u6709<strong>  <\/strong>\u8fd9\u6761\u8bb0\u5f55\u4e86\u3002"]}}';
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