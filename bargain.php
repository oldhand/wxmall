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

//$_SESSION['u'] = 'hx5eyjjmlg6'; //老手
//$_SESSION['accessprofileid'] = 'j6zda8gh860'; //徐雁



require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $supplierid = $_SESSION['supplierid'];
}
else
{
    messagebox('错误', "没有店铺ID!");
    die();
}

if (isset($_SESSION['u']) && $_SESSION['u'] != '')
{
    $share_profileid = $_SESSION['u'];
}
else
{
    messagebox('错误', "只能在朋友圈里点开!");
    die();
} 

if (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
    messagebox('错误', "只能在朋友圈里点开!");
    die();
}

if (isset($_REQUEST['salesactivityid']) && $_REQUEST['salesactivityid'] != '')
{
    $salesactivityid = $_REQUEST['salesactivityid'];
}
else
{
    messagebox('错误', "没有活动ID!");
    die();
}

require_once('Smarty_setup.php');

$smarty = new vtigerCRM_Smarty;


if (isset($_REQUEST['productid']) && $_REQUEST['productid'] != '')
{
    $productid = $_REQUEST['productid'];
    $smarty->assign('productid', $productid);
}
else
{
    messagebox('错误', '无法获得productid');
    die();
}

try
{
    $product_info = XN_Content::load($productid, "mall_products_" . $supplierid);
    if ($product_info->my->hitshelf == 'off')
    {
        messagebox('提示', '商品已下架！');
        die();
    }
    $productname = $product_info->my->productname;  
	
   
    $mall_salesactivity_info = XN_Content::load($salesactivityid, 'mall_salesactivitys');
    $activityname = $mall_salesactivity_info->my->activityname;
    $activitymode = $mall_salesactivity_info->my->activitymode;
    $bargainrequirednumber = $mall_salesactivity_info->my->bargainrequirednumber;
    $activitys_products = XN_Query::create("Content")->tag("mall_salesactivitys_products")
        ->filter("type", "eic", "mall_salesactivitys_products")
        ->filter("my.salesactivityid", "=", $salesactivityid)
        ->filter("my.productid", "=", $productid)
        ->filter("my.status", "=", '0')
        ->end(1)
        ->execute();
    if (count($activitys_products) > 0)
    {
        $activitys_product_info = $activitys_products[0];
        $zhekou = $activitys_product_info->my->zhekou;
        $zhekoulabel = $activitys_product_info->my->label;
        $salesactivity_product_id = $activitys_product_info->id;
    }
    else
    {
        messagebox('提示', '活动中找不到商品！');
        die();
    } 
	$bargains_profile = array(); 
	$bargaincount = 0;
    $bargains_products = XN_Query::create("YearContent")->tag("mall_bargains_".$share_profileid)
                                 ->filter("type", "eic", "mall_bargains")
                                 ->filter("my.salesactivityid", "=", $salesactivityid)
                                 ->filter("my.productid", "=", $productid)
                                 ->filter("my.supplierid", "=", $supplierid)
                                 ->filter("my.profileid", "=", $share_profileid)
                                 ->end(-1)
                                 ->execute();
    foreach($bargains_products as $bargains_info)
	{
		$bargainer = $bargains_info->my->bargainer;
		$bargain = $bargains_info->my->bargain;
		if ($bargain == "1") $bargaincount++;
        $bargains_profile[$bargainer]["published"] = date("m-d H:i",strtotime($bargains_info->published));
		$bargains_profile[$bargainer]["bargain"] = $bargain;
    }
    $profileinfo = getProfileInfoArrByids(array_keys($bargains_profile),false);
    foreach($profileinfo as $pid => $profile_info){
        $bargains_profile[$pid]["givenname"] = $profile_info["givenname"];
        $bargains_profile[$pid]["headimgurl"] = $profile_info["headimgurl"];
    } 
}
catch (XN_Exception $e)
{ 
    messagebox('错误', '根据产品ID（' . $productid . '）获得信息失败！');
    die();
}
 

if (isset($_SESSION['width']) && $_SESSION['width'] != '')
{
    $width = $_SESSION['width'];
}
else
{
    $width = "320";
    $_SESSION['width'] = $width;
}

$productlogo = $product_info->my->productlogo;
$productthumbnail = $product_info->my->productthumbnail;

global $APISERVERADDRESS, $width;
if (isset($productlogo) && $productlogo != "")
{
    $productlogo = $APISERVERADDRESS . $product_info->my->productlogo . "?width=" . $width;
}

if (isset($productthumbnail) && $productthumbnail != "")
{
    $productthumbnail = $APISERVERADDRESS . $productthumbnail . "?width=200";
}

$productinfo = array();

