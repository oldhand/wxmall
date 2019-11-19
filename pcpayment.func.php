<?php

require_once(dirname(__FILE__) . "/config.common.php");

//打印输出数组信息
function printf_info($data)
{
    foreach ($data as $key => $value)
    {
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

function checkconsumelog($orderid)
{
    $consumelogs = XN_Query::create('YearContent')->tag('mall_consumelogs')
        ->filter('type', 'eic', 'mall_consumelogs')
        ->filter('my.deleted', '=', '0')
        ->filter('my.orderid', '=', $orderid)
        ->end(-1)
        ->execute();
    if (count($consumelogs) > 0)
    {
        foreach ($consumelogs as $consumelog_info)
        {
            $consumelog_info->my->consumelogsstatus = '混合支付成功';
            $consumelog_info->my->tradestatus = "trade";
            $consumelog_info->my->paymentdatetime = date("Y-m-d H:i");
            $consumelog_info->save('mall_consumelogs');
        }
    }
}

function weixin_notify($notify_info)
{
    /*
[2015-07-13 21:00:53][debug] begin notify
[2015-07-13 21:00:53][debug] call back:{"appid":"wx7962fafc7ec5b6c6","attach":"IPhone 6 Plus","bank_type":"CIB_DEBIT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1252726901","nonce_str":"kxblsgybs7peka3xtfknz5n2tbgafc8p","openid":"oaCu_s1UUUCiUx-plwiykseHSuj0","out_trade_no":"2015071324697","result_code":"SUCCESS","return_code":"SUCCESS","sign":"6CCA5EB354B3DB5393DC41F40AC4B233","time_end":"20150713210021","total_fee":"1","trade_type":"JSAPI","transaction_id":"1008040813201507130400559622"}
[2015-07-13 21:00:54][debug] query:{"appid":"wx7962fafc7ec5b6c6","attach":"IPhone 6 Plus","bank_type":"CIB_DEBIT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1252726901","nonce_str":"5M9sL54l3Q8TyNue","openid":"oaCu_s1UUUCiUx-plwiykseHSuj0","out_trade_no":"2015071324697","result_code":"SUCCESS","return_code":"SUCCESS","return_msg":"OK","sign":"8DF011FD1173ADB59E8A41F46A3D306C","time_end":"20150713210019","total_fee":"1","trade_state":"SUCCESS","trade_type":"JSAPI","transaction_id":"1008040813201507130400559622"}

[2015-07-14 18:55:54][debug] begin notify
[2015-07-14 18:55:54][debug] call back:{"appid":"wx7962fafc7ec5b6c6","attach":"26119","bank_type":"CIB_DEBIT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1252726901","nonce_str":"9r36to4jo7q7up907jgtr29iymmhqhq4","openid":"oaCu_s1UUUCiUx-plwiykseHSuj0","out_trade_no":"ORD150714029","result_code":"SUCCESS","return_code":"SUCCESS","sign":"1F795E4FE6B58E223DD591075015DF3F","time_end":"20150714185533","total_fee":"1","trade_type":"JSAPI","transaction_id":"1008040813201507140405718776"}
[2015-07-14 18:55:55][debug] query:{"appid":"wx7962fafc7ec5b6c6","attach":"26119","bank_type":"CIB_DEBIT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1252726901","nonce_str":"DqXVOIHUgudJPaZx","openid":"oaCu_s1UUUCiUx-plwiykseHSuj0","out_trade_no":"ORD150714029","result_code":"SUCCESS","return_code":"SUCCESS","return_msg":"OK","sign":"61E9CF8349A67EC24D6A6EB111EFE497","time_end":"20150714185532","total_fee":"1","trade_state":"SUCCESS","trade_type":"JSAPI","transaction_id":"1008040813201507140405718776"}
[2015-07-14 19:04:43][debug] begin notify
[2015-07-14 19:04:43][debug] call back:{"appid":"wx7962fafc7ec5b6c6","attach":"26122","bank_type":"CFT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1252726901","nonce_str":"5ij3y946ib6wo1q11kgs22ih2a17zq6m","openid":"oaCu_s1UUUCiUx-plwiykseHSuj0","out_trade_no":"ORD150714030","result_code":"SUCCESS","return_code":"SUCCESS","sign":"7876540B8D8BCA124FDA7AFE9C10C5B3","time_end":"20150714190422","total_fee":"1","trade_type":"JSAPI","transaction_id":"1008040813201507140405777740"}
[2015-07-14 19:04:44][debug] query:{"appid":"wx7962fafc7ec5b6c6","attach":"26122","bank_type":"CFT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1252726901","nonce_str":"50fn6p0MwXR7Ez0l","openid":"oaCu_s1UUUCiUx-plwiykseHSuj0","out_trade_no":"ORD150714030","result_code":"SUCCESS","return_code":"SUCCESS","return_msg":"OK","sign":"0C2E8D2C0EAA304529AFCC8D89041790","time_end":"20150714190421","total_fee":"1","trade_state":"SUCCESS","trade_type":"JSAPI","transaction_id":"1008040813201507140405777740"} */
    $appid = $notify_info['appid'];
    $attach = $notify_info['attach'];
    $fee_type = $notify_info['fee_type'];
    $cash_fee = $notify_info['cash_fee'];
    $is_subscribe = $notify_info['is_subscribe'];
    $openid = $notify_info['openid'];
    $out_trade_no = $notify_info['out_trade_no'];
    $total_fee = $notify_info['total_fee'];
    $trade_type = $notify_info['trade_type'];
    $transaction_id = $notify_info['transaction_id'];


    try
    {
        try
        {
            $running = XN_MemCache::get("weixin_notify_" . $attach);
            if ($running == "running")
            {
                return;
            }
        }
        catch (XN_Exception $e)
        {
            XN_MemCache::put("running", "weixin_notify_" . $attach, "60");
        }

        XN_Application::$CURRENT_URL = 'admin';
        $order_info = XN_Content::load($attach, 'mall_orders', 7);
        $orderid = $attach;
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
            $order_info->my->payment = "微信支付";
            $order_info->my->paymenttime = date("Y-m-d H:i");
            $order_info->my->order_status = "已付款";
            $order_info->my->deleted = "0";
            $order_info->save('mall_orders,mall_orders_' . $profileid . 'mall_orders_' . $orderssource . ',mall_orders_' . $supplierid);

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
            $newcontent->my->orderid = $attach;
            $newcontent->my->out_trade_no = $out_trade_no;
            $newcontent->my->trade_no = $transaction_id;
            $newcontent->my->amount = $paymentamount;
            $newcontent->my->usemoney = $usemoney;
            $newcontent->my->sumorderstotal = $sumorderstotal;
            $newcontent->my->payment = "微信";
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


function weixin_pc_jsapi($profileid, $ordername, $money, $trade_no, $orderid)
{
    //$money = 1;
    ini_set('date.timezone', 'Asia/Shanghai');
    //error_reporting(E_ERROR);
    require_once dirname(__FILE__) . "/plugins/wxpayapi/lib/WxPay.Api.php";
    require_once dirname(__FILE__) . "/plugins/wxpayapi/WxPay.JsApiPay.php";
    require_once dirname(__FILE__) . "/plugins/wxpayapi/WxPay.NativePay.php";
    require_once dirname(__FILE__) . '/plugins/wxpayapi/log.php';

    //初始化日志
    $logHandler = new CLogFileHandler(dirname(__FILE__) . "/plugins/wxpayapi/logs/" . date('Y-m-d') . '.log');
    $log = Log::Init($logHandler, 15);

    $notify = 'http://' . $_SERVER['HTTP_HOST'] . '/plugins/wxpayapi/pcnotify.php';


    global $wxsetting;

    if (!isset($wxsetting))
    {
        $_SESSION['appid'] = "wxe54a943a23a4603d";
        $appid = $_SESSION['appid']; 
        check_wxsetting_config($appid);
    }
    $product_id = $orderid.'28'.time();
    $appid = $wxsetting['appid'];
    $secret = $wxsetting['secret'];
    $weixintype = $wxsetting['weixintype'];
    $weixinpay = $wxsetting['weixinpay'];
    $mchid = $wxsetting['mchid'];
    $mchkey = $wxsetting['mchkey'];
    $sslcert = $wxsetting['sslcert'];
    $sslkey = $wxsetting['sslkey'];


    if ($weixinpay == '3')
    {
        throw new XN_Exception('微信支付已经关闭！');
        return false;
    }
    else if ($weixinpay == '1')
    {

        if (isset($mchid) && $mchid != '' &&
            isset($mchkey) && $mchkey != '' &&
            isset($sslcert) && $sslcert != '' &&
            isset($sslkey) && $sslkey != ''
        )
        {
            WxPayConfig::$APPID = $appid;
            WxPayConfig::$MCHID = $mchid;
            WxPayConfig::$KEY = $mchkey;
            WxPayConfig::$APPSECRET = $secret;
            WxPayConfig::$SSLCERT_PATH = $_SERVER["DOCUMENT_ROOT"] . $sslcert;
            WxPayConfig::$SSLKEY_PATH = $_SERVER["DOCUMENT_ROOT"] . $sslkey;
        }
        else
        {
            throw new XN_Exception('您的公众号的微信支付配置有误！');
            return false;
        }
    }

    $wxopenids = XN_Query::create('MainContent')->tag("profile_" . $profileid)
        ->filter('type', 'eic', 'wxopenids')
        ->filter('my.profileid', '=', $profileid)
        ->filter('my.appid', '=', WxPayConfig::$APPID)
        ->end(1)
        ->execute();
    if (count($wxopenids) == 0)
    {
        throw new XN_Exception("没有wxopenid");
        return false;
    }
    else
    {
        $wxopenid_info = $wxopenids[0];

        $openId = $wxopenid_info->my->wxopenid;
        try
        {
            //①、获取用户openid
            $tools = new JsApiPay();

            //$openId = $tools->GetOpenid();

            //②、统一下单
            //$ordername = 'IPhone 6 Plus';
            $input = new WxPayUnifiedOrder();
            $input->SetBody($ordername);
            $input->SetAttach($orderid);
            //$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
            $input->SetOut_trade_no($trade_no);
            $input->SetTotal_fee($money);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag($ordername);
            $input->SetNotify_url($notify);
            $input->SetTrade_type("NATIVE");
            //$input->SetOpenid($openId);
            $input->SetProduct_id($product_id);

            $order = WxPayApi::unifiedOrder($input);

            if (isset($order['return_code']) && $order['return_code'] == 'SUCCESS')
            {
                return json_encode($order['code_url']);
            }
            else
            {
                $code = $order['return_code'];
                $msg = $order['return_msg'];
                throw new XN_Exception($msg . ',errorcode[' . $code . ']');
                return false;
            }
        }
        catch (WxPayException $e)
        {
            throw new XN_Exception($e->getMessage());
        }

    }

}

function discount($sumorderstotal, $vipcardusageid)
{
    global $vipcardid;
    $mall_usage_info = XN_Content::load($vipcardusageid, 'mall_usages', 7);
    $vipcardid = $mall_usage_info->my->vipcardid;
    $amount = $mall_usage_info->my->amount;
    $discount = $mall_usage_info->my->discount;
    $orderamount = $mall_usage_info->my->orderamount;
    $cardtype = $mall_usage_info->my->cardtype;
    $isused = $mall_usage_info->my->isused;
    $timelimit = $mall_usage_info->my->timelimit;
    if ($timelimit == '1') $isused = '0'; // 若不限次 是否已经使用，重置为0
    $vipcard_info = XN_Content::load($vipcardid, "mall_vipcards");
    $status = $vipcard_info->my->status;
    if ($status != '0')
    {
        throw new XN_Exception("您的卡券已经失效!");
        return false;
    }
    if ($isused != '0')
    {
        throw new XN_Exception("您的卡券已经使用过了!");
        return false;
    }
    if ($orderamount == '0' || round($sumorderstotal, 2) >= round(floatval($orderamount), 2))
    {
        if ($cardtype == '2')
        {
            $total_discount = $sumorderstotal - $sumorderstotal * floatval($discount) / 10;

        }
        else
        {
            $total_discount = floatval($amount);
        }
    }
    else
    {
        throw new XN_Exception("您的卡券使用条件异常!");
        return false;
    }
    return $total_discount;
}

function finished_yue_trade($order_info, $vipcardusageid = "", $vipcardusageamount = "")
{
    try
    {
        $tradestatus = $order_info->my->tradestatus;
        if ($tradestatus != "trade")
        {
            $orderid = $order_info->id;
            $profileid = $order_info->my->profileid;
            $orderssource  = $order_info->my->orderssources;
            $supplierid = $order_info->my->supplierid;
            $sumorderstotal = floatval($order_info->my->sumorderstotal);


            $profile_info = get_supplier_profile_info($profileid, $supplierid);


            if (count($profile_info) > 0)
            {
                $wxopenid = $profile_info['wxopenid'];
                $money = $profile_info['money'];
                $accumulatedmoney = $profile_info['accumulatedmoney'];
                $rank = $profile_info['rank'];
            }
            else
            {
                throw new XN_Exception("您根本没有余额可付!");
                return false;
            }

            $total_discount = 0;
            global $vipcardid;
            if (isset($vipcardusageid) && $vipcardusageid != "" &&
                isset($vipcardusageamount) && $vipcardusageamount != ""
            )
            {
                $total_discount = discount($sumorderstotal, $vipcardusageid);
            }
            else
            {
                $vipcardid = "";
            }

            if (round(floatval($vipcardusageamount), 2) != round(floatval($total_discount), 2))
            {
                throw new XN_Exception("您的卡券使用条件异常，请联系客服!");
                return false;
            }

            if (round(floatval($money), 2) >= round((floatval($sumorderstotal) - $total_discount), 2))
            {
                $usemoney = floatval($sumorderstotal) - $total_discount;
                $order_info->my->paymentamount = '0.00';
                $order_info->my->deleted = '0';
                $order_info->my->usemoney = number_format($usemoney, 2, ".", "");
                $order_info->my->discount = number_format($total_discount, 2, ".", "");
                $order_info->my->vipcardid = $vipcardid;
                $order_info->my->usageid = $vipcardusageid;
                $order_info->my->tradestatus = "trade";
                $order_info->my->payment = "余额支付";
                $order_info->my->paymentway = "tzb";
                $order_info->my->paymentmode = '1';
                $order_info->my->paymenttime = date("Y-m-d H:i");
                $order_info->my->order_status = "已付款";
                $order_info->save('mall_orders,mall_orders_' . $profileid . ',mall_orders_'.$orderssource.',mall_orders_' . $supplierid);
                
                XN_Content::create('mall_orders', '', false, 2)
                        ->my->add('status', 'waiting') 
                        ->my->add('orderid', $orderid) 
                        ->save("mall_orders");

                $newrank = intval($rank) + round($sumorderstotal);
                $new_money = floatval($money) - $usemoney;

                $profile_info['money'] = $new_money;
                $profile_info['rank'] = $newrank;

                $supplierinfo = get_supplier_info($supplierid);
                $ranklimit = $supplierinfo['ranklimit']; // 资格限制
                $ranklimit = intval($ranklimit);

                update_supplier_profile_info($profile_info, $ranklimit);

                XN_MemCache::delete("mall_badges_" . $supplierid . "_" . $profileid);

                if ($total_discount > 0)
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
                    $newcontent->my->discount = number_format($total_discount, 2, ".", "");
                    $newcontent->my->sumorderstotal = number_format($sumorderstotal, 2, ".", "");
                    $newcontent->save('mall_usages_details,mall_usages_details_' . $profileid . ',mall_usages_details_' . $supplierid);
                }
                /*$supplier_profile_info->my->rank = $newrank;
                $supplier_profile_info->my->money = number_format($new_money,2,".","");
                $supplier_profile_info->save("supplier_profile,supplier_profile_".$profileid.",supplier_profile_".$wxopenid); */


                $consumelogs = XN_Query::create('YearContent')->tag('mall_consumelogs_' . $profileid)
                    ->filter('type', 'eic', 'mall_consumelogs')
                    ->filter('my.deleted', '=', '0')
                    ->filter('my.orderid', '=', $orderid)
                    ->execute();

                if (count($consumelogs) == 0)
                {
                    $newcontent = XN_Content::create('mall_consumelogs', '', false, 7);
                    $newcontent->my->deleted = '0';
                    $newcontent->my->supplierid = $supplierid;
                    $newcontent->my->profileid = $profileid;
                    $newcontent->my->orderid = $orderid;
                    $newcontent->my->paymentdatetime = date("Y-m-d H:i");
                    $newcontent->my->amount = number_format($usemoney, 2, ".", "");
                    $newcontent->my->remain = number_format($new_money, 2, ".", "");
                    $newcontent->my->sumorderstotal = number_format($sumorderstotal, 2, ".", "");
                    $newcontent->my->consumelogsstatus = '纯余额支付成功';
                    $newcontent->my->tradestatus = "trade";
                    $newcontent->save('mall_consumelogs,mall_consumelogs_' . $profileid);
                }

                $billwaters = XN_Query::create('MainYearContent')->tag('mall_billwaters_' . $profileid)
                    ->filter('type', 'eic', 'mall_billwaters')
                    ->filter('my.deleted', '=', '0')
                    ->filter('my.billwatertype', '=', 'consumption')
                    ->filter('my.orderid', '=', $orderid)
                    ->execute();

                if (count($billwaters) == 0)
                {
                    $newcontent = XN_Content::create('mall_billwaters', '', false, 8);
                    $newcontent->my->deleted = '0';
                    $newcontent->my->supplierid = $supplierid;
                    $newcontent->my->profileid = $profileid;
                    $newcontent->my->billwatertype = 'consumption';
                    $newcontent->my->sharedate = '-';
                    $newcontent->my->orderid = $orderid;
                    $newcontent->my->amount = '-' . number_format($usemoney, 2, ".", "");
                    $newcontent->my->money = number_format($new_money, 2, ".", "");
                    $newcontent->save('mall_billwaters,mall_billwaters_' . $profileid);
                    XN_MemCache::delete("mall_badges_" . $supplierid . "_" . $profileid);
                }

                $orders_no = $order_info->my->mall_orders_no;

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
                    XN_Message::sendmessage($profileid, '您的订单' . $orders_no . '使用余额支付，付款成功!', $appid);
                }
                return true;
            }
            else
            {
                throw new XN_Exception("您的余额不够!");
                return false;
            }

            return $paymentamount;
        }
        else
        {
            throw new XN_Exception('该订单已经支付完成');
            return false;
        }
    }
    catch (XN_Exception $e)
    {
        throw new XN_Exception($e->getMessage());
        return false;
    }
}

function execute_profile_commission($profileid, $supplierid, $commissions, $middleman = "")
{
    try
    {
        $profile_info = get_supplier_profile_info($profileid, $supplierid);

        $orderid = $commissions['orderid'];
        $productid = $commissions['productid'];
        $orders_productid = $commissions['orders_productid'];
        $royaltyrate = $commissions['royaltyrate'];
        $totalprice = $commissions['totalprice'];
        $quantity = $commissions['quantity'];
        $amount = $commissions['amount'];
        $propertyid = $commissions['propertyid'];
        $distributionmode = $commissions['distributionmode'];
        $consumer = $commissions['profileid'];
        $level = $commissions['level'];
        $productname = $commissions['productname'];
        $givenname = $commissions['givenname'];
        $published = $commissions['published'];
        $orders_no = $commissions['orders_no'];
        $ranklimit = $commissions['ranklimit'];

        $ranklevel = $profile_info['ranklevel'];

        if ($ranklevel == '1' && count($profile_info) > 0)
        {
            $newcontent = XN_Content::create('mall_commissions', '', false, 7);
            $newcontent->my->deleted = '0';
            $newcontent->my->supplierid = $supplierid;
            $newcontent->my->profileid = $profileid;
            $newcontent->my->consumer = $consumer;
            $newcontent->my->middleman = $middleman;
            $newcontent->my->subordinate = '';
            $newcontent->my->commissionsource = '0';
            $newcontent->my->orderid = $orderid;
            $newcontent->my->productid = $productid;
            $newcontent->my->orders_productid = $orders_productid;
            $newcontent->my->royaltyrate = $royaltyrate;
            $newcontent->my->commissiontype = '0';
            $newcontent->my->totalprice = $totalprice;
            $newcontent->my->quantity = $quantity;
            $newcontent->my->amount = $amount;
            $newcontent->my->propertyid = $propertyid;
            $newcontent->my->distributionmode = $distributionmode; 
            $newcontent->save('mall_commissions,mall_commissions_' . $profileid . ',mall_commissions_' . $supplierid);

            if ($consumer == $profileid)
            {
                $message = '您购买了'.$productname.'。\n提成收益:' . $amount . '元订\n单号:'.$orders_no.'\n时间:'.$published.'\n说明:第'.$level.'层提成';
            }
            else
            {
                $message = '恭喜您，您的粉丝'.$givenname.'购买了'.$productname.'。\n提成收益:' . $amount . '元\n订单号:'.$orders_no.'\n时间:'.$published.'\n说明:第'.$level.'层提成';
            }
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
                XN_Message::sendmessage($profileid, $message, $appid);
            }
        }
        else if (count($profile_info) > 0)
        {
            if ($consumer == $profileid)
            {
                $message = '很遗憾，由于您没有取得分佣资格，购买的：' . $productname . '，将无法获得提成收益' . $amount . '元，说明：第' . $level . '层提成。消费' . $ranklimit . '元后获得分佣资格!';
            }
            else
            {
                $message = '很遗憾，由于您没有取得分佣资格，您的粉丝' . $givenname . '购买了：' . $productname . '，您将无法获得提成收益' . $amount . '元，说明：第' . $level . '层提成。消费' . $ranklimit . '元后获得分佣资格!';
            }
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
                XN_Message::sendmessage($profileid, $message, $appid);
            }
        }
    }
    catch (XN_Exception $e)
    {

    }
}

