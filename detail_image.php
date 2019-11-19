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

 
require_once('Smarty_setup.php');

$smarty = new vtigerCRM_Smarty; 


if(isset($_REQUEST['productid']) && $_REQUEST['productid'] !='')
{
    $productid = $_REQUEST['productid'];
    $smarty->assign('productid',$productid);
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
   $profileid = "anonymous";
}
 

try
{
    $product_info = XN_Content::load($productid,"mall_products"); 
    $productname = $product_info->my->productname;
}
catch ( XN_Exception $e )
{ 
    messagebox('错误','根据产品ID（'.$productid.'）获得产品信息失败！');
    die();
}

$brandid  = $product_info->my->brand; 
try
{
    $brand_info = XN_Content::load($brandid,"mall_brands");
}
catch ( XN_Exception $e )
{
    $brand_info = null;
}


$productinfo =array();
$productid = $product_info->id;
$productinfo['productid'] = $product_info->id;
$productinfo['deleted'] = $product_info->my->deleted;
$productinfo['productlogo'] = $product_info->my->productlogo;
$productinfo['keywords'] = $product_info->my->keywords;
$productinfo['market_price'] = number_format($product_info->my->market_price,2,".","");  
$productinfo['shop_price'] = number_format($product_info->my->shop_price,2,".",""); 
$productinfo['promote_price'] = number_format($product_info->my->promote_price,2,".",""); 
$productinfo['productname'] = $product_info->my->productname; 
$productinfo['simple_desc'] = $product_info->my->simple_desc;
$description = $product_info->my->description; 
$description = str_replace("http://www.ttwz168.com","", $description);
$description = str_replace("http://www.tezan.cn","", $description);

if(isset($_SESSION['width']) && $_SESSION['width'] !='')
{
	$width = $_SESSION['width']; 
}
else
{
	$width = "320";
	$_SESSION['width'] = $width;  
}

//$description = preg_replace('#<img(.*?)src="(.*?)"(.*?)>#','<img$1 class="img-responsive" src="images/lazyload.png" data-lazyload="'.$APISERVERADDRESS.'$2?width='.$width.'"$3>', $description);
$description = preg_replace('#<img(.*?)src="(.*?)"(.*?)>#','<img$1 class="img-responsive lazy" src="images/lazyload.png" data-original="'.$APISERVERADDRESS.'$2"$3>', $description);

$description = str_replace("<p>","", $description);
$description = str_replace("</p>","", $description);
$productinfo['description'] = $description;
$productinfo['product_weight'] = $product_info->my->product_weight;
$productinfo['weight_unit'] = $product_info->my->weight_unit;
$productinfo['brand'] = $product_info->my->brand;
$productinfo['categorys'] = $product_info->my->categorys;
$productinfo['suppliers'] = $product_info->my->suppliers;
$productinfo['promote_start_date'] = $product_info->my->promote_start_date;
$productinfo['promote_end_date'] = $product_info->my->promote_end_date; 
$productinfo['postage'] = number_format($product_info->my->postage,2,".","");
if ($brand_info != null)
{
    $productinfo['brand_logo'] = $brand_info->my->brand_logo;
    $productinfo['brand_name'] = $brand_info->my->brand_name;
}
else
{
    $productinfo['brand_logo'] = "/images/brand_logo.png";
    $productinfo['brand_name'] = "";
}

 

$smarty->assign("islogined",true);
$smarty->assign("profileid",$profileid);
$smarty->assign("headimgurl",$_SESSION['headimgurl']);
$smarty->assign("givenname",$_SESSION['givenname']);
$smarty->assign("productinfo",$productinfo); 

 

$panel = strtolower(basename(__FILE__,".php"));
$smarty->assign("actionname",$panel);

$share_info = checkrecommend();

$query_string = base64_encode($_SERVER["REQUEST_URI"]);
$shareurl = 'http://'.$WX_DOMAIN.'/index.php?u='.$loginprofileid.'&sid='.$supplierid.'&uri='.$query_string; 
$share_info['share_url'] = $shareurl;
$productname = str_replace('"','',$productinfo['productname']);
$share_info['share_title'] = $productname;

$smarty->assign("share_info",$share_info); 
$smarty->assign("supplier_info",get_supplier_info()); 
 

$sysinfo = array();
$sysinfo['action'] = 'index'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS;  
$sysinfo['http_user_agent'] = check_http_user_agent(); 
$sysinfo['webpath'] = $WEB_PATH;  
$sysinfo['width'] = $_SESSION['width'];  

$smarty->assign("sysinfo",$sysinfo);  
 
if(isset($_REQUEST['scrolltop']) && $_REQUEST['scrolltop']>0){
    $smarty->assign("scrolltop",$_REQUEST['scrolltop']);
}	
$smarty->assign("pagenum",$_REQUEST['pagenum']); 
$smarty->assign("from",$_REQUEST['from']);

$smarty->display('detail_image.tpl');



?>