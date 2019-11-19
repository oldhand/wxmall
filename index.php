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

if (isset($_REQUEST['appid']) && $_REQUEST['appid'] != '' &&
    isset($_REQUEST['type']) && ($_REQUEST['type'] == 'init' || $_REQUEST['type'] == 'usercenter')
)
{
    $appid = $_REQUEST["appid"];
    if (isset($_SESSION['appid']) && $_SESSION['appid'] != '')
    {
        if ($_SESSION['appid'] != $appid)
        {
            unset($_SESSION['businesseid']);
            unset($_SESSION['supplierid']);
        }
    }
    $_SESSION['appid'] = $appid;
}

require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");


if (isset($_REQUEST['code']) && $_REQUEST['code'] != '' &&
    isset($_REQUEST['type']) && ($_REQUEST['type'] == 'init' || $_REQUEST['type'] == 'usercenter')
)
{
    $code = $_REQUEST["code"];
    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $WX_APPID . '&secret=' . $WX_SECRET . '&code=' . $code . '&grant_type=authorization_code';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $get_token_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res, true);

    $access_token = $json_obj['access_token'];
    $openid = $json_obj['openid'];
    $_REQUEST['openid'] = $openid;
    if ($_REQUEST['type'] == 'usercenter')
    {
        $_SESSION['uri'] = base64_encode('usercenter.php');
    }
    else
    {
        unset($_SESSION['uri']);
    }
}

if (isset($_REQUEST['width']) && $_REQUEST['width'] != '')
{
    $width = $_REQUEST['width'];
    if (intval($width) > 768)
    {
        $width = "768";
    }
    $_SESSION['width'] = $width;
}