function execute_ticheng($distributionmode, $distributionrate, $level, $ticheng)
{
    if (!isset($distributionrate) || $distributionrate == "")
    {
        $distributionrate = '111';
    }
    $newticheng = 0;
    if ($distributionmode == '2')
    {
        switch ($distributionrate)
        {
            case "111":
                $newticheng = $ticheng / 2;
                break;
            case "321":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 5 * 3;
                }
                else
                {
                    $newticheng = $ticheng / 5 * 2;
                }
                break;
            case "211":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 3 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 3;
                }
                break;
            case "311":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 4 * 3;
                }
                else
                {
                    $newticheng = $ticheng / 4;
                }
                break;
            case "221":
                $newticheng = $ticheng / 2;
                break;
            case "421":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 3 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 3;
                }
                break;
            case "345":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 7 * 3 ;
                }
                else
                {
                    $newticheng = $ticheng / 7 * 4 ;
                }
                break;
             case "1564":
                    if ($level == 1)
                    {
                        $newticheng = $ticheng / 21 * 15 ;
                    }
                    else if ($level == 2)
                    {
                        $newticheng = $ticheng / 21 * 6 ;
                    } 
              break;
        }
    }
    else if ($distributionmode == '3')
    {
        switch ($distributionrate)
        {
            case "111":
                $newticheng = $ticheng / 3;
                break;
            case "321":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 6 * 3;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 6 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 6;
                }
                break;
            case "211":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 4 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 4;
                }
                break;
            case "311":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 5 * 3;
                }
                else
                {
                    $newticheng = $ticheng / 5;
                }
                break;
            case "221":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 5 * 2;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 5 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 5;
                }
                break;
            case "421":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 7 * 4;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 7 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 7;
                }
                break;
            case "345":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 4 ;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 3  ;
                }
                else
                {
                    $newticheng = $ticheng / 12 * 5 ;
                }
                break;
             case "1564":
                    if ($level == 1)
                    {
                        $newticheng = $ticheng / 25 * 15 ;
                    }
                    else if ($level == 2)
                    {
                        $newticheng = $ticheng / 25 * 6 ;
                    }
                    else
                    {
                        $newticheng = $ticheng / 25 * 4 ;
                    }
              break;
        }
    }
    return  number_format($newticheng, 2, ".", "");
}

