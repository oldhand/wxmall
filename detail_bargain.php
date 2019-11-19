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

require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");


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
if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $supplierid = $_SESSION['supplierid'];
}
else
{
    messagebox('错误', "没有店铺ID!");
    die();
}
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
    $profileid = "anonymous";
}
$activitymode = 0;
$bargainrequirednumber = 0;
$bargains_profile = array();
if (isset($_REQUEST['from']) && $_REQUEST['from'] == 'salesactivity' &&
    isset($_REQUEST['salesactivityid']) && $_REQUEST['salesactivityid'] != ''
)
{
    $salesactivityid = $_REQUEST['salesactivityid'];
    $smarty->assign('salesactivityid', $salesactivityid);
    $mall_salesactivity_info = XN_Content::load($salesactivityid, 'mall_salesactivitys');
    $activityname = $mall_salesactivity_info->my->activityname;
    $activitymode = intval($mall_salesactivity_info->my->activitymode);
    $bargainrequirednumber = intval($mall_salesactivity_info->my->bargainrequirednumber);
    $enddate = $mall_salesactivity_info->my->enddate;
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
        $zhekou = "";
        $zhekoulabel = "";
        $activityname = "";
        $salesactivityid = "";
        $salesactivity_product_id = "";
    }
    if($activitymode === 1){
		$bargaincount = 0;
        $bargains_products = XN_Query::create("YearContent")->tag("mall_bargains")
                                     ->filter("type", "eic", "mall_bargains")
                                     ->filter("my.salesactivityid", "=", $salesactivityid)
                                     ->filter("my.productid", "=", $productid)
                                     ->filter("my.supplierid", "=", $supplierid)
                                     ->filter("my.profileid", "=", $profileid)
                                     ->end(-1)
                                     ->execute();
        foreach($bargains_products as $bargains_info){
			$bargainer = $bargains_info->my->bargainer;
			$bargain = $bargains_info->my->bargain;
			if ($bargain == "1") $bargaincount++;
            if($bargaincount > $bargainrequirednumber){
                $bargaincount = $bargainrequirednumber;
            }
            $bargains_profile[$bargainer]["published"] = date("m-d H:i",strtotime($bargains_info->published));
			$bargains_profile[$bargainer]["bargain"] = $bargain;
        }
        $profileinfo = getProfileInfoArrByids(array_keys($bargains_profile),false);
        foreach($profileinfo as $pid => $profile_info){
            $bargains_profile[$pid]["givenname"] = $profile_info["givenname"];
            $bargains_profile[$pid]["headimgurl"] = $profile_info["headimgurl"];
        }
    }
}
else
{
    $zhekou = "";
    $zhekoulabel = "";
    $activityname = "";
    $salesactivityid = "";
    $salesactivity_product_id = "";
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
}
catch (XN_Exception $e)
{
    // echo $e->getMessage();exit();
    messagebox('错误', '根据产品ID（' . $productid . '）获得产品信息失败！');
    die();
}