if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'oauth2' &&
    isset($_REQUEST['code']) && $_REQUEST['code'] != '' &&
    isset($_REQUEST['wxopenid']) && $_REQUEST['wxopenid'] != '' &&
    isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] != ''
)
{
    $code = $_REQUEST["code"];
    $supplierid = $_REQUEST["supplierid"];
    $wxopenid = $_REQUEST["wxopenid"];

    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $WX_MAIN_APPID . '&secret=' . $WX_MAIN_SECRET . '&code=' . $code . '&grant_type=authorization_code';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $get_token_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res, true);


    $access_token = $json_obj['access_token'];
    $mainwxopenid = $json_obj['openid'];

    require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
    XN_WX::$APPID = $WX_MAIN_APPID;
    XN_WX::$SECRET = $WX_MAIN_SECRET;

    unset($_SESSION['u']);

    try
    {
        $profile = XN_WX::initprofile($mainwxopenid, true);
        $profileid = $profile->profileid;

        try
        {
            $userinfo = XN_MemCache::get("wxopenid_" . $wxopenid);

            if ($profile->type == "unsubscribe" && is_array($userinfo))
            {
                if ($userinfo['subscribe'] == '1')
                {
                    $profile->status = 'True';

                    $nickname = str_replace("'", "", $userinfo['nickname']);
                    $nickname = str_replace(" ", "", $nickname);
                    $nickname = str_replace("\\", "", $nickname);
                    $nickname = strip_tags($nickname);
                    $profile->givenname = $nickname;
                    $profile->country = $userinfo['country'];
                    $profile->province = $userinfo['province'];
                    $profile->city = $userinfo['city'];
                    $profile->link = $userinfo['headimgurl'];

                    if ($userinfo['sex'] == '1')
                    {
                        $profile->gender = '男';
                    }
                    else
                    {
                        if ($userinfo['sex'] == '2')
                        {
                            $profile->gender = '女';
                        }
                        else
                        {
                            //$profile->gender = '未知';
                        }
                    }
                    $profile->reg_ip = $_SERVER['REMOTE_ADDR'];
                    $profile->browser = getsharebrowser();
                    $profile->system = getsharesystem();
                    $money = preg_replace("/[^0-9\.]/i", "", $profile->money);
                    $frozen_money = preg_replace("/[^0-9\.]/i", "", $profile->frozen_money);
                    $accumulatedmoney = preg_replace("/[^0-9\.]/i", "", $profile->accumulatedmoney);
                    if ($profile->rank == "")
                    {
                        $profile->rank = "0";
                    }
                    if ($money == "")
                    {
                        $profile->money = "0";
                    }
                    if ($frozen_money == "")
                    {
                        $profile->frozen_money = "0";
                    }
                    if ($accumulatedmoney == "")
                    {
                        $profile->accumulatedmoney = "0";
                    }

                    $profile->type = "wxuser";
                    $profile->save("profile,profile_" . $profile->profileid . ",profile_" . $profile->wxopenid);
                }
            }
        }
        catch (XN_Exception $e)
        {
            errorprint('错误', $e->getMessage());
            die();
        }

        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $wxopenid)
            ->filter('type', 'eic', 'supplier_profile')
            //->filter('my.profileid', '=', $profileid)
            //->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')
            ->filter('my.wxopenid', '=', $wxopenid)
            ->end(1)
            ->execute();
        if (count($supplier_profile) == 0)
        {
            $givenname = $profile->givenname;
            if (isset($givenname) && $givenname != "")
            {
	             $onelevelsourcer = "";
				 $twolevelsourcer = "";
				 $threelevelsourcer = ""; 
				 $hassourcer = '0';
	             $supplier_scans = XN_Query::create('MainContent')->tag("supplier_wxscans_" . $supplierid) 
	                        ->filter('type', 'eic', 'supplier_wxscans')
                            ->filter('my.wxopenid', '=', $wxopenid)
                            ->filter('my.deleted', '=', '0')
                            ->end(1)
                            ->execute();
                if (count($supplier_scans) > 0)
                {
	                $supplier_scan_info = $supplier_scans[0];
	                $onelevelsourcer = $supplier_scan_info->my->profileid;
					$hassourcer = '1';
	                $profile_info = get_supplier_profile_info($onelevelsourcer, $supplierid);
	                if (count($profile_info) > 0)
			        { 
				         $twolevelsourcer = $profile_info['onelevelsourcer'];
						 $threelevelsourcer = $profile_info['twolevelsourcer']; 
			        }
			        $supplier_scan_info->my->executestatus = '1';
                    $supplier_scan_info->save('supplier_wxscans,supplier_wxscans_' . $supplierid);
				}
                else
                {
                    $supplier_wxfirstmeets = XN_Query::create('MainContent')->tag('supplier_wxfirstmeets_'.$profileid)
                        ->filter('type', 'eic', 'supplier_wxfirstmeets')
                        ->filter('my.deleted', '=', '0')
                        ->filter('my.supplierid', '=', $supplierid)
                        ->filter('my.profileid', '=', $profileid)
                        ->end(1)
                        ->execute();
                    if (count($supplier_wxfirstmeets) > 0)
                    {
                        $supplier_wxfirstmeet_info = $supplier_wxfirstmeets[0];
                        $onelevelsourcer = $supplier_wxfirstmeet_info->my->sourcer;
						$hassourcer = '1';
                        $profile_info = get_supplier_profile_info($onelevelsourcer, $supplierid);
                        if (count($profile_info) > 0)
                        {
                            $twolevelsourcer = $profile_info['onelevelsourcer'];
                            $threelevelsourcer = $profile_info['twolevelsourcer'];
                        }
                        $supplier_wxfirstmeet_info->my->executestatus = '1';
                        $supplier_wxfirstmeet_info->save('supplier_wxfirstmeets,supplier_wxfirstmeets_' . $supplierid);
                    }
                }
				$_SESSION['supplierid'] = $supplierid;
				$supplier_info = get_supplier_info(); 
				$newprofilebenefit = 0;
				if (isset($supplier_info['newprofilebenefit']) && intval($supplier_info['newprofilebenefit']) > 0)
				{
					$newprofilebenefit = intval($supplier_info['newprofilebenefit']);
				}
                $application = XN_Application::$CURRENT_URL;
                XN_Application::$CURRENT_URL = "admin";
                $newcontent = XN_Content::create('supplier_profile', '', false);
                $newcontent->my->deleted = '0';
                $newcontent->my->profileid = $profileid;
                $newcontent->my->supplierid = $supplierid;
                $newcontent->my->wxopenid = $wxopenid;
                $newcontent->my->givenname = strip_tags($profile->givenname);
                $newcontent->my->province = $profile->province;
                $newcontent->my->city = $profile->city;
                $newcontent->my->gender = $profile->gender;
                $newcontent->my->mobile = $profile->mobile;
                $newcontent->my->birthdate = $profile->birthdate;
                $newcontent->my->gender = $profile->gender;
                $newcontent->my->invitationcode = $profile->invitationcode;
                $newcontent->my->reg_ip = $profile->reg_ip;
                $newcontent->my->rank = '0';
                $newcontent->my->accumulatedmoney = $newprofilebenefit;
                $newcontent->my->money = $newprofilebenefit;
                $newcontent->my->maxtakecash = '0';
                $newcontent->my->latitude = '';
                $newcontent->my->longitude = '';
                $newcontent->my->ranklevel = '0';
				$newcontent->my->subscribe = '0';
				$newcontent->my->authenticationprofile = '0';
                $newcontent->my->onelevelsourcer = $onelevelsourcer;
				$newcontent->my->twolevelsourcer = $twolevelsourcer;
				$newcontent->my->threelevelsourcer = $threelevelsourcer;
				$newcontent->my->hassourcer = $hassourcer;
				$tag = "supplier_profile,supplier_profile_" . $wxopenid . ",supplier_profile_" . $profileid. ",supplier_profile_" . $supplierid;
				$tag .= ",supplier_profile_" . $onelevelsourcer;
				$tag .= ",supplier_profile_" . $twolevelsourcer;
				$tag .= ",supplier_profile_" . $threelevelsourcer;
                $newcontent->save($tag);
                XN_Application::$CURRENT_URL = $application;
				
				if ( $newprofilebenefit > 0)
				{
	                $newcontent = XN_Content::create('mall_billwaters', '', false, 8);
	                $newcontent->my->deleted = '0';
	                $newcontent->my->profileid = $profileid;
	                $newcontent->my->supplierid = $supplierid;
	                $newcontent->my->billwatertype = 'newprofilebenefit';
	                $newcontent->my->sharedate = '-';
	                $newcontent->my->orderid = '';
	                $newcontent->my->amount = '+' . $newprofilebenefit;
	                $newcontent->my->money = $newprofilebenefit;
	                $newcontent->save('mall_billwaters,mall_billwaters_' . $profileid . ',mall_billwaters_' . $supplierid);
	            } 
				if (isset($supplier_info['allowphysicalstore']) && $supplier_info['allowphysicalstore'] == '0' &&
					isset($onelevelsourcer) && $onelevelsourcer != '')
				{
					$supplier_physicalstoreprofiles = XN_Query::create ( 'Content' ) 
					    ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
						->filter ( 'my.supplierid', '=',$supplierid)
						->filter ( 'my.profileid', '=',$onelevelsourcer)
					    ->filter ( 'my.deleted', '=', '0' )
						->end(1)
					    ->execute ();
					if (count($supplier_physicalstoreprofiles) > 0)
					{
						$supplier_physicalstoreprofile_info = $supplier_physicalstoreprofiles[0];
						$physicalstoreid = $supplier_physicalstoreprofile_info->my->physicalstoreid;
						$storeprofileid = $supplier_physicalstoreprofile_info->my->storeprofileid;
						$assistantprofileid = $supplier_physicalstoreprofile_info->my->assistantprofileid;
				
				        $newcontent = XN_Content::create('supplier_physicalstoreprofiles', '', false);
				        $newcontent->my->deleted = '0';
				        $newcontent->my->profileid = $profileid;
				        $newcontent->my->supplierid = $supplierid;
				        $newcontent->my->physicalstoreid = $physicalstoreid;
						$newcontent->my->storeprofileid = $storeprofileid; 
						$newcontent->my->assistantprofileid = $assistantprofileid; 
				        $newcontent->save("supplier_physicalstoreprofiles,supplier_physicalstoreprofiles_" . $profileid. ",supplier_physicalstoreprofiles_" . $supplierid);
				 
				 
					}
				}
            }
        }

        $wxopenids = XN_Query::create('MainContent')->tag("wxopenids_" . $profileid)
            ->filter('type', 'eic', 'wxopenids')
            //->filter('my.profileid', '=', $profileid)
            ->filter('my.wxopenid', '=', $wxopenid)
            ->end(1)
            ->execute();
        if (count($wxopenids) == 0)
        {
            $newcontent = XN_Content::create('wxopenids', '', false);
            $newcontent->my->profileid = $profileid;
            $newcontent->my->unionid = '';
            $newcontent->my->wxopenid = $wxopenid;
            $newcontent->my->appid = $WX_APPID;
            $newcontent->save("wxopenids,wxopenids_" . $wxopenid . ",wxopenids_" . $profileid);
        }
    }
    catch (XN_Exception $e)
    {
        errorprint('错误', $e->getMessage());
        die();
    }
    $_REQUEST['openid'] = $wxopenid;
}


