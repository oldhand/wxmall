<?php

require_once(dirname(__FILE__) . "/config.error.php");


if (!function_exists('matchbrowser'))
{

    function matchbrowser($Agent, $Patten)
    {
        if (preg_match($Patten, $Agent, $Tmp))
        {
            return $Tmp[1];
        }
        else
        {
            return false;
        }
    }
}
//获取浏览器信息
if (!function_exists('getsharebrowser'))
{
    function getsharebrowser()
    {
        $useragent = $_SERVER["HTTP_USER_AGENT"];
        if ($Browser = matchbrowser($useragent, "|(myie[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(Netscape[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(Opera[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(NetCaptor[^;^^()]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(TencentTraveler)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(Firefox[0-9/\.^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(MSN[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(Lynx[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(Konqueror[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(WebTV[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(msie[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(Maxthon[^;^)^(]*)|i")) ;
        else if ($Browser = matchbrowser($useragent, "|(QQ[0-9.\/]*) |i")) ;
        else if ($Browser = matchbrowser($useragent, "|MicroMessenger/([^;^)^(]*)_|i"))
        {
            return "微信" . trim($Browser);
        }
        else if ($Browser = matchbrowser($useragent, "|MicroMessenger/([^;^)^(]*) |i"))
        {
            return "微信" . trim($Browser);
        }
        else if ($Browser = matchbrowser($useragent, "|(Scrapy[^;^)^(]*)|i")) 
	    {
		    $Browser = 'Scrapy';
	    }
	    else if ($Browser = matchbrowser($useragent, "|(tezan_iOS[^;^)^(]*)|i")) 
	    {
		    $Browser = 'tezan';
	    }
	    else if ($Browser = matchbrowser($useragent, "|(tezan_Android[^;^)^(]*)|i")) 
	    {
		    $Browser = 'tezan';
	    }
        else
        {
            $Browser = '其它';
        }
        return trim($Browser);
    }
}


//获取操作系统版本
if (!function_exists('getsharesystem'))
{
    function getsharesystem()
    {
        $useragent = $_SERVER["HTTP_USER_AGENT"];
        if ($System = matchbrowser($useragent, "|(Windows NT[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(Windows Phone[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(Windows[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(iPhone OS[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(Mac[^;^)]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(unix)|i")) ;
        else if ($System = matchbrowser($useragent, "|(Android[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(Linux[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(SunOS[\ 0-9\.]*)|i")) ;
        else if ($System = matchbrowser($useragent, "|(BSD[\ 0-9\.]*)|i")) ;
        else if (matchbrowser($useragent, "|(tezan_iOS[^;^)^(]*)|i")) 
	    {
		    $System = 'tezan';
	    }
	    else if (matchbrowser($useragent, "|(tezan_Android[^;^)^(]*)|i")) 
	    {
		    $System = 'tezan';
	    }
        else
        {
            $System = '其它';
        }
        return trim($System);
    }
}
//检测浏览器客户端
function check_http_user_agent()
{
    $userAgent = $_SERVER['HTTP_USER_AGENT']; 
    if (matchbrowser($userAgent, "|(tezan_iOS[^;^)^(]*)|i")) 
    {
	    return 'tezan';
    }
    else if (matchbrowser($userAgent, "|(tezan_Android[^;^)^(]*)|i")) 
    {
	    return 'tezan';
    }
    else if (strpos($userAgent, "iPhone") || strpos($userAgent, "iPad") || strpos($userAgent, "iPod") || strpos($userAgent, "iOS"))
    {
        //iPhone
        return "ios";
    }
    else if (strpos($userAgent, "Android"))
    {
        //Android
        return "android";
    }
    
    else
    {
        //电脑
        return "pc";
    }
}

if (!function_exists('checkisweixin'))
{
    function checkisweixin()
    {
        $useragent = $_SERVER["HTTP_USER_AGENT"];

        if (preg_match("|(MicroMessenger[^;^)^(]*)|i", $useragent))
        {
            return true;
        }
        return false;
    }
}
if (!function_exists('checkismobile'))
{
    function checkismobile()
    {
        $useragent = $_SERVER["HTTP_USER_AGENT"];

        if (preg_match("|(Android)|i", $useragent) || preg_match("|(iPhone)|i", $useragent))
        {
            return true;
        }
        return false;
    }
}

if (!function_exists('checkismobileapp'))
{
    function checkismobileapp()
    {
        $useragent = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("|(tezan_Android)|", $useragent))
        {
            return "android";
        }
        else if (preg_match("|(tezan_iOS)|", $useragent))
        {
            return "ios";
        }
        else
        {
            return "";
        }
    }
}

//更新分享图片配置函数
function ReWriteShareLogosConfig()
{
    global $configfile;

    if (@file_exists($configfile))
    {
        if (!is_writeable($configfile))
        {
            echo '配置文件' . $configfile . '不支持写入，无法修改微信的配置参数！';
            die;
        }
    }
    $fp = fopen($configfile, 'w+');
    flock($fp, 3);

    $sharelogos = scandir($_SERVER['DOCUMENT_ROOT'] . '/public/sharelogos');

    $wxsettings = "<?php\n\t\$sharelogos = array (\n";
    $key = 0;
    foreach ($sharelogos as $sharelogo)
    {
        if ($sharelogo != "." && $sharelogo != "..")
        {
            $wxsettings .= "\t\t'" . $key . "' => '" . $sharelogo . "',\n";
            $key++;
        }
    }
    $wxsettings .= "\t);\n?>";

    fwrite($fp, $wxsettings);
    fclose($fp);
}

function randomkeys($length)
{
    $pattern = '1234567890';
    $key = "";
    for ($i = 0; $i < $length; $i++)
    {
        $key .= $pattern{mt_rand(0, 9)};    //生成php随机数
    }
    return $key;
}


function formatnumber($value)
{
    if ($value == 0) return "0";
    if ($value == "") return "";
    if ($value == "-") return "-";
    return number_format(floatval($value), 2, ".", ",");
}


function guid()
{
    mt_srand((double)microtime() * 10000);
    return strtoupper(md5(uniqid(rand(), true)));
}

if (!function_exists('cn_substr_utf8'))
{
    function cn_substr_utf8($str, $length, $start = 0)
    {
        if (strlen($str) < $start + 1)
        {
            return '';
        }
        preg_match_all("/./su", $str, $ar);
        $str = '';
        $tstr = '';

        //为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
        for ($i = 0; isset($ar[0][$i]); $i++)
        {
            if (strlen($tstr) < $start)
            {
                $tstr .= $ar[0][$i];
            }
            else
            {
                if (strlen($str) < $length + strlen($ar[0][$i]))
                {
                    $str .= $ar[0][$i];
                }
                else
                {
                    break;
                }
            }
        }
        return $str;
    }
}