$productid = $product_info->id;
$productinfo['productid'] = $product_info->id;
$productinfo['deleted'] = $product_info->my->deleted;
$productinfo['productlogo'] = $productlogo; 
$productinfo['keywords'] = $product_info->my->keywords;
$productinfo['market_price'] = number_format($product_info->my->market_price, 2, ".", "");
$productinfo['shop_price'] = number_format($product_info->my->shop_price, 2, ".", "");
$productinfo['productname'] = $product_info->my->productname;
$productinfo['simple_desc'] = $product_info->my->simple_desc;
$productinfo['product_weight'] = $product_info->my->product_weight;
$productinfo['weight_unit'] = $product_info->my->weight_unit;
$productinfo['brand'] = $product_info->my->brand;
$productinfo['categorys'] = $product_info->my->categorys;
$productinfo['suppliers'] = $product_info->my->suppliers;
$productinfo['promote_start_date'] = $product_info->my->promote_start_date;
$productinfo['promote_end_date'] = $product_info->my->promote_end_date;
$productinfo['postage'] = number_format($product_info->my->postage, 2, ".", "");
$productinfo['mergepostage'] = $product_info->my->mergepostage;
$productinfo["includepost"]  = $product_info->my->include_post_count;

 

$productinfo['zhekou'] = $zhekou;
$productinfo['zhekoulabel'] = $zhekoulabel;
$productinfo['activityname'] = $activityname;
$productinfo['salesactivityid'] = $salesactivityid;
$productinfo['salesactivity_product_id'] = $salesactivity_product_id;
$productinfo["activitymode"] = $activitymode;
$productinfo["bargainrequirednumber"] = $bargainrequirednumber;
$productinfo["bargaincount"] = $bargaincount;


if (in_array($profileid,array_keys($bargains_profile)))
{
	$productinfo["bargain"] = $bargains_profile[$profileid]["bargain"];
}
else
{
	$productinfo["bargain"] = '0';
}

if ($profileid == $share_profileid)
{
	 $productinfo["bargain"] = '3';
}

$productinfo['bargains'] = $bargains_profile;
//$productinfo["bargain"] = '0';


$profile_info = get_supplier_profile_info($share_profileid, $supplierid);

$productinfo["share_profileid"] = $share_profileid;
$productinfo["share_givenname"] = $profile_info['givenname'];
 
 
$shop_price = $product_info->my->shop_price;



if ($zhekou != "")
{
    if ($activitymode == "1")
    {
	    if ( $bargaincount >= $bargainrequirednumber)
	    {
		    $promotionalprice = floatval($shop_price) - floatval($shop_price) * (10 - floatval($zhekou)) / 10 ;
	    }
	    else
	    {
		    $promotionalprice = floatval($shop_price) - floatval($shop_price) * (10 - floatval($zhekou)) / 10 / $bargainrequirednumber * $bargaincount;
	    }
        
    }
    else
    {
        $promotionalprice = floatval($shop_price) * floatval($zhekou) / 10;
    }
	$floor_price = floatval($shop_price) * floatval($zhekou) / 10;
	 
    $productinfo['promotional_price'] = number_format($promotionalprice, 2, ".", ""); 
    $productinfo['total_money'] = number_format(floatval($shop_price) * floatval($zhekou) / 10,2,".","");
}
else
{
    $productinfo['promotional_price'] = number_format($shop_price, 2, ".", "");
    $productinfo['total_money'] = number_format($shop_price, 2, ".", "");
}


$inventorys = XN_Query::create('Content')->tag('mall_inventorys_' . $supplierid)
    ->filter('type', 'eic', 'mall_inventorys')
    ->filter('my.productid', '=', $productid)
    ->filter('my.deleted', '=', '0')
    ->end(-1)
    ->execute();
$total_inventory = 0;
$mall_inventorys = array();
foreach ($inventorys as $inventory_info)
{
    $propertytypeid = $inventory_info->my->propertytypeid;
    $inventory = $inventory_info->my->inventory;
    $total_inventory += intval($inventory);
    $mall_inventorys[$propertytypeid] = $inventory;
}
$productinfo['inventory'] = $total_inventory; 

 
$smarty->assign("productinfo", $productinfo);
$smarty->assign('salesactivityid', $salesactivityid);
  
$panel = strtolower(basename(__FILE__, ".php"));
$smarty->assign("actionname", $panel);
 

$query_string = base64_encode($_SERVER["REQUEST_URI"]);

$shareurl = 'http://' . $WX_DOMAIN . '/index.php?u=' . $profileid . '&sid=' . $supplierid . '&uri=' . $query_string;

$share_info['share_url'] = $shareurl;
$productname = str_replace('"', '', $productinfo['productname']);
$share_info['share_title'] = $productname;

if (isset($productthumbnail) && $productthumbnail != "")
{
    $share_info['share_logo'] = $productthumbnail;
}

$smarty->assign("share_info", checkrecommend());
$smarty->assign("supplier_info", get_supplier_info());

 
$smarty->display('bargain.tpl');
  
function getProfileInfoArrByids($ids,$anonymous=true)
{
    if (count($ids) == 0) return array();
    $infos = XN_Profile::loadMany($ids, "id", "profile");
    $givenNames = array();
    foreach ($infos as $info)
    {
	    if ($info->type == "unsubscribe")
        {
	        $givenname = '匿名';
        }
        else
        {
	        $givenname = $info->givenname; 
	        if ($givenname == "")
	        {
	            $givenname = '匿名';
	        }
	        if($anonymous)
	        {
	            $givenname = replace_star($givenname);
	        }
        }
        
        $headimgurl = $info->link;
        if ($headimgurl == "")
        {
            $headimgurl = 'images/user.jpg';
        }
        $givenNames[$info->profileid] = array('givenname' => $givenname, 'headimgurl' => $headimgurl);
    }
    return $givenNames;
}
 

?>