if (isset($_REQUEST['openid']) && $_REQUEST['openid'] != '')
{
    $_SESSION['profileid'] = '';

    $firsttime = 'false';
    $wxopenid = $_REQUEST['openid'];
   
    $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $wxopenid)
        ->filter('type', 'eic', 'supplier_profile')
        ->filter('my.deleted', '=', '0')
        ->filter('my.wxopenid', '=', $wxopenid)
        ->end(1)
        ->execute();
    if (count($supplier_profile) > 0)
    {
        $supplier_profile_info = $supplier_profile[0];
        $profileid = $supplier_profile_info->my->profileid;
        $profile = XN_Profile::load($profileid, "id", "profile_" . $profileid);
        $supplierid = $supplier_profile_info->my->supplierid;

        if (isset($_SESSION['appid']) && $_SESSION['appid'] != '')
        {
            $appid = $_SESSION['appid'];
            $wxopenids = XN_Query::create('MainContent')->tag("profile_" . $profileid)
                ->filter('type', 'eic', 'wxopenids')
                ->filter('my.profileid', '=', $profileid)
                ->filter('my.wxopenid', '=', $wxopenid)
                ->end(1)
                ->execute();
            if (count($wxopenids) > 0)
            {
                $wxopenid_info = $wxopenids[0];
                if ($wxopenid_info->my->appid != $appid)
                {
                    $wxopenid_info->my->appid = $appid;
                    $wxopenid_info->save("profile,profile_" . $profileid . ",profile_" . $wxopenid);
                }
            }
        }
    }
    else
    {
        global $wxsetting;
        $supplierid = $wxsetting['supplierid'];

        if (isset($supplierid) && $supplierid != "")
        {
            require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
            XN_WX::$APPID = $WX_APPID;
            XN_WX::$SECRET = $WX_SECRET;

            $userinfo = XN_WX::getuserinfo($wxopenid);

            XN_MemCache::put($userinfo, "wxopenid_" . $wxopenid, 100);
            $host = $_SERVER['HTTP_HOST'];
            $redirect_uri = sprintf('http://%s/o2o.php?target=index&type=init&wxopenid=%s&supplierid=%s&host=%s', $WX_MAIN_DOMAIN, $wxopenid, $supplierid, $host);
            $url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=1#wechat_redirect', $WX_MAIN_APPID, urlencode($redirect_uri));

            header("Location:" . $url);
            die();
        }
        else
        {
            errorprint('错误', '无法获得商家ID！');
            die();
        }
    }


    unset($_SESSION['accessprofileid']);
    $_SESSION['profileid'] = $profileid;
    $_SESSION['supplierid'] = $supplierid;
    $_SESSION['u'] = $profileid;
    $profileinfo = get_supplier_profile_info($profileid, $supplierid);
    if (count($profileinfo) == 0)
    {
        errorprint('错误', '无法获得用户信息！');
        die();
    }
    if (isset($_SESSION['uri']) && $_SESSION['uri'] != '')
    {
        $uri = $_SESSION['uri'];
        unset($_SESSION['uri']);
        header("Location:" . base64_decode($uri));
        die();
    }
}

