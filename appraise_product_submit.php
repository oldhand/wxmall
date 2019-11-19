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

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
    $profileid = $_SESSION['profileid'];
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
	messagebox('错误','请从微信公众号“特赞商城”或朋友圈中朋友分享链接进入本平台，如您确实采用上述方式仍然出现本信息，请与系统管理员联系。');
	die();
}
if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	messagebox('错误',"没有店铺ID!");
	die();  
}

if(isset($_REQUEST['record']) && $_REQUEST['record'] != '' &&
   $_REQUEST['type'] == 'submit')
{
	$orders_products_id = $_REQUEST['record'];
	$orderid = $_REQUEST['orderid'];
	$productid = $_REQUEST['productid']; 
	$imagesserverids = $_REQUEST['imagesserverids']; 
	$praise  = $_REQUEST['praise']; 
	$remark  = $_REQUEST['remark']; 
	
	$orders_products_info = XN_Content::load($orders_products_id,"mall_orders_products_".$profileid,7); 
	$praiseid =  $orders_products_info->my->praiseid;
	if (isset($praiseid) && $praiseid != "")
	{
		header("Location: appraise_submit.php?record=".$orderid);
		die();
	}
	global $wxsetting,$WX_APPID;  
	$newcontent = XN_Content::create('mall_appraises','',false,7); 
	$newcontent->my->supplierid = $supplierid; 
    $newcontent->my->orderid = $orderid;
	$newcontent->my->orders_products_id = $orders_products_id;
	$newcontent->my->productid = $productid; 
	$newcontent->my->profileid = $profileid;
	$newcontent->my->praise = $praise;
	$newcontent->my->remark = $remark;
	$newcontent->my->hasimages = '0';
	$newcontent->my->deleted = '0';
	$newcontent->my->mall_appraisesstatus = 'JustCreated';
	$newcontent->save("mall_appraises,mall_appraises_".$orderid.",mall_appraises_".$profileid.",mall_appraises_".$supplierid);
	$praiseid = $newcontent->id;
	
	$mediaids = json_decode($imagesserverids);
	require_once (XN_INCLUDE_PREFIX."/XN/Wx.php"); 
	global $wxsetting;
	XN_WX::$APPID = $wxsetting['appid'];
	XN_WX::$SECRET = $wxsetting['secret'];
	$images = array();
	foreach($mediaids as $mediaid_info)
	{  
		$image = XN_WX::downloadimage($mediaid_info,$praiseid,"Mall_Appraises");
		if ($image != "")
		{
			$images[] = $image;
		} 
	}  
	if (count($images) > 0)
	{
		$newcontent->my->images = $images;
		$newcontent->my->hasimages = count($images);
		$newcontent->save("mall_appraises,mall_appraises_".$orderid.",mall_appraises_".$profileid.",mall_appraises_".$supplierid);
	}
	$order_info = XN_Content::load($orderid,"mall_orders_".$profileid,7); 
	$appraisestatus =  $order_info->my->appraisestatus;
	if ($appraisestatus != 'yes')
	{
		$order_info->my->appraisestatus = 'yes';
		$order_info->save("mall_orders,mall_orders_".$profileid);
	}
	
	$orders_products_info = XN_Content::load($orders_products_id,"mall_orders_products_".$profileid,7);  
	if ( $order_info->my->praiseid != $praiseid)
	{
		$orders_products_info->my->praiseid = $praiseid;
		$orders_products_info->save("mall_orders_products,mall_orders_products_".$orderid.",mall_orders_products_".$profileid.",mall_orders_products_".$supplierid);
	}
	header("Location: appraise_submit.php?record=".$orderid);
	die();
} 
 

 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$orders_products_id = $_REQUEST['record'];  
}
else
{
	messagebox('错误','参数错误。');
	die(); 
} 
try{   
	 
	$orders_products_info = XN_Content::load($orders_products_id,"mall_orders_products_".$profileid,7); 
	$praiseid =  $orders_products_info->my->praiseid;
	$appraise_info = array(
		'id' => $orders_products_info->id,
		'productid' => $orders_products_info->my->productid,
		'orderid' => $orders_products_info->my->orderid,
		'praiseid' => $praiseid,
		'productname' => $orders_products_info->my->productname,
		'productthumbnail' => $orders_products_info->my->productthumbnail,
		'quantity' => $orders_products_info->my->quantity,
		'shop_price' => number_format($orders_products_info->my->shop_price,2,".",""), 
		'market_price' => number_format($orders_products_info->my->market_price,2,".",""), 
		'total_price' => $orders_products_info->my->total_price,
		'product_property_id' => $orders_products_info->my->product_property_id,
		'propertydesc' => $orders_products_info->my->propertydesc, 
	 );   
	if (isset($praiseid) && $praiseid != "")
	{
		$appraises_info = XN_Content::load($praiseid,"mall_appraises_".$profileid,7);
		$images = $appraises_info->my->images;
		$appraise_info['praise'] =  $appraises_info->my->praise;
		$appraise_info['remark'] =  $appraises_info->my->remark;
		if ($images == "")
		{ 
			$appraise_info['images'] =  array();
		}
		else
		{ 
			$appraise_info['images'] =  (array)$images;
		}
		
	}  
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	messagebox('错误',$msg);
	die(); 
} 

 

require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;
 
$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
	$islogined = true;
} 
$smarty->assign("islogined",$islogined); 

$action = strtolower(basename(__FILE__,".php")); 

$recommend_info = checkrecommend();   
$smarty->assign("share_info",$recommend_info); 
$smarty->assign("supplier_info",get_supplier_info()); 
$smarty->assign("profile_info",get_supplier_profile_info()); 

 
$smarty->assign("appraise_info",$appraise_info); 

$sysinfo = array();
$sysinfo['action'] = 'index'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>