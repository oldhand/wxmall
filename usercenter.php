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


if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
	$supplierid = $_SESSION['supplierid'];
}
else
{
	messagebox('错误', '没有店铺ID。');
	die();
}

if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
{
	$profileid = $_SESSION['profileid'];
}
elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
{
	$profileid = $_SESSION['accessprofileid'];
}
else
{
	messagebox('错误', '请从微信公众号“特赞商城”或朋友圈中朋友分享链接进入本平台，如您确实采用上述方式仍然出现本信息，请与系统管理员联系。');
	die();
}
if(isset($_REQUEST['type']) && $_REQUEST['type'] =='cancal_subscribe')
{
	try
	{
		$supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
		    ->filter('type', 'eic', 'supplier_profile') 
		    ->filter('my.deleted', '=', '0')  
			->filter('my.supplierid', '=', $supplierid)  
			->filter('my.profileid', '=', $profileid) 
		    ->end(-1)
		    ->execute();   
		if (count($supplier_profile) > 0)
		{
			$supplier_profile_info = $supplier_profile[0];
			if ($supplier_profile_info->my->subscribe != '1')
			{
				$supplier_profile_info->my->subscribe = '1';
				$supplier_profile_info->save("supplier_profile,supplier_profile_".$profileid.",supplier_profile_".$supplierid);
			}
		}
		
	}
	catch ( XN_Exception $e )
	{
		 
	}
}	
if(isset($_REQUEST['profileid']) && $_REQUEST['profileid'] !='')
{
	try
	{
		global $wxsetting;
		$supplierid = $wxsetting['supplierid'];
		if (isset($supplierid) && $supplierid != "")
		{
			require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
			XN_WX::$APPID = $WX_APPID;
			XN_WX::$SECRET = $WX_SECRET;
			$profile_info = get_supplier_profile_info();
			$wxopenid = $profile_info['wxopenid'];
			$userinfo = XN_WX::getuserinfo($wxopenid);
			if (count($userinfo) > 0)
			{
				$givenname = str_replace("'","",$userinfo['nickname']);
				$givenname = str_replace(" ","",$givenname);
				$givenname = str_replace("\\","",$givenname);

				$link = $userinfo['headimgurl'];
				$sex = $userinfo['sex'];
				$profile = XN_Profile::load($profileid,"id","profile_".$profileid);
				if ($userinfo['sex'] == '1')
				{
					$gender = '男';
					$profile->gender = $gender;
				}
				else if($userinfo['sex'] == '2')
				{
					$gender = '女';
					$profile->gender = $gender;
				}
				if ($profile->givenname != $givenname  || $profile->link != $link)
				{
					$profile->givenname = $givenname;
					$profile->link = $link;
					$profile->save("profile,profile_".$profileid.",profile_".$wxopenid);
					XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $profileid);
					XN_MemCache::delete("tezan_profile_" . $profileid);
				}
			}
		}

	}
	catch ( XN_Exception $e )
	{
		messagebox('错误',  $e->getMessage ());
		die();
	}
}
 