if (isset($_GET['u']) && $_GET['u'] != '' && isset($_GET['sid']) && $_GET['sid'] != '')
{
    $profileid = $_GET['u'];
    $supplierid = $_GET['sid'];


    if (isset($_GET['uri']) && $_GET['uri'] != '')
    {
        $uri = $_GET['uri'];
        $_SESSION['uri'] = $uri;
    }
    else
    {
        $uri = '';
    }

    unset($_SESSION['profileid']);

    $_SESSION['u'] = $profileid;

    try
    {
        $supplierinfo = get_supplier_info($supplierid);
        $appid = $supplierinfo['appid'];

        if (isset($_SESSION['appid']) && $_SESSION['appid'] != '')
        {
            if ($_SESSION['appid'] != $appid)
            {
                unset($_SESSION['businesseid']);
            }
        }

        $_SESSION['appid'] = $appid;
        $_SESSION['supplierid'] = $supplierid;
    }
    catch (XN_Exception $e)
    {

    }
    $host = $_SERVER['HTTP_HOST'];

    $redirect_uri = sprintf('http://%s/o2o.php?target=index&type=access&supplierid=%s&host=%s', $WX_MAIN_DOMAIN, $supplierid, $host);
    $url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=1#wechat_redirect', $WX_MAIN_APPID, urlencode($redirect_uri));
    header("Location:" . $url);
    die();
}
if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'home' &&
    isset($_REQUEST['code']) && $_REQUEST['code'] != '' &&
    isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] != ''
)
{
    $code = $_REQUEST["code"];
    $supplierid = $_REQUEST["supplierid"];

    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $WX_MAIN_APPID . '&secret=' . $WX_MAIN_SECRET . '&code=' . $code . '&grant_type=authorization_code';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $get_token_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res, true);


    $access_token = $json_obj['access_token'];
    $wxopenid = $json_obj['openid'];

    require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
    XN_WX::$APPID = $WX_MAIN_APPID;
    XN_WX::$SECRET = $WX_MAIN_SECRET;

    try
    {
        global $firsttime;
        $profile = XN_WX::initprofile($wxopenid, true);
        $profileid = $profile->profileid;
        $sourcer = $profile->sourcer;

        $_SESSION['profileid'] = $profileid;
        $_SESSION['u'] = $profileid;

        if (isset($_SESSION['productid']) && $_SESSION['productid'] != '')
        {
            $productid = $_SESSION['productid'];
            unset($_SESSION['productid']);
            header("Location:detail.php?productid=" . $productid);
            die();
        }
        if (isset($_SESSION['uri']) && $_SESSION['uri'] != '')
        {
            $uri = $_SESSION['uri'];
            unset($_SESSION['uri']);
            header("Location:" . base64_decode($uri));
            die();
        }

    }
    catch (XN_Exception $e)
    {
        messagebox('错误', $e->getMessage());
        die();
    }
}