$brandid = $product_info->my->brand;
try
{
    $brand_info = XN_Content::load($brandid, "mall_brands_" . $supplierid);
}
catch (XN_Exception $e)
{
    $brand_info = null;
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
$include_post_count = $product_info->my->include_post_count;
if (!isset($include_post_count) || $include_post_count == "")
{
	$include_post_count = "0";
}
$productinfo["includepost"]  = $include_post_count;

$productinfo["activitymode"] = $activitymode;
$productinfo["bargainrequirednumber"] = $bargainrequirednumber;

$productinfo['bargains'] = $bargains_profile;
$productinfo["bargaincount"] = $bargaincount;
$productinfo['zhekou'] = $zhekou;
$productinfo['zhekoulabel'] = $zhekoulabel;
$productinfo['activityname'] = $activityname;
$productinfo['salesactivityid'] = $salesactivityid;
$productinfo['enddate'] = $enddate;
$productinfo['salesactivity_product_id'] = $salesactivity_product_id;


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
$shop_price = $product_info->my->shop_price;
if ($zhekou != "")
{
    if ($activitymode === 1)
    {
        $promotionalprice = floatval($shop_price) - floatval($shop_price) * (10 - floatval($zhekou)) / 10 / $bargainrequirednumber * $bargaincount;
    }
    else
    {
        $promotionalprice = floatval($shop_price) * floatval($zhekou) / 10;
    }
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


if (trim($product_info->my->property_type) != "")
{
    $propertys = XN_Query::create('Content')
        ->tag('mall_propertys_' . $supplierid)
        ->filter('type', 'eic', 'mall_propertys')
        ->filter('my.productid', '=', $productid)
        ->filter('my.status', '=', '0')
        ->filter('my.deleted', '=', '0')
        ->begin(0)
        ->end(-1)
        ->execute();
    if (count($propertys) > 0)
    {
        $decodeproperty_type = array();
        foreach ($propertys as $property_info)
        {
            $property_value = $property_info->my->property_value;
            $property_type = $property_info->my->property_type;
            $propertyid = $property_info->id;
            $decodeproperty_type[$property_type][$propertyid] = $property_value;
        }

        //$property_type = $product_info->my->property_type;
        //$decodeproperty_type = json_decode($property_type,true);

        $product_propertys = XN_Query::create('Content')->tag('mall_product_property_' . $supplierid)
            ->filter('type', 'eic', 'mall_product_property')
            ->filter('my.productid', '=', $productid)
            ->filter('my.deleted', '=', '0')
            ->order("my.recommend", XN_Order::ASC_NUMBER)
            ->end(-1)
            ->execute();

        $mall_product_property = array();

        foreach ($product_propertys as $product_property_info)
        {
            $propertytypeid = $product_property_info->id;
            $imgurl = $product_property_info->my->imgurl;
            $market = $product_property_info->my->market;
            $shop = $product_property_info->my->shop;
            $propertydesc = $product_property_info->my->propertydesc;
            $propertyids = $product_property_info->my->propertyids;
            if (is_array($propertyids))
            {
                $propertyids = join(",", $propertyids);
            }
            $info = array();
            $info['propertytypeid'] = $propertytypeid;
            if (isset($imgurl) && $imgurl != '')
            {
                $info['imgurl'] = $imgurl;
            }
            else
            {
                $info['imgurl'] = $productinfo['productlogo'];
            }

            $info['market_price'] = number_format($market, 2, ".", "");
            $info['shop_price'] = number_format($shop, 2, ".", "");
            $info['propertydesc'] = $propertydesc;
            $info['propertyids'] = $propertyids;
            if (isset($mall_inventorys[$propertytypeid]) && $mall_inventorys[$propertytypeid] != '')
            {
                $info['inventory'] = $mall_inventorys[$propertytypeid];
            }
            else
            {
                $info['inventory'] = '0';
            }
            if ($zhekou != "")
            {
                if($activitymode === 1){
                    $promotionalprice = floatval($shop) - floatval($shop) * (10 - floatval($zhekou)) / 10 / $bargainrequirednumber * count($bargains_profile);
                }else
                {
                    $promotionalprice = floatval($shop) * floatval($zhekou) / 10;
                }
                $productinfo['promotional_price'] = number_format($promotionalprice, 2, ".", "");
                $productinfo['total_money'] = number_format(floatval($shop) * floatval($zhekou) / 10, 2, ".", "");
            }

            $mall_product_property[] = $info;
        }
    }
    else
    {
        $decodeproperty_type = array();
        $mall_product_property = array();
    }
}
else
{
    $decodeproperty_type = array();
    $mall_product_property = array();
}


global $wxsetting, $WX_APPID;


$query = XN_Query::create('YearContent_Count')->tag('mall_orders_products_' . $supplierid)
    ->filter('type', 'eic', 'mall_orders_products')
    //
    ->filter('my.deleted', '=', '0')
    ->filter('my.productid', '=', $productid)
    ->filter('my.tradestatus', '=', 'trade')
    ->rollup()
    ->end(-1);
$query->execute();
$tradecount = $query->getTotalCount();

$query = XN_Query::create('YearContent_Count')->tag('mall_appraises_' . $supplierid)
    ->filter('type', 'eic', 'mall_appraises')
    //
    ->filter('my.deleted', '=', '0')
    ->filter('my.productid', '=', $productid)
    ->rollup()
    ->end(-1);
$query->execute();
$appraisecount = $query->getTotalCount();

$mall_orders_products = XN_Query::create('YearContent')->tag('mall_orders_products_' . $supplierid)
    ->filter('type', 'eic', 'mall_orders_products')
    //
    ->filter('my.deleted', '=', '0')
    ->filter('my.productid', '=', $productid)
    ->filter('my.tradestatus', '=', 'trade')
    ->order("published", XN_Order::DESC)
    ->end(10)
    ->execute();
$transactionrecords = array();
$profileids = array();
foreach ($mall_orders_products as $mall_orders_product_info)
{
    $profileids[] = $mall_orders_product_info->my->profileid;
    $key = $mall_orders_product_info->id;
    $transactionrecords[$key]['profileid'] = $mall_orders_product_info->my->profileid;
    $transactionrecords[$key]['shop_price'] = number_format($mall_orders_product_info->my->shop_price, 2, ".", "");
    $transactionrecords[$key]['quantity'] = $mall_orders_product_info->my->quantity;
    $transactionrecords[$key]['published'] = date("Y-m-d H:i", strtotime($mall_orders_product_info->published));
}
$profiles = getGivenNameArrByids($profileids);

foreach ($transactionrecords as $key => $transactionrecord_info)
{
    $transactionprofileid = $transactionrecord_info['profileid'];
    if (isset($profiles[$transactionprofileid]) && $profiles[$transactionprofileid] != "")
    {
        $givenname = $profiles[$transactionprofileid];
        $transactionrecords[$key]['givenname'] = $givenname;
    }
}

$mall_appraises = XN_Query::create('YearContent')->tag('mall_appraises_' . $supplierid)
    ->filter('type', 'eic', 'mall_appraises')
    //
    ->filter('my.deleted', '=', '0')
    ->filter('my.productid', '=', $productid)
    ->order("published", XN_Order::DESC)
    ->end(10)
    ->execute();
$appraises = array();
$profileids = array();
foreach ($mall_appraises as $mall_appraise_info)
{
    $profileids[] = $mall_appraise_info->my->profileid;
    $key = $mall_appraise_info->id;
    $appraises[$key]['profileid'] = $mall_appraise_info->my->profileid;
    $appraises[$key]['remark'] = $mall_appraise_info->my->remark;
    $appraises[$key]['hasimages'] = $mall_appraise_info->my->hasimages;
    $appraises[$key]['images'] = $mall_appraise_info->my->images;
    $appraises[$key]['published'] = date("Y-m-d H:i", strtotime($mall_appraise_info->published));
    $images = $mall_appraise_info->my->images;
    $appraises[$key]['images'] = appraise_images($images);
    $praise = $mall_appraise_info->my->praise;
    $appraises[$key]['praise'] = $praise;
    if ($praise == '1')
    {
        $appraises[$key]['praise_info'] = '好评';
    }
    else if ($praise == '2')
    {
        $appraises[$key]['praise_info'] = '中评';
    }
    else if ($praise == '3')
    {
        $appraises[$key]['praise_info'] = '差评';
    }
    else
    {
        $appraises[$key]['praise_info'] = '好评';
    }
}
$profiles = getProfileInfoArrByids($profileids);

foreach ($appraises as $key => $appraise_info)
{
    $appraise_profileid = $appraise_info['profileid'];
    if (isset($profiles[$appraise_profileid]) && $profiles[$appraise_profileid] != "")
    {
        $profile_info = $profiles[$appraise_profileid];

        $appraises[$key]['givenname'] = $profile_info['givenname'];
        $appraises[$key]['headimgurl'] = $profile_info['headimgurl'];
    }
}


$smarty->assign("islogined", true);
$smarty->assign("profileid", $profileid);
$smarty->assign("headimgurl", $_SESSION['headimgurl']);
$smarty->assign("givenname", $_SESSION['givenname']);
$smarty->assign("productinfo", $productinfo);


$smarty->assign("tradecount", $tradecount);
$smarty->assign("appraisecount", $appraisecount);
$smarty->assign("transactionrecords", $transactionrecords);
$smarty->assign("appraises", $appraises);


$smarty->assign("property_type", $decodeproperty_type);

$property_count = count($decodeproperty_type);
if ($property_count > 0)
{
    $smarty->assign("property_type_count", $property_count);
    $smarty->assign("type", '');
}
else
{
    $smarty->assign("property_type_count", '0');
    $smarty->assign("type", 'submit');
}

$smarty->assign("propertys", json_encode($mall_product_property));

$panel = strtolower(basename(__FILE__, ".php"));
$smarty->assign("actionname", $panel);

$share_info = checkrecommend();


$request_uri = 'bargain.php?productid='.$productid.'&salesactivityid='.$salesactivityid;
$query_string = base64_encode($request_uri);  

$shareurl = 'http://' . $WX_DOMAIN . '/index.php?u=' . $profileid . '&sid=' . $supplierid . '&uri=' . $query_string;

$share_info['share_url'] = $shareurl;

$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
	$givenname = $profile_info['givenname'];  
    $share_info['share_title'] = "来帮我砍一下价呵！".$givenname."在此谢过！";
	$share_info['share_description'] = "【".$productinfo['productname']."】原价".$productinfo['shop_price'].",现在只需要".$productinfo['total_money']."元,一定要帮我点一下呵！";
}
else
{ 	
	$productname = str_replace('"', '', $productinfo['productname']);
	$share_info['share_title'] = $productname; 
}




if (isset($productthumbnail) && $productthumbnail != "")
{
    $share_info['share_logo'] = $productthumbnail;
}

$smarty->assign("share_info", $share_info);
$smarty->assign("supplier_info", get_supplier_info());

if ($profileid == "anonymous")
{
    $smarty->assign("mycollections", '0');
}
else
{
    $mycollections = XN_Query::create('Content')
        ->tag("mall_mycollections_" . $profileid)
        ->filter('type', 'eic', 'mall_mycollections')
        ->filter('my.deleted', '=', '0')
        ->filter('my.profileid', '=', $profileid)
        ->filter('my.productid', '=', $productid)
        ->filter('my.status', '=', '1')
        ->end(1)
        ->execute();

    if (count($mycollections) > 0)
    {
        $smarty->assign("mycollections", '1');
    }
    else
    {
        $smarty->assign("mycollections", '0');
    }
}

$sysinfo = array();
$sysinfo['action'] = 'index';
$sysinfo['date'] = date("md");
$sysinfo['api'] = $APISERVERADDRESS;
$sysinfo['http_user_agent'] = check_http_user_agent();
$sysinfo['webpath'] = $WEB_PATH;
$sysinfo['width'] = $_SESSION['width'];

$smarty->assign("sysinfo", $sysinfo);

if (isset($_REQUEST['scrolltop']) && $_REQUEST['scrolltop'] > 0)
{
    $smarty->assign("scrolltop", $_REQUEST['scrolltop']);
}
$smarty->assign("pagenum", $_REQUEST['pagenum']);
$smarty->assign("from", $_REQUEST['from']);
 
$smarty->display('detail_bargain.tpl');
 

function appraise_images($images)
{
    global $APISERVERADDRESS;
    $images = (array)$images;
    $newimages = array();
    foreach ($images as $image_info)
    {
        if (isset($image_info) && $image_info != "")
        {
            $width = 320;
            $image_info = $APISERVERADDRESS . $image_info . "?width=" . $width;
            $newimages[] = $image_info;
        }
    }
    return $newimages;
}

function replace_star($string)
{
    $count = mb_strlen($string, 'UTF-8'); //此处传入编码，建议使用utf-8。此处编码要与下面mb_substr()所使用的一致
    if (!$count)
    {
        return $string;
    }
    $start = 1;
    $end = $count;
    if ($count > 2)
    {
        $end = $count - 1;
    }
    if ($count > 7)
    {
        $start = 2;
        $end = $count - 2;
    }

    $i = 0;
    $returnString = '';
    while ($i < $count)
    {
        $tmpString = mb_substr($string, $i, 1, 'UTF-8'); // 与mb_strlen编码一致
        if ($start <= $i && $i < $end)
        {
            $returnString .= '*';
        }
        else
        {
            $returnString .= $tmpString;
        }
        $i++;
    }
    return $returnString;
}

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

function getGivenNameArrByids($ids)
{
    if (count($ids) == 0) return array();
    $infos = XN_Profile::loadMany($ids, "id", "profile");
    $givenNames = array();
    foreach ($infos as $info)
    {    
        if ($info->type == "unsubscribe")
        {
	        $givenNames[$info->profileid] = '匿名';
        }
        else
        {
	        $givenname = $info->givenname;
	        if ($givenname == "")
	        {
	            $givenname = '匿名';
	        }
	        $givenname = replace_star($givenname);
	        $givenNames[$info->profileid] = $givenname;
        }
        
    }
    return $givenNames;
}

?>