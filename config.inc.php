<?php

global $copyrights;
 
$copyrights = array(
	'name' => '特赞电子商务平台',
	'site' => 'tezan.cn', 
	'trademark' => '特赞', 
	'company' => '湖南赛明威科技有限公司', 
	'icp' => '湘ICP备15009349号', 
	'logo' => 'icon-logo', 
	'official' => '0',
	/*'sms_access_key_id' => 'LTAIv69hqCMYf6Yv',
	'sms_access_key_secret' => 'EtIrTchbzkZ4PSd9IO42uoOy93AjKe', 
	'sms_signname' => '农夫帮',
	'sms_authentication_templatecode' => 'SMS_127810013',
	'sms_tixian_templatecode' => 'SMS_127152802',*/
);
 
 
require_once (dirname(__FILE__) . "/config.common.php");	

$APISERVERADDRESS  = 'http://api.nfb6.com';    


XN_Application::$CURRENT_URL = "admin";
 

 
global $wxsetting;

if(!isset($wxsetting) && isset($_SESSION['appid']) && $_SESSION['appid'] !='')
{
	$appid = $_SESSION['appid']; 
    check_wxsetting_config($appid);
} 
 
 
//会员访问的公众号参数 
$WX_APPID = $wxsetting['appid']; 
$WX_SECRET = $wxsetting['secret']; 

 
//会员汇聚的主公众号
$WX_MAIN_DOMAIN = "mall.tezan.cn";
$WX_MAIN_APPID = "wx7962fafc7ec5b6c6"; 
$WX_MAIN_SECRET = "4c35458e913efbcf86ef621d22387b10"; 
$APISERVERADDRESS  = 'http://api.tezan.cn';  
 

 
if (strpos($_SERVER["SERVER_SOFTWARE"],"nginx") !==false)
{
	$WX_DOMAIN = $_SERVER['HTTP_HOST']; 
}
else
{ 
	$WX_DOMAIN = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
} 
 

$default_timezone = 'PRC';
 
if(isset($default_timezone) && function_exists('date_default_timezone_set')) {
	@date_default_timezone_set($default_timezone);
}
 


if (!function_exists('checkispc'))
{
    function checkispc()
    {
        $useragent = $_SERVER["HTTP_USER_AGENT"];

        if (preg_match("|(Android)|i", $useragent) || preg_match("|(iPhone)|i", $useragent))
        {
            return false;
        }
        return true;
    }
}

if(checkispc())
{
	if (strpos($_SERVER["SERVER_SOFTWARE"],"nginx") !==false)
	{
		$domain=$_SERVER['HTTP_HOST'];
		$domain = str_replace(":7000","",$domain);
	}
	else
	{
		$domain=$_SERVER['SERVER_NAME'];
	}

	$allowdomains = array(
		'www.dnyoupin.com' => '266184',
		'www.dnyoupin.cn' => '266184',
		'demo.tezan.cn' => '71352',
		'f2c.tezan.cn' => '71352',
	);

 
	if (isset($allowdomains[$domain]) && $allowdomains[$domain] != "")
	{ 
		$_SESSION['supplierid'] = $allowdomains[$domain];
	} 
}

 
?>