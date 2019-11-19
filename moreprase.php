<?php
/**
 * Created by PhpStorm.
 * User: wangzhenming
 * Date: 17/2/21
 * Time: 下午5:47
 */
session_start();
require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");

if (isset($_REQUEST['productid']) && $_REQUEST['productid'] != '')
{
    $productid = $_REQUEST['productid'];
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
$cur_page=$_REQUEST['cur_page'];
$limit_start=$cur_page*10;
$limit_end=($cur_page+1)*10;
$mall_appraises = XN_Query::create('YearContent')->tag('mall_appraises_' . $supplierid)
    ->filter('type', 'eic', 'mall_appraises')
    //
    ->filter('my.deleted', '=', '0')
    ->filter('my.productid', '=', $productid)
    ->order("published", XN_Order::DESC)
    ->begin($limit_start)
    ->end($limit_end)
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
    $appraises[$key]['active_praise'] = $praise;
    $appraises[$key]['unactive_praise'] = intval(5-$praise);
    $order_id=$mall_appraise_info->my->orderid;
    $orders_products=XN_Query::create("YearContent")
        ->tag("mall_orders_products")
        ->filter('type', 'eic', 'mall_orders_products')
        ->filter('my.deleted', '=', '0')
        ->filter('my.orderid','=',$order_id)
        ->filter('my.productid','=',$productid)
        ->end(1)
        ->execute();
    if(count($orders_products)){
        $order_product_info=$orders_products[0];
        if($order_product_info->my->propertydesc!=''){
            $appraises[$key]['propertydesc'] = str_replace("  ","",trim($order_product_info->my->propertydesc));
        }
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

$html='';
foreach($appraises as $appraises_info){
    $html.='<li class="clearfix reviewlist" style="padding: 8px 5px;background: #fff;">
                <div class="reviewlist" style="width:100%;">
                    <ul class="star" style="display:block;width:79%;float:left;height:30px;line-height:30px;margin-left:1%;">';
                        for($i=0;$i<$appraises_info["active_praise"];$i++){
                            $html.='<li class="active" style="width: 13px;height: 13px;display: block;float: left;margin-right: 5px;"></li>';
                        }
                        for($i=0;$i<$appraises_info["unactive_praise"];$i++){
                            $html.='<li style="width: 13px;height: 13px;display: block;float: left;margin-right: 5px;"></li>';
                        }
            $html.='</ul>
                    <div style="display:block;width:20%;float:left;text-align: right;">'.$appraises_info['published'].'</div>
                </div>
                <div class="reviewlistbox clearfix mt-10 reviewlist" style="width:100%;">
                    <div style="max-height:50px;line-height: 1;width:60%;text-align: left;margin-left:1%;">'.$appraises_info["remark"].'</div>
                    <div style="width:14%;margin-left:5%;display:block;float:left;min-height:25px;">
                        <ul>
                            <li><span class="black">'.$appraises_info["propertydesc"].'</span></li>
                        </ul>
                    </div>
                    <div class="mui-media-body reviewlist" style="width:20%;display:block;float:left;text-align: right;">
                        <p><img class="mui-media-object mui-pull-left" style="width:20px;height:20px;" src="'.$appraises_info["headimgurl"].'"><strong>'.$appraises_info["givenname"].'</strong></p>
                    </div>
                </div>';
            if($appraises_info["hasimages"]>0){
                $html.='<div class="mui-media-body reviewlist" style="width:100%;">
                            <div class="imgshow pull-left">
                                <ul class="poto-sma  mb-10 mt-10 clearfix">';
                            foreach($appraises_info["images"] as $index=>$appraise_image_info){
                                if($index==0){
                                    $html.='<li onclick="imgView(this);" class="active" style="width:auto;"><img class="mui-media-object mui-pull-left" src="'.$appraise_image_info.'" width="105" height="75"></li>';
                                }
                                else{
                                    $html.='<li onclick="imgView(this);" style="width:auto;"><img class="mui-media-object mui-pull-left" src="'.$appraise_image_info.'" width="105" height="75"></li>';
                                }
                            }
                $html.='</ul>
                        <div class="poto-big border">
                            <ul class="poto-big-img">';
                            foreach($appraises_info["images"] as $index=>$appraise_image_info){
                                if($index==0){
                                    $html.='<li class="active"><img src="'.$appraise_image_info.'" width="505" height="360"></li>';
                                }
                                else{
                                    $html.='<li><img src="'.$appraise_image_info.'" width="505" height="360"></li>';
                                }
                            }
                    $html.='</ul>
                        </div>
                    </div>
                </div>';
                }
        $html.='</li>';

}
echo $html;

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

function getProfileInfoArrByids($ids)
{
    if (count($ids) == 0) return array();
    $infos = XN_Profile::loadMany($ids, "id", "profile");
    $givenNames = array();
    foreach ($infos as $info)
    {
        $givenname = $info->givenname;

        if ($givenname == "")
        {
            $fullName = $info->fullName;

            if (preg_match('.[#].', $fullName))
            {
                $fullNames = explode('#', $fullName);
                $fullName = $fullNames[0];
            }
            $givenname = $fullName;
        }
        $givenname = replace_star($givenname);
        $headimgurl = $info->link;
        if ($headimgurl == "")
        {
            $headimgurl = 'images/user.jpg';
        }
        $givenNames[$info->profileid] = array('givenname' => $givenname, 'headimgurl' => $headimgurl);
    }
    return $givenNames;
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