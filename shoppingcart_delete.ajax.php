<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
	$loginprofileid = $_SESSION['profileid']; 
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
	$loginprofileid = $_SESSION['accessprofileid']; 
}
else
{
	$loginprofileid = "anonymous";
}

if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
}
 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$shoppingcartid = $_REQUEST['record'];  
}
else
{
	echo '{"code":201,"msg":"参数错误!"}';
	die(); 
}

try{   
	 /*$shoppingcart_info = XN_Content::load($shoppingcartid,"mall_shoppingcarts");
	 $shoppingcart_info->my->deleted = "1";
	 $shoppingcart_info->save('mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid );*/
	   
	 XN_Content::delete($shoppingcartid,'mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid.',mall_shoppingcarts_'.$supplierid,7);
	  
 
	echo 'success';
	die();
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	echo '{"code":202,"msg":"'.$msg.'"}'; 
	die();  
} 

 
 
 
?>