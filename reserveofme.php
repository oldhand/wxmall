<?php
/*+**********************************************************************************
 * The contents of this file are subject to the 361CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  361CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/


session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
    $profileid = $_SESSION['profileid'];
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
	messagebox('错误','请从微信公众号“特赞商城”或朋友圈中朋友分享链接进入本平台，如您确实采用上述方式仍然出现本信息，请与系统管理员联系。');
	die();
}
 
if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	messagebox('错误',"没有店铺ID!");
	die();  
}

try{   
	if($_REQUEST['type'] == 'submit')
	{ 
		$record = $_REQUEST['record']; 
		$consignee = $_REQUEST['consignee'];  
		$mobile= $_REQUEST['mobile'];  
		$reservetime = $_REQUEST['reservetime'];  
		$deskofpeople = $_REQUEST['deskofpeople'];  
		$numberofpeople = $_REQUEST['numberofpeople'];  
		$place = $_REQUEST['place'];  
		$memo = $_REQUEST['memo']; 
		
		if(isset($_REQUEST['record']) && $_REQUEST['record'] != '')
		{
			$record = $_REQUEST['record'];
			$reserves_info = XN_Content::load($record,"mall_reserves_".$profileid,7); 
			$reserves_info->my->mobile = $mobile;
			$reserves_info->my->consignee = $consignee;
			$reserves_info->my->reservetime = $reservetime;
			$reserves_info->my->numberofpeople = $numberofpeople;
			$reserves_info->my->deskofpeople = $deskofpeople;
			$reserves_info->my->reserveplace = $place;
			$reserves_info->my->memo = $memo;
			$reserves_info->my->mall_reservesstatus = "JustCreated"; 
			$reserves_info->save("mall_reserves,mall_reserves_".$profileid);
		}
		else
		{
			global $wxsetting,$WX_APPID;  
			$newcontent = XN_Content::create('mall_reserves','',false,7);  
			$newcontent->my->supplierid = $supplierid;  
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->consignee = $consignee;
			$newcontent->my->mobile = $mobile;
			$newcontent->my->reservetime = $reservetime;
			$newcontent->my->numberofpeople = $numberofpeople;
			$newcontent->my->deskofpeople = $deskofpeople;
			$newcontent->my->reserveplace = $place;
			$newcontent->my->memo = $memo;
			$newcontent->my->mall_reservesstatus = "JustCreated";
			$newcontent->my->deleted = '0';
			$newcontent->save("mall_reserves,mall_reserves_".$profileid);
			 
			//require_once (XN_INCLUDE_PREFIX."/XN/Message.php");
			//XN_Message::sendmessage($profileid,'您在'.$businessename.'预订了'.$deskofpeople.'!'); 
		} 
	} 
	
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	messagebox('错误',$msg);
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

$recommend_info = checkrecommend();   
$smarty->assign("share_info",$recommend_info); 
$smarty->assign("supplier_info",get_supplier_info()); 
$profileinfo = get_supplier_profile_info();
$smarty->assign("profile_info",$profileinfo); 


$sysinfo = array();
$sysinfo['action'] = 'index'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>