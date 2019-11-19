<?php

require_once (dirname(__FILE__) . "/config.inc.php");	
require_once (dirname(__FILE__) . "/util.php");		

session_start();

if(isset($_REQUEST['accessopenid']) && $_REQUEST['accessopenid'] =='anonymous' )
{
	if(isset($_REQUEST['target']) && $_REQUEST['target'] != '' )
	{
		$target = $_REQUEST['target'];  
		
		if ($target == "index")
		{
			header("Location: ".$target.".php");
		}
		else
		{
			$newtarget= base64_decode($target);
			header("Location: ".$newtarget);
		} 
		
	}
	else
	{
		errorprint('错误','系统禁止的重定向空参数!');	 
	} 
	die();
}
else if(isset($_REQUEST['accessopenid']) && $_REQUEST['accessopenid'] !='' )
{ 
	if(isset($_REQUEST['target']) && $_REQUEST['target'] != '' )
	{
		$target = $_REQUEST['target']; 
		
	    $wxopenid = $_REQUEST['accessopenid'];
	    $_SESSION['accessopenid'] = $wxopenid; 
		$_SESSION['wxopenid'] = $wxopenid; 
	
		if(isset($_REQUEST['width']) && $_REQUEST['width'] !='')
		{
			$width = $_REQUEST['width'];
			if(intval($width) > 768) $width = "768";
			$_SESSION['width'] = $width;  
		} 
	
		require_once (XN_INCLUDE_PREFIX."/XN/Wx.php");
		XN_WX::$APPID = $WX_APPID;
		XN_WX::$SECRET = $WX_SECRET;
	
		try 
		{
			$profile = XN_WX::initprofile($wxopenid);
		}
		catch ( XN_Exception $e ) 
		{ 
			errorprint('错误',$e->getMessage());	  
			die();
		}
	
		$screenName = $profile->profileid;
		$_SESSION['accessprofileid'] = $screenName;
		unset($_SESSION['profileid']);
		$_SESSION['province'] = $profile->province;
		$_SESSION['city'] = $profile->city;
		$_SESSION['signature'] = $profile->signature;
		if ($profile->province == '' || $profile->city == '')
		{
			$_SESSION['province'] = '湖南省';
			$_SESSION['city'] = '长沙市'; 
		}  
		
		if ($target == "index")
		{
			header("Location: ".$target.".php");
		}
		else
		{
			$newtarget= base64_decode($target);
			header("Location: ".$newtarget);
		} 
		die();
	}
	else
	{
		errorprint('错误','系统禁止的重定向空参数!');	 
	} 
}
else if(isset($_REQUEST['openid']) && $_REQUEST['openid'] !='' )
{ 
	if(isset($_REQUEST['target']) && $_REQUEST['target'] != '' )
	{
		$target = $_REQUEST['target']; 
		
		if(isset($_REQUEST['width']) && $_REQUEST['width'] !='')
		{
			$width = $_REQUEST['width']; 
			if(intval($width) > 768) $width = "768";
			$_SESSION['width'] = $width ;  
		} 
	
		$_SESSION['u'] = '';
		$_SESSION['d'] = '';
		$_SESSION['reco_title'] = '';
		$_SESSION['recommendid'] = '';
		$_SESSION['logo'] = ''; 
		$_SESSION['openid'] = ''; 
		$_SESSION['wxopenid'] = ''; 
		$_SESSION['profileid'] = '';  
	 
		$firsttime = 'false';
		$wxopenid  = $_REQUEST['openid'];  
	
		require_once (XN_INCLUDE_PREFIX."/XN/Wx.php");
		XN_WX::$APPID = $WX_APPID;
		XN_WX::$SECRET = $WX_SECRET;
	
		try 
		{
			$profile = XN_WX::initprofile($wxopenid,true);
		}
		catch ( XN_Exception $e ) 
		{ 
			errorprint('错误',$e->getMessage());	  
			die();
		} 
	 
		$_SESSION['openid'] = $_REQUEST['openid'];
		$givenname = $profile->givenname;
		$screenName = $profile->profileid;
		$_SESSION['profileid'] = $screenName;
		unset($_SESSION['accessprofileid']);
		$givenname = strip_tags($givenname);
		$_SESSION['givenname'] = $givenname;
		$_SESSION['province'] = $profile->province;
		$_SESSION['city'] = $profile->city;
		$_SESSION['signature'] = $profile->signature;
		$headimgurl = $profile->link;
		if ($headimgurl == "")
		{
			$headimgurl = 'images/user.jpg';
		}
		$_SESSION['headimgurl'] = $headimgurl;
		$_SESSION['wxopenid'] = $profile->wxopenid;
  
		$_SESSION['u'] = $screenName;
		$_SESSION['d'] = date("Y-m-d"); 
	
		$profileid = $screenName;
		unset($_SESSION['reco_title']);
		unset($_SESSION['recommendid']);
		unset($_SESSION['logo']); 
		
		if ($target == "index")
		{
			header("Location: ".$target.".php");
		}
		else
		{
			$newtarget= base64_decode($target);
			header("Location: ".$newtarget);
		} 
		die();
	}
	else
	{
		errorprint('错误','系统禁止的重定向空参数!');	 
	} 
}


