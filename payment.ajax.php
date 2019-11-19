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
	$orderid = $_REQUEST['record'];  
}
else
{
	echo '{"code":201,"msg":"参数错误!"}';
	die(); 
}

try{    
	$order_info = XN_Content::load($orderid,"mall_orders_".$profileid,7);   
	$tradestatus = $order_info->my->tradestatus;
	if ($tradestatus == "trade")
	{
		echo '{"code":200,"msg":"trade"}';
	}
	else
	{
		echo '{"code":201,"msg":"waiting"}';
	} 
	die(); 
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	echo '{"code":202,"msg":"'.$msg.'"}'; 
	die();  
} 

 
 
 
?>