//检测wap端公众号配置
function check_wap_wxsetting_config($appid)
{
    global $wxsetting;
    try
    {
        $wxsetting = XN_MemCache::get("wxsettings_" . $appid);
        if (!isset($wxsetting['appid']) || $wxsetting['appid'] == "")
        {
            make_wap_wxsetting_config($appid);
        }
    }
    catch (XN_Exception $e)
    {
        make_wap_wxsetting_config($appid);
    }
}
function make_wap_wxsetting_config($appid)
{
    $application = XN_Application::$CURRENT_URL;
    XN_Application::$CURRENT_URL = "admin";
    $wxsettings = XN_Query::create('Content')->tag('wxsettings')
        ->filter('type', 'eic', 'wxsettings')
        ->filter('my.deleted', '=', '0')
        ->filter('my.appid', '=', $appid)
        ->end(1)
        ->execute();
    if (count($wxsettings) > 0)
    {
        $sysconfig_info = $wxsettings[0];
        $wxid = $sysconfig_info->id;
        $wxname = $sysconfig_info->my->wxname;
        $appid = $sysconfig_info->my->appid;
        $secret = $sysconfig_info->my->secret;
        $token = $sysconfig_info->my->token;
        $wxtype = $sysconfig_info->my->wxtype;
        $welcometitle = $sysconfig_info->my->welcometitle;
        $image = $sysconfig_info->my->image;
        $originalid = $sysconfig_info->my->originalid;

        $description = $sysconfig_info->my->description;
        $defaultreply = $sysconfig_info->my->defaultreply;
        $welcomewords = $sysconfig_info->my->welcomewords;

        $weixintype = $sysconfig_info->my->weixintype;
        $weixinpay = $sysconfig_info->my->weixinpay;
        $mchid = $sysconfig_info->my->mchid;
        $mchkey = $sysconfig_info->my->mchkey;
        $sslcert = $sysconfig_info->my->sslcert;
        $sslkey = $sysconfig_info->my->sslkey;


        $wxsetting = array();
        $wxsetting['wxid'] = $wxid;
        $wxsetting['wxname'] = $wxname;
        $wxsetting['appid'] = $appid;
        $wxsetting['originalid'] = $originalid;
        $wxsetting['secret'] = $secret;
        $wxsetting['token'] = $token;
        $wxsetting['wxtype'] = $wxtype;
        $wxsetting['welcometitle'] = $welcometitle;
        $wxsetting['description'] = $description;
        $wxsetting['defaultreply'] = $defaultreply;
        $wxsetting['image'] = $image;
        $wxsetting['welcomewords'] = $welcomewords;

        $wxsetting['qrcodeimage'] = $sysconfig_info->my->qrcodeimage;
        $wxsetting['adbackgroundimage'] = $sysconfig_info->my->adbackgroundimage;

        $wxsetting['weixintype'] = $weixintype;
        $wxsetting['weixinpay'] = $weixinpay;
        $wxsetting['mchid'] = $mchid;
        $wxsetting['mchkey'] = $mchkey;
        $wxsetting['sslcert'] = $sslcert;
        $wxsetting['sslkey'] = $sslkey;

        $sysconfigs = XN_Query::create('Content')->tag('wxroles')
            ->filter('type', 'eic', 'wxroles')
            ->filter('my.wxid', '=', $wxid)
            ->filter('my.status', '=', 'Active')
            ->filter('my.deleted', '=', '0')
            ->end(-1)
            ->execute();
        if (count($sysconfigs) > 0)
        {
            foreach ($sysconfigs as $sysconfig_info)
            {
                $roleid = $sysconfig_info->id;
                $wxid = $sysconfig_info->my->wxid;
                $reply = $sysconfig_info->my->reply;
                $triggerkey = $sysconfig_info->my->triggerkey;
                $wxroletype = $sysconfig_info->my->wxroletype;
                $replytitle = $sysconfig_info->my->replytitle;
                $image = $sysconfig_info->my->image;
                $description = $sysconfig_info->my->description;
                $description = str_replace("'", "\\'", $description);

                $wxrolesettings = array();
                $wxrolesettings['roleid'] = $roleid;
                $wxrolesettings['wxid'] = $wxid;
                $wxrolesettings['triggerkey'] = $triggerkey;
                $wxrolesettings['wxroletype'] = $wxroletype;
                $wxrolesettings['replytitle'] = $replytitle;
                $wxrolesettings['description'] = $description;
                $wxrolesettings['image'] = $image;
                $wxrolesettings['reply'] = str_replace('\'', '\\\'', $reply);
                $wxsetting['wxrolesettings'][] = $wxrolesettings;
            }
        }
        XN_MemCache::put($wxsetting, "wxsettings_" . $appid);
    }
    else
    {
        errorprint('错误', 'no wxsetting!');
        die();
    }
    XN_Application::$CURRENT_URL = $application;
}
//检测f2c端公众号配置
function check_wxsetting_config($appid)
{
    global $wxsetting;
    try
    {
        $wxsetting = XN_MemCache::get("wxsettings_" . $appid);
        if (!isset($wxsetting['appid']) || $wxsetting['appid'] == "")
        {
            make_wxsetting_config($appid);
        }
    }
    catch (XN_Exception $e)
    {
        make_wxsetting_config($appid);
    }
}

function make_wxsetting_config($appid)
{
    $application = XN_Application::$CURRENT_URL;
    XN_Application::$CURRENT_URL = "admin";
    $wxsettings = XN_Query::create('Content')->tag('supplier_wxsettings')
        ->filter('type', 'eic', 'supplier_wxsettings')
        ->filter('my.deleted', '=', '0')
        ->filter('my.appid', '=', $appid)
        ->end(1)
        ->execute();
    if (count($wxsettings) > 0)
    {
        $sysconfig_info = $wxsettings[0];
        $wxid = $sysconfig_info->id;
        $wxname = $sysconfig_info->my->wxname;
        $appid = $sysconfig_info->my->appid;
        $supplierid = $sysconfig_info->my->supplierid;
        $secret = $sysconfig_info->my->secret;
        $token = $sysconfig_info->my->token;
        $wxtype = $sysconfig_info->my->wxtype;
        $welcometitle = $sysconfig_info->my->welcometitle;
        $image = $sysconfig_info->my->image;
        $originalid = $sysconfig_info->my->originalid;

        $description = $sysconfig_info->my->description;
        $defaultreply = $sysconfig_info->my->defaultreply;
        $welcomewords = $sysconfig_info->my->welcomewords;

        $weixintype = $sysconfig_info->my->weixintype;
        $weixinpay = $sysconfig_info->my->weixinpay;
        $mchid = $sysconfig_info->my->mchid;
        $mchkey = $sysconfig_info->my->mchkey;
        $sslcert = $sysconfig_info->my->sslcert;
        $sslkey = $sysconfig_info->my->sslkey;

        $wxsetting = array();
        $wxsetting['wxid'] = $wxid;
        $wxsetting['wxname'] = $wxname;
        $wxsetting['appid'] = $appid;
        $wxsetting['supplierid'] = $supplierid;
        $wxsetting['originalid'] = $originalid;
        $wxsetting['secret'] = $secret;
        $wxsetting['token'] = $token;
        $wxsetting['wxtype'] = $wxtype;
        $wxsetting['welcometitle'] = $welcometitle;
        $wxsetting['description'] = $description;
        $wxsetting['defaultreply'] = $defaultreply;
        $wxsetting['image'] = $image;
        $wxsetting['welcomewords'] = $welcomewords;

        $wxsetting['qrcodeimage'] = $sysconfig_info->my->qrcodeimage;
        $wxsetting['adbackgroundimage'] = $sysconfig_info->my->adbackgroundimage;

        $wxsetting['weixintype'] = $weixintype;
        $wxsetting['weixinpay'] = $weixinpay;
        $wxsetting['mchid'] = $mchid;
        $wxsetting['mchkey'] = $mchkey;
        $wxsetting['sslcert'] = $sslcert;
        $wxsetting['sslkey'] = $sslkey;

        $sysconfigs = XN_Query::create('Content')->tag('supplier_wxroles')
            ->filter('type', 'eic', 'supplier_wxroles')
            ->filter('my.wxid', '=', $wxid)
            ->filter('my.status', '=', 'Active')
            ->filter('my.deleted', '=', '0')
            ->end(-1)
            ->execute();
        if (count($sysconfigs) > 0)
        {
            foreach ($sysconfigs as $sysconfig_info)
            {
                $roleid = $sysconfig_info->id;
                $wxid = $sysconfig_info->my->wxid;
                $reply = $sysconfig_info->my->reply;
                $triggerkey = $sysconfig_info->my->triggerkey;
                $wxroletype = $sysconfig_info->my->wxroletype;
                $replytitle = $sysconfig_info->my->replytitle;
                $image = $sysconfig_info->my->image;
                $description = $sysconfig_info->my->description;
                $description = str_replace("'", "\\'", $description);

                $wxrolesettings = array();
                $wxrolesettings['roleid'] = $roleid;
                $wxrolesettings['wxid'] = $wxid;
                $wxrolesettings['triggerkey'] = $triggerkey;
                $wxrolesettings['wxroletype'] = $wxroletype;
                $wxrolesettings['replytitle'] = $replytitle;
                $wxrolesettings['description'] = $description;
                $wxrolesettings['image'] = $image;
                $wxrolesettings['reply'] = str_replace('\'', '\\\'', $reply);
                $wxsetting['wxrolesettings'][] = $wxrolesettings;
            }
        }
        XN_MemCache::put($wxsetting, "wxsettings_" . $appid);
    }

    XN_Application::$CURRENT_URL = $application;
}