function execute_commission($profileid, $distributionmode, $supplierid, $commissions)
{
    try
    {
        $ticheng = $commissions['amount'];
        $distributionrate = $commissions['distributionrate'];

        if ($distributionmode == '1')
        {
            $commissions['level'] = 1;
            execute_profile_commission($profileid, $supplierid, $commissions);
        }
        else if ($distributionmode == '2')
        {
            $profile_info = get_supplier_profile_info($profileid, $supplierid);
            $onelevelsourcer = $profile_info['onelevelsourcer'];
            $commissions['amount'] = execute_ticheng($distributionmode, $distributionrate, 1, $ticheng);
            $commissions['level'] = 1;
            execute_profile_commission($profileid, $supplierid, $commissions);
            if (isset($onelevelsourcer) && $onelevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode, $distributionrate, 2, $ticheng);
                $commissions['level'] = 2;
                execute_profile_commission($onelevelsourcer, $supplierid, $commissions, $profileid);
            }
        }
        else if ($distributionmode == '3')
        {
            $profile_info = get_supplier_profile_info($profileid, $supplierid);
            $onelevelsourcer = $profile_info['onelevelsourcer'];
            $twolevelsourcer = $profile_info['twolevelsourcer'];

            $commissions['amount'] = execute_ticheng($distributionmode, $distributionrate, 1, $ticheng);
            $commissions['level'] = 1;
            execute_profile_commission($profileid, $supplierid, $commissions);
            if (isset($onelevelsourcer) && $onelevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode, $distributionrate, 2, $ticheng);
                $commissions['level'] = 2;
                execute_profile_commission($onelevelsourcer, $supplierid, $commissions, $profileid);
            }
            if (isset($twolevelsourcer) && $twolevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode, $distributionrate, 3, $ticheng);
                $commissions['level'] = 3;
                execute_profile_commission($twolevelsourcer, $supplierid, $commissions, $onelevelsourcer);
            }
        }
    }
    catch (XN_Exception $e)
    {

    }
}

