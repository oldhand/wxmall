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
  
if(isset($_REQUEST['productid']) && $_REQUEST['productid'] !='')
{
    $productid = $_REQUEST['productid']; 
}
else
{
    messagebox('错误','无法获得productid');
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



try
{
    $product_info = XN_Content::load($productid,"mall_products_".$supplierid);
    if($product_info->my->hitshelf=='off'){
        messagebox('提示','商品已下架！');
        die();
    }
    $productname = $product_info->my->productname;
}
catch ( XN_Exception $e )
{
    // echo $e->getMessage();exit();
    messagebox('错误','根据产品ID（'.$productid.'）获得产品信息失败！');
    die();
}
  

if(isset($_SESSION['width']) && $_SESSION['width'] !='')
{
	$width = $_SESSION['width']; 
}
else
{
	$width = "320";
	$_SESSION['width'] = $width;  
}

$productlogo = $product_info->my->productlogo;

global $APISERVERADDRESS,$width;
if (isset($productlogo) && $productlogo != "")
{ 
	$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=".$width;
}

$productinfo =array();
$productid = $product_info->id;
$productinfo['productid'] = $product_info->id;
$productinfo['deleted'] = $product_info->my->deleted;
$productinfo['productlogo'] = $productlogo;
$productinfo['keywords'] = $product_info->my->keywords;
$productinfo['market_price'] = number_format($product_info->my->market_price,2,".","");  
$productinfo['shop_price'] = number_format($product_info->my->shop_price,2,".",""); 
$productinfo['promote_price'] = number_format($product_info->my->promote_price,2,".",""); 
$productinfo['productname'] = $product_info->my->productname; 
$productinfo['simple_desc'] = $product_info->my->simple_desc;
$productinfo['product_weight'] = $product_info->my->product_weight;
$productinfo['weight_unit'] = $product_info->my->weight_unit;
$productinfo['brand'] = $product_info->my->brand;
$productinfo['categorys'] = $product_info->my->categorys;
$productinfo['suppliers'] = $product_info->my->suppliers;
$productinfo['promote_start_date'] = $product_info->my->promote_start_date;
$productinfo['promote_end_date'] = $product_info->my->promote_end_date; 
$productinfo['postage'] = number_format($product_info->my->postage,2,".",""); 
 

 

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
$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
	$smarty->assign("profile_info",$profile_info);
}
else
{
	$smarty->assign("profile_info",get_profile_info());
}

$smarty->assign("productinfo",$productinfo);


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