function create_profilerank_config()
{
    $profileranks = XN_Query::create('MainContent')->tag('profilerank')
        ->filter('type', 'eic', 'profilerank')
        ->filter('my.deleted', '=', '0')
        ->order('my.minrank', XN_Order::DESC_NUMBER)
        ->end(-1)
        ->execute();
    if (count($profileranks) == 0)
    {
        require_once(dirname(__FILE__) . "/config.ranks.php");
        $profileranks = XN_Query::create('MainContent')->tag('profilerank')
            ->filter('type', 'eic', 'profilerank')
            ->filter('my.deleted', '=', '0')
            ->order('my.minrank', XN_Order::DESC_NUMBER)
            ->end(-1)
            ->execute();
    }
    $profilerankconfig = array();
    foreach ($profileranks as $profilerank_info)
    {
        $id = $profilerank_info->id;
        $rankname = $profilerank_info->my->rankname;
        $minrank = $profilerank_info->my->minrank;
        $csskey = $profilerank_info->my->csskey;
        $profilerankconfig[$id] = array(
            'rankname'     => $rankname,
            "minrank"      => $minrank,
            "csskey"         => $csskey
        );
    }
    XN_MemCache::put($profilerankconfig, "profilerank_" . XN_Application::$CURRENT_URL);
    return $profilerankconfig;
}


function getProfileRank($rank)
{
    try
    {
        $profilerankconfig = XN_MemCache::get("profilerank_" . XN_Application::$CURRENT_URL);
        foreach ($profilerankconfig as $profilerank_info)
        {
            $rankname = $profilerank_info['rankname'];
            $minrank = $profilerank_info['minrank'];
            if ($rank >= $minrank)
                return $rankname;
        }
        $profilerank_info = $profilerankconfig[0];
        return $profilerank_info['rankname'];
    }
    catch (XN_Exception $e)
    {
        create_profilerank_config();
        return "普通会员";
    }
}

function getProfileRankInfo($rank)
{
    if ($rank < 0) $rank = 0;
    try
    {
        $profilerankconfig = XN_MemCache::get("profilerank_" . XN_Application::$CURRENT_URL);
        foreach ($profilerankconfig as $profilerank_info)
        {
            $rankname = $profilerank_info['rankname'];
            $minrank = $profilerank_info['minrank'];
            $csskey = $profilerank_info['csskey'];

            if ($rank >= $minrank)
            {

                if ($minrank == 1)
                {
                    if ($rank < 100)
                    {
                        return array($csskey);
                    }
                    else
                    {
                        $rankinfo = array_fill(0, floor($rank / 100), $csskey);
                        if ($rank % 100 != 0)
                        {
                            $rankinfo[] = "half-".$csskey;
                        }
                    }
                }
                else
                {
                    if ($rank == 0)
                    {
                        $rankinfo[] = $csskey;
                    }
                    else
                    {
                        $rankinfo = array_fill(0, floor($rank / $minrank), $csskey);
                        if ($rank % $minrank != 0)
                        {
                            $rankinfo[] = "half-".$csskey;
                        }
                    }

                }
                return $rankinfo;
            }

        }
    }
    catch (XN_Exception $e)
    {
        create_profilerank_config();
        return array("xinhuiyuan");
    }
}



//更新商家用户
function update_supplier_profile_info($profile_info, $ranklimit = 0)
{
    try
    {
        $record = $profile_info['record'];
        $profileid = $profile_info['profileid'];
        $supplierid = $profile_info['supplierid'];
        $supplier_profile_info = XN_Content::load($record, "supplier_profile_" . $profileid, 4);

        $money = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->money);
        $accumulatedmoney = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->accumulatedmoney);
        $rank = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->rank);
		$accumulatedrank = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->accumulatedrank);
        $maxtakecash = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->maxtakecash);

        $wxopenid = $supplier_profile_info->my->wxopenid;

        $needupdate = false;
        if (round(floatval($money), 2) != round(floatval($profile_info['money']), 2))
        {
            $supplier_profile_info->my->money = number_format($profile_info['money'], 2, ".", "");
            $needupdate = true;
        }
        if (round(floatval($accumulatedmoney), 2) != round($profile_info['accumulatedmoney'], 2))
        {
            $supplier_profile_info->my->accumulatedmoney = number_format($profile_info['accumulatedmoney'], 2, ".", "");
            $needupdate = true;
        }
        if ($rank != $profile_info['rank'])
        {
            $supplier_profile_info->my->rank = $profile_info['rank'];
            $needupdate = true;
        }
        if ($accumulatedrank != $profile_info['accumulatedrank'])
        {
            $supplier_profile_info->my->accumulatedrank = $profile_info['accumulatedrank'];
            $needupdate = true;
        }
        if ($maxtakecash != $profile_info['maxtakecash'])
        {
            $supplier_profile_info->my->maxtakecash = $profile_info['maxtakecash'];
            $needupdate = true;
        }
        else
        {
            if (round(floatval($profile_info['maxtakecash']), 2) > round(floatval($profile_info['money']), 2))
            {
                $supplier_profile_info->my->maxtakecash = floatval($profile_info['money']);
                $needupdate = true;
            }
        }
        $ranklevel = $supplier_profile_info->my->ranklevel;
        if (!isset($ranklevel) || $ranklevel == "" || $ranklevel == "0")
        {
            if (intval($profile_info['rank']) >= $ranklimit && $ranklimit > 0)
            {
                $supplier_profile_info->my->ranklevel = '1';
                $needupdate = true; 
            }
            else if (intval($profile_info['rank']) > 0 && $ranklimit == 0)
            {
                $supplier_profile_info->my->ranklevel = '1';
                $needupdate = true; 
            }
        }
        if ($supplier_profile_info->my->mobile != $profile_info['mobile'])
        {
            $supplier_profile_info->my->mobile = $profile_info['mobile'];
            $needupdate = true;
        }
        if ($needupdate)
        {
            $supplier_profile_info->save("supplier_profile,supplier_profile_" . $profileid . ",supplier_profile_" . $wxopenid . ",supplier_profile_" . $supplierid);
            XN_MemCache::delete("supplier_profile_" . $supplierid . '_' . $profileid);
        }

    }
    catch (XN_Exception $e)
    {
        //throw new XN_Exception($e->getMessage ());
    }
}


