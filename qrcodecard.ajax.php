<?php


session_start();

require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");


if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
{
    $loginprofileid = $_SESSION['profileid'];
}
elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
{
    $loginprofileid = $_SESSION['accessprofileid'];
}
else
{
    $loginprofileid = "anonymous";
}

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $supplierid = $_SESSION['supplierid'];
}
else
{
    echo '{"code":201,"msg":"没有店铺ID!"}';
    die();
}


try
{

    $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
        ->filter('type', 'eic', 'supplier_profile')
        ->filter('my.profileid', '=', $loginprofileid)
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.deleted', '=', '0')
        ->end(1)
        ->execute();
    if (count($supplier_profile) == 0)
    {
        echo '{"code":201,"msg":"当前会员还没有关注本商城!"}';
        die();
    }
    $supplier_profile_info = $supplier_profile[0];

    $ranklevel = $supplier_profile_info->my->ranklevel;
	
	$supplier_info = get_supplier_info();  
	if (isset($supplier_info['popularizeqrcode']) && $supplier_info['popularizeqrcode'] == "1")
	{
		$ranklevel = "1";
	} 

    if ($ranklevel == "1")
    {
        $wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
            ->filter('type', 'eic', 'supplier_wxsettings')
            ->filter('my.deleted', '=', '0')
            ->filter('my.supplierid', '=', $supplierid)
            ->end(1)
            ->execute();
        if (count($wxsettings) == 0)
        {
            echo '{"code":201,"msg":"请先配置该商城的微信设置!"}';
            die();
        }

        $sysconfig_info = $wxsettings[0];
        $wxid = $sysconfig_info->id;
        $appid = $sysconfig_info->my->appid;
        $secret = $sysconfig_info->my->secret;


        $supplier_wxchannels = XN_Query::create('MainContent')->tag("supplier_wxchannels_" . $loginprofileid)
            ->filter('type', 'eic', 'supplier_wxchannels')
            ->filter('my.profileid', '=', $loginprofileid)
            ->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')
            ->end(1)
            ->execute();
        if (count($supplier_wxchannels) == 0)
        {
            $qrid = 0;
            $claimrecords = XN_Query::create('MainContent')->tag('supplier_wxchannels')
                ->filter('type', 'eic', 'supplier_wxchannels')
                ->filter('my.deleted', '=', '0')
                ->filter('my.supplierid', '=', $supplierid)
                ->order('my.qrid', XN_Order::DESC_NUMBER)
                ->begin(0)->end(1)
                ->execute();
            if (count($claimrecords) > 0)
            {
                $claimrecord_info = $claimrecords[0];
                $qrid = $claimrecord_info->my->qrid;
                $qrid = intval($qrid) + 1;
            }
            require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
            XN_WX::$APPID = $appid;
            XN_WX::$SECRET = $secret;
            $ticket = XN_WX::qrcode($qrid);
            if (isset($ticket) && $ticket != "")
            {
                $channel = XN_Content::create('supplier_wxchannels', '', false);
                $channel->my->qrid = $qrid;
                $channel->my->profileid = $loginprofileid;
                $channel->my->supplierid = $supplierid;
                $channel->my->wxid = $wxid;
                $channel->my->appid = $appid;
                $channel->my->ticket = $ticket;
                $channel->my->deleted = 0;
                $channel->save('supplier_wxchannels,supplier_wxchannels_'.$loginprofileid.',supplier_wxchannels_'.$supplierid);
            }
            else
            {
                echo '{"code":201,"msg":"生成二维码失败,请稍候再试!"}';
                die();
            }
        }

        echo '{"code":200,"supplierid":"'.$supplierid.'","profileid":"'.$loginprofileid.'"}';
    }
    else
    {
        echo '{"code":201,"msg":"您还没有购买,暂不能生成名片,请购买后再生成名片!"}';
        die();
    }
}
catch (XN_Exception $e)
{
    $msg = $e->getMessage();
    echo '{"code":202,"msg":"' . $msg . '"}';
    die();
}


?>