if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'access' &&
    isset($_REQUEST['code']) && $_REQUEST['code'] != '' &&
    isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] != ''
)
{
    $code = $_REQUEST["code"];
    $supplierid = $_REQUEST["supplierid"];

    $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $WX_MAIN_APPID . '&secret=' . $WX_MAIN_SECRET . '&code=' . $code . '&grant_type=authorization_code';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $get_token_url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res, true);


    $access_token = $json_obj['access_token'];
    $wxopenid = $json_obj['openid'];

    require_once(XN_INCLUDE_PREFIX . "/XN/Wx.php");
    XN_WX::$APPID = $WX_MAIN_APPID;
    XN_WX::$SECRET = $WX_MAIN_SECRET;

    try
    {
        global $firsttime;
        $profile = XN_WX::initprofile($wxopenid, true);
        $profileid = $profile->profileid;
        $sourcer = $profile->sourcer;
        if (isset($sourcer) && $sourcer != "" && $firsttime == "true")
        {
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
                $message = '新客户入驻.';
                XN_Message::sendmessage($sourcer, $message, $appid);
            }
        }




        $_SESSION['accessprofileid'] = $profileid;




        if (isset($_SESSION['uri']) && $_SESSION['uri'] != '')
        {
            $uri = $_SESSION['uri'];
            unset($_SESSION['uri']);
            header("Location:" . base64_decode($uri));
            die();
        }

    }
    catch (XN_Exception $e)
    {
        messagebox('错误', $e->getMessage());
        die();
    }
}


