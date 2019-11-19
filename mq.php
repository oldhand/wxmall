<?php
/*+**********************************************************************************
 * The contents of this file are subject to the 361CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  361CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/util.php");

ini_set('memory_limit','2048M');
set_time_limit(0);

session_start();

XN_Application::$CURRENT_URL = 'admin';

$loopcallbacks = XN_Query::create ( 'MainContent' )
		   ->tag ( 'loopcallback' )
		   ->filter ( 'type', 'eic', 'loopcallback' )
		   ->filter ( 'my.deleted', '=', '0' )
		   ->filter ( 'my.url', '=', '/mq.php' )
		   ->execute ();

if (count($loopcallbacks) == 0)
{
	 if (strpos($_SERVER["SERVER_SOFTWARE"],"nginx") !==false)
	 {
		$domain=$_SERVER['HTTP_HOST'];
		$web_root = $domain;
	 }
	 else
	 {
		$domain=$_SERVER['SERVER_NAME'];
		$web_root = $domain.':'.$_SERVER['SERVER_PORT'];
	 }
   
	 $newcontent = XN_Content::create('loopcallback','',false,4); 
	 $newcontent->my->deleted = 0;    
	 $newcontent->my->url = '/mq.php'; 
	 $newcontent->my->sleep = '30'; 
	 $newcontent->my->webroot = $web_root;  
	 $newcontent->my->status = 'Active';    
	 $newcontent->save('loopcallback');
}
else
{
	$loopcallback_info = $loopcallbacks[0];
	if ($loopcallback_info->my->sleep != "30")
	{   
		 $loopcallback_info->my->sleep = "30";
		 $loopcallback_info->save('loopcallback');
	}
}
 

try {

	$mq_orders = XN_Query::create('mq')->tag("mall_orders")
	    ->filter('type', 'eic', 'mall_orders')  
		->filter('my.status', '=', 'waiting')  
		->end(10)
	    ->execute();
	
	echo 'mall_orders:'.count($mq_orders).'<br>'; 

	foreach($mq_orders as $mq_order_info)
	{
		$orderid = $mq_order_info->my->orderid;
		 
        $order_info = XN_Content::load($orderid, 'mall_orders', 7);
        $paymentamount = $order_info->my->paymentamount;
        $usemoney = $order_info->my->usemoney;
        $profileid = $order_info->my->profileid;
		$supplierid = $order_info->my->supplierid;
		$orderssources = $order_info->my->orderssources;
        $tradestatus = $order_info->my->tradestatus;
        $ordername = $order_info->my->ordername;
		$mobile = $order_info->my->mobile;
		$sumorderstotal = $order_info->my->sumorderstotal;
		$consignee = $order_info->my->consignee;
		$mall_orders_no = $order_info->my->mall_orders_no;
		 
		

        $supplierid = $order_info->my->supplierid;
        $sumorderstotal = floatval($order_info->my->sumorderstotal);
		if ($tradestatus == "trade")
        {
			$mq_order_info->my->status = "ok";
			$mq_order_info->save("mall_orders",2);
			
			if ($orderssources != $profileid)
			{
				$supplier_wxsettings = XN_Query::create ( 'MainContent' ) ->tag('supplier_wxsettings')
					->filter ( 'type', 'eic', 'supplier_wxsettings')
					->filter ( 'my.deleted', '=', '0' )
					->filter ( 'my.supplierid', '=' ,$supplierid)
					->end(1)
					->execute();
				if (count($supplier_wxsettings) > 0)
				{
					$supplier_wxsetting_info = $supplier_wxsettings[0];
					$appid = $supplier_wxsetting_info->my->appid;
					require_once (XN_INCLUDE_PREFIX."/XN/Message.php"); 
					$order_profile_info = get_profile_info($profileid);
					$givenname = $order_profile_info['givenname'];
					$message = '恭喜，您朋友【'.$givenname.'】的订单【'.$mall_orders_no.'】支付成功。';
					XN_Message::sendmessage($orderssources,$message,$appid);  
				} 
			}
			
		    $supplier_users = XN_Query::create ( 'Content' ) ->tag('supplier_users')
		        ->filter ( 'type', 'eic', 'supplier_users')
		        ->filter ( 'my.deleted', '=', '0' )
				->filter ( 'my.supplierid', '=' ,$supplierid)
		        ->filter ( 'my.supplierusertype', 'in' ,array("boss","delivery"))
				->end(-1)
		        ->execute();
		    if (count($supplier_users) > 0)
		    {
				$personman = array();
				foreach($supplier_users as $supplier_user_info)
				{
					$personman[] = $supplier_user_info->my->profileid;
				} 
				$description = "订单号：".$mall_orders_no.";\n";
				$description .= "会员：".$consignee.";\n";
				$description .= "手机号码：".$mobile.";\n";
				$description .= "订单金额：".$sumorderstotal.".\n"; 
				
				 
				$calendar_data = array();
				$calendar_data['supplierid'] = $supplierid;
				$calendar_data['personman'] = $personman;
				$calendar_data['plannedconsumetime'] = '24';
				$calendar_data['timeunit'] = '小时';
				$calendar_data['dotheme'] = '新订单【'.$mall_orders_no.'】发货通知';
				$calendar_data['description'] = $description;
				$calendar_data['tabid'] = '3006';
				$calendar_data['record'] = $orderid; 
				insert_calendar($calendar_data);
		    } 
			
			////
            $orders_products = XN_Query::create('YearContent')->tag('mall_orders_products')
                ->filter('type', 'eic', 'mall_orders_products')
                ->filter('my.deleted', '=', '0')
                ->filter('my.orderid', '=', $orderid)
                ->execute();
			$vendorids = array();
            foreach ($orders_products as $orders_product_info)
            {
                $productid = $orders_product_info->my->productid; 
                $shop_price = $orders_product_info->my->shop_price; 
                $quantity = $orders_product_info->my->quantity;
                $propertydesc = $orders_product_info->my->propertydesc; 
				$vendorid = $orders_product_info->my->vendorid; 
				$productname = $orders_product_info->my->productname; 
				if (isset($vendorid) && $vendorid != "")
				{
					if (isset($vendorids[$vendorid]) && $vendorids[$vendorid] != "")
					{
						 $vendorids[$vendorid] .= "商品名称：".$productname."，数量：".$quantity."件;\n";
					}
					else
					{
						 $vendorids[$vendorid] = "商品名称：".$productname."，数量：".$quantity."件;\n";
					}
				}  
			}
			if (count($vendorids) > 0)
			{
				foreach($vendorids as $vendorid => $product_info)
				{
					 $vendor_info = XN_Content::load($vendorid, 'mall_vendors');
					 $vendorname = $vendor_info->my->vendorname;
					 $profileid = $vendor_info->my->profileid;
					 
	 				$description = "订单号：".$mall_orders_no.";\n";
	 				$description .= "会员：".$consignee.";\n";
	 				$description .= "手机号码：".$mobile.";\n";
	 				$description .= "订单金额：".$sumorderstotal.";\n"; 
				    $description .= $product_info; 
				 
	 				$calendar_data = array();
	 				$calendar_data['supplierid'] = $supplierid;
	 				$calendar_data['personman'] = $profileid;
	 				$calendar_data['plannedconsumetime'] = '24';
	 				$calendar_data['timeunit'] = '小时';
	 				$calendar_data['dotheme'] = $vendorname.'的新订单【'.$mall_orders_no.'】发货通知';
	 				$calendar_data['description'] = $description;
	 				$calendar_data['tabid'] = '3006';
	 				$calendar_data['record'] = $orderid; 
	 				insert_calendar($calendar_data);
					
				}
				
			}
			
		}
	}

} catch (XN_Exception $e) { }

echo 'ok';


function insert_calendar($data)
{
    $supplierid = $data['supplierid'];//提交给哪个商家
    $dotheme = $data['dotheme'];//标题
	$personman = $data['personman'];  //提交给哪个人处理
	$plannedconsumetime = $data['plannedconsumetime'];  //预计时间
	$timeunit = $data['timeunit'];  //时间单位(Minute;Hour;Day)
    $description = $data['description'];//描述
    $tabid = $data['tabid'];//表单tabid
    $record = $data['record'];//记录id


    $newcontent = XN_Content::create('calendar', '', false);
    $newcontent->my->deleted = '0'; 
    $newcontent->my->supplierid = $supplierid; 
	$newcontent->my->dotheme = $dotheme;
	$newcontent->my->doclass = '任务';
    $newcontent->my->startdate = date("Y-m-d H:i");
	$newcontent->my->enddate = '';
	$newcontent->my->swaptime = '';
	$newcontent->my->personman = $personman;
	$newcontent->my->description = $description;
	$newcontent->my->plannedconsumetime = $plannedconsumetime;
	$newcontent->my->timeunit = $timeunit;
	$newcontent->my->tabid = $tabid;
	$newcontent->my->record = $record;
	$newcontent->my->calendarstatus = 'Not implemented';
    if($data['link']!=""){
		$newcontent->my->link = $data['link'];
	}
    $newcontent->save('calendar,calendar_'.$supplierid);
}

?>