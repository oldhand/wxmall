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
 
 
try{   
	$promotioncenter = array();
	$promotioncenter['captureprofit'] = '0.00';
	$promotioncenter['expectedprofit'] = '0.00';
	$promotioncenter['totalprice'] = '0.00';
    $promotioncenter['ordercount'] = '0'; 
	
	
    $commissions_query = XN_Query::create('YearContent_Count')->tag('mall_commissions_'.$profileid)
	   		->filter('type','eic','mall_commissions')
	   		->filter('my.deleted','=','0') 
            ->filter('my.supplierid','=',$supplierid) 
   		    ->filter('my.profileid','=',$profileid) 	
			->filter('my.consumer','!=',$profileid)  
   		    ->rollup('my.amount')
            ->group('my.commissiontype')
	   		->begin(0)
	   		->end(-1)
	   		->execute();
   foreach($commissions_query as $commission_info)
   {
       $amount = $commission_info->my->amount;
	   $commissiontype = $commission_info->my->commissiontype;
	   if ($commissiontype == "0")
	   { 
	   	 	$promotioncenter['captureprofit'] =  number_format(floatval($amount), 2, ".", "");
	   }
	   if ($commissiontype == "1")
	   {
	   	 	$promotioncenter['expectedprofit'] =  number_format(floatval($amount), 2, ".", "");
	   }
   } 
   
   $orders_query = XN_Query::create('YearContent_Count')->tag('mall_orders_'.$profileid)
   		->filter('type','eic','mall_orders')
   		->filter('my.deleted','=','0') 
        ->filter('my.supplierid','=',$supplierid) 
  	    ->filter('my.profileid','!=',$profileid) 	
		->filter('my.orderssources','=',$profileid) 	  
  		->rollup('my.sumorderstotal') 
   		->begin(0)
   		->end(-1);
   $orders = $orders_query->execute();
   if (count($orders) > 0 )
   {
	   $order_info = $orders[0];
	   $totalprice = $order_info->my->sumorderstotal;
   	   $promotioncenter['totalprice'] = number_format(floatval($totalprice), 2, ".", "");
	   $promotioncenter['ordercount'] = $orders_query->getTotalCount();  
   } 
   
    
   $onelevelsourcer = XN_Filter( 'my.onelevelsourcer', '=', $profileid);
   $twolevelsourcer = XN_Filter( 'my.twolevelsourcer', '=', $profileid);
   $threelevelsourcer = XN_Filter( 'my.threelevelsourcer', '=', $profileid);
   
   $query = XN_Query::create('Content_count')->tag('supplier_profile_' . $profileid)
       ->filter('type', 'eic', 'supplier_profile')
       ->filter('my.deleted', '=', '0')
       ->filter('my.supplierid', '=', $supplierid)
       ->filter(XN_Filter::any($onelevelsourcer,$twolevelsourcer,$threelevelsourcer))
       ->rollup()
       ->end(-1);
   $query->execute();  
   $promotioncenter['funs'] = $query->getTotalCount(); 
   
   $startdate = date("Y-m-01", strtotime("today")).' 00:00:00'; 
   $enddate = date('Y-m-t', strtotime('this month', time())).' 23:59:59'; 
   $query = XN_Query::create('Content_count')->tag('supplier_profile_' . $profileid)
       ->filter('type', 'eic', 'supplier_profile')
       ->filter('my.deleted', '=', '0')
       ->filter('my.supplierid', '=', $supplierid)
       ->filter('published', '>=', $startdate)
	   ->filter('published', '<=', $enddate)
       ->filter(XN_Filter::any($onelevelsourcer,$twolevelsourcer,$threelevelsourcer))
       ->rollup()
       ->end(-1);
   $query->execute();  
   $promotioncenter['thismonthaddfuns'] = $query->getTotalCount(); 
   
   
   
	 
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
			$storeprofileid = $supplier_physicalstoreprofile_info->my->storeprofileid;
			$physicalstore_info = XN_Content::load($physicalstoreid,"supplier_physicalstores");
			$profile_info['physicalstorename'] = $physicalstore_info->my->storename;
			$assistantprofile_info = get_profile_info($assistantprofileid);
			$profile_info['assistantprofile'] = $assistantprofile_info['givenname']; 
			
			if ($storeprofileid == $profileid)
			{
				$profile_info['isphysicalstore'] = '1'; 
			}
			else
			{
				$profile_info['isphysicalstore'] = '0';
			}
			if ($assistantprofileid == $profileid)
			{
				$profile_info['isassistant'] = '1'; 
			}
			else
			{
				$profile_info['isassistant'] = '0';
			} 
		}
	}
	$smarty->assign("profile_info",$profile_info);
}
else
{
	$smarty->assign("profile_info",get_profile_info());
}

$smarty->assign("badges",$badges);


if ($profile_info['isphysicalstore'] == '1')
{
    $query = XN_Query::create('Content_count')->tag('supplier_physicalstoreprofiles_' . $profileid)
        ->filter('type', 'eic', 'supplier_physicalstoreprofiles')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid) 
        ->filter('my.storeprofileid','=',$profileid) 
        ->rollup()
        ->end(-1);
    $query->execute();  
    $promotioncenter['assistants'] = $query->getTotalCount(); 
   
    $startdate = date("Y-m-01", strtotime("today")).' 00:00:00'; 
    $enddate = date('Y-m-t', strtotime('this month', time())).' 23:59:59'; 
    $query = XN_Query::create('Content_count')->tag('supplier_physicalstoreprofiles_' . $profileid)
        ->filter('type', 'eic', 'supplier_physicalstoreprofiles')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('published', '>=', $startdate)
 	    ->filter('published', '<=', $enddate)
        ->filter('my.storeprofileid','=',$profileid) 
        ->rollup()
        ->end(-1);
    $query->execute();  
    $promotioncenter['thismonthaddassistants'] = $query->getTotalCount(); 
} 
else if ($profile_info['isassistant'] == '1')
{ 
    $query = XN_Query::create('Content_count')->tag('supplier_physicalstoreprofiles_' . $profileid)
        ->filter('type', 'eic', 'supplier_physicalstoreprofiles')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid) 
        ->filter('my.assistantprofileid','=',$profileid) 
        ->rollup()
        ->end(-1);
    $query->execute();  
    $promotioncenter['assistants'] = $query->getTotalCount(); 
   
    $startdate = date("Y-m-01", strtotime("today")).' 00:00:00'; 
    $enddate = date('Y-m-t', strtotime('this month', time())).' 23:59:59'; 
    $query = XN_Query::create('Content_count')->tag('supplier_physicalstoreprofiles_' . $profileid)
        ->filter('type', 'eic', 'supplier_physicalstoreprofiles')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('published', '>=', $startdate)
 	    ->filter('published', '<=', $enddate)
        ->filter('my.assistantprofileid','=',$profileid) 
        ->rollup()
        ->end(-1);
    $query->execute();  
    $promotioncenter['thismonthaddassistants'] = $query->getTotalCount(); 

}


$smarty->assign("promotioncenter",$promotioncenter); 

$sysinfo = array();
$sysinfo['action'] = 'promotioncenter'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>