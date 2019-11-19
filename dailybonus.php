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
if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
	$supplierid = $_SESSION['supplierid'];
}
else
{
	messagebox('错误', '没有店铺ID。');
	die();
}
 
try{   
	 $dailybonus = array();
	 
	 $mall_dailybonus = XN_Query::create('YearContent')->tag('mall_dailybonus')
								 ->filter('type', 'eic', 'mall_dailybonus') 
								 ->filter('my.deleted', '=', '0')
								 ->filter('my.supplierid', '=', $supplierid)
								 ->filter('my.profileid', '=', $profileid)
								 ->filter('my.date', '=', date('Y-m-d'))
								 ->end(-1)
								 ->execute();
								 
	if (count($mall_dailybonus) == 0)
    {
	      $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $wxopenid)
            ->filter('type', 'eic', 'supplier_profile')
            ->filter('my.profileid', '=', $profileid)
            ->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')             
            ->end(1)
            ->execute();
        if (count($supplier_profile) == 0)
        {
			$dailybonus['status'] = 'error';
			$dailybonus['msg'] = '请先关注后才能签到！';
		}
		else
		{
			$newcontent = XN_Content::create('mall_dailybonus','',false,7);					  
			$newcontent->my->deleted = '0';  
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->supplierid = $supplierid; 		 
			$newcontent->my->date = date('Y-m-d'); 
			$newcontent->save('mall_dailybonus,mall_dailybonus_'.$profileid.',mall_dailybonus_'.$supplierid);
			
			$profile_info = get_supplier_profile_info($profileid, $supplierid); 	       
	        $rank = $profile_info['rank'];
	        $accumulatedrank = $profile_info['accumulatedrank'];
	 
			$paymentamount = 10;
			$new_rank = $new_rank + $paymentamount;
	        $new_accumulatedrank= $new_accumulatedrank + $paymentamount;
	
	        $profile_info['rank'] = $new_rank;                 
	        $profile_info['accumulatedrank'] = $new_accumulatedrank;
	        
	        update_supplier_profile_info($profile_info);
	        	
			$newcontent = XN_Content::create('mall_rankwaters','',false,8);					  
			$newcontent->my->deleted = '0';  
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->supplierid = $supplierid;  
			$newcontent->my->oper = $profileid; 
			$newcontent->my->rankwatertype = 'dailybonus';
			$newcontent->my->amount = '+'.round($paymentamount); 
			$newcontent->my->rank = $new_rank; 
			$newcontent->my->submitdatetime = date('Y-m-d H:i:s');
			$newcontent->save('mall_rankwaters,mall_rankwaters_'.$profileid.',mall_rankwaters_'.$supplierid); 
			
			$dailybonus['status'] = 'ok';
			$msg = '您在'.date('Y-m-d').'签到成功，获得'.$paymentamount.'积分!';
			$dailybonus['msg'] = $msg;
			
			
		    $supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
	                ->filter('type', 'eic', 'supplier_wxsettings')
	                ->filter('my.deleted', '=', '0')
	                ->filter('my.supplierid', '=', $supplierid)
	                ->end(1)
	                ->execute();
		    if (count($supplier_wxsettings) > 0)
		    {
		        $supplier_wxsetting_info = $supplier_wxsettings[0];
		        $appid = $supplier_wxsetting_info->my->appid;
		        require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
		        XN_Message::sendmessage($profileid, $msg, $appid);
		    }
		}
	    
    }
    else
    {
	    $dailybonus['status'] = 'error';
		$msg = '您在'.date('Y-m-d').'已经签到!'; 
		$dailybonus['msg'] = $msg;
		
	     $supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
                ->filter('type', 'eic', 'supplier_wxsettings')
                ->filter('my.deleted', '=', '0')
                ->filter('my.supplierid', '=', $supplierid)
                ->end(1)
                ->execute();
	    if (count($supplier_wxsettings) > 0)
	    {
	        $supplier_wxsetting_info = $supplier_wxsettings[0];
	        $appid = $supplier_wxsetting_info->my->appid;
	        require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
	        XN_Message::sendmessage($profileid, $msg, $appid);
	    }
    }

	
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	$dailybonus['status'] = 'error'; 
	$dailybonus['msg'] = $msg;
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
 
$smarty->assign("dailybonus",$dailybonus); 

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