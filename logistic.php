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
    $errormsg = "";
	$supplier_info = get_supplier_info();
	$logistic_info = array();
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
			$logisticdriver_info = $logisticdrivers[0];
			$logistic_info['type'] = 'logisticdriver';
			$logistic_info['driverid'] = $logisticdriver_info->id;
			$logistic_info['drivername'] = $logisticdriver_info->my->drivername;
			$logistic_info['vehiclemodel'] = $logisticdriver_info->my->vehiclemodel;
			$logistic_info['licenseplate'] = $logisticdriver_info->my->licenseplate;
		}
		else 
		{ 
		   	 	messagebox('错误', '没有适合您的操作。');
		        die(); 
		}
	
	}
	else
	{
   	 	messagebox('错误', '您必须在商城设置中开启物流模块。');
        die();
	}
	if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'tripcreate') 
	{
	    $mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
	        ->filter('type', 'eic', 'mall_logistictrips')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.profileid', '=', $profileid) 
	        ->filter('my.logistictripstatus', 'in', array('0','1','2'))
	        ->end(1)
	        ->execute();
		if (count($mall_logistictrips) == 0)
		{
			$prev_inv_no = XN_ModentityNum::get("Mall_LogisticTrips");
		   
		    $logisticbill_info = XN_Content::create('mall_logistictrips','',false,7);
            $logisticbill_info->my->deleted = '0';
			$logisticbill_info->my->supplierid = $supplierid;
			$logisticbill_info->my->profileid = $profileid;
			$logisticbill_info->my->driverid = $logistic_info['driverid'];
			$logisticbill_info->my->mall_logistictrips_no = $prev_inv_no; 
            $logisticbill_info->my->startdate = "";
			$logisticbill_info->my->enddate = "";
			$logisticbill_info->my->swaptime = "0";
			$logisticbill_info->my->billcount = "0";
			$logisticbill_info->my->packagecount = "0";
			$logisticbill_info->my->logistictripstatus = "0";
            $logisticbill_info->my->mall_logistictripsstatus = 'JustCreated';  
            $logisticbill_info->save('mall_logistictrips,mall_logistictrips_'.$supplierid);
			
		}
		 
	}
	else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'start') 
	{
		$mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
	        ->filter('type', 'eic', 'mall_logistictrips')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.profileid', '=', $profileid) 
	        ->filter('my.logistictripstatus', '=', '1')
	        ->end(1)
	        ->execute();
		if (count($mall_logistictrips) > 0)
		{
			 $logisticbill_info = $mall_logistictrips[0];
			 $logisticbill_info->my->startdate = date("Y-m-d H:i:s"); 
			 $logisticbill_info->my->logistictripstatus = '2'; 
			 $logisticbill_info->my->mall_logistictripsstatus = 'Start'; 
			 $logisticbill_info->save('mall_logistictrips,mall_logistictrips_'.$supplierid); 
			 	
			 $logistictripid = $logisticbill_info->id;
			 
			 $mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
				    ->filter('type', 'eic', 'mall_logisticbills')
				    ->filter('my.deleted', '=', '0') 
				    ->filter('my.supplierid', '=', $supplierid)  
				    ->filter('my.logistictripid', '=', $logistictripid)  
				    ->end(-1)
				    ->execute();
			foreach($mall_logisticbills as $mall_logisticbill_info)
			{
				 	$logisticroute_info = XN_Content::create('mall_logisticroutes','',false,7);
		            $logisticroute_info->my->deleted = '0';
					$logisticroute_info->my->supplierid = $supplierid;  
					$logisticroute_info->my->logisticbillid = $mall_logisticbill_info->id; 
					$logisticroute_info->my->route = "物流车辆已经出发。";
		            $logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
			}    		
		}
		
		
	}
	else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'uploadlocation') 
	{
		if(isset($_REQUEST['latitude']) && $_REQUEST['latitude'] != '' && 
		   isset($_REQUEST['longitude']) && $_REQUEST['longitude'] != '') 
		   {
			     require_once (XN_INCLUDE_PREFIX."/XN/Wx.php");
	 
				 $latitude = $_REQUEST['latitude'];
				 $longitude = $_REQUEST['longitude'];
			 	 $location = XN_WX::geocoder($latitude,$longitude);
			 	 
			 	 $address = $location['formatted_address'];
			 	 $newaddress = str_replace(array($location['province'],$location['city']),array ('',''),$address);
	     
			 	 if (count($location) > 0)
			 	 { 
				 	 $mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
				        ->filter('type', 'eic', 'mall_logistictrips')
				        ->filter('my.deleted', '=', '0')
				        ->filter('my.supplierid', '=', $supplierid)
				        ->filter('my.profileid', '=', $profileid) 
				        ->filter('my.logistictripstatus', '=', '2')
				        ->end(1)
				        ->execute();
					if (count($mall_logistictrips) > 0)
					{
						 $logisticbill_info = $mall_logistictrips[0]; 						 	
						 $logistictripid = $logisticbill_info->id;
						 
						 $mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
							    ->filter('type', 'eic', 'mall_logisticbills')
							    ->filter('my.deleted', '=', '0') 
							    ->filter('my.supplierid', '=', $supplierid)  
							    ->filter('my.logistictripid', '=', $logistictripid)  
							    ->end(-1)
							    ->execute();
						foreach($mall_logisticbills as $mall_logisticbill_info)
						{
							 	$logisticroute_info = XN_Content::create('mall_logisticroutes','',false,7);
					            $logisticroute_info->my->deleted = '0';
								$logisticroute_info->my->supplierid = $supplierid;  
								$logisticroute_info->my->logisticbillid = $mall_logisticbill_info->id; 
								$logisticroute_info->my->route = "物流车辆到达".$newaddress."。";
					            $logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
						}    		
					} 
			 		 
			 	 }
		   }
	}
	else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'end') 
	{
		$mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
	        ->filter('type', 'eic', 'mall_logistictrips')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.profileid', '=', $profileid) 
	        ->filter('my.logistictripstatus', '=', '2')
	        ->end(1)
	        ->execute();
		if (count($mall_logistictrips) > 0)
		{
			 $logisticbill_info = $mall_logistictrips[0];
			 $logistictripid = $logisticbill_info->id;
			 
			 
			 $startdate = $logisticbill_info->my->startdate;
			 $logisticbill_info->my->enddate = date("Y-m-d H:i:s");  
		     $now = date_create("now");
		     $diff = date_diff(date_create($startdate),$now);
	     	 $logisticbill_info->my->swaptime = $diff->format("%h 小时 %i 分钟"); 
	     	 $logisticbill_info->my->logistictripstatus = '3'; 
			 $logisticbill_info->my->mall_logistictripsstatus = 'End'; 
			 $logisticbill_info->save('mall_logistictrips,mall_logistictrips_'.$supplierid); 
				 	
				 
				 
			 $mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
				    ->filter('type', 'eic', 'mall_logisticbills')
				    ->filter('my.deleted', '=', '0') 
				    ->filter('my.supplierid', '=', $supplierid)  
				    ->filter('my.logistictripid', '=', $logistictripid)  
				    ->end(-1)
				    ->execute();
			foreach($mall_logisticbills as $mall_logisticbill_info)
			{
				    $mall_logisticbill_info->my->mall_logisticbillsstatus = "End";
				    $mall_logisticbill_info->save('mall_logisticbills,mall_logisticbills_'.$supplierid);
				    
				 	$logisticroute_info = XN_Content::create('mall_logisticroutes','',false,7);
		            $logisticroute_info->my->deleted = '0';
					$logisticroute_info->my->supplierid = $supplierid;  
					$logisticroute_info->my->logisticbillid = $mall_logisticbill_info->id; 
					$logisticroute_info->my->route = "物流车辆已经抵达。";
		            $logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
			}  
		} 
	}
	else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'selectpackage') 
	{
		if(isset($_REQUEST['logistictripid']) && $_REQUEST['logistictripid'] != '' &&
		   isset($_REQUEST['logisticpackageid']) && $_REQUEST['logisticpackageid'] != '') 
		{
			$logistictripid = $_REQUEST['logistictripid'];
			$logisticpackageid = $_REQUEST['logisticpackageid'];
			$logistictrip_info = XN_Content::load($logistictripid,"mall_logistictrips_".$supplierid,7);
			if ($logistictrip_info->my->logisticpackageid != $logisticpackageid)
			{
				$logistictrip_info->my->logisticpackageid = $logisticpackageid;
				$logistictrip_info->save("mall_logistictrips,mall_logistictrips_".$supplierid);
			} 
		}
	
	}
 	 
 	 
 	$logistictrip_info = array();
 	 
    $mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
        ->filter('type', 'eic', 'mall_logistictrips')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.profileid', '=', $profileid) 
        ->filter('my.logistictripstatus', 'in', array('0','1','2'))
        ->end(1)
        ->execute();
	if (count($mall_logistictrips) > 0)
	{
		$mall_logistictrip_info = $mall_logistictrips[0];
		
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
	
		$logistictrip_info['logistictripid'] = $mall_logistictrip_info->id;
		
		
		$logisticpackageid  = $mall_logistictrip_info->my->logisticpackageid;
		$logistictrip_info['logisticpackageid'] = $logisticpackageid;
		if (isset($logisticpackageid) && $logisticpackageid != "")
		{
			$logisticpackage_info = XN_Content::load($logisticpackageid,"mall_logisticpackages_".$supplierid);
			$logistictrip_info['serialname'] = $logisticpackage_info->my->serialname;
		}
		else
		{
			$logistictrip_info['serialname'] = "";
		} 
		 
		$logistictrip_info['logisticpackageid'] = $logisticpackageid;
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

$smarty->assign("logistic_info", $logistic_info); 
$smarty->assign("logistictrip_info", $logistictrip_info); 
$smarty->assign("errormsg", $errormsg);


$mall_logisticpackages = XN_Query::create ( 'Content' )->tag('mall_logisticpackages_'.$supplierid)
    ->filter ( 'type', 'eic', 'mall_logisticpackages') 
    ->filter('my.deleted','=','0')
	->filter('my.status','=','0')  
    ->end(-1)
    ->execute(); 
$logisticpackages = array();
foreach ($mall_logisticpackages as $logisticpackage_info)
{ 
	$logisticpackages[$logisticpackage_info->id] = $logisticpackage_info->my->serialname;
}
$smarty->assign("logisticpackages", $logisticpackages); 

$smarty->display($action . '.tpl');


?>