try
{
    $recommend_info = checkrecommend();
}
catch (XN_Exception $e)
{
    messagebox('错误', $e->getMessage());
    die();
}


require_once('Smarty_setup.php');

$smarty = new vtigerCRM_Smarty;

$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
    $islogined = true;
}
else
{
    global $wxsetting;
    $qrcodeimage = $wxsetting['qrcodeimage'];

    if (isset($qrcodeimage) && $qrcodeimage != "")
    {
        $profileid = $_SESSION['accessprofileid'];
        $supplierid = $_SESSION['supplierid'];
        $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
            ->filter('type', 'eic', 'supplier_profile')
            ->filter('my.profileid', '=', $profileid)
            ->filter('my.supplierid', '=', $supplierid)
            ->filter('my.deleted', '=', '0')
            ->end(1)
            ->execute();
        if (count($supplier_profile) == 0)
        {
            if (isset($_SESSION['u']) && $_SESSION['u'] != "")
            {
                $supplier_wxfirstmeets = XN_Query::create('MainContent')->tag('supplier_wxfirstmeets_'.$profileid)
                    ->filter('type', 'eic', 'supplier_wxfirstmeets')
                    ->filter('my.deleted', '=', '0')
                    ->filter('my.supplierid', '=', $supplierid)
                    ->filter('my.profileid', '=', $profileid)
                    ->end(1)
                    ->execute();
                if (count($supplier_wxfirstmeets) == 0)
                {
                    try
                    {
                        $application = XN_Application::$CURRENT_URL;
                        XN_Application::$CURRENT_URL = "admin";
                        $newcontent = XN_Content::create('supplier_wxfirstmeets', '', false);
                        $newcontent->my->deleted = '0';
                        $newcontent->my->profileid = $profileid;
                        $newcontent->my->supplierid = $supplierid;
                        $newcontent->my->sourcer = $_SESSION['u'];
                        $newcontent->my->executestatus = '0';
                        $newcontent->save("supplier_wxfirstmeets,supplier_wxfirstmeets_" . $profileid. ",supplier_profile_" . $supplierid);
                        XN_Application::$CURRENT_URL = $application;
                    }
                    catch (XN_Exception $e)
                    {
                    }
                }
            }
            $smarty->assign("seekattention", 'yes');
        }
    }
}
$smarty->assign("islogined", $islogined);

$smarty->assign("share_info", $recommend_info);
 

$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
    $smarty->assign("profile_info", $profile_info);
}
else
{
    $smarty->assign("profile_info", get_profile_info());
}

$supplier_info = get_supplier_info();

$forceactivation = $supplier_info['forceactivation'];


if ($forceactivation == "0")
{
	$hassourcer = $profile_info['hassourcer'];
	$sourcer = $profile_info['onelevelsourcer']; 
	if ($hassourcer == '0' && (!isset($sourcer) || $sourcer == ''))
	{
        header("Location: userdetail.php?type=forceactivation");
        die();
	}
}

$smarty->assign("supplier_info", $supplier_info);
$smarty->assign("actionname", strtolower(basename(__FILE__, ".php")));


if (!isset($_SESSION['width']) || $_SESSION['width'] == '')
{
    $_SESSION['width'] = "320";
}

if (check_http_user_agent() == 'ios')
{
    $width = $_SESSION['width'];
    $width = $width * 2;
    if ($width > 768)
    {
        $width = 768;
    }
    $_SESSION['width'] = $width;
}

$sysinfo = array();
$sysinfo['action'] = 'index';
$sysinfo['date'] = date("md");
$sysinfo['api'] = $APISERVERADDRESS;
$sysinfo['http_user_agent'] = check_http_user_agent();
$sysinfo['domain'] = $WX_DOMAIN;
$sysinfo['width'] = $_SESSION['width'];
$smarty->assign("sysinfo", $sysinfo);

