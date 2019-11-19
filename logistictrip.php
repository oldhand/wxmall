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

require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");


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
 

try
{
	 if(isset($_REQUEST['record']) && $_REQUEST['record'] != '')
	 {
		    $logistictripid = $_REQUEST['record'];
		    $supplier_info = get_supplier_info();
			$logistictrip_info = array(); 			 
		    
			$mall_logistictrip_info = XN_Content::load($logistictripid,'mall_logistictrips_' . $supplierid,7);
			$logistictripid = $mall_logistictrip_info->id;
			$logistictrip_info['mall_logistictrips_no'] = $mall_logistictrip_info->my->mall_logistictrips_no;
			$logistictrip_info['published'] = $mall_logistictrip_info->published;
			$logistictrip_info['startdate'] = $mall_logistictrip_info->my->startdate;
			$logistictrip_info['enddate'] = $mall_logistictrip_info->my->enddate;
			$logistictrip_info['swaptime'] = $mall_logistictrip_info->my->swaptime;
			$logistictrip_info['billcount'] = $mall_logistictrip_info->my->billcount; 
			$logistictrip_info['logistictripstatus'] = $mall_logistictrip_info->my->logistictripstatus;
			$mall_logistictripsstatus = $mall_logistictrip_info->my->mall_logistictripsstatus;
			if ($mall_logistictripsstatus == "JustCreated")
			{
				$logistictrip_info['mall_logistictripsstatus'] = "刚刚创建";
			} 
			else if ($mall_logistictripsstatus == "Packing")
			{
				$logistictrip_info['mall_logistictripsstatus'] = "正在装箱";
			} 
			else if ($mall_logistictripsstatus == "Start")
			{
				$logistictrip_info['mall_logistictripsstatus'] = "在途";
			} 
			else if ($mall_logistictripsstatus == "End")
			{
				$logistictrip_info['mall_logistictripsstatus'] = "已经到达";
			}
			$logisticpackageid  = $mall_logistictrip_info->my->logisticpackageid;
			$logistic_info['logisticpackageid'] = $logisticpackageid;
			if (isset($logisticpackageid) && $logisticpackageid != "")
			{
				$logisticpackage_info = XN_Content::load($logisticpackageid,"mall_logisticpackages_".$supplierid);
				$logistictrip_info['serialname'] = $logisticpackage_info->my->serialname;
			}
			else
			{
				$logistictrip_info['serialname'] = "";
			} 
			
			$logisticbill_info = array();
		
			$mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logisticbills')
			    ->filter('my.deleted', '=', '0') 
			    ->filter('my.supplierid', '=', $supplierid)    
				->filter('my.logistictripid', '=', $logistictripid)    
			    ->filter('my.logisticpackageid', '=', $logisticpackageid) 
			    ->end(-1)
			    ->execute();  
			foreach($mall_logisticbills as $mall_logisticbill_info)
			{
				$billid = $mall_logisticbill_info->id;   
				$vendorid = $mall_logisticbill_info->my->vendorid;
				if (isset($vendorid) && $vendorid != "")
				{
					$vendor_info = XN_Content::load($vendorid,"mall_vendors_".$supplierid);
					$vendorname = $vendor_info->my->vendorname;
					$logisticbill_info[$vendorid]['vendorname'] = $vendorname;
					$logisticbill_info[$vendorid]['bills'][$billid]['logisticbills_no'] = $mall_logisticbill_info->my->logisticbills_no;
				}
				else
				{
					$logisticbill_info['other']['vendorname'] = '';
					$logisticbill_info['other']['bills'][$billid]['logisticbills_no'] = $mall_logisticbill_info->my->logisticbills_no;
				}
			} 
			$logistictrip_info['bills'] = $logisticbill_info;
			
			 
	 } 	 
}
catch (XN_Exception $e)
{
    $msg = $e->getMessage();
    messagebox('错误', $msg);
    die();
}


require_once('Smarty_setup.php');

$smarty = new vtigerCRM_Smarty;

$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
    $islogined = true;
}
$smarty->assign("islogined", $islogined);

$action = strtolower(basename(__FILE__, ".php"));

$recommend_info = checkrecommend();
$smarty->assign("share_info", $recommend_info);
$smarty->assign("supplier_info", $supplier_info);
$smarty->assign("profile_info", get_supplier_profile_info());  

$smarty->assign("logistictrip_info", $logistictrip_info); 

 

$smarty->display($action . '.tpl');
 

?>