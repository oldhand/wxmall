<?php

session_start(); 


require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/config.error.php");	 
require_once (dirname(__FILE__) . "/config.inc.php");   
	
if(isset($_REQUEST['code']) && $_REQUEST['code'] !='' && 
   isset($_REQUEST['wxopenid']) && $_REQUEST['wxopenid'] !='' &&
   isset($_REQUEST['supplierid']) && $_REQUEST['supplierid'] !='' )
{
	$code = $_REQUEST["code"];  
	$supplierid = $_REQUEST["supplierid"];  
	$wxopenid = $_REQUEST["wxopenid"];  
	
	$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$WX_MAIN_APPID.'&secret='.$WX_MAIN_SECRET.'&code='.$code.'&grant_type=authorization_code';  
   
	$ch = curl_init();  
	curl_setopt($ch,CURLOPT_URL,$get_token_url);  
	curl_setopt($ch,CURLOPT_HEADER,0);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );  
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);  
	$res = curl_exec($ch);  
	curl_close($ch);  
	$json_obj = json_decode($res,true);  
  
 
	$access_token = $json_obj['access_token'];  
	$mainwxopenid = $json_obj['openid'];  
 
	require_once (XN_INCLUDE_PREFIX."/XN/Wx.php");
	XN_WX::$APPID = $WX_MAIN_APPID;
	XN_WX::$SECRET = $WX_MAIN_SECRET;
	
	try 
	{
		$profile = XN_WX::initprofile($mainwxopenid,true);
		$profileid = $profile->profileid;
		
		$supplier_profile = XN_Query::create ( 'Content' )->tag("supplier_profile_".$wxopenid)
	 						->filter ( 'type', 'eic', 'supplier_profile')  
					        ->filter (  'my.profileid', '=', $profileid)
							->filter (  'my.supplierid', '=', $supplierid)
					        ->filter (  'my.deleted', '=', '0' )
	 						->end(1)
	 						->execute ();
	 	if (count($supplier_profile) > 0 )	
	 	{
			$supplier_profile_info = $supplier_profile[0];
			$supplier_profile_info->wxopenid = $wxopenid;
			$supplier_profile_info->save("supplier_profile,supplier_profile_".$wxopenid.",supplier_profile_".$profileid);
		}
		else
		{
            $newcontent = XN_Content::create('supplier_profile','',false);
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
            $newcontent->my->rank = '0';
			$newcontent->my->accumulatedmoney = '0';
			$newcontent->my->money = '0';
		    $newcontent->my->sharefund = '0';
			$newcontent->my->latitude = '';
            $newcontent->my->longitude = '';
            $newcontent->save("supplier_profile,supplier_profile_".$wxopenid.",supplier_profile_".$profileid);
		} 
	}
	catch ( XN_Exception $e ) 
	{ 
		errorprint('错误',$e->getMessage());	  
		die();
	} 
	$url = sprintf('index.php?openid=%s',$wxopenid);  
 	header("Location:".$url); 
	die();  
 
}  
else
{
	errorprint('错误','系统禁止的调用!');	 
}
	
?>