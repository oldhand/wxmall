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
 


 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$orderid = $_REQUEST['record'];  
}
else
{
	messagebox('错误','参数错误。');
	die(); 
} 
try{   
	 
	$order_info = XN_Content::load($orderid,"mall_orders_".$profileid,7); 
	  
	if($_REQUEST['type'] =='confirmreceipt')
	{
		$confirmreceipt = $order_info->my->confirmreceipt;
		$supplierid = $order_info->my->supplierid;
	 	if ($confirmreceipt != 'receipt')
	 	{ 
		 	$order_info->my->confirmreceipt = "receipt"; 
			$order_info->my->needconfirmreceipt = "yes";  
		 	$order_info->my->confirmreceipttype = "self";
		 	$order_info->my->confirmreceipt_time = date("Y-m-d H:i");
		 	$order_info->my->order_status = "确认收货";
			$order_info->my->membersettlement = "0"; 
		 	$order_info->save('mall_orders,mall_orders_'.$profileid.",mall_orders_".$supplierid);  
			
			 $mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
				    ->filter('type', 'eic', 'mall_logisticbills')
				    ->filter('my.deleted', '=', '0') 
				    ->filter('my.supplierid', '=', $supplierid)  
				    ->filter('my.orderid', '=', $orderid)  
				    ->end(-1)
				    ->execute();
			foreach($mall_logisticbills as $mall_logisticbill_info)
			{
				 	$logisticroute_info = XN_Content::create('mall_logisticroutes','',false,7);
		            $logisticroute_info->my->deleted = '0';
					$logisticroute_info->my->supplierid = $supplierid;  
					$logisticroute_info->my->logisticbillid = $mall_logisticbill_info->id; 
					$logisticroute_info->my->route = "购货人确认收货签收。";
		            $logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
			}    
	 	} 
		
	}
	
	$paymentamount = $order_info->my->paymentamount;
	
	$usemoney = $order_info->my->usemoney; 
	$sumorderstotal = $order_info->my->sumorderstotal;  
	$order_no = $order_info->my->mall_orders_no;
	$orderinfo = array();
	$orderinfo['orderid'] = $order_info->id;
	$orderinfo['order_no'] = $order_no; 
	$orderinfo['tradestatus'] = $order_info->my->tradestatus;
	$orderinfo['appraisestatus'] = $order_info->my->appraisestatus;
	$orderinfo['paymentamount'] = number_format($paymentamount,2,".","");
	$orderinfo['usemoney'] = number_format($usemoney,2,".","");
	$orderinfo['sumorderstotal'] = number_format($sumorderstotal,2,".",""); 
	$orderinfo['confirmreceipt_time'] = $order_info->my->confirmreceipt_time;
	$orderinfo['deliveraddressid'] = $order_info->my->deliveraddressid; 
    $orderinfo['address'] = $order_info->my->address; 
    $orderinfo['province'] = $order_info->my->province;
    $orderinfo['city'] = $order_info->my->city;
    $orderinfo['district'] = $order_info->my->district;
    $orderinfo['consignee'] = $order_info->my->consignee;
    $orderinfo['mobile'] =  $order_info->my->mobile; 
    $orderinfo['zipcode'] = $order_info->my->zipcode; 
	
    $orderinfo['platform'] = $order_info->my->platform;  
	$orderinfo['distance'] = $order_info->my->distance; 
	$orderinfo['delivermode'] = $order_info->my->delivermode;  
    $orderinfo['tradestatus'] = $order_info->my->tradestatus; 
    $orderinfo['customersmsg'] = $order_info->my->customersmsg;   
    $orderinfo['order_status'] = $order_info->my->order_status;
    $orderinfo['payment'] = $order_info->my->payment; 
    $orderinfo['paymenttime'] = $order_info->my->paymenttime; 
	
	$orderinfo['delivermode'] = $order_info->my->delivermode;
	$orderinfo['paymentmode'] = $order_info->my->paymentmode;
    $orderinfo['paymentway'] = $order_info->my->paymentway;
	
	
	
	$ordersproducts = array();
	
	$orders_products = XN_Query::create ( 'YearContent' )->tag('mall_orders_products_'.$profileid )
					->filter ( 'type', 'eic', 'mall_orders_products' )
					->filter (  'my.orderid', '=',$orderid)
					->filter (  'my.deleted', '=','0')
					->end(-1)
					->execute ();
	foreach($orders_products as $orders_product_info)
	{
		$product_info = array(
			'id' => $orders_product_info->id,
			'productid' => $orders_product_info->my->productid,
			'productname' => $orders_product_info->my->productname,
			'productthumbnail' => $orders_product_info->my->productthumbnail,
			'praiseid' => $orders_product_info->my->praiseid,
			'quantity' => $orders_product_info->my->quantity,
			'shop_price' => number_format($orders_product_info->my->shop_price,2,".",""), 
			'market_price' => number_format($orders_product_info->my->market_price,2,".",""),
			'total_price' => number_format($orders_product_info->my->total_price,2,".",""), 
			'product_property_id' => $orders_product_info->my->product_property_id,
			'propertydesc' => $orders_product_info->my->propertydesc, 
			'old_shop_price' => $orders_product_info->my->old_shop_price, 
			'zhekou' => $orders_product_info->my->zhekou, 
			'salesactivityid' => $orders_product_info->my->salesactivityid, 
			'salesactivitys_product_id' => $orders_product_info->my->salesactivitys_product_id, 
			'zhekoulabel' => $orders_product_info->my->zhekoulabel, 
		 );  
		 $ordersproducts[] = $product_info;
	}
	$orderinfo['orders_products'] = $ordersproducts;
	
	 
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
$smarty->assign("profile_info",get_supplier_profile_info()); 

 
$smarty->assign("orderinfo",$orderinfo); 

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