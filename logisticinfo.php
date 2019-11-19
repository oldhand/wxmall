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
 

try
{ 
	if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'logisticpointreceive' &&
	   isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] != '' &&
	   isset($_REQUEST['profileid']) && $_REQUEST['profileid'] != '' &&
	   isset($_REQUEST['packageid']) && $_REQUEST['packageid'] != '')
	{
		
	    $supplierid = $_REQUEST['supplierid']; 
	    $_SESSION['supplierid'] = $supplierid; 
		$profileid = $_REQUEST['profileid'];
		$packageid = $_REQUEST['packageid'];
		
		$logisticpackage_info = XN_Content::load($packageid,'mall_logisticpackages_' . $supplierid);
		$serialname = $logisticpackage_info->my->serialname;
		
		$mall_logisticpoints = XN_Query::create('Content')->tag('mall_logisticpoints')
	        ->filter('type', 'eic', 'mall_logisticpoints')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.profileid', '=', $profileid)
	        ->filter('my.approvalstatus', '=', '2')
	        ->filter('my.status', '=', '0')
	        ->end(1)
	        ->execute();
		if (count($mall_logisticpoints) > 0)
		{
			 $mall_logisticpoint_info = $mall_logisticpoints[0];
			 $pointname = $mall_logisticpoints->my->pointname;
			 $managername = $mall_logisticpoints->my->managername;
			 $mobile = $mall_logisticpoints->my->mobile;
			 $address = $mall_logisticpoints->my->address;
		 
			 
			 $mall_logistictrip_packages = XN_Query::create('YearContent')->tag('mall_logistictrip_packages_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logistictrip_packages')
			    ->filter('my.deleted', '=', '0') 
			    ->filter('my.status', '=', '0')
			    ->filter('my.supplierid', '=', $supplierid)   
			    ->filter('my.logisticpackageid', '=', $packageid)
			    ->end(1)
			    ->execute();
			if (count($mall_logistictrip_packages) > 0)
			{  
				$mall_logistictrip_package_info = $mall_logistictrip_packages[0];
				$logistictripid = $mall_logistictrip_package_info->my->logistictripid;
				$receivestatus = $mall_logistictrip_package_info->my->receivestatus;
				if ($receivestatus != "1")
				{
					$mall_logistictrip_package_info->my->receivestatus = '1';
					$mall_logistictrip_package_info->save('mall_logistictrip_packages,mall_logistictrip_packages_' . $supplierid);
					$mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
						    ->filter('type', 'eic', 'mall_logisticbills')
						    ->filter('my.deleted', '=', '0') 
						    ->filter('my.supplierid', '=', $supplierid)  
						    ->filter('my.logistictripid', '=', $logistictripid)  
						    ->filter('my.logisticpackageid', '=', $packageid)
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
							$logisticroute_info->my->route = "配送点【".$pointname."】已经接收。负责人:".$managername.",".$mobile.",地址:".$address;
				            $logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
					} 
				}
			}
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
		$supplier_info = get_supplier_info();
		$smarty->assign("supplier_info", $supplier_info);  
		$smarty->assign("package_info", array()); 
		$smarty->assign("bill_info", array());  
		
		$smarty->assign("type", 'logisticpointreceive'); 		 
		 
		$smarty->display($action . '.tpl');
		die();
	}
	
	if(isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] != '' &&
	   isset($_REQUEST['billid']) && $_REQUEST['billid'] != '')
	{
		$url = "supplierid=".$_REQUEST['supplierid']."&billid=".$_REQUEST['billid']; 
		$token = base64_encode($url);
		$_REQUEST['token'] = $token;
	}
	$package_info = array();
	$bill_info = array();
	if(isset($_REQUEST['token']) && $_REQUEST['token'] != '')
	{
		$query_string = base64_decode($_REQUEST['token']);
		parse_str($query_string, $params);
		 
		if(isset($params['packageid']) && $params['packageid'] != '' &&
		   isset($params['supplierid']) && $params['supplierid'] != '')
		{
			$supplierid = $params['supplierid']; 
			$_SESSION['supplierid'] = $supplierid;
			$packageid = $params['packageid'];  
			
			$package_info['packageid'] = $packageid;
			$package_info['logisticpointreceive'] = 'close'; 
 	 
		    
			/*$supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
		        ->filter('type', 'eic', 'supplier_wxsettings')
		        ->filter('my.deleted', '=', '0')
		        ->filter('my.supplierid', '=', $supplierid)
		        ->end(1)
		        ->execute();
		    if (count($supplier_wxsettings) > 0)
		    {
		        $supplier_wxsetting_info = $supplier_wxsettings[0];
		        $appid = $supplier_wxsetting_info->my->appid; 
		        $secret = $supplier_wxsetting_info->my->secret;  
		        $package_info['logisticpointreceive'] = 'open';
				$package_info['profileid'] = 'hx5eyjjmlg6';
		   		require_once dirname(__FILE__).'/wxoauth2.php';
				WxOauth2::$APPID = $appid;
				WxOauth2::$APPSECRET = $secret;
				$wxopenid = WxOauth2::GetOpenid();
				$wxopenids = XN_Query::create('MainContent')->tag("profile_" . $profileid)
			        ->filter('type', 'eic', 'wxopenids') 
			        ->filter('my.wxopenid', '=', $wxopenid)
			        ->end(1)
			        ->execute();
			    if (count($wxopenids) == 0)
			    {
					$wxopenid_info = $wxopenids[0];
					$profileid = $wxopenid_info->my->profileid;
					$mall_logisticpoints = XN_Query::create('Content')->tag('mall_logisticpoints')
				        ->filter('type', 'eic', 'mall_logisticpoints')
				        ->filter('my.deleted', '=', '0')
				        ->filter('my.supplierid', '=', $supplierid)
				        ->filter('my.profileid', '=', $profileid)
				        ->filter('my.approvalstatus', '=', '2')
				        ->filter('my.status', '=', '0')
				        ->end(1)
				        ->execute();
					if (count($mall_logisticpoints) > 0)
					{
						$package_info['logisticpointreceive'] = 'open';
						$package_info['profileid'] = $profileid;
					}
				}
			}  */
			
			$logisticpackage_info = XN_Content::load($packageid,'mall_logisticpackages_' . $supplierid);
			$package_info['serialname'] = $logisticpackage_info->my->serialname;
			
			$supplier_info = get_supplier_info();
			$mylogisticname = $supplier_info['mylogisticname'];
			 
			
			$mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logistictrips')
			    ->filter('my.deleted', '=', '0')  
			    ->filter('my.supplierid', '=', $supplierid)   
			    ->filter('my.logistictripstatus', 'in', array('0','1','2'))
			    ->filter('my.logisticpackageid', '=', $packageid)
			    ->end(1)
			    ->execute();
			if (count($mall_logistictrips) > 0)
			{ 
				$mall_logistictrip_info = $mall_logistictrips[0];   
				$logistictripid = $mall_logistictrip_info->id;
				$logistictrip_info = array();
				$logistictrip_info['mall_logistictrips_no'] = $mall_logistictrip_info->my->mall_logistictrips_no;
				$logistictrip_info['published'] = $mall_logistictrip_info->published;
				$logistictrip_info['startdate'] = $mall_logistictrip_info->my->startdate;
				$logistictrip_info['enddate'] = $mall_logistictrip_info->my->enddate;
				$logistictrip_info['swaptime'] = $mall_logistictrip_info->my->swaptime;
				$logistictrip_info['billcount'] = $mall_logistictrip_info->my->billcount; 
				$logistictripstatus = $mall_logistictrip_info->my->logistictripstatus;
				$logistictrip_info['logistictripstatus'] = $logistictripstatus;  
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
				$driverid  = $mall_logistictrip_info->my->driverid;
				$logistictrip_info['driverid'] = $driverid;
				if (isset($driverid) && $driverid != "")
				{
					$logistictrips_info = XN_Content::load($driverid,"mall_logistictrips_".$supplierid);
					$logistictrip_info['drivername'] = $logistictrips_info->my->drivername;
				}
				else
				{
					$logistictrip_info['drivername'] = "";
				} 
				
				$package_info['logistictrip'] = $logistictrip_info; 
				$logisticbill_info = array();
		
				$mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
				    ->filter('type', 'eic', 'mall_logisticbills')
				    ->filter('my.deleted', '=', '0') 
				    ->filter('my.supplierid', '=', $supplierid)    
					->filter('my.logistictripid', '=', $logistictripid)    
				    ->filter('my.logisticpackageid', '=', $packageid) 
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
						$logisticbill_info[$vendorid]['bills'][$billid]['mall_logisticbillsstatus'] = '已揽件';
					}
					else
					{
						$logisticbill_info['other']['vendorname'] = $mylogisticname;
						$logisticbill_info['other']['bills'][$billid]['logisticbills_no'] = $mall_logisticbill_info->my->logisticbills_no;
						$logisticbill_info['other']['bills'][$billid]['mall_logisticbillsstatus'] = '已揽件';
					}
				}    
				$package_info['logisticbills'] = $logisticbill_info; 
			} 
			 
		   $logisticbill_info = array();
		   $mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logisticbills')
			    ->filter('my.deleted', '=', '0') 
			    ->filter('my.supplierid', '=', $supplierid)   
				->filter('my.mall_logisticbillsstatus', '=', 'JustCreated')   
			    ->filter('my.logisticpackageid', '=', $packageid) 
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
					$logisticbill_info[$vendorid]['bills'][$billid]['mall_logisticbillsstatus'] = '等待揽件';
				}
				else
				{
					$logisticbill_info['other']['vendorname'] = $mylogisticname;
					$logisticbill_info['other']['bills'][$billid]['logisticbills_no'] = $mall_logisticbill_info->my->logisticbills_no;
					$logisticbill_info['other']['bills'][$billid]['mall_logisticbillsstatus'] = '等待揽件';
				}
			}   
		
			$package_info['bills'] = $logisticbill_info;
			
		} 
		if(isset($params['billid']) && $params['billid'] != '' &&
		   isset($params['supplierid']) && $params['supplierid'] != '')
		{
			$supplierid = $params['supplierid']; 
			$_SESSION['supplierid'] = $supplierid;
			$billid = $params['billid'];
			$bill_info['billid'] = $billid;
			
			$mall_logisticbills_info = XN_Content::load($billid,'mall_logisticbills_' . $supplierid,7);
			 
			$bill_info['logisticbills_no'] = $mall_logisticbills_info->my->logisticbills_no;
			$bill_info['published'] = $mall_logisticbills_info->published;
			$bill_info['orderid'] = $mall_logisticbills_info->my->orderid; 
			$bill_info['consignee'] = $mall_logisticbills_info->my->consignee; 
			$bill_info['mobile'] = $mall_logisticbills_info->my->mobile; 
			$bill_info['province'] = $mall_logisticbills_info->my->province; 
			$bill_info['city'] = $mall_logisticbills_info->my->city; 
			$bill_info['district'] = $mall_logisticbills_info->my->district; 
			$address = $mall_logisticbills_info->my->address; 
			$newaddress = str_replace(array($bill_info['province'],$bill_info['city'],$bill_info['district']),array ('','',''),$address);
			$bill_info['address'] = $newaddress; 
			$bill_info['mall_logisticbillsstatus'] = $mall_logisticbills_info->my->mall_logisticbillsstatus; 
			$bill_info['logisticdriverid'] = $mall_logisticbills_info->my->logisticdriverid; 
			$bill_info['logistictripid'] = $mall_logisticbills_info->my->logistictripid;
			$bill_info['logisticpackageid'] = $mall_logisticbills_info->my->logisticpackageid; 
			
			if (isset($bill_info['orderid']) && $bill_info['orderid'] != "")
			{
				$mall_order_info = XN_Content::load($bill_info['orderid'],'mall_orders_' . $supplierid,7);
				$mall_orders_no = $mall_order_info->my->mall_orders_no;
				$bill_info['mall_orders_no'] = $mall_orders_no;
			}
			if (isset($bill_info['logisticdriverid']) && $bill_info['logisticdriverid'] != "")
			{
				$mall_logisticdriver_info = XN_Content::load($bill_info['logisticdriverid'],'mall_logisticdrivers_' . $supplierid,7);
				$drivername = $mall_logisticdriver_info->my->drivername;
				$bill_info['drivername'] = $drivername;
			}
			if (isset($bill_info['logistictripid']) && $bill_info['logistictripid'] != "")
			{
				$mall_logistictrips_info = XN_Content::load($bill_info['logistictripid'],'mall_logistictrips_' . $supplierid,7);
				$mall_logistictrips_no = $mall_logistictrips_info->my->mall_logistictrips_no;
				$bill_info['mall_logistictrips_no'] = $mall_logistictrips_no;
			}
			if (isset($bill_info['logisticpackageid']) && $bill_info['logisticpackageid'] != "")
			{
				$mall_logisticpackages_info = XN_Content::load($bill_info['logisticpackageid'],'mall_logisticpackages_' . $supplierid);
				$serialname = $mall_logisticpackages_info->my->serialname;
				$bill_info['serialname'] = $serialname;
			}
			$mall_logisticroutes =   XN_Query::create ( 'YearContent' )->tag("mall_logisticroutes_".$supplierid)
				->filter ( 'type', 'eic', 'mall_logisticroutes' ) 
				->filter('my.supplierid', '=', $supplierid)   
				->filter ( 'my.logisticbillid', '=', $billid )
				->filter ( 'my.deleted', '=', '0' )
				->order('published',XN_Order::ASC)
				->end(-1)
				->execute (); 
			$logisticroutes = array();
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
			$bill_info['logisticroutes'] = $logisticroutes;
		}
	}
	else
	{
		messagebox('错误', "错误的token!");
		die();
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
if  (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '' && $_SESSION['supplierid'] != '0')
{  
	$supplier_info = get_supplier_info();
	$smarty->assign("supplier_info", $supplier_info);  
}

$smarty->assign("package_info", $package_info); 
$smarty->assign("bill_info", $bill_info); 

if(isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] != '' &&
   isset($_REQUEST['billid']) && $_REQUEST['billid'] != '')
{
	$smarty->assign("showbackaction", 'true'); 
}
 
$smarty->display($action . '.tpl');
 

?>