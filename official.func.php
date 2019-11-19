<?php 
 


function official_jsapi($profileid,$amount,$order_info)
{
	$supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
	    ->filter('type', 'eic', 'supplier_profile') 
	    ->filter('my.deleted', '=', '0') 
		->filter('my.official', '=', '0') 
		->filter('my.profileid', '=', $profileid)  
	    ->end(1)
	    ->execute();  
	if (count($supplier_profile) > 0)
	{
		$supplier_profile_info = $supplier_profile[0]; 
		$official_supplierid = $supplier_profile_info->my->supplierid;
		
		
		$mall_officialauthorizeevents = XN_Query::create('MainContent')->tag("mall_officialauthorizeevents")
		    ->filter('type', 'eic', 'mall_officialauthorizeevents') 
		    ->filter('my.deleted', '=', '0') 
			->filter('my.status', '=', '0') 
			->filter('my.authorizedtype', '=', "1")
			->filter('my.approvalstatus', '=', '2') 
			->filter('my.supplierid', '=', $official_supplierid)  
			->filter('my.applicant', '=', $profileid)  
		    ->end(1)
		    ->execute();  
		if (count($mall_officialauthorizeevents) > 0)
		{
			$mall_officialauthorizeevent_info = $mall_officialauthorizeevents[0];
			$mall_officialenterprisecurrencysauthorizes = XN_Query::create('MainContent')->tag("mall_officialenterprisecurrencysauthorizes")
			    ->filter('type', 'eic', 'mall_officialenterprisecurrencysauthorizes') 
			    ->filter('my.deleted', '=', '0') 
				->filter('my.status', '=', '0') 
				->filter('my.approvalstatus', '=', '2') 
				->filter('my.supplierid', '=', $official_supplierid)  
				->filter('my.profileid', '=', $profileid)  
			    ->end(1)
			    ->execute();  
			if (count($mall_officialenterprisecurrencysauthorizes) > 0)
			{
				$mall_officialenterprisecurrencysauthorize_info = $mall_officialenterprisecurrencysauthorizes[0];  
				$authorizedenterprisecurrency = $mall_officialenterprisecurrencysauthorize_info->my->authorizedenterprisecurrency;
				$currentcumulativeamount = $mall_officialenterprisecurrencysauthorize_info->my->currentcumulativeamount;
				$enterprisecurrencyid = $mall_officialenterprisecurrencysauthorize_info->my->enterprisecurrencyid; 
			 
				$mall_officialenterprisecurrency_info = XN_Content::load($enterprisecurrencyid, 'mall_officialenterprisecurrencys');
			
				$enterprisecurrency  = $mall_officialenterprisecurrency_info->my->enterprisecurrency;
				$exchangerate = $mall_officialenterprisecurrency_info->my->exchangerate;
			
			
			
	            if (round(floatval($amount), 2) > round(floatval($authorizedenterprisecurrency) * floatval($exchangerate)*100, 2))
	            {
				    throw new XN_Exception('您的企业币【'.$enterprisecurrency.'】余额不够！');
				    return "";
				}
				else
				{ 
					require_once (dirname(__FILE__) . "/payment.func.php");
				
					official_notify($order_info,$mall_officialauthorizeevent_info,round(floatval($amount)/100, 2));
				
					$cost = round(floatval($amount) / 100 / floatval($exchangerate) , 2);
				
				
					$new_authorizedenterprisecurrency = floatval($authorizedenterprisecurrency) - floatval($cost); 
        
		
					$new_currentcumulativeamount = floatval($currentcumulativeamount) + floatval($cost);
					$mall_officialenterprisecurrencysauthorize_info->my->authorizedenterprisecurrency = $new_authorizedenterprisecurrency; 
					$mall_officialenterprisecurrencysauthorize_info->my->currentcumulativeamount = $new_currentcumulativeamount; 
				 	$mall_officialenterprisecurrencysauthorize_info->save('mall_officialenterprisecurrencysauthorizes,mall_officialenterprisecurrencysauthorizes_'.$profileid.',mall_officialenterprisecurrencysauthorizes_'.$official_supplierid); 
	  
		 
					$newcontent = XN_Content::create('mall_officialenterprisecurrencylogs','',false,8);					  
					$newcontent->my->deleted = '0';  
					$newcontent->my->profileid = $profileid; 
					$newcontent->my->supplierid = $official_supplierid;  
					$newcontent->my->operator = $profileid;
					$newcontent->my->enterprisecurrencyid = $enterprisecurrencyid;
					$newcontent->my->enterprisecurrencytype = 'consumption';   
					$newcontent->my->orderid = $order_info->id;
					$newcontent->my->money = number_format($new_authorizedenterprisecurrency,2,".","");
					$newcontent->my->amount = number_format($cost,2,".","");
					$newcontent->my->submitdatetime = date('Y-m-d H:i:s');
					$newcontent->save('mall_officialenterprisecurrencylogs,mall_officialenterprisecurrencylogs_'.$profileid.',mall_officialenterprisecurrencylogs_'.$official_supplierid); 
		
		
		
					return "ok"; 
				}
			}
			else
			{
			    throw new XN_Exception('您还没有取得企业币的授权，请联系您所在企业的管理员，进行企业币授权操作！');
			    return "";
			} 
		}
		else
		{
		    throw new XN_Exception('您还没有取得购物事件授权，请联系您所在企业的管理员，进行购物事件授权操作！');
		    return "";
		}
	}
	else
	{
	    throw new XN_Exception('您还没有加入企业事务官！');
	    return "";
	} 
     
}
function official_notify($order_info,$mall_officialauthorizeevent_info,$amount)
{ 
    try
    {  
        XN_Application::$CURRENT_URL = 'admin'; 
        $orderid = $order_info->id;
        $paymentamount = $order_info->my->paymentamount;
        $usemoney = $order_info->my->usemoney;
        $profileid = $order_info->my->profileid;
		$orderssource  = $order_info->my->orderssources;
        $tradestatus = $order_info->my->tradestatus;
        $ordername = $order_info->my->ordername;

        $supplierid = $order_info->my->supplierid;
        $sumorderstotal = floatval($order_info->my->sumorderstotal);


        if ($tradestatus != "trade")
        {
            $order_info->my->tradestatus = "trade";
            $order_info->my->payment = "事务管支付";
            $order_info->my->paymenttime = date("Y-m-d H:i");
            $order_info->my->order_status = "已付款";
            $order_info->my->deleted = "0";
            $order_info->save('mall_orders,mall_orders_' . $profileid . 'mall_orders_' . $orderssource . ',mall_orders_' . $supplierid);


			official($order_info,$mall_officialauthorizeevent_info);
	
            XN_MemCache::delete("mall_badges_" . $supplierid . "_" . $profileid);
			
	        XN_Content::create('mall_orders', '', false, 2)
		            ->my->add('status', 'waiting') 
		            ->my->add('orderid', $orderid) 
		            ->save("mall_orders");

            //ticheng($order_info);  提成应该在积分计算之后

            $newcontent = XN_Content::create('mall_payments', '', false, 7);
            $newcontent->my->deleted = '0';
            $newcontent->my->profileid = $profileid;
            $newcontent->my->supplierid = $supplierid;
            $newcontent->my->orderid = $orderid;
            $newcontent->my->out_trade_no = '';
            $newcontent->my->trade_no = '';
            $newcontent->my->amount = $paymentamount;
            $newcontent->my->usemoney = $usemoney;
            $newcontent->my->sumorderstotal = $sumorderstotal;
            $newcontent->my->payment = "事务管";
            $newcontent->my->ordername = $ordername;
            $newcontent->my->buyer_email = "-";
            $newcontent->my->total_fee = number_format(($total_fee / 100), 2, ".", "");
            $newcontent->my->appid = $appid;
            $newcontent->my->wxopenid = $openid;
            $newcontent->save('mall_payments,mall_payments_' . $profileid . ',mall_payments_' . $supplierid);

            $discount = $order_info->my->discount;
            $vipcardid = $order_info->my->vipcardid;
            $vipcardusageid = $order_info->my->usageid;


            if (isset($vipcardid) && $vipcardid != "" &&
                isset($vipcardusageid) && $vipcardusageid != "" &&
                floatval($discount) > 0
            )
            {
                $mall_usage_info = XN_Content::load($vipcardusageid, 'mall_usages_' . $profileid, 7);
                $usecount = $mall_usage_info->my->usecount;
                $newusecount = intval($usecount) + 1;
                $mall_usage_info->my->isused = '1';
                $mall_usage_info->my->usecount = $newusecount;
                $mall_usage_info->my->lastusetime = date("Y-m-d H:i");
                $mall_usage_info->my->mall_usagesstatus = '已使用';
                if ($mall_usage_info->my->timelimit == '0')
                {
                    $mall_usage_info->my->usagevalid = '1';
                    $mall_usage_info->my->orderid = $orderid;
                    $mall_usage_info->my->presubmittime = date("Y-m-d H:i:s");
                }
                $mall_usage_info->save("mall_usages,mall_usages_" . $profileid . ",mall_usages_" . $supplierid);

                $vipcard_info = XN_Content::load($vipcardid, "mall_vipcards");
                $usagecount = $vipcard_info->my->usagecount;
                $vipcard_info->my->usagecount = intval($usagecount) + 1;
                $vipcard_info->save('mall_vipcards,mall_vipcards_' . $profileid . ',mall_vipcards_' . $supplierid);

                $newcontent = XN_Content::create('mall_usages_details', '', false, 7);
                $newcontent->my->deleted = '0';
                $newcontent->my->supplierid = $supplierid;
                $newcontent->my->profileid = $profileid;
                $newcontent->my->orderid = $orderid;
                $newcontent->my->vipcardid = $vipcardid;
                $newcontent->my->usageid = $vipcardusageid;
                $newcontent->my->usecount = $newusecount;
                $newcontent->my->discount = number_format($discount, 2, ".", "");
                $newcontent->my->sumorderstotal = number_format($sumorderstotal, 2, ".", "");
                $newcontent->save('mall_usages_details,mall_usages_details_' . $profileid . ',mall_usages_details_' . $supplierid);
            }

            $supplierinfo = get_supplier_info($supplierid);
            $ranklimit = $supplierinfo['ranklimit']; // 资格限制
            $ranklimit = intval($ranklimit);

            if (floatval($usemoney) > 0)
            {
                $billwaters = XN_Query::create('MainYearContent')->tag('mall_billwaters_' . $profileid)
                    ->filter('type', 'eic', 'mall_billwaters')
                    ->filter('my.deleted', '=', '0')
                    ->filter('my.billwatertype', '=', 'consumption')
                    ->filter('my.orderid', '=', $attach)
                    ->execute();

                if (count($billwaters) > 0) return;

                $profile_info = get_supplier_profile_info($profileid, $supplierid);

                $money = $profile_info['money'];
                $accumulatedmoney = $profile_info['accumulatedmoney'];
                $rank = $profile_info['rank'];

                $new_money = floatval($money) - floatval($usemoney);
                $new_rank = $rank + round(floatval($sumorderstotal));

                $profile_info['money'] = $new_money;
                $profile_info['rank'] = $new_rank;

                update_supplier_profile_info($profile_info, $ranklimit);

                $newcontent = XN_Content::create('mall_billwaters', '', false, 8);
                $newcontent->my->deleted = '0';
                $newcontent->my->profileid = $profileid;
                $newcontent->my->supplierid = $supplierid;
                $newcontent->my->billwatertype = 'consumption';
                $newcontent->my->sharedate = '-';
                $newcontent->my->orderid = $attach;
                $newcontent->my->amount = '-' . $usemoney;
                $newcontent->my->money = $new_money;
                $newcontent->save('mall_billwaters,mall_billwaters_' . $profileid . ',mall_billwaters_' . $supplierid);
                XN_MemCache::delete("mall_badges_" . $supplierid . "_" . $profileid);
            }
            else
            {
                $profile_info = get_supplier_profile_info($profileid, $supplierid);
                $rank = $profile_info['rank'];
                $new_rank = $rank + round(floatval($sumorderstotal));
                $profile_info['rank'] = $new_rank;
                update_supplier_profile_info($profile_info, $ranklimit);
            }
			
            checkconsumelog($attach);

            ticheng($order_info);  //提成应该在积分计算之后

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
                require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
                XN_Message::sendmessage($profileid, '您的订单' . $out_trade_no . '付款成功!', $appid);
            }
        }
        else
        {
            throw new XN_Exception('' . $attach . '' . $out_trade_no . '当前订单已经付款！');
        }
    }
    catch (XN_Exception $e)
    {
        throw new XN_Exception($e->getMessage());
    }
}

