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
 
if(isset($_REQUEST['type']) && $_REQUEST['type'] !='')
{
	$type = $_REQUEST['type'];  
}

if(isset($_REQUEST['invoicenumber']) && $_REQUEST['invoicenumber'] !='')
{
	$invoicenumber = $_REQUEST['invoicenumber'];  
}
 
 

require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;
 
$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
	$islogined = true;
} 
$smarty->assign("islogined",$islogined); 

$smarty->assign("type",$type); 
$smarty->assign("invoicenumber",$invoicenumber); 


$logisticroutes = array();

$mall_logisticbills =   XN_Query::create ( 'YearContent' )->tag("mall_logisticbills_".$supplierid)
		->filter ( 'type', 'eic', 'mall_logisticbills' ) 
		->filter ( 'my.logisticbills_no', '=', $invoicenumber )
		->filter ( 'my.deleted', '=', '0' )
		->end(1)
		->order('published',XN_Order::DESC)
		->execute (); 
if (count ( $mall_logisticbills ) > 0) 
{ 	
	$mall_logisticbill_info = $mall_logisticbills[0];
	$billid = $mall_logisticbill_info->id;
	$mall_logisticroutes =   XN_Query::create ( 'YearContent' )->tag("mall_logisticroutes_".$supplierid)
		->filter ( 'type', 'eic', 'mall_logisticroutes' ) 
		->filter ( 'my.logisticbillid', '=', $billid )
		->filter ( 'my.deleted', '=', '0' )
		->order('published',XN_Order::ASC)
		->end(-1)
		->execute (); 
	if (count ( $mall_logisticroutes ) > 0) 
	{  
		$pos = 1;
		foreach($mall_logisticroutes as $logisticroute_info)
		{ 
			$route_info = array();
			$published = $logisticroute_info->published;
			if ($pos == 1)
			{
				$route_info['pos'] = 'start';
			} 
			else if ($pos == count($mall_logisticroutes))
			{
				$route_info['pos'] = 'end';
			} 
			else
			{
				$route_info['pos'] = '';
			}
			$route_info['date'] = date("Y-m-d",strtotime($published));   
			$route_info['time'] = date("H:i",strtotime($published)); 
			$route_info['route'] = $logisticroute_info->my->route;
			$logisticroutes[] = $route_info;
			$pos++;
		}
	} 
}
$smarty->assign("logisticroutes",$logisticroutes); 		

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