if (isset($_REQUEST['scrollTop']) && $_REQUEST['scrollTop'] > 0)
{
    $smarty->assign("scrollTop", $_REQUEST['scrollTop']);
}
$supplierid = $_SESSION['supplierid'];
//轮播
$ads = XN_Query::create('Content')
    ->tag('mall_ads_' . $supplierid)
    ->filter('type', 'eic', 'mall_ads')
    ->filter('my.deleted', '=', '0')
    ->filter(XN_Filter::any(XN_Filter('my.timeset', '=', '0'), XN_Filter::all(XN_Filter('my.starttime', '<=', date("Y-m-d")), XN_Filter('my.endtime', '>=', date("Y-m-d")))))
    ->filter('my.supplierid', '=', $supplierid)
    ->filter('my.status', '=', '0')
    ->order("my.sequence", XN_Order::ASC_NUMBER)
    ->execute();
$adshuffle = array();
foreach ($ads as $ad_info)
{
    $link = $ad_info->my->link;
    $image = $ad_info->my->image;
	$webimage = $ad_info->my->webimage; 
    $f2cadlinktarget = $ad_info->my->f2cadlinktarget;
    $productid = $ad_info->my->productid;
    $salesactivityid = $ad_info->my->salesactivityid;
    $newsid = $ad_info->my->newsid;

    global $APISERVERADDRESS;
    if (isset($image) && $image != "")
    {
        $image = $APISERVERADDRESS . $image;

        if (isset($productid) && $productid != "" && $f2cadlinktarget == '0')
        {
            $adshuffle[] = array(
                'id'      => $ad_info->id,
                'adtitle' => $ad_info->my->adtitle,
                'banner'  => $image,
				'webimage'  => $webimage,
                'link'    => 'detail.php?from=index&productid=' . $productid,);
        }
        else
        {
            if (isset($salesactivityid) && $salesactivityid != "" && $f2cadlinktarget == '1')
            {
                $adshuffle[] = array(
                    'id'      => $ad_info->id,
                    'adtitle' => $ad_info->my->adtitle,
                    'banner'  => $image,
					'webimage'  => $webimage,
                    'link'    => 'salesactivity.php?record=' . $salesactivityid,);
            }
            else
            {
                if (isset($newsid) && $newsid != "" && $f2cadlinktarget == '2')
                {
                    $adshuffle[] = array(
                        'id'      => $ad_info->id,
                        'adtitle' => $ad_info->my->adtitle,
                        'banner'  => $image,
						'webimage'  => $webimage,
                        'link'    => 'view.php?id=' . $newsid,);
                }
                else
                {
                    $adshuffle[] = array(
                        'id'      => $ad_info->id,
                        'adtitle' => $ad_info->my->adtitle,
                        'banner'  => $image,
						'webimage'  => $webimage,
                        'link'    => '',);
                }
            }
        }
    }

}
$smarty->assign("ads", $adshuffle);

 


$mall_salesactivitys = XN_Query::create('Content')->tag('mall_salesactivitys_' . $supplierid)
    ->filter('type', 'eic', 'mall_salesactivitys')
    ->filter('my.deleted', '=', '0')
    ->filter('my.supplierid', '=', $supplierid)
    ->filter('my.approvalstatus', '=', '2')
    ->filter('my.status', '=', '0')
    ->filter('my.showhomepage', '=', '0')
    ->filter('my.begindate', '<=', date("Y-m-d"))
    ->filter('my.enddate', '>=', date("Y-m-d"))
    ->order("my.sequence", XN_Order::DESC_NUMBER)
    ->end(-1)
    ->execute();