function official($order_info,$mall_officialauthorizeevent_info)
{
	try{     
		 $supplier_info = get_supplier_info();
	 	 $copyrights = $supplier_info['copyrights'];
	
	 	 if ($copyrights['official'] == '1')
	 	 {
	         $paymentamount = $order_info->my->paymentamount;
	         $usemoney = $order_info->my->usemoney;
	         $profileid = $order_info->my->profileid;
	 		 $orderssource  = $order_info->my->orderssources;
	         $tradestatus = $order_info->my->tradestatus;
	         $ordername = $order_info->my->ordername; 
	         $supplierid = $order_info->my->supplierid;
			 $mall_orders_no = $order_info->my->mall_orders_no;
	         $sumorderstotal = floatval($order_info->my->sumorderstotal);
			 
	 		$supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
	 		    ->filter('type', 'eic', 'supplier_profile') 
	 		    ->filter('my.deleted', '=', '0') 
	 			->filter('my.official', '=', '0') 
	 			->filter('my.profileid', '=', $profileid)  
	 		    ->end(1)
	 		    ->execute();  
	 		if (count($supplier_profile) > 0)
	 		{
				$supplier_profile_info = $supplier_profile[0]; 
				$official_supplierid = $supplier_profile_info->my->supplierid;
				
				$orderid = $order_info->id;
				XN_Profile::$VIEWER = $profileid;
		        $newcontent = XN_Content::create('mall_officialorders', '', false);
		        $newcontent->my->deleted = '0'; 
				$newcontent->my->profileid = $profileid; 
		        $newcontent->my->supplierid = $official_supplierid; 
				$newcontent->my->vendorid = $supplierid; 
		        $newcontent->my->authorizeevent =  $mall_officialauthorizeevent_info->id;
				$newcontent->my->authorizedperson = $mall_officialauthorizeevent_info->my->authorizedperson;
				$newcontent->my->authorized = '0';
				$newcontent->my->decider = $mall_officialauthorizeevent_info->my->decider;
				$newcontent->my->opinion = $mall_officialauthorizeevent_info->my->opinion;
		        $newcontent->my->orderid = $orderid;
				$newcontent->my->mall_orders_no = $mall_orders_no;
				$newcontent->my->orderdatetime = date("Y-m-d H:i"); 
				$newcontent->my->sumorderstotal = $sumorderstotal;  
				$newcontent->my->approvalstatus = "0";
				$newcontent->my->sequence = strtotime("now");
				$newcontent->my->mall_officialordersstatus = 'JustCreated'; 
		        $newcontent->save("mall_officialorders,mall_officialorders_".$supplierid.",mall_officialorders_".$profileid);
				$officialorderid = $newcontent->id;
		
				$opinion = $mall_officialauthorizeevent_info->my->opinion;
				foreach((array)$opinion as $opinion_info)
				{
			        $newcontent = XN_Content::create('mall_officialopinions', '', false);
			        $newcontent->my->deleted = '0'; 
					$newcontent->my->profileid = $opinion_info; 
					$newcontent->my->submitid = $profileid;  
					$newcontent->my->submitgivenname = getGivenName($profileid);
			        $newcontent->my->supplierid = $supplierid; 
				    $newcontent->my->record = $officialorderid;
			        $newcontent->my->opiniontype =  'order'; 
					$newcontent->my->opinioned = "0";  
					$newcontent->my->opinion = "";  
					$newcontent->my->sequence = strtotime("now");
			        $newcontent->save("mall_officialopinions,mall_officialopinions_".$supplierid.",mall_officialopinions_".$profileid);
		 		} 
			}
		 }
	}
	catch(XN_Exception $e)
	{
		 
	}  
}

function getGivenName($profileid)
{
	try
	{
		$info      = XN_Profile::load($profileid);
		$givenname = $info->givenname;
		if ($givenname == "")
		{
			$fullName = $info->fullName;
			if (preg_match('.[#].', $fullName))
			{
				$fullNames = explode('#', $fullName);
				$fullName  = $fullNames[0];
			}
			$givenname = $fullName;
		}

		return $givenname;
	}
	catch (XN_Exception $e)
	{
		return "";
	}
}
 
 
?>