function execute_noconsumer_commission($profileid, $distributionmode, $supplierid, $commissions)
{
    try
    {
     
        $profile_info = get_supplier_profile_info($profileid, $supplierid);

        $onelevelsourcer = $profile_info['onelevelsourcer'];
        $twolevelsourcer = $profile_info['twolevelsourcer'];
        $threelevelsourcer = $profile_info['threelevelsourcer'];
        
        $ticheng = $commissions['amount']; 
        $distributionrate = $commissions['distributionrate'];


        if ($distributionmode == '1')
        {
            if (isset($onelevelsourcer) && $onelevelsourcer != "")
            {
                execute_profile_commission($onelevelsourcer, $supplierid, $commissions);
            }
        }
        else if ($distributionmode == '2')
        {
            if (isset($onelevelsourcer) && $onelevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode,$distributionrate,1,$ticheng); 
                $commissions['level'] = 1;
                execute_profile_commission($onelevelsourcer, $supplierid, $commissions);
            }
            if (isset($twolevelsourcer) && $twolevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode,$distributionrate,2,$ticheng);
                $commissions['level'] = 2; 
                execute_profile_commission($twolevelsourcer, $supplierid, $commissions, $onelevelsourcer);
            }
        }
        else if ($distributionmode == '3')
        {
            if (isset($onelevelsourcer) && $onelevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode,$distributionrate,1,$ticheng);
                $commissions['level'] = 1;  
                execute_profile_commission($onelevelsourcer, $supplierid, $commissions);
            }
            if (isset($twolevelsourcer) && $twolevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode,$distributionrate,2,$ticheng); 
                $commissions['level'] = 2; 
                execute_profile_commission($twolevelsourcer, $supplierid, $commissions, $onelevelsourcer);
            }
            if (isset($threelevelsourcer) && $threelevelsourcer != "")
            {
                $commissions['amount'] = execute_ticheng($distributionmode,$distributionrate,3,$ticheng); 
                $commissions['level'] = 3; 
                execute_profile_commission($threelevelsourcer, $supplierid, $commissions, $twolevelsourcer);
            }
        }
    }
    catch (XN_Exception $e)
    {

    }
}
 


