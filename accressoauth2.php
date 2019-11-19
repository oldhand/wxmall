<?php

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/config.error.php");	 	

if(isset($_GET['code']) && $_GET['code'] !='')
{
	$code = $_GET["code"];  
	$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$WX_APPID.'&secret='.$WX_SECRET.'&code='.$code.'&grant_type=authorization_code';  
   
	$ch = curl_init();  
	curl_setopt($ch,CURLOPT_URL,$get_token_url);  
	curl_setopt($ch,CURLOPT_HEADER,0);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );  
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
	$res = curl_exec($ch);  
	curl_close($ch);  
	$json_obj = json_decode($res,true);  
  
  
	//根据openid和access_token查询用户信息  
	$access_token = $json_obj['access_token'];  
	$openid = $json_obj['openid']; 	
 
	echo '<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title></title>
	<body>
	<form action="/index.php" method="post" name="frm">
	      <input type="hidden" name="accessopenid"   value="'.$openid.'"/>
		  <input type="hidden" name="access_token"   value="'.$access_token.'" />
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
	errorprint('错误','系统禁止的调用!');	 
}
	
?>