<?php
 	

session_start(); 

require_once(dirname(__FILE__) . "/config.inc.php");	
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");



//测试数据
$supplierid = '953102'; //大印湘西
$supplierid = '916927'; //铜官印象
$supplierid = '41461';  //若米家 


$_GET['profileid'] = 'hx5eyjjmlg6'; //老手

$_SESSION['supplierid'] = $supplierid;



XN_Application::$CURRENT_URL = 'admin'; 
 
$supplierinfo = get_supplier_info($supplierid);
 
if(isset($_GET['profileid']) && $_GET['profileid'] !='')
{
	try 
	{  
		$profileid = $_GET['profileid'];
		$profile = XN_Profile::load($profileid,"id","profile_".$profileid);  
		
		/*  直接登录*/
		$_SESSION['profileid'] = $profileid;
		$_SESSION['u'] = $profileid;
		unset($_SESSION['accessprofileid']); 
 
		
		header("Location: index.php");
	}
	catch ( XN_Exception $e ) 
	{ 
		errorprint('错误',$e->getMessage());
	}
}
else
{
	errorprint('错误','系统禁止的调用!');	 
}