try{   
	$badges = array(); 
	
	$query = XN_Query::create ( 'YearContent_count' )->tag('mall_orders_'.$profileid)
				->filter ( 'type', 'eic', 'mall_orders') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid) 
				->filter ( 'my.tradestatus', '=', 'notrade')   
				->end(-1);
	$query->execute();
	$badges['pendingpayment'] = $query->getTotalCount();  
	
	 
	$query = XN_Query::create ( 'YearContent_count' )->tag('mall_orders_'.$profileid)
				->filter ( 'type', 'eic', 'mall_orders') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid) 
				->filter ( 'my.tradestatus', '=', 'trade')  
				->filter ( 'my.order_status', '=', '已付款') 
				->end(-1);
	$query->execute();
	$badges['nosend'] = $query->getTotalCount();  
	
	$query = XN_Query::create ( 'YearContent_count' )->tag('mall_orders_'.$profileid)
				->filter ( 'type', 'eic', 'mall_orders') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid) 
				->filter ( 'my.tradestatus', '=', 'trade')   
				->filter ( 'my.confirmreceipt', '!=', 'receipt') 
				->filter ( 'my.deliverystatus', '=', 'sendout')  
				->filter ( 'my.aftersaleservicestatus', '!=', 'yes') 
				->end(-1); 
	$query->execute();
	$badges['receipt'] = $query->getTotalCount();  
	 
	
	$query = XN_Query::create ( 'YearContent_count' )->tag('mall_orders_'.$profileid)
				->filter ( 'type', 'eic', 'mall_orders') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid) 
				->filter ( 'my.tradestatus', '=', 'trade')  
				->filter ( 'my.confirmreceipt', '=', 'receipt')   
				->filter ( 'my.appraisestatus', '=', 'no') 
				->end(-1); 
	$query->execute();
	$badges['appraise'] = $query->getTotalCount();  
	
	$query = XN_Query::create ( 'YearContent_count' )->tag('mall_orders_'.$profileid)
				->filter ( 'type', 'eic', 'mall_orders') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid) 
				->filter ( 'my.tradestatus', '=', 'trade')   
				->filter ( 'my.aftersaleservicestatus', '=', 'yes') 
				->end(-1); 
	$query->execute();
	$badges['aftersaleservice'] = $query->getTotalCount();  
	 
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

$supplier_info = get_supplier_info();

if ($supplier_info['mylogistic'] == '1')	
{
    $logisticdrivers = XN_Query::create('Content')->tag('mall_logisticdrivers_' . $supplierid)
        ->filter('type', 'eic', 'mall_logisticdrivers')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.profileid', '=', $profileid)
        ->filter('my.approvalstatus', '=', '2')
        ->filter('my.status', '=', '0')
        ->end(1)
        ->execute();
	if (count($logisticdrivers) > 0)
	{
		$supplier_info['mylogistic'] = 'open';
	}
	else 
	{
	    $logisticdrivers = XN_Query::create('Content')->tag('mall_logisticpoints_' . $supplierid)
	        ->filter('type', 'eic', 'mall_logisticpoints')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.profileid', '=', $profileid)
	        ->filter('my.approvalstatus', '=', '2')
	        ->filter('my.status', '=', '0')
	        ->end(1)
	        ->execute();
		if (count($logisticdrivers) > 0)
		{
			$supplier_info['mylogistic'] = 'open';
		} 
	}
	
}
$smarty->assign("supplier_info", $supplier_info);

$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
	if (isset($supplier_info['allowphysicalstore']) && $supplier_info['allowphysicalstore'] == '0')
	{
		$supplier_physicalstoreprofiles = XN_Query::create ( 'Content' ) 
		    ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
			->filter ( 'my.supplierid', '=',$supplierid)
			->filter ( 'my.profileid', '=',$profileid)
		    ->filter ( 'my.deleted', '=', '0' )
			->end(1)
		    ->execute ();
		if (count($supplier_physicalstoreprofiles) > 0)
		{
			$supplier_physicalstoreprofile_info = $supplier_physicalstoreprofiles[0];
			$physicalstoreid = $supplier_physicalstoreprofile_info->my->physicalstoreid;
			$assistantprofileid = $supplier_physicalstoreprofile_info->my->assistantprofileid;
			$physicalstore_info = XN_Content::load($physicalstoreid,"supplier_physicalstores");
			$profile_info['physicalstorename'] = $physicalstore_info->my->storename;
			$assistantprofile_info = get_profile_info($assistantprofileid);
			$profile_info['assistantprofile'] = $assistantprofile_info['givenname']; 
		}
	}
	$smarty->assign("profile_info",$profile_info);
}
else
{
	$smarty->assign("profile_info",get_profile_info());
}

$smarty->assign("badges",$badges);



$sysinfo = array();
$sysinfo['action'] = 'usercenter'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>