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
	messagebox('错误',"没有店铺ID!");
	die();  
}
 
if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' &&
   $_REQUEST['type'] == 'submit')
{ 
	$orderid = $_REQUEST['record']; 
	$imagesserverids = $_REQUEST['imagesserverids'];  
	$reason = $_REQUEST['reason']; 
	
	$orders_info = XN_Content::load($orderid,"mall_orders_".$profileid,7); 
	$aftersaleservicestatus =  $orders_info->my->aftersaleservicestatus; 
	if ($aftersaleservicestatus == "yes")
	{
		header("Location: aftersaleservice.php?record=".$orderid);
		die();
	}
	$returnedgoodsapplys = XN_Query::create ( 'YearContent' )->tag('mall_returnedgoodsapplys_'.$profileid )
					->filter ( 'type', 'eic', 'mall_returnedgoodsapplys' )
					->filter (  'my.orderid', '=',$orderid)
					->filter (  'my.deleted', '=','0')
					->order('published',XN_Order::DESC)
					->end(1)
					->execute ();
	if (count($returnedgoodsapplys) > 0)
	{
		header("Location: aftersaleservice.php?record=".$orderid);
		die();
	}
	$order_status = $orders_info->my->order_status;
	$orders_info->my->aftersaleservicestatus = 'yes';
	$orders_info->my->old_order_status = $order_status;
	$orders_info->my->order_status = '退货中';
	$orders_info->my->aftersaleservices_time = date("Y-m-d H:i");
	$orders_info->save("mall_orders,mall_orders_".$profileid.",mall_orders_".$supplierid);
	
	global $wxsetting,$WX_APPID; 
	
	$prev_inv_no = XN_ModentityNum::get("Mall_ReturnedGoodsApplys");  
	
	$newcontent = XN_Content::create('mall_returnedgoodsapplys','',false,7);
	$newcontent->my->mall_returnedgoodsapplys_no  = $prev_inv_no; 
	$newcontent->my->supplierid = $supplierid; 
    $newcontent->my->orderid = $orderid; 
	$newcontent->my->profileid = $profileid; 
	$newcontent->my->reason = $reason;
	$newcontent->my->hasimages = '0';
	$newcontent->my->returnedgoodsquantity = '';
	$newcontent->my->returnedgoodsamount = '';
	$newcontent->my->operator = '';
	$newcontent->my->mall_returnedgoodsapplysstatus = "JustCreated";
	$newcontent->my->deleted = '0';
	$newcontent->save("mall_returnedgoodsapplys,mall_returnedgoodsapplys_".$orderid.",mall_returnedgoodsapplys_".$supplierid.",mall_returnedgoodsapplys_".$profileid);
	$returnedgoodsapplyid = $newcontent->id;
	
	$mediaids = json_decode($imagesserverids);
	require_once (XN_INCLUDE_PREFIX."/XN/Wx.php"); 
	global $wxsetting;
	XN_WX::$APPID = $wxsetting['appid'];
	XN_WX::$SECRET = $wxsetting['secret'];
	$images = array();
	foreach($mediaids as $mediaid_info)
	{  
		$image = XN_WX::downloadimage($mediaid_info,$returnedgoodsapplyid,"Mall_ReturnedGoodsApplys");
		if ($image != "")
		{
			$images[] = $image;
		} 
	}  
	if (count($images) > 0)
	{
		$newcontent->my->images = $images;
		$newcontent->my->hasimages = count($images);
		$newcontent->save("mall_returnedgoodsapplys,mall_returnedgoodsapplys_".$orderid.",mall_returnedgoodsapplys_".$profileid);
 	}   
	$orders_products = XN_Query::create ( 'YearContent' )->tag('mall_orders_products_'.$profileid )
					->filter ( 'type', 'eic', 'mall_orders_products' )
					->filter (  'my.orderid', '=',$orderid)
					->filter (  'my.deleted', '=','0')
					->end(-1)
					->execute ();
	$ordersproductids = array();
	foreach($orders_products as $orders_product_info)
	{ 
		 $orders_productid = $orders_product_info->id;
		 $shop_price = $orders_product_info->my->shop_price;
		 if (isset($_REQUEST['qty_item_'.$orders_productid]) && $_REQUEST['qty_item_'.$orders_productid] != "")
		 {
		 	$returnedgoodsquantity = $_REQUEST['qty_item_'.$orders_productid];
			$returnedgoodsamount = intval($returnedgoodsquantity) * $shop_price;
		 }
		 else
		 {
		 	$returnedgoodsquantity = '0';
			$returnedgoodsamount = '0';
		 } 
	 	 $newcontent = XN_Content::create('mall_returnedgoodsapplys_products','',false,7);
	 	 $newcontent->my->supplierid = $supplierid; 
	     $newcontent->my->orderid = $orderid; 
		 $newcontent->my->returnedgoodsapplyid = $returnedgoodsapplyid;
	 	 $newcontent->my->profileid = $profileid; 
	 	 $newcontent->my->orders_productid = $orders_product_info->id;
		 $newcontent->my->productid = $orders_product_info->my->productid; 
		 $newcontent->my->shop_price = $orders_product_info->my->shop_price; 
		 $newcontent->my->quantity = $orders_product_info->my->quantity; 
		 $newcontent->my->total_price = $orders_product_info->my->total_price;
		 $newcontent->my->propertydesc = $orders_product_info->my->propertydesc; 
		 $newcontent->my->product_property_id = $orders_product_info->my->product_property_id; 
		 $newcontent->my->returnedgoodsquantity = $returnedgoodsquantity; 
		 $newcontent->my->returnedgoodsamount = $returnedgoodsamount; 
		 $newcontent->my->deleted = '0';
	 	 $newcontent->save("mall_returnedgoodsapplys_products,mall_returnedgoodsapplys_products_".$orderid.",mall_returnedgoodsapplys_products_".$supplierid.",mall_returnedgoodsapplys_products_".$profileid);
	     
		 $orders_productid = $orders_product_info->id;
		 $mall_settlementorders = XN_Query::create("YearContent")->tag("mall_settlementorders_".$supplierid)
	         ->filter("type","eic","mall_settlementorders") 
			 ->filter("my.orders_productid","=",$orders_productid)
	 		 ->filter("my.deleted","=",'0')
	         ->end(1)
	         ->execute();
		 if (count($mall_settlementorders) > 0)
		 {
	         $mall_settlementorder_info = $mall_settlementorders[0];
	         $mall_settlementorder_info->my->mall_settlementordersstatus = '退货中';
	 		 $mall_settlementorder_info->my->vendorsettlementstatus = '4';
			 $quantity = $mall_settlementorder_info->my->quantity;
			 $vendor_price = $mall_settlementorder_info->my->vendor_price;
			 $mall_settlementorder_info->my->returnedquantity = $returnedgoodsquantity;
			 $vendormoney = floatval($vendor_price) * (floatval($quantity) - floatval($returnedgoodsquantity));
			 $mall_settlementorder_info->my->vendormoney = $vendormoney;
			 $mall_settlementorder_info->save("mall_settlementorders,mall_settlementorders_".$supplierid); 
	     }
	}
	
    
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
	$paymentamount = $order_info->my->paymentamount;
	$usemoney = $order_info->my->usemoney; 
	$sumorderstotal = $order_info->my->sumorderstotal;  
	$order_no = $order_info->my->mall_orders_no;
	$orderinfo = array();
	$orderinfo['orderid'] = $order_info->id;
	$orderinfo['order_no'] = $order_no; 
	$orderinfo['tradestatus'] = $order_info->my->tradestatus;
	$orderinfo['paymentamount'] = number_format($paymentamount,2,".","");
	$orderinfo['usemoney'] = number_format($usemoney,2,".","");
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
	$orderinfo['aftersaleservicestatus'] = $order_info->my->aftersaleservicestatus;
	$orderinfo['returnedgoodsstatus'] = $order_info->my->returnedgoodsstatus;
	$orderinfo['aftersaleservices_time'] = $order_info->my->aftersaleservices_time;
	
	
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
	
	$confirmreceipt = $order_info->my->confirmreceipt;
	$orderinfo['confirmreceipt'] = $confirmreceipt;
	if ($confirmreceipt == "receipt")
	{  
		$confirmreceipt_time = $order_info->my->confirmreceipt_time;
		$orderinfo['confirmreceipt_time'] = $confirmreceipt_time;
		$timeinfo = datediff('+7days',$confirmreceipt_time);
		if ($timeinfo != "timeout")
		{
			$orderinfo['autosettlement'] = $timeinfo."后,系统自动结算提成。<br>解冻您的提成收益，退货流程终结！";  
		} 
		else
		{
			$orderinfo['autosettlement'] = "timeout";
		}
	}  
	
	$returnedgoodsapplys = XN_Query::create ( 'YearContent' )->tag('mall_returnedgoodsapplys_'.$profileid )
					->filter ( 'type', 'eic', 'mall_returnedgoodsapplys' )
					->filter (  'my.orderid', '=',$orderid)
					->filter (  'my.deleted', '=','0')
					->order('published',XN_Order::DESC)
					->end(1)
					->execute ();
	if (count($returnedgoodsapplys) > 0)
	{
		$returnedgoodsapply_info = $returnedgoodsapplys[0];
		$orderinfo['returnedgoodsapplyid'] = $returnedgoodsapply_info->id;
		$orderinfo['reason'] = $returnedgoodsapply_info->my->reason;
		$orderinfo['images'] = $returnedgoodsapply_info->my->images;
		$operator = $returnedgoodsapply_info->my->operator;
		$orderinfo['operator'] = get_profile_givenname($operator);
		$orderinfo['returnedgoodsamount'] = $returnedgoodsapply_info->my->returnedgoodsamount;
		$orderinfo['returnedgoodsquantity'] = $returnedgoodsapply_info->my->returnedgoodsquantity; 
		$returnedgoodsapplysstatus = $returnedgoodsapply_info->my->mall_returnedgoodsapplysstatus;
		switch($returnedgoodsapplysstatus)
		{
			case "JustCreated":
			   $orderinfo['returnedgoodsapplysstatus'] = '待处理';
			   $orderinfo['tipmsg'] = '您的退货请求已经提交，请等待商家进行确认。';
			break; 
			case "已退货": 
			   $orderinfo['returnedgoodsapplysstatus'] = $returnedgoodsapplysstatus;
			   $orderinfo['tipmsg'] = '您的退货请求已经办理，<br>并等待下一步退款操作。';
			break;
			case "已退款": 
			   $orderinfo['returnedgoodsapplysstatus'] = $returnedgoodsapplysstatus;
			   $orderinfo['tipmsg'] = '您的退款已经办理，请查收！';
			break;
			case "不退货": 
			case "换货": 
			   $orderinfo['returnedgoodsapplysstatus'] = $returnedgoodsapplysstatus;
			   $orderinfo['tipmsg'] = '您的退货请求已经处理完成。';
			break;
			default:
			   $orderinfo['returnedgoodsapplysstatus'] = $returnedgoodsapplysstatus;
			   $orderinfo['tipmsg'] = '商家已经确认，请耐心等待下一步操作！';
			break;
		}
		
		 
	}
	
	
	$ordersproducts = array();
	
	$orders_products = XN_Query::create ( 'YearContent' )->tag('mall_orders_products_'.$profileid )
					->filter ( 'type', 'eic', 'mall_orders_products' )
					->filter (  'my.orderid', '=',$orderid)
					->filter (  'my.deleted', '=','0')
					->end(-1)
					->execute ();
	$ordersproductids = array();
	foreach($orders_products as $orders_product_info)
	{
		$returnedgoodsapplys_products = XN_Query::create ( 'YearContent' )->tag('mall_returnedgoodsapplys_products_'.$profileid )
						->filter ( 'type', 'eic', 'mall_returnedgoodsapplys_products' )
						->filter (  'my.orders_productid', '=',$orders_product_info->id)
						->filter (  'my.deleted', '=','0')
						->end(-1)
						->execute ();
	    if (count($returnedgoodsapplys_products) > 0)
		{
			$returnedgoodsapplys_product_info = $returnedgoodsapplys_products[0];
			$returnedgoodsquantity = $returnedgoodsapplys_product_info->my->returnedgoodsquantity;
		}
		else
		{
			$returnedgoodsquantity = '0';
		}
		$product_info = array(
			'id' => $orders_product_info->id,
			'productid' => $orders_product_info->my->productid,
			'productname' => $orders_product_info->my->productname,
			'productthumbnail' => $orders_product_info->my->productthumbnail,
			'quantity' => $orders_product_info->my->quantity,
			'shop_price' => $orders_product_info->my->shop_price,
			'market_price' => $orders_product_info->my->market_price, 
			'total_price' => $orders_product_info->my->total_price,
			'product_property_id' => $orders_product_info->my->product_property_id,
			'propertydesc' => $orders_product_info->my->propertydesc, 
			'returnedgoodsquantity' => $returnedgoodsquantity, 
		 );  
		 $ordersproducts[] = $product_info;
		 $ordersproductids[] = $orders_product_info->id;
	}
	$orderinfo['orders_products'] = $ordersproducts;
	//print_r($orderinfo);
	//die();
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
$smarty->assign("ordersproductids",json_encode($ordersproductids)); 


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