//获得商家的用户信息
function get_supplier_profile_info($profileid = null, $supplierid = null)
{
    $memcache = false;
    if ($profileid == null)
    {
        $memcache = true;
        if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
        {
            $profileid = $_SESSION['profileid'];
        }
        elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
        {
            $profileid = $_SESSION['accessprofileid'];
        }
        else
        {
            return array();
        }
    }
    if ($supplierid == null)
    {
        $memcache = true;
        if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
        {
            $supplierid = $_SESSION['supplierid'];
        }
        else
        {
            return array();
        }
    }
    if ($memcache)
    {
        try
        {
            $profile_info = XN_MemCache::get("supplier_profile_" . $supplierid . '_' . $profileid);
            return $profile_info;
        }
        catch (XN_Exception $e)
        {
        }
    }
    $profile_info = array();
    $supplier_profiles = XN_Query::create('MainContent')->tag("supplier_profile_" . $profileid)
        ->filter('type', 'eic', 'supplier_profile')
        ->filter('my.profileid', '=', $profileid)
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.deleted', '=', '0')
        ->end(1)
        ->execute();
    if (count($supplier_profiles) > 0)
    {
        $supplier_profile_info = $supplier_profiles[0];
        $wxopenid = $supplier_profile_info->my->wxopenid;
        $money = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->money);
        $accumulatedmoney = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->accumulatedmoney);
        $rank = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->rank);
		$accumulatedrank = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->accumulatedrank);
        $maxtakecash = preg_replace("/[^0-9\.]/i", "", $supplier_profile_info->my->maxtakecash);

        $ranklevel = $supplier_profile_info->my->ranklevel;

        $onelevelsourcer = $supplier_profile_info->my->onelevelsourcer;
        $twolevelsourcer = $supplier_profile_info->my->twolevelsourcer;
        $threelevelsourcer = $supplier_profile_info->my->threelevelsourcer;
        $hassourcer = $supplier_profile_info->my->hassourcer;
		$authenticationprofile = $supplier_profile_info->my->authenticationprofile;

        $profile = XN_Profile::load($profileid, "id", "profile_" . $profileid);
        $headimgurl = $profile->link;
        $givenname = strip_tags($profile->givenname);
        if ($headimgurl == "")
        {
            $headimgurl = 'images/user.jpg';
        }

        $profile_info['profileid'] = $profileid;
        $profile_info['supplierid'] = $supplierid;
        $profile_info['wxopenid'] = $wxopenid;
        $profile_info['record'] = $supplier_profile_info->id;
        $profile_info['money'] = floatval($money);
        $profile_info['accumulatedmoney'] = floatval($accumulatedmoney);
        $profile_info['maxtakecash'] = floatval($maxtakecash);
        $profile_info['rank'] = intval($rank);
		$profile_info['accumulatedrank'] = intval($accumulatedrank);
        $profile_info['mobile'] = $supplier_profile_info->my->mobile;
        $profile_info['identitycard'] = $profile->identitycard;
        $profile_info['birthdate'] = $profile->birthdate;
        $profile_info['gender'] = $profile->gender;
        $profile_info['mobile'] = $profile->mobile;
        $profile_info['headimgurl'] = $headimgurl;
        $profile_info['givenname'] = $givenname;
        $profile_info['invitationcode'] = $profile->invitationcode;
        $profile_info['sourcer'] = $profile->sourcer;
        $profile_info['province'] = $profile->province;
        $profile_info['city'] = $profile->city;
        $profile_info['ranklevel'] = $ranklevel;
        $profile_info['rankname'] = getProfileRank($accumulatedrank);
        $profile_info['logintime'] = strtotime("now");

        $profile_info['rankinfo'] = getProfileRankInfo($accumulatedrank);

        $profile_info['onelevelsourcer'] = $onelevelsourcer;
        $profile_info['twolevelsourcer'] = $twolevelsourcer;
        $profile_info['threelevelsourcer'] = $threelevelsourcer;
        $profile_info['hassourcer'] = $hassourcer;
		$profile_info['authenticationprofile'] = $authenticationprofile;
		$profile_info['repurchasestatus'] = $supplier_profile_info->my->repurchasestatus;
		$profile_info['purchasedate'] = $supplier_profile_info->my->purchasedate;
		$profile_info['orderid'] = $supplier_profile_info->my->orderid; 
		

        XN_MemCache::put($profile_info, "supplier_profile_" . $supplierid . '_' . $profileid);
    }
    return $profile_info;
}
//获得会员昵称
function get_profile_givenname($profileid)
{
	$profile_info = get_profile_info($profileid);
	if (isset($profile_info['givenname']) && $profile_info['givenname'] != "")
	{
		return $profile_info['givenname'];
	}
	return ""; 
} 
 
//获得会员信息
function get_profile_info($profileid = null)
{
	global $cache_profileinfos; 
	if (!isset($cache_profileinfos))
	{
		$cache_profileinfos = array();
	}  
	if (isset($cache_profileinfos[$profileid]) && $cache_profileinfos[$profileid] != "")
	{
		return $cache_profileinfos[$profileid];
	}
    $memcache = false;
    if ($profileid == null)
    {
        $memcache = true;
        if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
        {
            $profileid = $_SESSION['profileid'];
        }
        elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
        {
            $profileid = $_SESSION['accessprofileid'];
        }
        else
        {
            return array('profileid' => 'anonymous');
        }
    }
    if ($memcache)
    {
        try
        {
            $profile_info = XN_MemCache::get('tezan_profile_' . $profileid);
			$cache_profileinfos[$profileid] = $profile_info;
            return $profile_info;
        }
        catch (XN_Exception $e)
        {
        }
    }
    $profile_info = array();
    $profile = XN_Profile::load($profileid, "id", "profile_" . $profileid);
    $headimgurl = $profile->link;
    $givenname = strip_tags($profile->givenname);
    if ($headimgurl == "")
    {
        $headimgurl = 'images/user.jpg';
    }

    $wxopenids = XN_Query::create('MainContent')->tag("wxopenids_" . $profileid)
        ->filter('type', 'eic', 'wxopenids')
        ->filter('my.profileid', '=', $profileid)
        ->filter('my.appid', '=', 'wx7962fafc7ec5b6c6')
        ->end(1)
        ->execute();
    if (count($wxopenids) == 0)
    {
        //throw new XN_Exception("没有wxopenid");
        //return false;
		$wxopenid = "";
    }
    else
    {
        $wxopenid_info = $wxopenids[0];
        $wxopenid = $wxopenid_info->my->wxopenid;
    }
    $profile_info['profileid'] = $profileid;
    $profile_info['wxopenid'] = $wxopenid;
    $profile_info['money'] = 0;
    $profile_info['accumulatedmoney'] = 0;
    $profile_info['sharefund'] = 0;
    $profile_info['rank'] = 0;
    $profile_info['mobile'] = $profile->mobile;
    $profile_info['identitycard'] = $profile->identitycard;
    $profile_info['mobile'] = $profile->mobile;
    $profile_info['birthdate'] = $profile->birthdate;
    $profile_info['gender'] = $profile->gender;
    $profile_info['headimgurl'] = $headimgurl;
    $profile_info['givenname'] = $givenname;
    $profile_info['invitationcode'] = $profile->invitationcode;
    $profile_info['sourcer'] = $profile->sourcer;
    $profile_info['province'] = $profile->province;
    $profile_info['city'] = $profile->city;
    $profile_info['rankname'] = getProfileRank(0);
    $profile_info['logintime'] = strtotime("now");
    $profile_info['rankinfo'] = getProfileRankInfo(0);

    XN_MemCache::put($profile_info, "tezan_profile_" . $profileid);

	$cache_profileinfos[$profileid] = $profile_info;
    return $profile_info;
}