if (checkisweixin())
{
	global $WEB_PATH,$WX_DOMAIN; 
	if(isset($_SESSION['u']) && $_SESSION['u'] !='')
	{
		if(isset($_GET['target']) && $_GET['target'] !='')
		{

			$target = $_GET['target']; 
			require_once (dirname(__FILE__) . "/config.inc.php");  
			$redirect_uri = sprintf('http://%s/oauth2.php?target='.$target,$WX_DOMAIN);
			$url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=1#wechat_redirect',$WX_APPID,urlencode($redirect_uri));  
	
			header("Location:".$url);
			die();
		}
		else
		{
			errorprint('错误','必须指定返回参数!');	  
			die();
		} 
	}
	else
	{
		require_once (dirname(__FILE__) . "/config.inc.php"); 
		$redirect_uri = sprintf('http://%s/oauth2.php?target=index&type=init',$WX_DOMAIN);
		$url = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=1#wechat_redirect',$WX_APPID,urlencode($redirect_uri));  

		header("Location:".$url); 
		die();
	}
} 
else if(checkismobile())
{ 
	$_SESSION['accessprofileid'] = 'anonymous';
	unset($_SESSION['profileid']);
 
	$_SESSION['signature'] = '';
	$_SESSION['province'] = '湖南省';
	$_SESSION['city'] = '长沙市';  
	
	if(isset($_REQUEST['target']) && $_REQUEST['target'] != '' )
	{
		$target = $_REQUEST['target'];
		if ($target == "index")
		{
			echo '<!DOCTYPE html>
					<html lang="zh-CN">
					  <head>
					    <meta charset="utf-8">
					    <title></title>
						<body>
						<form action="index.php" method="post" name="frm">  
							  <input type="hidden" id="width" name="width"   value="0" />
							  <input type="hidden" id="height" name="height"   value="0" /> 
						</form>
						<script language="JavaScript"> 
						  document.getElementById("width").value=screen.width;
						  document.getElementById("height").value=screen.height; 
						  document.frm.submit();
						</script> 
						</body>
					</html>';
		}
		else
		{  
			echo '<!DOCTYPE html>
					<html lang="zh-CN">
					  <head>
					    <meta charset="utf-8">
					    <title></title>
						<body>
						<form action="checklogin.php" method="post" name="frm">  
							  <input type="hidden" id="width" name="width"   value="0" /> 
							  <input type="hidden" id="height" name="height"   value="0" /> 
							  <input type="hidden" id="accessopenid" name="accessopenid"   value="anonymous" /> 
							  <input type="hidden" id="target" name="target"   value="'.$target.'" /> 
						</form>
						<script language="JavaScript"> 
						  document.getElementById("width").value=screen.width;
						  document.getElementById("height").value=screen.height; 
						  document.frm.submit();
						</script> 
						</body>
					</html>';
		}  
	}
	else
	{
		errorprint('错误','系统禁止的重定向空参数!');	 
	} 
	die();
}
else
{
	unset($_SESSION['accessprofileid']);
	unset($_SESSION['profileid']);
	if(isset($_REQUEST['target']) && $_REQUEST['target'] != '' )
	{
		$target = $_REQUEST['target'];
		if ($target == "index")
		{
			header("Location: http://www.ttwz168.com"); 
		}
		else
		{
			$newtarget= base64_decode($target);
			if( preg_match("|detail.php\?from=index&productid=([^;^)^(]*)&scrollTop=|i", $newtarget, $Tmp ) )
			{ 
				$productid = $Tmp[1];
				header('Location: http://www.ttwz168.com/detail-'.$productid.'.html'); 
				die();
			}elseif( preg_match("|smashegg.php\?record=([^;^)^(]*)|i", $newtarget, $Tmp))
            {
                $record = $Tmp[1];
                header('Location: http://'.$WX_DOMAIN.$WEB_PATH.'/smashegg.php?record='.$record);
                die();
            }
			else
			{
				  header("Location: http://www.ttwz168.com");
			} 
		}
	} 
	die(); 
	//errorprint('错误','请从微信公众号“天天微赚”或朋友圈中朋友分享链接进入本平台!');	  
	//die();
}

 

?>