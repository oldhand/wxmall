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
 
$justcreated = false;
global $wxsetting,$WX_APPID; 
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
    $vipcardinfo = array();
	
	if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' &&
	   $_REQUEST['type'] == 'submit')
	{ 
		$vipcardid = $_REQUEST['record'];  
		$vipcard_info = XN_Content::load($vipcardid,"mall_vipcards"); 
		$query = XN_Query::create ( 'YearContent_Count' )->tag('mall_usages_'.$profileid)
					->filter ( 'type', 'eic', 'mall_usages') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $loginprofileid) 
					->filter ( 'my.vipcardid', '=', $vipcardid)  
					->rollup()
					->end(-1);
		$query->execute();
		if ($query->getTotalCount() == 0 )
		{  
			$newcontent = XN_Content::create('mall_usages','',false,7);  
			$newcontent->my->deleted = '0'; 
			$newcontent->my->supplierid = $supplierid; 
		    $newcontent->my->vipcardid = $vipcardid; 
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->usecount = '0'; 
			$newcontent->my->usagevalid = '0'; 
			$newcontent->my->lastusedatetime = ''; 
			$newcontent->my->mall_usagesstatus = "JustCreated"; 
			$newcontent->my->vipcardname = $vipcard_info->my->vipcardname;  
			$newcontent->my->amount = $vipcard_info->my->amount; 
			$newcontent->my->discount = $vipcard_info->my->discount; 
			$newcontent->my->orderamount = $vipcard_info->my->orderamount; 
			$newcontent->my->cardtype = $vipcard_info->my->cardtype;
			$newcontent->my->starttime = $vipcard_info->my->starttime;
			$newcontent->my->endtime = $vipcard_info->my->endtime;
			$newcontent->my->timelimit = $vipcard_info->my->timelimit; 
			$newcontent->my->isused = '0'; 
			$newcontent->my->usedtimes = '0';
			$newcontent->save("mall_usages,mall_usages_".$profileid.',mall_usages_'.$supplierid);
			
			$query = XN_Query::create ( 'YearContent_Count' )->tag('mall_usages_'.$profileid)
						->filter ( 'type', 'eic', 'mall_usages') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.supplierid', '=', $supplierid)
						->filter ( 'my.profileid', '=', $profileid) 
						->filter ( 'my.vipcardid', '=', $vipcardid)  
						->rollup()
						->end(-1);
			$query->execute();
			$count = $query->getTotalCount();
			$total =  $mall_vipcard_info->my->count; 
			$remaincount = intval($total) - intval($count);
			if ($remaincount != $mall_vipcard_info->my->remaincount)
			{
				$mall_vipcard_info->my->remaincount = $remaincount; 
				$vipcard_info->save("mall_usages");
			} 
			$justcreated = true;
		}	 
		$vipcardinfo['id'] = $vipcard_info->id;    
	    $vipcardinfo['published'] =  date("Y-m-d H:i",strtotime($vipcard_info->published));   
		$vipcardinfo['vipcardname'] = $vipcard_info->my->vipcardname;  
		$vipcardinfo['amount'] = number_format($vipcard_info->my->amount,2,".","");
		$vipcardinfo['discount'] = $vipcard_info->my->discount; 
		$vipcardinfo['orderamount'] = $vipcard_info->my->orderamount; 
		$vipcardinfo['cardtype'] = $vipcard_info->my->cardtype;   
		$vipcardinfo['count'] = $vipcard_info->my->count; 
		$vipcardinfo['remaincount'] = $vipcard_info->my->remaincount; 
		$vipcardinfo['usagecount'] = $vipcard_info->my->usagecount; 
		$vipcardinfo['description'] = $vipcard_info->my->description; 
		$vipcardinfo['starttime'] = $vipcard_info->my->starttime;
		$vipcardinfo['endtime'] = $vipcard_info->my->endtime;
		$vipcardinfo['timelimit'] = $vipcard_info->my->timelimit;
	} 
	else if(isset($_REQUEST['record']) && $_REQUEST['record'] != '')
	{ 
		$vipcardid = $_REQUEST['record'];  
		$vipcard_info = XN_Content::load($vipcardid,"mall_vipcards"); 
	 
		$vipcardinfo['id'] = $vipcard_info->id;    
	    $vipcardinfo['published'] =  date("Y-m-d H:i",strtotime($vipcard_info->published));   
		$vipcardinfo['vipcardname'] = $vipcard_info->my->vipcardname;  
		$vipcardinfo['amount'] = number_format($vipcard_info->my->amount,2,".","");
		$vipcardinfo['discount'] = $vipcard_info->my->discount; 
		$vipcardinfo['orderamount'] = $vipcard_info->my->orderamount; 
		$vipcardinfo['cardtype'] = $vipcard_info->my->cardtype;   
		$vipcardinfo['count'] = $vipcard_info->my->count; 
		$vipcardinfo['remaincount'] = $vipcard_info->my->remaincount; 
		$vipcardinfo['usagecount'] = $vipcard_info->my->usagecount; 
		$vipcardinfo['description'] = $vipcard_info->my->description; 
		$vipcardinfo['starttime'] = $vipcard_info->my->starttime;
		$vipcardinfo['endtime'] = $vipcard_info->my->endtime;
		$vipcardinfo['timelimit'] = $vipcard_info->my->timelimit;
	}
 
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	messagebox('错误',$msg);
	die(); 
} 


$mall_usages = XN_Query::create ( 'YearContent_Count' )->tag('mall_usages_'.$profileid)
				->filter ( 'type', 'eic', 'mall_usages') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->rollup("my.amount")
				->end(-1)
				->execute(); 
if (count($mall_usages) > 0)
{
	 $mall_usage_info = $mall_usages[0];
	 $amount = $mall_usage_info->my->amount;
}
else
{
	 $amount = "0";
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

 

$smarty->assign("justcreated",$justcreated); 
$smarty->assign("amount",number_format($amount,2,".","")); 
$smarty->assign("vipcardinfo",$vipcardinfo); 


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