//获得商家信息
function get_businesse_info($businesseid = null)
{
    $memcache = false;
    if ($businesseid == null)
    {
        $memcache = true;
        if (isset($_SESSION['businesseid']) && $_SESSION['businesseid'] != '')
        {
            $businesseid = $_SESSION['businesseid'];
        }
        else
        {
            return array();
        }
    }
    if ($memcache)
    {
        try
        {
            $businesseinfo = XN_MemCache::get("businesseid_" . $businesseid);
            return $businesseinfo;
        }
        catch (XN_Exception $e)
        {
        }
    }

    $businesseinfo = array();
    $businesse_info = XN_Content::load($businesseid, "local_businesses", 4);
    $productprefix = $businesse_info->my->productprefix;
    $supplierid = $businesse_info->my->supplierid;
    if ($productprefix == "dishes")
    {
        $cn_productprefix = "菜品";
    }
    else if ($productprefix == "treasure")
    {
        $cn_productprefix = "宝贝";
    }
    else if ($productprefix == "product")
    {
        $cn_productprefix = "商品";
    }
    else
    {
        $cn_productprefix = "商品";
    }
    $businesseinfo['domain'] = $businesse_info->application;
    $businesseinfo['businessename'] = $businesse_info->my->name;
    $businesseinfo['supplierid'] = $supplierid;
    $businesseinfo['address'] = $businesse_info->my->address;
    $businesseinfo['mobile'] = $businesse_info->my->mobile;
    $businesseinfo['thumb'] = $businesse_info->my->thumb;
    $businesseinfo['productprefix'] = $cn_productprefix;
    $businesseinfo['operatemode'] = $businesse_info->my->operatemode;
    $businesseinfo['categoryslevel'] = $businesse_info->my->categoryslevel;
    $businesseinfo['latitude'] = $businesse_info->my->latitude;
    $businesseinfo['longitude'] = $businesse_info->my->longitude;
    $businesseinfo['description'] = $businesse_info->my->description;
    $businesseinfo['businesseid'] = $businesseid;

    $supplier_settings = XN_Query::create('MainContent')->tag("supplier_settings")
        ->filter('type', 'eic', 'supplier_settings')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.deleted', '=', '0')
        ->end(1)
        ->execute();
    if (count($supplier_settings) > 0)
    {
        $supplier_setting_info = $supplier_settings[0];
        $businesseinfo['popularizemode'] = $supplier_setting_info->my->popularizemode;
        $businesseinfo['distributionrate'] = $supplier_setting_info->my->distributionrate;
        $businesseinfo['popularizefund'] = $supplier_setting_info->my->popularizefund;
        $businesseinfo['distributionmode'] = $supplier_setting_info->my->distributionmode;
        $businesseinfo['sharefund'] = $supplier_setting_info->my->sharefund;
        $businesseinfo['ranklimit'] = $supplier_setting_info->my->ranklimit;
        $businesseinfo['moneypaymentrate'] = $supplier_setting_info->my->moneypaymentrate;

        $businesseinfo['allowtakecash'] = $supplier_setting_info->my->allowtakecash;
        $businesseinfo['popularizetriggermode'] = $supplier_setting_info->my->popularizetriggermode;
        $businesseinfo['autoredenvelope'] = $supplier_setting_info->my->autoredenvelope;
        $businesseinfo['redenvelopefund'] = $supplier_setting_info->my->redenvelopefund;
        $businesseinfo['redenvelopetriggermode'] = $supplier_setting_info->my->redenvelopetriggermode;
		$businesseinfo['newprofilebenefit']  = $supplier_setting_info->my->newprofilebenefit;
		$businesseinfo['totalpricefreeshipping']  = $supplier_setting_info->my->totalpricefreeshipping;
		$businesseinfo['popularizeqrcode']  = $supplier_setting_info->my->popularizeqrcode;
    }
    else
    {
        $businesseinfo['popularizemode'] = '0'; // 1 =>  1级推广', 2 =>  '2级推广',3 =>  '3级推广',  0 => '无推广'
        $businesseinfo['distributionrate'] = '111'; // 平均分配
        $businesseinfo['popularizefund'] = '0'; // 推广金
        $businesseinfo['distributionmode'] = '0'; // 1 =>  1级分销', 2 =>  '2级分销',3 =>  '3级分销',  0 => '无分销'
        $businesseinfo['sharefund'] = '0'; // 分享金
        $businesseinfo['ranklimit'] = '0'; // 资格限制
        $businesseinfo['moneypaymentrate'] = '100'; // 资格限制

        $businesseinfo['allowtakecash'] = '0'; // 0 =>  不允许, 2 =>  允许
        $businesseinfo['popularizetriggermode'] = '0'; // 推广金 发放方式 0 =>  完善个人资料, 2 =>  关注
        $businesseinfo['autoredenvelope'] = '0'; // 0 =>  关闭, 2 =>  打开
        $businesseinfo['redenvelopefund'] = '1'; // 红包金额
        $businesseinfo['redenvelopetriggermode'] = '0'; // 红包发放方式 0 =>  完善个人资料, 2 =>  关注
		$businesseinfo['newprofilebenefit']  = '0';
		$businesseinfo['totalpricefreeshipping']  = '0';
		$businesseinfo['popularizeqrcode']  = '0';
    }


    $local_shopsets = XN_Query::create('MainContent')->tag("local_shopset")
        ->filter('type', 'eic', 'local_shopset')
        ->filter('my.businesseid', '=', $businesseid)
        ->filter('my.deleted', '=', '0')
        ->end(1)
        ->execute();
    if (count($local_shopsets) > 0)
    {
        $local_shopset_info = $local_shopsets[0];
        $announcement = $local_shopset_info->my->announcement;
        $announcement = str_replace(array('"', '\'', "\n"), array('', '', ''), $announcement);
        $sharetitle = $local_shopset_info->my->sharetitle;
        $sharetitle = str_replace(array('"', '\'', "\n"), array('', '', ''), $sharetitle);
        $businesseinfo['share_title'] = $sharetitle;
        $businesseinfo['share_description'] = $announcement;
        $businesseinfo['shop_status'] = $local_shopset_info->my->shop_status;
        $businesseinfo['logo'] = $local_shopset_info->my->logo;
        $businesseinfo['sendarea'] = $local_shopset_info->my->sendarea;
        $businesseinfo['sendprice'] = $local_shopset_info->my->sendprice;
        $businesseinfo['auto_print'] = $local_shopset_info->my->auto_print;
        $businesseinfo['print_number'] = $local_shopset_info->my->print_number;
        $businesseinfo['maxordernumber'] = $local_shopset_info->my->maxordernumber;
        $businesseinfo['shop_hours'] = $local_shopset_info->my->shop_hours;
    }
    else
    {
        $businessename = $businesse_info->my->name;
        $businesseinfo['share_title'] = $businessename . '欢迎你!';
        $businesseinfo['share_description'] = $businessename . '欢迎你!';
        $businesseinfo['shop_status'] = '2'; //  1 => '营业中',  2 => '打烊',
        $businesseinfo['logo'] = '';
        $businesseinfo['sendarea'] = '1'; //配送距离
        $businesseinfo['sendprice'] = '0'; //配送金额
        $businesseinfo['auto_print'] = '0';  //自动打印
        $businesseinfo['print_number'] = '1'; //打印数量
        $businesseinfo['maxordernumber'] = '500';  //每日订单数
        $businesseinfo['shop_hours'] = '8:00 - 20:00';
    }
    $wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
        ->filter('type', 'eic', 'supplier_wxsettings')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->end(1)
        ->execute();
    if (count($wxsettings) > 0)
    {
        $sysconfig_info = $wxsettings[0];
        $businesseinfo['wxid'] = $sysconfig_info->id;
        $businesseinfo['appid'] = $sysconfig_info->my->appid;
    }
    else
    {
        $businesseinfo['wxid'] = '';
        $businesseinfo['appid'] = '';
    }

    XN_MemCache::put($businesseinfo, "businesseid_" . $businesseid);
    return $businesseinfo;
}

