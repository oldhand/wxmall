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
		   ->filter ( 'my.url', '=', '/orders.php' )
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
	 $newcontent->my->url = '/orders.php'; 
	 $newcontent->my->sleep = '300'; 
	 $newcontent->my->webroot = $web_root;  
	 $newcontent->my->status = 'Active';    
	 $newcontent->save('loopcallback');
}
else
{
	$loopcallback_info = $loopcallbacks[0];
	if ($loopcallback_info->my->sleep != "300")
	{   
		 $loopcallback_info->my->sleep = "300";
		 $loopcallback_info->save('loopcallback');
	}
}
 

try {

    $time = strtotime("now");
	//$time = strtotime('-7 days',$time);
	

	$orders = XN_Query::create('YearContent')->tag('mall_orders')
					->filter('type','eic','mall_orders')
					->filter('my.deliverystatus','=','sendout')
					->filter('my.order_status','=','已发货')
					->filter('my.deliverytime','<',date("Y-m-d H:i",$time))
					->filter('my.supplierid','=','266184')
					->begin(0)->end(-1) 
					->execute();

	foreach($orders as $order_info)
	{
		$profileid = $order_info->my->profileid;
		$supplierid = $order_info->my->supplierid;
		$confirmreceipt = $order_info->my->confirmreceipt;
		if ($confirmreceipt != 'receipt')
		{
			$order_info->my->confirmreceipt = "receipt";
			$order_info->my->needconfirmreceipt = "no";
			$order_info->my->confirmreceipttype = "timeout";
			$order_info->my->confirmreceipt_time = date("Y-m-d H:i");
			$order_info->my->order_status = "确认收货";
			$order_info->my->membersettlement = "0";
			$order_info->save('mall_orders,mall_orders_'.$profileid.',mall_orders_'.$supplierid);
		}
		$orderid = $order_info->id;
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
				$logisticroute_info->my->route = "系统自动签收。";
				$logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
		}
	}
		
		
	$time = strtotime("now");
	//$time = strtotime('-7 days',$time);

	$orders = XN_Query::create('YearContent')->tag('mall_orders')
					->filter('type','eic','mall_orders')
					->filter('my.confirmreceipt','=','receipt')
					->filter("my.order_status","=","确认收货")
					->filter("my.membersettlement","=","0")
					->filter('my.supplierid','=','266184')
					->filter('my.confirmreceipt_time','<',date("Y-m-d H:i",$time))
					->begin(0)->end(200)
					->order("published",XN_Order::DESC)
					->execute();

	foreach($orders as $order_info)
	{
			$orderid = $order_info->id;
			$membersettlement= $order_info->my->membersettlement;
			$profileid = $order_info->my->profileid;
			$supplierid = $order_info->my->supplierid;

			$order_info->my->membersettlement = "1";
			$order_info->my->membersettlement_time = date("Y-m-d H:i");
			$order_info->save('mall_orders,mall_orders_'.$profileid.',mall_orders_'.$supplierid);
			// 结算新机制
			$commissions = XN_Query::create('YearContent')
							->filter('type','eic','mall_commissions')
							->filter('my.orderid','=',$orderid)
							->filter('my.commissiontype','=','0')
							->filter('my.deleted','=','0')
							->execute();

			foreach($commissions as $commission_info)
			{
				$profileid = $commission_info->my->profileid;
				$amount = $commission_info->my->amount;
				$supplierid = $order_info->my->supplierid;

				$commission_info->my->commissiontype = '1';
				$commission_info->my->settlementtime = date("Y-m-d H:i");
				$commission_info->save('mall_commissions,mall_commissions_'.$profileid.',mall_commissions_'.$supplierid);


				$profile_info = get_supplier_profile_info($profileid,$supplierid);

				$money = $profile_info['money'];
				$new_money = floatval($money) + floatval($amount);
				$accumulatedmoney = $profile_info['accumulatedmoney'];
				$new_accumulatedmoney = floatval($accumulatedmoney) + floatval($amount);


				$billwater_info = XN_Content::create('mall_billwaters','',false,8);
				$billwater_info->my->deleted = '0';
				$billwater_info->my->supplierid = $supplierid;
				$billwater_info->my->profileid = $profileid;
				$billwater_info->my->billwatertype = 'commission';
				$billwater_info->my->submitdatetime = date("Y-m-d H:i");
				$billwater_info->my->sharedate = '';
				$billwater_info->my->orderid = $orderid;
				$billwater_info->my->shareid = '';
				$billwater_info->my->amount = '+'.number_format($amount,2,".","");
				$billwater_info->my->money = number_format($new_money,2,".","");
				$billwater_info->save('mall_billwaters,mall_billwaters_'.$profileid.',mall_billwaters_'.$supplierid);


				$profile_info['money'] = $new_money;
				$profile_info['accumulatedmoney'] = $new_accumulatedmoney;

				$supplierinfo = get_supplier_info($supplierid);
				$takecashitem = $supplierinfo['takecashitem'];
				if (in_array('0',$takecashitem))
				{
					$maxtakecash = $profile_info['maxtakecash'];
					$new_maxtakecash = floatval($maxtakecash) + floatval($amount);
					$profile_info['maxtakecash'] = $new_maxtakecash;
				}

				update_supplier_profile_info($profile_info);

				require_once (XN_INCLUDE_PREFIX."/XN/Message.php");
				$message = '您有'.number_format($amount,2,".","").'元的提成金已经到账。';


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
					//XN_Message::sendmessage($profileid,$message,$appid);
				}


			}

			// 供应商机制
			$settlementorders = XN_Query::create('YearContent')
							->filter('type','eic','mall_settlementorders')
							->filter('my.orderid','=',$orderid)
							->filter('my.vendortradestatus','=','0')
							->filter('my.deleted','=','0')
							->execute();

			foreach($settlementorders as $settlementorder_info)
			{
				$profileid = $settlementorder_info->my->profileid;
				$supplierid = $settlementorder_info->my->supplierid;
				$deliverystatus = $settlementorder_info->my->deliverystatus;
				if ($deliverystatus == '1')
				{
					$settlementorder_info->my->vendortradestatus = '1';
					$settlementorder_info->my->vendorsettlementstatus = '1';
					$settlementorder_info->my->mall_settlementordersstatus = '可结算';
					$settlementorder_info->save('mall_settlementorders,mall_settlementorders_'.$profileid.',mall_settlementorders_'.$supplierid);
				}
			}
		} 
		
		
	    ////支付成功后直接发放分销佣金
	    $mall_settings = XN_Query::create('Content')->tag('mall_settings')
	        ->filter('type', 'eic', 'mall_settings')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.commissionmode', '=', '1')
	        ->begin(0)->end(-1)
	        ->execute();
	    foreach ($mall_settings as $mall_setting_info)
	    {
	        $supplierid = $mall_setting_info->my->supplierid;
	        $supplier_info = get_supplier_info($supplierid);

	        $orders = XN_Query::create('YearContent')->tag('mall_orders')
	            ->filter('type', 'eic', 'mall_orders')
	            ->filter('my.deleted', '=', '0')
	            ->filter('my.tradestatus', '=', 'trade')
	            ->filter("my.membersettlement", "=", "0")
	            ->filter('my.supplierid', '=', $supplierid)
	            ->begin(0)->end(200)
	            ->order("published", XN_Order::DESC)
	            ->execute();

	        foreach ($orders as $order_info)
	        {
	            $orderid = $order_info->id;
	            $membersettlement = $order_info->my->membersettlement;
	            $profileid = $order_info->my->profileid;
	            $supplierid = $order_info->my->supplierid;

	            $order_info->my->membersettlement = "1";
	            $order_info->my->membersettlement_time = date("Y-m-d H:i");
	            $order_info->save('mall_orders,mall_orders_' . $profileid . ',mall_orders_' . $supplierid);
	            // 结算新机制
	            $commissions = XN_Query::create('YearContent')
	                ->filter('type', 'eic', 'mall_commissions')
	                ->filter('my.orderid', '=', $orderid)
	                ->filter('my.commissiontype', '=', '0')
	                ->filter('my.deleted', '=', '0')
	                ->execute();

	            foreach ($commissions as $commission_info)
	            {
	                $profileid = $commission_info->my->profileid;
	                $amount = $commission_info->my->amount;
	                $supplierid = $order_info->my->supplierid;

	                $commission_info->my->commissiontype = '1';
	                $commission_info->my->settlementtime = date("Y-m-d H:i");
	                $commission_info->save('mall_commissions,mall_commissions_' . $profileid . ',mall_commissions_' . $supplierid);


	                $profile_info = get_supplier_profile_info($profileid, $supplierid);

	                $money = $profile_info['money'];
	                $new_money = floatval($money) + floatval($amount);
	                $accumulatedmoney = $profile_info['accumulatedmoney'];
	                $new_accumulatedmoney = floatval($accumulatedmoney) + floatval($amount);

	                $maxtakecash = $profile_info['maxtakecash'];
	                $new_maxtakecash = floatval($maxtakecash) + floatval($amount);


	                $billwater_info = XN_Content::create('mall_billwaters', '', false, 8);
	                $billwater_info->my->deleted = '0';
	                $billwater_info->my->supplierid = $supplierid;
	                $billwater_info->my->profileid = $profileid;
	                $billwater_info->my->billwatertype = 'commission';
	                $billwater_info->my->submitdatetime = date("Y-m-d H:i");
	                $billwater_info->my->sharedate = '';
	                $billwater_info->my->orderid = $orderid;
	                $billwater_info->my->shareid = '';
	                $billwater_info->my->amount = '+' . number_format($amount, 2, ".", "");
	                $billwater_info->my->money = number_format($new_money, 2, ".", "");
	                $billwater_info->save('mall_billwaters,mall_billwaters_' . $profileid . ',mall_billwaters_' . $supplierid);


	                $profile_info['money'] = $new_money;
	                $profile_info['accumulatedmoney'] = $new_accumulatedmoney;
	                $profile_info['maxtakecash'] = $new_maxtakecash;

	                update_supplier_profile_info($profile_info);

	                require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
	                $message = '您有' . number_format($amount, 2, ".", "") . '元的分销佣金已经到账。';


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
	                    XN_Message::sendmessage($profileid, $message, $appid);
	                } 
	            }
				// 供应商机制
				$settlementorders = XN_Query::create('YearContent')
	                            ->filter('type','eic','mall_settlementorders')
	                            ->filter('my.orderid','=',$orderid) 
								->filter('my.vendortradestatus','=','0') 
	                            ->filter('my.deleted','=','0')  
							    ->execute();
		
	            foreach($settlementorders as $settlementorder_info)
				{
	                $profileid = $settlementorder_info->my->profileid;   
					$supplierid = $settlementorder_info->my->supplierid; 
					$deliverystatus = $settlementorder_info->my->deliverystatus;
					if ($deliverystatus == '1')
					{
		                $settlementorder_info->my->vendortradestatus = '1';  
						$settlementorder_info->my->vendorsettlementstatus = '1';  
						$settlementorder_info->my->mall_settlementordersstatus = '可结算';
						$settlementorder_info->save('mall_settlementorders,mall_settlementorders_'.$profileid.',mall_settlementorders_'.$supplierid);  
					}
				}  
	        }

	    }
		
		
	    ////支付成功后处理有可能一直冻结的分销佣金
	    $mall_settings = XN_Query::create('Content')->tag('mall_settings')
	        ->filter('type', 'eic', 'mall_settings')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.commissionmode', '=', '1')
	        ->begin(0)->end(-1)
	        ->execute();
	    foreach ($mall_settings as $mall_setting_info)
	    {
	        $supplierid = $mall_setting_info->my->supplierid; 
			
            $commissions = XN_Query::create('YearContent')
                ->filter('type', 'eic', 'mall_commissions')
                ->filter('my.supplierid', '=', $supplierid)
                ->filter('my.commissiontype', '=', '0')
                ->filter('my.deleted', '=', '0')
                ->execute();

            foreach ($commissions as $commission_info)
            {
                $profileid = $commission_info->my->profileid;
                $amount = $commission_info->my->amount;
                $orderid = $commission_info->my->orderid;
				 

                $commission_info->my->commissiontype = '1';
                $commission_info->my->settlementtime = date("Y-m-d H:i");
                $commission_info->save('mall_commissions,mall_commissions_' . $profileid . ',mall_commissions_' . $supplierid);


                $profile_info = get_supplier_profile_info($profileid, $supplierid);

                $money = $profile_info['money'];
                $new_money = floatval($money) + floatval($amount);
                $accumulatedmoney = $profile_info['accumulatedmoney'];
                $new_accumulatedmoney = floatval($accumulatedmoney) + floatval($amount);

                $maxtakecash = $profile_info['maxtakecash'];
                $new_maxtakecash = floatval($maxtakecash) + floatval($amount);


                $billwater_info = XN_Content::create('mall_billwaters', '', false, 8);
                $billwater_info->my->deleted = '0';
                $billwater_info->my->supplierid = $supplierid;
                $billwater_info->my->profileid = $profileid;
                $billwater_info->my->billwatertype = 'commission';
                $billwater_info->my->submitdatetime = date("Y-m-d H:i");
                $billwater_info->my->sharedate = '';
                $billwater_info->my->orderid = $orderid;
                $billwater_info->my->shareid = '';
                $billwater_info->my->amount = '+' . number_format($amount, 2, ".", "");
                $billwater_info->my->money = number_format($new_money, 2, ".", "");
                $billwater_info->save('mall_billwaters,mall_billwaters_' . $profileid . ',mall_billwaters_' . $supplierid);


                $profile_info['money'] = $new_money;
                $profile_info['accumulatedmoney'] = $new_accumulatedmoney;
                $profile_info['maxtakecash'] = $new_maxtakecash;

                update_supplier_profile_info($profile_info);

                require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
                $message = '您有' . number_format($amount, 2, ".", "") . '元的分销佣金已经到账。';


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
                    XN_Message::sendmessage($profileid, $message, $appid);
                } 
				// 供应商机制
				$settlementorders = XN_Query::create('YearContent')
	                            ->filter('type','eic','mall_settlementorders')
	                            ->filter('my.orderid','=',$orderid) 
								->filter('my.vendortradestatus','=','0') 
	                            ->filter('my.deleted','=','0')  
							    ->execute();
	
	            foreach($settlementorders as $settlementorder_info)
				{
	                $profileid = $settlementorder_info->my->profileid;   
					$supplierid = $settlementorder_info->my->supplierid; 
					$deliverystatus = $settlementorder_info->my->deliverystatus;
					if ($deliverystatus == '1')
					{
		                $settlementorder_info->my->vendortradestatus = '1';  
						$settlementorder_info->my->vendorsettlementstatus = '1';  
						$settlementorder_info->my->mall_settlementordersstatus = '可结算';
						$settlementorder_info->save('mall_settlementorders,mall_settlementorders_'.$profileid.',mall_settlementorders_'.$supplierid);  
					}
				}  
            }
			
		}
		
	    /////////  支付成功后,自动发货充值卡
       $mall_settings = XN_Query::create('Content')->tag('mall_settings')
	        ->filter('type', 'eic', 'mall_settings')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.autodeliverrechargeablecard', '=', '1')
	        ->begin(0)->end(-1)
	        ->execute();
	    foreach ($mall_settings as $mall_setting_info)
	    {
	        $supplierid = $mall_setting_info->my->supplierid;
	        $supplier_info = get_supplier_info($supplierid);
		
		 
			
		    $logistics = XN_Query::create('Content')->tag('logistics')
		        ->filter('type', 'eic', 'logistics')
		        ->filter('my.deleted', '=', '0')
		        ->filter('my.logisticsname', '=', '充值卡')
		        ->end(1)
		        ->execute();
			if (count($logistics) > 0)
			{
				$logistic_info = $logistics[0];
				$logisticid = $logistic_info->id;
			}
			else
			{
				$newcontent = XN_Content::create('logistics','',false);					  
				$newcontent->my->deleted = '0';
				$newcontent->my->logisticsname = '充值卡';
				$newcontent->my->telphone = '';
				$newcontent->my->site = '';
				$newcontent->my->status = 'Active';
				$newcontent->my->sequence = '100'; 
				$newcontent->my->description = '';
				$newcontent->save('logistics'); 
				$logisticid = $newcontent->id;
			}

	        $orders = XN_Query::create('YearContent')->tag('mall_orders_'.$supplierid)
	            ->filter('type', 'eic', 'mall_orders')
	            ->filter('my.deleted', '=', '0')
	            ->filter('my.tradestatus', '=', 'trade')
	            ->filter("my.delivery_status", "=", "0")
	            ->filter('my.supplierid', '=', $supplierid)
	            ->begin(0)->end(200)
	            ->order("published", XN_Order::DESC)
	            ->execute();

	        foreach ($orders as $order_info)
	        {
	            $orderid = $order_info->id;
	            $profileid = $order_info->my->profileid;
	            $supplierid = $order_info->my->supplierid;


	            /*$order_info->my->order_status = "已发货";
	            $order_info->my->deliverystatus = "sendout";
	            $order_info->my->delivery_status = "1";
	            $order_info->my->invoicenumber = $_REQUEST['invoicenumber']; //物流单号
	            $order_info->my->delivery = $_REQUEST['delivery'];
	            $order_info->my->deliverytime = date('Y-m-d H:i:s');*/

	            //$order_info->save('mall_orders,mall_orders_' . $profileid . ',mall_orders_' . $supplierid);
	            $deliver = true;
	            $ordersproducts = array();
	            $pos = 0;
	            $orders_products = XN_Query::create ( 'YearContent' )->tag('mall_orders_products_'.$supplierid)
	                ->filter ( 'type', 'eic', 'mall_orders_products' )
	                ->filter (  'my.orderid', '=',$orderid)
	                ->filter (  'my.deleted', '=','0')
	                ->end(-1)
	                ->execute ();
	            foreach($orders_products as $orders_product_info)
	            {
	                $productid = $orders_product_info->my->productid;
	                $quantity = $orders_product_info->my->quantity;
	                $ordersproducts[$pos]['productid'] = $productid;
	                $ordersproducts[$pos]['quantity'] = $quantity;
	                $ordersproducts[$pos]['orders_product_id'] = $orders_product_info->id;

	                $mall_rechargeablecards = XN_Query::create ( 'YearContent' )->tag('mall_rechargeablecards_'.$supplierid)
	                    ->filter ( 'type', 'eic', 'mall_rechargeablecards' )
	                    ->filter (  'my.supplierid', '=',$supplierid)
	                    ->filter (  'my.productid', '=',$productid)
	                    ->filter (  'my.mall_rechargeablecardsstatus', '=','OnShelf')
	                    ->filter (  'my.deleted', '=','0')
	                    ->end($quantity)
	                    ->execute ();
	                if (count($mall_rechargeablecards) < $quantity)
	                {
	                    $deliver = false;
	                }
	                $ordersproducts[$pos]['rechargeablecard'] = $mall_rechargeablecards; 
	                $pos ++; 
	            }
				if ($deliver)
				{   
					$invoicenumber = "";
					foreach($ordersproducts as $ordersproduct_info)
					{
		                $productid = $ordersproduct_info['productid'];
		                $quantity = $ordersproduct_info['quantity'];
		                $orders_product_id = $ordersproduct_info['orders_product_id'];
						$mall_rechargeablecards = $ordersproduct_info['rechargeablecard'];
					 
						foreach($mall_rechargeablecards as $mall_rechargeablecard_info)
						{
							$username = $mall_rechargeablecard_info->my->username;
							$password = $mall_rechargeablecard_info->my->password;
							$invoicenumber .= "账号:".$username.",密码:".$password.";";
							$mall_rechargeablecard_info->my->mall_rechargeablecardsstatus = "Used";
							$mall_rechargeablecard_info->my->deliverdatetime = date('Y-m-d H:i:s');
							$mall_rechargeablecard_info->my->orderid = $orderid;
							$mall_rechargeablecard_info->my->orders_product_id = $orders_product_id;
							$mall_rechargeablecard_info->save('mall_rechargeablecards,mall_rechargeablecards_' . $supplierid); 
						} 
					}
					$order_info->my->order_status = "已发货";
		            $order_info->my->deliverystatus = "sendout";
		            $order_info->my->delivery_status = "1";
		            $order_info->my->invoicenumber = $invoicenumber; //物流单号
		            $order_info->my->delivery = $logisticid;
		            $order_info->my->deliverytime = date('Y-m-d H:i:s');

		            $order_info->save('mall_orders,mall_orders_' . $profileid . ',mall_orders_' . $supplierid);
				
					$smsCon = '您的订单'.$focus->my->mall_orders_no.'已经发货,充值卡信息:'.$invoicenumber;
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
				        XN_Message::sendmessage($profileid,$smsCon,$appid);   
					} 
	                echo '___'.$pos.'___'.$supplierid.'____'.$orderid.'_____'.$productid.'_____'.$quantity.'_____'.count($mall_rechargeablecards).'___'.$deliver.'_____<br>';
	 			} 
	        } 
	    } 

} catch (XN_Exception $e) { }

echo 'ok';

?>