function ticheng($order_info)
{
    try
    {
        $orderssources = $order_info->my->orderssources;
        $mall_orders_no = $order_info->my->mall_orders_no;
        if (isset($orderssources) && $orderssources != '')
        {
            $orderid = $order_info->id;
            $profileid = $orderssources;
            $supplierid = $order_info->my->supplierid;

            $profile_info = get_supplier_profile_info($profileid, $supplierid);
            $givenname = $profile_info['givenname'];

            $orders_products = XN_Query::create('YearContent')->tag('mall_orders_products')
                ->filter('type', 'eic', 'mall_orders_products')
                ->filter('my.deleted', '=', '0')
                ->filter('my.orderid', '=', $orderid)
                ->execute();

            foreach ($orders_products as $orders_product_info)
            {
                $productid = $orders_product_info->my->productid;
                $propertyid = $orders_product_info->my->product_property_id;
                $shop_price = $orders_product_info->my->shop_price;
                $memberrate = $orders_product_info->my->memberrate;
                $quantity = $orders_product_info->my->quantity;
                $propertydesc = $orders_product_info->my->propertydesc;
                $orders_product_info->my->tradestatus = "trade";
                $orders_product_info->save('mall_orders_products,mall_orders_products_' . $profileid . ',mall_orders_products_' . $supplierid);

                $product_info = XN_Content::load($productid,"mall_products_".$supplierid);
                $salevolume = $product_info->my->salevolume;
                $vendorid = $orders_product_info->my->vendorid;
                $vendor_price = $orders_product_info->my->vendor_price;
                $product_info->my->salevolume = intval($salevolume) + $quantity;
                $product_info->save("mall_products,mall_products_".$supplierid);
                $uniquesale = $product_info->my->uniquesale;
                $commissionswitch = $product_info->my->commissionswitch;
                
                $memberrate = floatval($memberrate);
                $supplierinfo = get_supplier_info($supplierid);
                
                if (floatval($shop_price) > 0 && floatval($memberrate) > 0 && $commissionswitch == "0")
                {
                    $totalmoney = floatval($shop_price) * floatval($quantity);
                    $ticheng = $totalmoney * $memberrate / 100;
                    //$ticheng = number_format($ticheng, 2, '.','');

                    

                    //$popularizemode = $supplierinfo['popularizemode'];  // 1 =>  1级推广', 2 =>  '2级推广',3 =>  '3级推广',  0 => '无推广'
                    //$popularizefund = floatval($supplierinfo['popularizefund']);  // 推广金
                    $distributionmode = $supplierinfo['distributionmode'];  // 1 =>  1级分销', 2 =>  '2级分销',3 =>  '3级分销',  0 => '无分销'
                    //$sharefund = $supplierinfo['sharefund']; // 分享金
                    $ranklimit = $supplierinfo['ranklimit']; // 资格限制
                    //$takecashitem = $supplierinfo['takecashitem'];
                    $distributionrate = $supplierinfo['distributionrate']; // 分配比率
                    $distributionobject = $supplierinfo['distributionobject']; // 分配对象

                    $ranklimit = intval($ranklimit);

                    if ($distributionmode != "" && $distributionmode != "0" && $ticheng > 0)
                    {
                        $distributionmode = intval($distributionmode);
                        if ($distributionmode > 0 && $distributionmode < 4)
                        {
                            $commissions = array();
                            $commissions['orderid'] = $orderid;
                            $commissions['productid'] = $productid;
                            $commissions['orders_productid'] = $orders_product_info->id;
                            $commissions['royaltyrate'] = $memberrate . '%';
                            $commissions['totalprice'] = number_format($totalmoney, 2, '.', '');
                            $commissions['quantity'] = $quantity;
                            $commissions['amount'] = $ticheng;
                            $commissions['propertyid'] = $propertyid;
                            $commissions['distributionmode'] = $distributionmode;
                            $commissions['ranklimit'] = $ranklimit;
                            $commissions['profileid'] = $profileid;
                            $commissions['productname'] = $orders_product_info->my->productname;
                            $commissions['givenname'] = $givenname;
                            $commissions['published'] = date("Y-m-d H:i");
                            $commissions['orders_no'] = $mall_orders_no;
                            $commissions['distributionrate'] = $distributionrate; 
                            
                            if (isset($distributionobject) && $distributionobject == "1")
                            {
                                execute_noconsumer_commission($profileid, $distributionmode, $supplierid, $commissions);
                            }
                            else
                            {
                                execute_commission($profileid, $distributionmode, $supplierid, $commissions);
                            } 
                        }
                    }
                    
                    

                    $inventoryquery = XN_Query::create('Content')->tag('mall_inventorys_' . $supplierid)
                        ->filter('type', 'eic', 'mall_inventorys')
                        ->filter('my.productid', '=', $productid)
                        ->filter('my.deleted', '=', '0');

                    if (isset($propertyid) && $propertyid != '')
                    {
                        $inventoryquery->filter('my.propertytypeid', '=', $propertyid);
                    }
                    $inventorys = $inventoryquery->end(1)->execute();

                    if (count($inventorys) > 0)
                    {
                        $inventory_info = $inventorys[0];
                        $inventory = $inventory_info->my->inventory;
                        $newinventory = intval($inventory) - $quantity;
                        $inventory_info->my->inventory = $newinventory;
                        $inventory_info->save('mall_inventorys,mall_inventorys_'.$supplierid);

                        $brand = XN_Content::create('mall_turnovers', "", false, 7);
                        $brand->my->supplierid = $supplierid;
                        $brand->my->deleted = '0';
                        $brand->my->productid = $productid;
                        $brand->my->productname = $orders_product_info->my->productname;;
                        $brand->my->propertyid = $propertyid;
                        $brand->my->propertydesc = $propertydesc;
                        $brand->my->mall_turnoversstatus = '销售出库';
                        $brand->my->oldinventory = $inventory;
                        $brand->my->amount = '-' . $quantity;
                        $newinventory = intval($inventory) - intval($quantity);
                        $brand->my->newinventory = $newinventory;
                        $brand->save('mall_turnovers,mall_turnovers_'.$supplierid);
                    }
                }
                if (isset($supplierinfo['allowphysicalstore']) && $supplierinfo['allowphysicalstore'] == '0' && $commissionswitch == "0")
                {
                    $totalmoney = floatval($shop_price) * floatval($quantity);
                    $supplier_physicalstoreprofiles = XN_Query::create ( 'Content' )->tag("supplier_physicalstoreprofiles_".$profileid)
                        ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
                        ->filter ( 'my.supplierid', '=',$supplierid)
                        ->filter ( 'my.profileid', '=', $profileid)
                        ->filter ( 'my.deleted', '=', '0' )
                        ->end(1)
                        ->execute ();
                    if (count($supplier_physicalstoreprofiles) > 0)
                    {
                        $supplier_physicalstoreprofile_info = $supplier_physicalstoreprofiles[0];
                        $physicalstoreid = $supplier_physicalstoreprofile_info->my->physicalstoreid;
                        $storeprofileid = $supplier_physicalstoreprofile_info->my->storeprofileid;
                        $assistantprofileid = $supplier_physicalstoreprofile_info->my->assistantprofileid; 
                        $physicalstore_info = XN_Content::load($physicalstoreid,"supplier_physicalstores_".$supplierid,4);
                        $bonusrate = $physicalstore_info->my->bonusrate; 
                        
                        $amount = $totalmoney * floatval($bonusrate) / 100;
                        
                        $newcontent = XN_Content::create('mall_commissions', '', false, 7);
                        $newcontent->my->deleted = '0';
                        $newcontent->my->supplierid = $supplierid;
                        $newcontent->my->profileid = $storeprofileid;
                        $newcontent->my->consumer = $profileid;
                        $newcontent->my->middleman = '';
                        $newcontent->my->subordinate = '';
                        $newcontent->my->commissionsource = '1';
                        $newcontent->my->orderid = $orderid;
                        $newcontent->my->productid = $productid;
                        $newcontent->my->orders_productid = $orders_product_info->id;
                        $newcontent->my->royaltyrate = $bonusrate.'%';
                        $newcontent->my->commissiontype = '0';
                        $newcontent->my->totalprice = number_format($totalmoney, 2, ".", "");
                        $newcontent->my->quantity = $quantity;
                        $newcontent->my->amount = number_format($amount, 2, ".", "");
                        $newcontent->my->propertyid = $propertyid;
                        $newcontent->my->distributionmode = ''; 
                        $newcontent->save('mall_commissions,mall_commissions_' . $profileid . ',mall_commissions_' . $supplierid);
                        $productname = $orders_product_info->my->productname;
                        $message = '恭喜您，您的顾客'.$givenname.'购买了'.$productname.'。\n提成收益:' . $amount . '元\n订单号:'.$mall_orders_no.'\n时间:'.date("Y-m-d H:i").'\n说明:店主提成。';
                        
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
                            XN_Message::sendmessage($storeprofileid, $message, $appid);
                        }
                        
                        $supplier_physicalstoreassistants = XN_Query::create ( 'Content' )->tag("supplier_physicalstoreassistants_".$profileid)
                            ->filter ( 'type', 'eic', 'supplier_physicalstoreassistants') 
                            ->filter ( 'my.supplierid', '=',$supplierid)
                            ->filter ( 'my.profileid', '=', $assistantprofileid)
                            ->filter ( 'my.deleted', '=', '0' )
                            ->end(1)
                            ->execute ();
                        if (count($supplier_physicalstoreassistants) > 0)
                        {
                            $supplier_physicalstoreassistant_info = $supplier_physicalstoreassistants[0];
                            $bonusrate = $supplier_physicalstoreassistant_info->my->bonusrate; 
                            $amount = $totalmoney * floatval($bonusrate) / 100;
                            
                            $newcontent = XN_Content::create('mall_commissions', '', false, 7);
                            $newcontent->my->deleted = '0';
                            $newcontent->my->supplierid = $supplierid;
                            $newcontent->my->profileid = $assistantprofileid;
                            $newcontent->my->consumer = $profileid;
                            $newcontent->my->middleman = '';
                            $newcontent->my->subordinate = '';
                            $newcontent->my->commissionsource = '2';
                            $newcontent->my->orderid = $orderid;
                            $newcontent->my->productid = $productid;
                            $newcontent->my->orders_productid = $orders_product_info->id;
                            $newcontent->my->royaltyrate = $bonusrate.'%';
                            $newcontent->my->commissiontype = '0';
                            $newcontent->my->totalprice = number_format($totalmoney, 2, ".", "");
                            $newcontent->my->quantity = $quantity;
                            $newcontent->my->amount = number_format($amount, 2, ".", "");
                            $newcontent->my->propertyid = $propertyid;
                            $newcontent->my->distributionmode = ''; 
                            $newcontent->save('mall_commissions,mall_commissions_' . $profileid . ',mall_commissions_' . $supplierid);
                            $productname = $orders_product_info->my->productname;
                            $message = '恭喜您，顾客'.$givenname.'购买了'.$productname.'。\n提成收益:' . $amount . '元\n订单号:'.$mall_orders_no.'\n时间:'.date("Y-m-d H:i").'\n说明:店员提成。';
                        
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
                                XN_Message::sendmessage($assistantprofileid, $message, $appid);
                            }
                        }
             
                    }
                }
                
                $mall_receptionproducts = XN_Query::create('Content')->tag('mall_receptionproducts_' . $supplierid)
                    ->filter('type', 'eic', 'mall_receptionproducts')
                    ->filter('my.supplierid', '=', $supplierid)
                    ->filter('my.productid', '=', $productid)
                    ->filter('my.deleted', '=', '0')
                    ->filter('my.approvalstatus', '=', '2')
                    ->end(1)->execute();
                     
                if (count($mall_receptionproducts) > 0)
                {
                    $mall_receptionproduct_info = $mall_receptionproducts[0];
                    $receptiontimes = $mall_receptionproduct_info->my->receptiontimes;
                    $mall_receptions = XN_Query::create('MainContent')->tag("mall_receptions_" . $supplierid)
                        ->filter('type', 'eic', 'mall_receptions') 
                        ->filter('my.deleted', '=', '0')
                        ->filter('my.supplierid', '=', $supplierid)
                        ->filter('my.profileid', '=', $profileid)
                        ->filter('my.productid', '=', $productid)
                        ->end(1)
                        ->execute();
                    if (count($mall_receptions) == 0)
                    {
                        $addreceptiontimes = $receptiontimes*intval($quantity);
                        $reception_info = XN_Content::create('mall_receptions', '', false);
                        $reception_info->my->deleted = '0';
                        $reception_info->my->supplierid = $supplierid;
                        $reception_info->my->profileid = $profileid;   
                        $reception_info->my->productid = $productid;   
                        $reception_info->my->amount = $addreceptiontimes;
                        $reception_info->my->used = '0'; 
                        $reception_info->save('mall_receptions,mall_receptions_' . $profileid . ',mall_receptions_' . $supplierid);

                        $receptionreord_info = XN_Content::create('mall_receptionrecords', '', false, 7);
                        $receptionreord_info->my->deleted = '0';
                        $receptionreord_info->my->supplierid = $supplierid;
                        $receptionreord_info->my->profileid = $profileid;  
                        $receptionreord_info->my->orderid = $orderid; 
                        $receptionreord_info->my->productid = $productid; 
                        $receptionreord_info->my->receptiontype = 'new'; 
                        $receptionreord_info->my->amount = '+'.$addreceptiontimes; 
                        $receptionreord_info->my->reception = $addreceptiontimes;
                        $receptionreord_info->save('mall_receptionrecords,mall_receptionrecords_' . $profileid . ',mall_receptionrecords_' . $supplierid);

                    }
                    else
                    {
                        $mall_reception_info = $mall_receptions[0];
                        $amount = $mall_reception_info->my->amount; 
                        $addreceptiontimes = $receptiontimes*intval($quantity);
                        $newamount =  intval($amount) + $addreceptiontimes; 
                        $mall_reception_info->my->amount = $newamount;  
                        $mall_reception_info->save('mall_receptions,mall_receptions_' . $profileid . ',mall_receptions_' . $supplierid);
                
                        $receptionreord_info = XN_Content::create('mall_receptionrecords', '', false, 7);
                        $receptionreord_info->my->deleted = '0';
                        $receptionreord_info->my->supplierid = $supplierid;
                        $receptionreord_info->my->profileid = $profileid;  
                        $receptionreord_info->my->orderid = $orderid; 
                        $receptionreord_info->my->productid = $productid; 
                        $receptionreord_info->my->receptiontype = 'add'; 
                        $receptionreord_info->my->amount = '+'.$addreceptiontimes; 
                        $receptionreord_info->my->reception = $newamount;
                        $receptionreord_info->save('mall_receptionrecords,mall_receptionrecords_' . $profileid . ',mall_receptionrecords_' . $supplierid);
                        
                    }
                }

                if (isset($vendorid) && $vendorid != "" && isset($vendor_price) && $vendor_price != "")
                {
                    $mall_settlementorders = XN_Query::create('YearContent')->tag('mall_settlementorders_' . $supplierid)
                        ->filter('type', 'eic', 'mall_settlementorders')
                        ->filter('my.supplierid', '=', $supplierid)
                        ->filter('my.orders_productid', '=', $orders_product_info->id)
                        ->filter('my.deleted', '=', '0')
                        ->end(-1)->execute();

                    if (count($mall_settlementorders) == 0)
                    {
                        $newcontent = XN_Content::create('mall_settlementorders', "", false, 7);
                        $newcontent->my->supplierid = $supplierid;
                        $newcontent->my->deleted = '0';
                        $newcontent->my->orders_productid = $orders_product_info->id;
                        $newcontent->my->orderid = $orderid;
                        $newcontent->my->profileid = $profileid;
                        $newcontent->my->productid = $productid;
                        $newcontent->my->propertyid = $propertyid;
                        $newcontent->my->propertydesc = $propertydesc;
                        $newcontent->my->vendorid = $vendorid;
                        $newcontent->my->vendor_price = $vendor_price;
                        $newcontent->my->shop_price = $shop_price;
                        $newcontent->my->quantity = $quantity;
                        $newcontent->my->returnedquantity = '0';
                        $totalmoney = floatval($shop_price) * floatval($quantity);
                        $newcontent->my->totalmoney = $totalmoney;
                        $vendormoney = floatval($vendor_price) * floatval($quantity);
                        $newcontent->my->vendormoney = $vendormoney;
                        $newcontent->my->vendorsettlementid = '';
                        $newcontent->my->vendorsettlementstatus = '0';
                        $newcontent->my->vendortradestatus = '0';
                        $newcontent->my->vendorsettlementtime = '';
                        $newcontent->my->deliverystatus = '0';
                        $newcontent->my->deliverytime = '';
                        $newcontent->my->delivery = '';
                        $newcontent->my->invoicenumber = '';
                        $newcontent->my->mall_settlementordersstatus = '待发货';

                        $newcontent->my->address          = $order_info->my->address;
                        $newcontent->my->shortaddress     = $order_info->my->shortaddress;
                        $newcontent->my->province         = $order_info->my->province;
                        $newcontent->my->city             = $order_info->my->city;
                        $newcontent->my->district         = $order_info->my->district;
                        $newcontent->my->consignee        = $order_info->my->consignee;
                        $newcontent->my->mobile           = $order_info->my->mobile;
                        $newcontent->my->zipcode          = $order_info->my->zipcode;

                        $newcontent->save('mall_settlementorders,mall_settlementorders_'.$supplierid);
                    }

                } 
                if ($uniquesale == '1')
                {
                    $newcontent = XN_Content::create('mall_uniquesales', "", false);
                    $newcontent->my->supplierid = $supplierid;
                    $newcontent->my->deleted = '0'; 
                    $newcontent->my->orderid = $orderid;
                    $newcontent->my->profileid = $profileid;
                    $newcontent->my->productid = $productid; 
                    $newcontent->my->uniquesaleprocess = '0';  
                    $newcontent->my->mall_uniquesalesstatus = 'JustCreated';  
                    $newcontent->save('mall_uniquesales,mall_uniquesales_'.$supplierid.',mall_uniquesales_'.$profileid);
                }

            }
        }

    }
    catch (XN_Exception $e)
    {
        /*$error=date("Y-m-d H:i:s")."   : [ticheng]-- ".$e->getMessage()."\n";
        $fp = fopen("ttwzerror.txt","a");
        fwrite($fp,$error);
        fclose($fp);*/
    }
}


?>