//获得商家信息
function get_supplier_info($supplierid = null)
{
    global $supplierinfo;
    if (isset($supplierinfo) && count($supplierinfo) > 0)
    {
        return $supplierinfo;
    }
    $memcache = false;
    if ($supplierid == null)
    {
        $memcache = true;
        if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
        {
            $supplierid = $_SESSION['supplierid'];
        }
        else
        {
            return array();
        }
    }
    if ($memcache)
    {
        try
        {
            $supplierinfo = XN_MemCache::get("supplier_" . $supplierid);
            return $supplierinfo;
        }
        catch (XN_Exception $e)
        {
        }
    }

    $supplierinfo = array();
    $supplier_info = XN_Content::load($supplierid, "suppliers_" . $supplierid);
	$supplierinfo['supplierid'] = $supplierid;
    $supplierinfo['suppliername'] = $supplier_info->my->suppliers_name;
	$supplierinfo['suppliertype'] = $supplier_info->my->suppliertype;
    $supplierinfo['address'] = $supplier_info->my->companyaddress; 
    $supplierinfo['mobile'] = $supplier_info->my->mobile;
    $supplierinfo['latitude'] = $supplier_info->my->latitude;
    $supplierinfo['longitude'] = $supplier_info->my->longitude;

    $supplier_settings = XN_Query::create('MainContent')->tag("supplier_settings")
        ->filter('type', 'eic', 'supplier_settings')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.deleted', '=', '0')
        ->end(1)
        ->execute();
    if (count($supplier_settings) > 0)
    {
        $supplier_setting_info = $supplier_settings[0];
        $supplierinfo['popularizemode'] = $supplier_setting_info->my->popularizemode;
        $supplierinfo['distributionrate'] = $supplier_setting_info->my->distributionrate;
        $supplierinfo['popularizefund'] = $supplier_setting_info->my->popularizefund;
        $supplierinfo['distributionmode'] = $supplier_setting_info->my->distributionmode;
        $supplierinfo['sharefund'] = $supplier_setting_info->my->sharefund;
        $supplierinfo['ranklimit'] = $supplier_setting_info->my->ranklimit;
        $supplierinfo['moneypaymentrate'] = $supplier_setting_info->my->moneypaymentrate;

        $supplierinfo['allowtakecash'] = $supplier_setting_info->my->allowtakecash;
        $supplierinfo['popularizetriggermode'] = $supplier_setting_info->my->popularizetriggermode;
        $supplierinfo['autoredenvelope'] = $supplier_setting_info->my->autoredenvelope;
        $supplierinfo['redenvelopefund'] = $supplier_setting_info->my->redenvelopefund;
        $supplierinfo['redenvelopetriggermode'] = $supplier_setting_info->my->redenvelopetriggermode;
        $takecashitem = $supplier_setting_info->my->takecashitem;
        $supplierinfo['takecashitem']  = (array)$takecashitem;
		$supplierinfo['newprofilebenefit']  = $supplier_setting_info->my->newprofilebenefit;
		$distributionobject =  $supplier_setting_info->my->distributionobject; 
		$vipauthentication =  $supplier_setting_info->my->vipauthentication; 
		$repurchase  =  $supplier_setting_info->my->repurchase; 
		$repurchasetimelimit  =  $supplier_setting_info->my->repurchasetimelimit; 
		$authenticationpayment =  $supplier_setting_info->my->authenticationpayment; 
		
		if (!isset($distributionobject) || $distributionobject == "")
		{
			$distributionobject = '0';
		} 
		$supplierinfo['distributionobject']  = $distributionobject; 
		
		if (!isset($vipauthentication) || $vipauthentication == "")
		{
			$vipauthentication = '0';
		} 
		if (!isset($authenticationpayment) || $authenticationpayment == "")
		{
			$authenticationpayment = '0';
		} 
		if (!isset($repurchase) || $repurchase == "")
		{
			$repurchase = '1';
		} 
		if (!isset($repurchasetimelimit) || $repurchasetimelimit == "")
		{
			$repurchasetimelimit = '2';
		} 
		$supplierinfo['vipauthentication']  = $vipauthentication;
		$supplierinfo['repurchase']  = $repurchase;
		$supplierinfo['repurchasetimelimit']  = $repurchasetimelimit;
		$supplierinfo['authenticationpayment']  = $authenticationpayment;

    }
    else
    {
        $supplierinfo['popularizemode'] = '0'; // 1 =>  1级推广', 2 =>  '2级推广',3 =>  '3级推广',  0 => '无推广'
        $supplierinfo['distributionrate'] = '111'; // 平均分配
        $supplierinfo['popularizefund'] = '0'; // 推广金
        $supplierinfo['distributionmode'] = '0'; // 1 =>  1级分销', 2 =>  '2级分销',3 =>  '3级分销',  0 => '无分销'
        $supplierinfo['sharefund'] = '0'; // 分享金
        $supplierinfo['ranklimit'] = '0'; // 资格限制
        $supplierinfo['moneypaymentrate'] = '100'; // 资格限制

        $supplierinfo['allowtakecash'] = '0'; // 0 =>  不允许, 2 =>  允许
        $supplierinfo['popularizetriggermode'] = '0'; // 推广金 发放方式 0 =>  完善个人资料, 2 =>  关注
        $supplierinfo['autoredenvelope'] = '0'; // 0 =>  关闭, 2 =>  打开
        $supplierinfo['redenvelopefund'] = '1'; // 红包金额
        $supplierinfo['redenvelopetriggermode'] = '0'; // 红包发放方式 0 =>  完善个人资料, 2 =>  关注
        $supplierinfo['takecashitem']  = array(); //提现基金
		$supplierinfo['newprofilebenefit']  = '0'; //新会员福利 
		$supplierinfo['distributionobject']  = '0'; //分销对象 
		$supplierinfo['vipauthentication']  = '0';
		$supplierinfo['repurchase']  = '1';
		$supplierinfo['repurchasetimelimit']  = '2';
		$supplierinfo['authenticationpayment']  = '0';
		
    }

    $wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
        ->filter('type', 'eic', 'supplier_wxsettings')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->end(1)
        ->execute();
    if (count($wxsettings) > 0)
    {
        $sysconfig_info = $wxsettings[0];
        $supplierinfo['wxid'] = $sysconfig_info->id;
        $supplierinfo['appid'] = $sysconfig_info->my->appid;
    }
    else
    {
        $supplierinfo['wxid'] = '';
        $supplierinfo['appid'] = '';
    }

    $mall_sharedatas = XN_Query::create('MainContent')->tag('mall_sharedatas')
        ->filter('type', 'eic', 'mall_sharedatas')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.deleted', '=', '0')
        ->filter('my.enablestatus', '=', '0')
        ->order('published', XN_Order::DESC)
        ->end(1)
        ->execute();
    if (count($mall_sharedatas) > 0)
    {
        $mall_sharedata_info = $mall_sharedatas[0];
        $supplierinfo['share_title'] = $mall_sharedata_info->my->share_title;
        $supplierinfo['share_description'] = $mall_sharedata_info->my->share_description;
        $sharelogo = $mall_sharedata_info->my->sharelogo;
        if (isset($sharelogo) && $sharelogo != "")
        {
            $supplierinfo['share_logo'] = $sharelogo;
        }
        else
        {
            $supplierinfo['share_logo'] = "";
        }

    }
    else
    {
        $suppliername = $supplier_info->my->suppliers_name;
        $supplierinfo['share_title'] = $suppliername . '欢迎你!';
        $supplierinfo['share_description'] = $suppliername . '欢迎你!';
        $supplierinfo['share_logo'] = "";
    }

    $mall_settings = XN_Query::create('MainContent')->tag('mall_settings')
        ->filter('type', 'eic', 'mall_settings')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.deleted', '=', '0')
        ->end(1)
        ->execute();
    if (count($mall_settings) > 0)
    {
        $mall_setting_info = $mall_settings[0];
        $supplierinfo['suppliername'] = $mall_setting_info->my->mallname;
        $supplierinfo['officialwebsite'] = $mall_setting_info->my->officialwebsite;
        $supplierinfo['categoryslevel'] = $mall_setting_info->my->categoryslevel;
        $supplierinfo['description'] = $mall_setting_info->my->description;
        $indexcolumns = $mall_setting_info->my->indexcolumns;
        $themecolor = $mall_setting_info->my->themecolor;
        $textcolor = $mall_setting_info->my->textcolor;
        $buttoncolor = $mall_setting_info->my->buttoncolor;
        $productpricecolor = $mall_setting_info->my->productpricecolor;
        $productbackgroundcolor = $mall_setting_info->my->productbackgroundcolor;
		$navbarcolor = $mall_setting_info->my->navbarcolor;
		$selectnavbarcolor = $mall_setting_info->my->selectnavbarcolor;


        $showcategory = $mall_setting_info->my->showcategory;
		$showpromotioncenter = $mall_setting_info->my->showpromotioncenter;
        $allowreturngoods = $mall_setting_info->my->allowreturngoods;
        $allowpayment = $mall_setting_info->my->allowpayment;
        $commissionmode = $mall_setting_info->my->commissionmode;
        $saletelphone = $mall_setting_info->my->saletelphone;
        $autodeliverrechargeablecard = $mall_setting_info->my->autodeliverrechargeablecard;
 	    $showvendor = $mall_setting_info->my->showvendor;
		
		
		 
		$presalesconsultation = $mall_setting_info->my->presalesconsultation;
		$productappraises = $mall_setting_info->my->productappraises; 
		$categoryscolumns = $mall_setting_info->my->categoryscolumns;
		
		$mainpageshowobject = $mall_setting_info->my->mainpageshowobject; // 首页展示对象
		$forceactivation = $mall_setting_info->my->forceactivation; // 强制激活 博爱，鼎亿，红星，需要
		$sourcerrequired = $mall_setting_info->my->sourcerrequired; // 推荐人必填 博爱，鼎亿，需要
		$allowshare = $mall_setting_info->my->allowshare; // 允许分享*/
		$showuniquesale = $mall_setting_info->my->showuniquesale; //  资格商品展示*/
		
		$showfubisi = $mall_setting_info->my->showfubisi;  
		$showtradeorderrecord = $mall_setting_info->my->showtradeorderrecord;  
		
		$billwatershowmode = $mall_setting_info->my->billwatershowmode;  
		
		$totalpricefreeshipping = $mall_setting_info->my->totalpricefreeshipping;  
		$totalquantityfreeshipping = $mall_setting_info->my->totalquantityfreeshipping; 
		
		$allowqrcode = $mall_setting_info->my->allowqrcode;  
		$allowphysicalstore = $mall_setting_info->my->allowphysicalstore; 
		$defaultphysicalstorerate = $mall_setting_info->my->defaultphysicalstorerate; 
		
		$productqrcode = $mall_setting_info->my->productqrcode; 
		$productdisplaymode = $mall_setting_info->my->productdisplaymode; 
		
		$mainpagetitle = $mall_setting_info->my->mainpagetitle; 
		$mainpageslider = $mall_setting_info->my->mainpageslider; 
		$mainpageproductshowmode = $mall_setting_info->my->mainpageproductshowmode; 
		
		$reception = $mall_setting_info->my->reception;  
		$mylogistic = $mall_setting_info->my->mylogistic; 
		$mylogisticname = $mall_setting_info->my->mylogisticname; 
		
		$popularizeqrcode = $mall_setting_info->my->popularizeqrcode; 
		
		$topadlogo = $mall_setting_info->my->topadlogo; 
		$bottomadlogo = $mall_setting_info->my->bottomadlogo; 
		
		$rankcost = $mall_setting_info->my->rankcost; 
		$rankcostrate = $mall_setting_info->my->rankcostrate; 

		$showheader = $mall_setting_info->my->showheader; 
		
		$showheader = $mall_setting_info->my->showheader; 
		$showleftmenu = $mall_setting_info->my->showleftmenu; 
		$footerstyle = $mall_setting_info->my->footerstyle; 
		$footericonstyle = $mall_setting_info->my->footericonstyle; 
		
		
        if (!isset($indexcolumns) || $indexcolumns == "")
        {
            $indexcolumns = '3';
        }
        if (!isset($themecolor) || $themecolor == "")
        {
            $themecolor = '#fe4401';
        }
        if (!isset($textcolor) || $textcolor == "")
        {
            $textcolor = '#fff';
        }
        if (!isset($buttoncolor) || $buttoncolor == "")
        {
            $buttoncolor = '#C30';
        }
        if (!isset($productpricecolor) || $productpricecolor == "")
        {
            $productpricecolor = '#dfbd84';
        }
        if (!isset($productbackgroundcolor) || $productbackgroundcolor == "")
        {
            $productbackgroundcolor = '#fe4401';
        }
        if (!isset($navbarcolor) || $navbarcolor == "")
        {
            $navbarcolor = '#555';
        }
        if (!isset($selectnavbarcolor) || $selectnavbarcolor == "")
        {
            $selectnavbarcolor = '#C30';
        } 

        if (!isset($showcategory) || $showcategory == "")
        {
            $showcategory = '0';
        }
        if (!isset($showpromotioncenter) || $showpromotioncenter == "")
        {
            $showpromotioncenter = '0';
        }
		
        if (!isset($allowreturngoods) || $allowreturngoods == "")
        {
            $allowreturngoods = '0';
        }
        if (!isset($allowpayment) || $allowpayment == "")
        {
            $allowpayment = '0';
        }

        if (!isset($commissionmode) || $commissionmode == "")
        {
            $commissionmode = '1';
        }
        if (!isset($autodeliverrechargeablecard) || $autodeliverrechargeablecard == "")
        {
            $autodeliverrechargeablecard = '0';
        }
        if (!isset($showvendor) || $showvendor == "")
        {
            $showvendor = '0';
        }
        if (!isset($showfubisi) || $showfubisi == "")
        {
             $showfubisi = '0';
        } 
        if (!isset($showtradeorderrecord) || $showtradeorderrecord == "")
        {
            $showtradeorderrecord = '0';
        } 
        if (!isset($billwatershowmode) || $billwatershowmode == "")
        {
            $billwatershowmode = '0';
        } 
        
        
        if (!isset($mainpageshowobject) || $mainpageshowobject == "")
        {
            $mainpageshowobject = '0';
        }
        if (!isset($forceactivation) || $forceactivation == "")
        {
            $forceactivation = '1';
        }
        if (!isset($sourcerrequired) || $sourcerrequired == "")
        {
            $sourcerrequired = '1';
        }
        if (!isset($allowshare) || $allowshare == "")
        {
            $allowshare = '0';
        }
        if (!isset($showuniquesale) || $showuniquesale == "")
        {
            $showuniquesale = '0';
        }
        if (!isset($totalpricefreeshipping) || $totalpricefreeshipping == "")
        {
            $totalpricefreeshipping = '0';
        }
        if (!isset($totalquantityfreeshipping) || $totalquantityfreeshipping == "")
        {
            $totalquantityfreeshipping = '0';
        }
        if (!isset($allowqrcode) || $allowqrcode == "")
        {
            $allowqrcode = '0';
        }
        if (!isset($allowphysicalstore) || $allowphysicalstore == "")
        {
            $allowphysicalstore = '1';
        }
        if (!isset($defaultphysicalstorerate) || $defaultphysicalstorerate == "")
        {
            $defaultphysicalstorerate = '0';
        } 
        if (!isset($productqrcode) || $productqrcode == "")
        {
            $productqrcode = '1';
        } 
        if (!isset($productdisplaymode) || $productdisplaymode == "")
        {
            $productdisplaymode = '0';
        } 
        if (!isset($reception) || $reception == "")
        {
            $reception = '0';
        } 
        if (!isset($mylogistic) || $mylogistic == "")
        {
            $mylogistic = '0';
        } 
       
        if (!isset($presalesconsultation) || $presalesconsultation == "")
        {
            $presalesconsultation = '0';
        } 
        if (!isset($productappraises) || $productappraises == "")
        {
            $productappraises = '0';
        } 
        if (!isset($categoryscolumns) || $categoryscolumns == "")
        {
            $categoryscolumns = '2';
        }  
        if (!isset($popularizeqrcode) || $popularizeqrcode == "")
        {
            $popularizeqrcode = '0';
        }  
		
        if (!isset($mainpagetitle) || $mainpagetitle == "")
        {
            $mainpagetitle = '0';
        } 
        if (!isset($mainpageslider) || $mainpageslider == "")
        {
            $mainpageslider = '1';
        } 
        if (!isset($mainpageproductshowmode) || $mainpageproductshowmode == "")
        {
            $mainpageproductshowmode = '0';
        } 
        if (!isset($mainpageproductshowmode) || $mainpageproductshowmode == "")
        {
            $mainpageproductshowmode = '0';
        } 
        if (!isset($topadlogo))
        {
            $topadlogo = '';
        } 
        if (!isset($bottomadlogo))
        {
            $bottomadlogo = '';
        }  
		
        if (!isset($rankcost) || $rankcost == "")
        {
            $rankcost = '1';
        } 
        if (!isset($rankcostrate) || $rankcostrate == "")
        {
            $rankcostrate = '10';
        } 
        if (!isset($showheader) || $showheader == "")
        {
            $showheader = '0';
        }  
        if (!isset($showleftmenu) || $showleftmenu == "")
        {
            $showleftmenu = '0';
        }  
        if (!isset($footerstyle) || $footerstyle == "")
        {
            $footerstyle = '0';
        }  
        if (!isset($footericonstyle) || $footericonstyle == "")
        {
            $footericonstyle = '0';
        }   
		 

        $supplierinfo['indexcolumns'] = $indexcolumns;
        $supplierinfo['themecolor'] = $themecolor;
        $supplierinfo['textcolor'] = $textcolor;
        $supplierinfo['buttoncolor'] = $buttoncolor;
        $supplierinfo['productpricecolor'] = $productpricecolor;
        $supplierinfo['productbackgroundcolor'] = $productbackgroundcolor;
		$supplierinfo['navbarcolor'] = $navbarcolor;
		$supplierinfo['selectnavbarcolor'] = $selectnavbarcolor;
        $supplierinfo['showcategory'] = $showcategory;
		$supplierinfo['showpromotioncenter'] = $showpromotioncenter; 
        $supplierinfo['allowreturngoods'] = $allowreturngoods;
        $supplierinfo['allowpayment'] = $allowpayment;
        $supplierinfo['commissionmode'] = $commissionmode;
        $supplierinfo['saletelphone'] = $saletelphone;
        $supplierinfo['autodeliverrechargeablecard'] = $autodeliverrechargeablecard;
		$supplierinfo['showvendor'] = $showvendor;
		$supplierinfo['mainpageshowobject'] = $mainpageshowobject;
		$supplierinfo['forceactivation'] = $forceactivation;
		$supplierinfo['sourcerrequired'] = $sourcerrequired;
		$supplierinfo['allowshare'] = $allowshare;
		$supplierinfo['showuniquesale'] = $showuniquesale;
		$supplierinfo['totalpricefreeshipping']  = $totalpricefreeshipping;
		$supplierinfo['totalquantityfreeshipping']  = $totalquantityfreeshipping;  
		$supplierinfo['showfubisi']  = $showfubisi; 
		$supplierinfo['showtradeorderrecord']  = $showtradeorderrecord;  
		$supplierinfo['billwatershowmode']  = $billwatershowmode;		
		$supplierinfo['allowqrcode']  = $allowqrcode; 
		$supplierinfo['allowphysicalstore']  = $allowphysicalstore; 
		$supplierinfo['defaultphysicalstorerate']  = $defaultphysicalstorerate; 
		$supplierinfo['productqrcode']  = $productqrcode; 
		$supplierinfo['productdisplaymode']  = $productdisplaymode; 
		$supplierinfo['reception']  = $reception; 
		$supplierinfo['mylogistic']  = $mylogistic; 
		$supplierinfo['mylogisticname']  = $mylogisticname;   
		$supplierinfo['presalesconsultation']  = $presalesconsultation;
		$supplierinfo['productappraises']  = $productappraises; 
		$supplierinfo['categoryscolumns']  = $categoryscolumns; 
		$supplierinfo['popularizeqrcode']  = $popularizeqrcode; 
        $supplierinfo['mainpagetitle'] = $mainpagetitle;
        $supplierinfo['mainpageslider'] = $mainpageslider;
        $supplierinfo['mainpageproductshowmode'] = $mainpageproductshowmode;
        $supplierinfo['topadlogo'] = $topadlogo;
        $supplierinfo['bottomadlogo'] = $bottomadlogo; 
		$supplierinfo['rankcost']  = $rankcost;
		$supplierinfo['rankcostrate']  = $rankcostrate;
		$supplierinfo['showheader']  = $showheader;
		$supplierinfo['showleftmenu']  = $showleftmenu;
		$supplierinfo['footerstyle']  = $footerstyle;
		$supplierinfo['footericonstyle']  = $footericonstyle;

    }
    else
    {
        $supplierinfo['officialwebsite'] = "";
        $supplierinfo['categoryslevel'] = "1";
        $supplierinfo['indexcolumns'] = "3";
        $supplierinfo['themecolor'] = "#fe4401";
        $supplierinfo['textcolor'] = "#fff";
        $supplierinfo['buttoncolor'] = "#C30";
        $supplierinfo['productpricecolor'] = "#dfbd84";
        $supplierinfo['productbackgroundcolor'] = "#fe4401";
		$supplierinfo['navbarcolor'] = "#555";
		$supplierinfo['selectnavbarcolor'] = "#C30"; 
        $supplierinfo['showcategory'] =  "0";
		$supplierinfo['showpromotioncenter'] = "0"; 
        $supplierinfo['allowreturngoods'] =  "0";
        $supplierinfo['allowpayment'] =  "0";
        $supplierinfo['commissionmode'] = "1";
        $supplierinfo['saletelphone'] = "";
        $supplierinfo['autodeliverrechargeablecard'] = '0';
		$supplierinfo['showvendor'] = '0';
		$supplierinfo['mainpageshowobject'] = '0';
		$supplierinfo['forceactivation'] = '1';
		$supplierinfo['sourcerrequired'] = '1';
		$supplierinfo['allowshare'] = '0';
		$supplierinfo['showuniquesale'] = '0';
		$supplierinfo['totalpricefreeshipping']  = '0';
		$supplierinfo['totalquantityfreeshipping']  = '0'; 
		$supplierinfo['showfubisi']  = '0'; 
		$supplierinfo['showtradeorderrecord']  = '0'; 
		$supplierinfo['billwatershowmode']  = '0'; 
		$supplierinfo['allowqrcode']  = '0'; 
		$supplierinfo['allowphysicalstore']  = '1'; 
		$supplierinfo['defaultphysicalstorerate']  = '0'; 
		$supplierinfo['productqrcode']  = '1'; 
		$supplierinfo['productdisplaymode']  = '0'; 
		$supplierinfo['reception']  = '0'; 
		$supplierinfo['mylogistic']  = '0'; 
		$supplierinfo['mylogisticname']  = ''; 
		$supplierinfo['realnameauthentication']  = '1';
		$supplierinfo['authenticationpayment']  = '0';
		$supplierinfo['presalesconsultation']  = '0';
		$supplierinfo['productappraises']  = '0';
		$supplierinfo['categoryscolumns']  = '2'; 
		$supplierinfo['popularizeqrcode']  = '0'; 
        $supplierinfo['mainpagetitle'] = '0';
        $supplierinfo['mainpageslider'] = '1';
        $supplierinfo['mainpageproductshowmode'] = '0';
        $supplierinfo['topadlogo'] = '';
        $supplierinfo['bottomadlogo'] = ''; 
		$supplierinfo['rankcost']  = '1';
		$supplierinfo['rankcostrate']  = '10';
		$supplierinfo['showheader']  = '0';
		$supplierinfo['showleftmenu']  = '0';
		$supplierinfo['footerstyle']  = '0';
		$supplierinfo['footericonstyle']  = '0';
		
    }
	$categorylist = XN_Query::create ( 'Content' )->tag('mall_categorys_'.$supplierid)
		->filter ( 'type', 'eic', 'mall_categorys')
		->filter("my.deleted","=","0")
		->filter("my.supplierid","=",$supplierid) 
		->order("my.sequence",XN_Order::ASC_NUMBER)
		->end(-1)
		->execute();
	$categorys = array();
	foreach($categorylist as $info){ 
		if ($info->my->pid == "0")
		{ 
		    $categorys[$info->id] = array('name'=>$info->my->categoryname,  
								'id'=>$info->id);
		} 
	} 
	foreach($categorylist as $info){ 
		if ($info->my->pid != "0")
		{
			$pid = $info->my->pid;
		    $categorys[$pid]['childs'][] = array('name'=>$info->my->categoryname, 
								'id'=>$info->id);
		} 
	} 
	$supplierinfo['categorys'] = $categorys;
	
	global $copyrights;
	$supplierinfo['copyrights'] = $copyrights;  
		
    XN_MemCache::put($supplierinfo, "supplier_" . $supplierid);
    return $supplierinfo;
}

function datediff($diff, $datetime)
{
    $newdatetime = strtotime($diff, strtotime($datetime));
    if (strtotime("now") > $newdatetime)
    {
        return 'timeout';
    }
    $now = date_create("now");
    $diff_date = date_diff(date_create(date("Y-m-d H:i:s", $newdatetime)), $now);
    $day = intval($diff_date->format("%a"));
    $hour = intval($diff_date->format("%h"));
    $min = intval($diff_date->format("%i"));
    $sec = intval($diff_date->format("%s"));


    if ($day == 0 && $hour == 0 && $min == 0)
    {
        return $sec . '秒';
    }
    else if ($day == 0 && $hour == 0)
    {
        return $min . '分钟';
    }
    else if ($day == 0)
    {
        return $hour . '小时' . $min . '分钟';
    }
    else
    {
        return $day . '天' . $hour . '小时' . $min . '分钟';
    }
}


function get_hidden_mobile($phone)
{
    if (isset($phone) && $phone != "")
    {
        $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i', $phone); //固定电话
        if ($IsWhat == 1)
        {
            return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i', '$1****$2', $phone);
        }
        else
        {
            return preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i', '$1****$2', $phone);
        }
    }
    else
    {
        return $phone;
    }

}

?>