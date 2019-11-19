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
 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$orderid = $_REQUEST['record'];  
}
else
{
	messagebox('错误','参数错误。');
	die(); 
}
$supplier_info = get_supplier_info();

try{
	$order_info = XN_Content::load($orderid,"mall_orders_".$profileid,7);   
	$paymentamount = $order_info->my->paymentamount;
	$usemoney = $order_info->my->usemoney; 
	$discount = $order_info->my->discount;
	$sumorderstotal = $order_info->my->sumorderstotal;  
	$order_no = $order_info->my->mall_orders_no;
	$orderinfo = array();
	$orderinfo['orderid'] = $order_info->id;
	$orderinfo['order_no'] = $order_no; 
	$orderinfo['tradestatus'] = $order_info->my->tradestatus;
	$orderinfo['confirmreceipt'] = $order_info->my->confirmreceipt; 
	$orderinfo['needconfirmreceipt'] = $order_info->my->needconfirmreceipt; 
	$orderinfo['aftersaleservicestatus'] = $order_info->my->aftersaleservicestatus; 
	
	
	$orderinfo['paymentamount'] = number_format($paymentamount,2,".","");
	$orderinfo['usemoney'] = number_format($usemoney,2,".","");
	$orderinfo['discount'] = number_format($discount,2,".","");
	$orderinfo['sumorderstotal'] = number_format($sumorderstotal,2,".",""); 
	
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
	$orderinfo['addpostage'] = $order_info->my->addpostage;
	 
 
	$confirmreceipt_time = $order_info->my->confirmreceipt_time;
	$paymenttime = $order_info->my->paymenttime;
	$aftersaleservicestatus = $order_info->my->aftersaleservicestatus;
	$returnedgoodsstatus = $order_info->my->returnedgoodsstatus;
	$confirmreceipt = $order_info->my->confirmreceipt;
	

	$deliveryid = $order_info->my->delivery;
	if (isset($deliveryid) && $deliveryid != "")
	{
		$orderinfo['invoicenumber'] = $order_info->my->invoicenumber;
		$orderinfo['delivery'] = $order_info->my->delivery;
		$orderinfo['deliverytime'] = $order_info->my->deliverytime;
		$loadcontent = XN_Content::load($order_info->my->delivery,"logistics");
		$orderinfo['deliveryname'] = $loadcontent->my->logisticsname;
	}
	else
	{
		$orderinfo['delivery'] = "";
	}  
	
	if ($returnedgoodsstatus == 'no' && $aftersaleservicestatus == 'no')
	{
		if ($confirmreceipt == 'receipt') 
		{ 
			$confirmreceipt_time = $order_info->my->confirmreceipt_time;
			$orderinfo['confirmreceipt_time'] = $confirmreceipt_time;
			
			$timeinfo = datediff('+7days',$confirmreceipt_time);
			if ($timeinfo != "timeout")
			{
				$orderinfo['autosettlement'] = $timeinfo."后,系统自动结算提成。<br>解冻您的提成收益，退货流程终结！";  
			} 
		}
		else
		{
			$timeinfo = datediff('+7days',$paymenttime);
			if ($timeinfo != "timeout")
			{
				$orderinfo['autoconfirmreceipt'] = $timeinfo."后,系统自动确认收货。"; 
			}	
			
		} 
	} 
	$ordersproducts = array();
	function productquantity($contents, $productid){
		$pct = 0;
		foreach($contents as $info){
			if($info->my->productid == $productid){
				$pct += intval($info->my->quantity);
			}
		}
		return $pct;
	}

	$orders_products = XN_Query::create ( 'YearContent' )->tag('mall_orders_products_'.$profileid )
					->filter ( 'type', 'eic', 'mall_orders_products' )
					->filter (  'my.orderid', '=',$orderid)
					->filter (  'my.deleted', '=','0')
					->end(-1)
					->execute ();
	$totalpricefreeshipping = 0;
	$totalquantityfreeshipping = 0;
	foreach ($orders_products as $orders_product_info){
		$totalpricefreeshipping += floatval($orders_product_info->my->shop_price) * intval($orders_product_info->my->quantity);
		$totalquantityfreeshipping += intval($orders_product_info->my->quantity);
	}
	foreach($orders_products as $orders_product_info)
	{
		$activitymode          = intval($orders_product_info->my->activitymode);
		$bargains_count        = intval($orders_product_info->my->bargains_count);
		$bargainrequirednumber = intval($orders_product_info->my->bargainrequirednumber);
		$salesactivityid       = $orders_product_info->my->salesactivityid;
		$productid             = $orders_product_info->my->productid;
		$total_price           = floatval($orders_product_info->my->total_price);
		$zhekou                = floatval($orders_product_info->my->zhekou);
		$old_shop_price        = floatval($orders_product_info->my->old_shop_price);
		if((!isset($orderinfo['tradestatus']) || $orderinfo['tradestatus'] != "trade") && isset($zhekou) && $zhekou > 0){
			if(intval($activitymode) === 1){
				$bargains_products = XN_Query::create("YearContent_Count")->tag("mall_bargains")
											 ->filter("type", "eic", "mall_bargains")
											 ->filter("my.salesactivityid", "=", $salesactivityid)
											 ->filter("my.productid", "=", $productid)
											 ->filter("my.supplierid", "=", $supplierid)
											 ->filter("my.profileid", "=", $profileid)
											 ->filter("my.bargain", "=", '1')
											 ->rollup()
											 ->end(-1);
				$bargains_products->execute();
				$bargains_count = intval($bargains_products->getTotalCount());
				if($bargains_count > $bargainrequirednumber){
					$bargains_count = $bargainrequirednumber;
				}
				$total_price = $old_shop_price - $old_shop_price * (10 - $zhekou) / 10 / $bargainrequirednumber * $bargains_count;
			}
		}
		$productallcount 	 = productquantity($orders_products,$productid);
		$product_info = array (
			'id'                        => $orders_product_info->id,
			'productid'                 => $productid,
			'productallcount'			=> $productallcount,
			'productname'               => $orders_product_info->my->productname,
			'productthumbnail'          => $orders_product_info->my->productthumbnail,
			'quantity'                  => $orders_product_info->my->quantity,
			'shop_price'                => $orders_product_info->my->shop_price,
			'market_price'              => $orders_product_info->my->market_price,
			'total_price'               => number_format($total_price, 2, ".", ""),
			'product_property_id'       => $orders_product_info->my->product_property_id,
			'propertydesc'              => $orders_product_info->my->propertydesc,
			'old_shop_price'            => number_format($old_shop_price, 2, ".", ""),
			'zhekou'                    => number_format($zhekou, 2, ".", ""),
			'salesactivityid'           => $salesactivityid,
			'salesactivitys_product_id' => $orders_product_info->my->salesactivitys_product_id,
			'zhekoulabel'               => $orders_product_info->my->zhekoulabel,
			'activitymode'              => $activitymode,
			'bargains_count'            => $bargains_count,
			'bargainrequirednumber'     => $bargainrequirednumber,
		);
		if((floatval($order_info->my->totalpricefreeshipping) <= 0 || floatval($order_info->my->totalpricefreeshipping) > $totalpricefreeshipping) &&
		   (intval($order_info->my->totalquantityfreeshipping) <= 0 || intval($order_info->my->totalquantityfreeshipping) > $totalquantityfreeshipping))
		{
			$product_info["postage"] = $orders_product_info->my->postage;
			$product_info["includepost"] = $orders_product_info->my->includepost;
			$product_info["mergepostage"] = $orders_product_info->my->mergepostage;
		}else{
			$product_info["postage"] = "0";
			$product_info["includepost"] = "0";
			$product_info["mergepostage"] = "0";
		}
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
$smarty->assign("supplier_info",$supplier_info);
$smarty->assign("profile_info",get_supplier_profile_info()); 

 
$smarty->assign("orderinfo",$orderinfo); 

$sysinfo = array();
$sysinfo['action'] = 'shoppingcart'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>