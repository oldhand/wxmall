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
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
} 
 
try{   
	global $wxsetting,$WX_APPID;  
	
	$mall_vipcards = XN_Query::create ( 'Content' )->tag('mall_vipcards')
				->filter ( 'type', 'eic', 'mall_vipcards') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.approvalstatus', '=', '2') 
				->filter ( 'my.status', '=', '0')  
				->filter ( 'my.vipcardhidden', '=', '0')   
				->filter ( 'my.starttime', '<=', date("Y-m-d")) 
				->filter ( 'my.endtime', '>=', date("Y-m-d"))  
				->order("published",XN_Order::DESC)  
				->end(-1)
				->execute ();
	 
	$vipcardlist = array();
	if (count($mall_vipcards) > 0)
	{   
		$vipcardids = array();
		foreach($mall_vipcards as $mall_vipcard_info)
		{
			$vipcardids[] = $mall_vipcard_info->id; 
		}
		
		$usageslists = array();
		$mall_usages = XN_Query::create ( 'YearContent' )->tag('mall_usages_'.$profileid)
					->filter ( 'type', 'eic', 'mall_usages') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid)
					->filter ( 'my.vipcardid', 'in', $vipcardids)  
					->end(-1)
					->execute ();
		foreach($mall_usages as $mall_usage_info)
		{
			$vipcardid = $mall_usage_info->my->vipcardid; 
			$usageslists[$vipcardid] = $mall_usage_info->id; 
		}
		
		foreach($mall_vipcards as $mall_vipcard_info)
		{
			$id = $mall_vipcard_info->id;  
			$published = $mall_vipcard_info->published;   
			$mall_reservesstatus = $mall_vipcard_info->my->mall_reservesstatus; 
			$amount = $mall_vipcard_info->my->amount;  
			$vipcardlist[$id]['id'] = $id;    
		    $vipcardlist[$id]['published'] =  date("Y-m-d H:i",strtotime($published));   
			$vipcardlist[$id]['vipcardname'] = $mall_vipcard_info->my->vipcardname;  
			$vipcardlist[$id]['amount'] = number_format($amount,2,".","");
			$vipcardlist[$id]['discount'] = $mall_vipcard_info->my->discount; 
			$vipcardlist[$id]['orderamount'] = $mall_vipcard_info->my->orderamount; 
			$vipcardlist[$id]['cardtype'] = $mall_vipcard_info->my->cardtype;   
			$vipcardlist[$id]['count'] = $mall_vipcard_info->my->count; 
			$vipcardlist[$id]['remaincount'] = $mall_vipcard_info->my->remaincount; 
			$vipcardlist[$id]['usagecount'] = $mall_vipcard_info->my->usagecount; 
			$vipcardlist[$id]['description'] = $mall_vipcard_info->my->description; 
			$vipcardlist[$id]['starttime'] = $mall_vipcard_info->my->starttime;
			$vipcardlist[$id]['endtime'] = $mall_vipcard_info->my->endtime;
			$vipcardlist[$id]['timelimit'] = $mall_vipcard_info->my->timelimit;
			 
			if ($mall_reservesstatus == "JustCreated")
			{
				$vipcardlist[$id]['mall_vipcardsstatus'] = '待处理'; 
			} 
			else
			{
				$vipcardlist[$id]['mall_vipcardsstatus'] = $mall_vipcard_info->my->mall_vipcardsstatus; 
			}
			if (isset($usageslists[$id]) && $usageslists[$id]  != '')
			{
				$vipcardlist[$id]['usagesid'] = $usageslists[$id];
				$vipcardlist[$id]['usagesstatus'] = '已领用';
			}  
			else
			{
				$vipcardlist[$id]['usagesid'] = '';
				$vipcardlist[$id]['usagesstatus'] = '未领用';
			} 
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

$smarty->assign("vipcardlist",$vipcardlist); 


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