$salesactivitylist = array();
if (count($mall_salesactivitys) > 0)
{
    foreach ($mall_salesactivitys as $mall_salesactivity_info)
    {
        $id = $mall_salesactivity_info->id;
        $published = $mall_salesactivity_info->published;
        $salesactivitylist[$id]['id'] = $id;
        $salesactivitylist[$id]['published'] = date("Y-m-d H:i", strtotime($published));
        $salesactivitylist[$id]['activityname'] = $mall_salesactivity_info->my->activityname;
        $salesactivitylist[$id]['display_type'] = $mall_salesactivity_info->my->display_type;
        $salesactivitylist[$id]['status'] = $mall_salesactivity_info->my->status;
        $salesactivitylist[$id]['sequence'] = $mall_salesactivity_info->my->sequence;
        $salesactivitylist[$id]['activitylogo'] = $mall_salesactivity_info->my->activitylogo;
        $salesactivitylist[$id]['homepage'] = $mall_salesactivity_info->my->homepage;
        $salesactivitylist[$id]['activity_desc'] = $mall_salesactivity_info->my->activity_desc;
        $salesactivitylist[$id]['begindate'] = date("Y-m-d", strtotime($mall_salesactivity_info->my->begindate));
        $salesactivitylist[$id]['enddate'] = date("Y-m-d", strtotime($mall_salesactivity_info->my->enddate));
        $salesactivitylist[$id]['mall_salesactivitysstatus'] = $mall_salesactivity_info->my->mall_salesactivitysstatus;

    }
}

$smarty->assign("salesactivitylist", $salesactivitylist);

$loginswap = 1;
if (isset($profile_info['logintime']) && $profile_info['logintime'] != '')
{
    $logintime = $profile_info['logintime'];
    $diff = strtotime("now") - $logintime;
    if ($diff < 1)
    {
        $loginswap = 0;
    }
}

$smarty->assign("loginswap", $loginswap);


if(isset($supplier_info['mainpageshowobject']) && $supplier_info['mainpageshowobject'] == '1')
{
	$supplierid = $_SESSION['supplierid']; 
	$mall_vendors = XN_Query::create('Content')->tag('mall_vendors_' . $supplierid)
	    ->filter('type', 'eic', 'mall_vendors')
	    ->filter('my.deleted', '=', '0')
	    ->filter('my.supplierid', '=', $supplierid)
	    ->filter('my.approvalstatus', '=', '2')
	    ->filter('my.status', '=', '0')  
	    ->end(-1)
	    ->execute();
	$vendors = array(); 
	foreach($mall_vendors as $mall_vendor_info)
	{
		$vendorid = $mall_vendor_info->id;
		$vendors[$vendorid]['vendorid'] = $vendorid;
		$vendors[$vendorid]['vendorname'] = $mall_vendor_info->my->vendorname;
		$vendors[$vendorid]['contact'] = $mall_vendor_info->my->contact;
		$vendors[$vendorid]['telphone'] = $mall_vendor_info->my->telphone;
		$vendors[$vendorid]['address'] = $mall_vendor_info->my->address; 
		$vendors[$vendorid]['image'] = $mall_vendor_info->my->image; 
	}
	shuffle($vendors);
	$smarty->assign("vendors", $vendors); 
} 

if(!checkismobile())
{
	 require_once (dirname(__FILE__) . "/index.func.php");
	 $products = mall_products(1,$supplierid,33);  
	 $smarty->assign("products", $products);
	 $salevolume_products = mall_salevolume_products($supplierid);
	 $smarty->assign("salevolume_products", $salevolume_products);
	 $newproducts = mall_newproducts($supplierid);
	 $smarty->assign("newproducts", $newproducts); 
}


$mall_orders = XN_Query::create('YearContent')->tag('mall_orders_' . $supplierid)
	    ->filter('type', 'eic', 'mall_orders')
	    ->filter('my.deleted', '=', '0')
	    ->filter('my.supplierid', '=', $supplierid)
	    ->filter('my.tradestatus', '=', 'trade') 
	    ->order("published",XN_Order::DESC)
	    ->end(1)
	    ->execute(); 
if (count($mall_orders) > 0)
{
	$mall_order_info = $mall_orders[0];
	$consignee = $mall_order_info->my->consignee;
	$mobile = $mall_order_info->my->mobile;
	$city = $mall_order_info->my->city;
	$district = $mall_order_info->my->district;
	$ordername = $mall_order_info->my->ordername;
	$productcount = $mall_order_info->my->productcount;
	$ordernews = $city.$district.substr($mobile,0,7)."****购买了".$ordername.$productcount."件";
	$smarty->assign("ORDERNEWS", $ordernews);
}	
else
{
	$smarty->assign("ORDERNEWS", ""); 
}    
	    
	    
$smarty->display('index.tpl');


?>