<?php 

session_start(); 
 
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/config.error.php");	 

if(isset($_GET['sid']) && $_GET['sid'] !='')
{
	$supplierid = $_SESSION['supplierid'] = $_GET['sid'];
} 
else
{
	errorprint('错误','系统禁止的调用!');	
}

if(isset($_GET['pid']) && $_GET['pid'] !='')
{
	$_SESSION['productid'] = $_GET['pid'];
} 
else
{  
	unset($_SESSION['productid']);	
}

if(isset($_GET['uri']) && $_GET['uri'] !='')
{
	$_SESSION['uri'] = $_GET['uri'];
}
else
{
	unset($_SESSION['uri']);
}
 	


unset($_SESSION['accessprofileid']);
unset($_SESSION['profileid']);

 
if (checkisweixin())
{
	try 
	{
		$supplierinfo = get_supplier_info($supplierid); 
		$appid = $supplierinfo['appid'];  
		
		if(isset($_SESSION['appid']) && $_SESSION['appid'] !='')
		{
			if ($_SESSION['appid'] != $appid)
			{
				unset($_SESSION['businesseid']); 
			} 
		}
		
		$_SESSION['appid'] = $appid; 
		$_SESSION['supplierid'] = $supplierid; 
	}
	catch ( XN_Exception $e ) 
	{ 
		 
	}
	$host = $_SERVER['HTTP_HOST'];
    require_once (dirname(__FILE__) . "/config.inc.php");
    
	$redirect_uri = sprintf('http://%s/o2o.php?target=index&type=home&supplierid=%s&host=%s',$WX_MAIN_DOMAIN,$supplierid,$host);
	$url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=1#wechat_redirect',$WX_MAIN_APPID,urlencode($redirect_uri));  
 	header("Location:".$url); 
} 
else if(checkismobile())
{  
	/*$_SESSION['u'] = 'anonymous';
	$_SESSION['accessprofileid'] = 'anonymous';
	$_SESSION['d'] = date("Y-m-d");  
	unset($_SESSION['reco_title']);
	unset($_SESSION['recommendid']);
	unset($_SESSION['logo']);
	unset($_SESSION['signature']);
	$_SESSION['givenname'] = '天天微逛';  
	$_SESSION['headimgurl'] = 'images/user.jpg';
	header("Location: index.php"); */
	errorprint('错误','系统禁止的调用!');	
	die();
}
else
{ 
	/*$_SESSION['u'] = 'anonymous';
	$_SESSION['accessprofileid'] = 'anonymous';
	$_SESSION['d'] = date("Y-m-d");  
	unset($_SESSION['reco_title']);
	unset($_SESSION['recommendid']);
	unset($_SESSION['logo']);
	unset($_SESSION['signature']);
	$_SESSION['givenname'] = '天天微逛';  
	$_SESSION['headimgurl'] = 'images/user.jpg'; 
	header("Location: http://www.ttwz168.com"); */
	errorprint('错误','系统禁止的调用!');	
	die();
}

 


?> 
