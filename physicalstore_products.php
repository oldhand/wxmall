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
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
}

function math_ticheng($distributionmode, $distributionrate, $level, $ticheng)
{
    if (!isset($distributionrate) || $distributionrate == "")
    {
        $distributionrate = '111';
    }
    $newticheng = 0;
    if ($distributionmode == '2')
    {
        switch ($distributionrate)
        {
            case "111":
                $newticheng = $ticheng / 2;
                break;
            case "321":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 5 * 3;
                }
                else
                {
                    $newticheng = $ticheng / 5 * 2;
                }
                break;
            case "211":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 3 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 3;
                }
                break;
            case "311":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 4 * 3;
                }
                else
                {
                    $newticheng = $ticheng / 4;
                }
                break;
            case "221":
                $newticheng = $ticheng / 2;
                break;
            case "421":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 3 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 3;
                }
                break;
            case "345":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 7 * 3 ;
                }
                else
                {
                    $newticheng = $ticheng / 7 * 4 ;
                }
                break;
   	         case "1564":
   	                if ($level == 1)
   	                {
   	                    $newticheng = $ticheng / 21 * 15 ;
   	                }
   	                else if ($level == 2)
   	                {
   	                    $newticheng = $ticheng / 21 * 6 ;
   	                } 
   	          break;
        }
    }
    else if ($distributionmode == '3')
    {
        switch ($distributionrate)
        {
            case "111":
                $newticheng = $ticheng / 3;
                break;
            case "321":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 6 * 3;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 6 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 6;
                }
                break;
            case "211":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 4 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 4;
                }
                break;
            case "311":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 5 * 3;
                }
                else
                {
                    $newticheng = $ticheng / 5;
                }
                break;
            case "221":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 5 * 2;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 5 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 5;
                }
                break;
            case "421":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 7 * 4;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 7 * 2;
                }
                else
                {
                    $newticheng = $ticheng / 7;
                }
                break;
            case "345":
                if ($level == 1)
                {
                    $newticheng = $ticheng / 4 ;
                }
                else if ($level == 2)
                {
                    $newticheng = $ticheng / 3  ;
                }
                else
                {
                    $newticheng = $ticheng / 12 * 5 ;
                }
                break;
	         case "1564":
	                if ($level == 1)
	                {
	                    $newticheng = $ticheng / 25 * 15 ;
	                }
	                else if ($level == 2)
	                {
	                    $newticheng = $ticheng / 25 * 6 ;
	                }
	                else
	                {
	                    $newticheng = $ticheng / 25 * 4 ;
	                }
	          break;
        }
    }
    return  number_format($newticheng, 2, ".", "");
}

function  memberrate($supplierid,$shop_price,$memberrate) 
{
	$supplierinfo = get_supplier_info($supplierid);
	
    if (floatval($shop_price) > 0 && floatval($memberrate) > 0)
	{
        $distributionmode = $supplierinfo['distributionmode'];  // 1 =>  1级分销', 2 =>  '2级分销',3 =>  '3级分销',  0 => '无分销'  
        $distributionrate = $supplierinfo['distributionrate']; // 分配比率
		$distributionobject = $supplierinfo['distributionobject']; // 分配对象
 
		if (isset($distributionobject) && $distributionobject == "1")
		{
			$newmemberrate =  math_ticheng($distributionmode, $distributionrate, 1, $memberrate);
		}
		else
		{
			$newmemberrate =  math_ticheng($distributionmode, $distributionrate, 2, $memberrate); 
		}
		return $newmemberrate;
	}
	return 0;
}
 
if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'ajax')
{
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != '')
	{
		$page = $_REQUEST['page'];  
		$supplier_info = get_supplier_info();
		$isphysicalstore = '0';
		$isassistant = '0';
		if (isset($supplier_info['allowphysicalstore']) && $supplier_info['allowphysicalstore'] == '0')
		{
			$supplier_physicalstoreprofiles = XN_Query::create ( 'Content' ) 
			    ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
				->filter ( 'my.supplierid', '=',$supplierid)
				->filter ( 'my.profileid', '=',$profileid)
			    ->filter ( 'my.deleted', '=', '0' )
				->end(1)
			    ->execute ();
			if (count($supplier_physicalstoreprofiles) > 0)
			{
				$supplier_physicalstoreprofile_info = $supplier_physicalstoreprofiles[0];
				$physicalstoreid = $supplier_physicalstoreprofile_info->my->physicalstoreid;
				$assistantprofileid = $supplier_physicalstoreprofile_info->my->assistantprofileid;
				$storeprofileid = $supplier_physicalstoreprofile_info->my->storeprofileid;  
				
			
				if ($storeprofileid == $profileid)
				{
					$isphysicalstore = '1';
					$physicalstore_info = XN_Content::load($physicalstoreid,"supplier_physicalstores_".$supplierid);
					$bonusrate = $physicalstore_info->my->bonusrate; 
				} 
				if ($assistantprofileid == $profileid)
				{
					$isassistant = '1';
					$supplier_physicalstoreassistants = XN_Query::create ( 'Content' )->tag("supplier_physicalstoreassistants_".$profileid)
					    ->filter ( 'type', 'eic', 'supplier_physicalstoreassistants') 
						->filter ( 'my.supplierid', '=',$supplierid)
						->filter ( 'my.profileid', '=', $assistantprofileid)
					    ->filter ( 'my.deleted', '=', '0' )
						->end(1)
					    ->execute ();
					if (count($supplier_physicalstoreassistants) > 0)
					{
						$supplier_physicalstoreassistant_info = $supplier_physicalstoreassistants[0];
						$bonusrate = $supplier_physicalstoreassistant_info->my->bonusrate; 
					}
				} 
			}
		}
		$mall_products = XN_Query::create ( 'Content' )->tag('mall_products')
					->filter ( 'type', 'eic', 'mall_products') 
					->filter ( 'my.deleted', '=', '0')
					->filter ( 'my.hitshelf', '=', 'on') 
					->filter ( 'my.supplierid', '=', $supplierid)   
					->filter ( 'my.commissionswitch', '=', '0') 
					->filter ( 'my.memberrate', '>', 0) 
					->order("my.memberrate",XN_Order::DESC_NUMBER) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
		
		$productinfos = array();
		$index = 0;
		global $APISERVERADDRESS; 
		foreach($mall_products as $product_info)
		{   
			$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=320";
			$productid = $product_info->id;
			$brandid = $product_info->my->brand; 
			$productinfos[$index]['productid'] = $productid;  
			$productinfos[$index]['productlogo'] = $productlogo; 
			$productinfos[$index]['keywords'] = $product_info->my->keywords; 
			$productinfos[$index]['market_price'] = number_format($product_info->my->market_price,2,".",""); 
			$productinfos[$index]['shop_price'] = number_format($product_info->my->shop_price,2,".","");   
			$productinfos[$index]['productname'] = $product_info->my->productname; 
			$memberrate = $product_info->my->memberrate;
			$shop_price = $product_info->my->shop_price;
			$productinfos[$index]['memberrate'] = $memberrate;
			$productinfos[$index]['description'] = $product_info->my->description; 
			$productinfos[$index]['simple_desc'] = $product_info->my->simple_desc; 
			$productinfos[$index]['product_weight'] = $product_info->my->product_weight; 
			$productinfos[$index]['weight_unit'] = $product_info->my->weight_unit; 
			$productinfos[$index]['brand'] = $product_info->my->brand; 
			$productinfos[$index]['categorys'] = $product_info->my->categorys; 
			$productinfos[$index]['supplierid'] = $product_info->my->supplierid; 
			$productinfos[$index]['salevolume'] = $product_info->my->salevolume;  
			$productinfos[$index]['isphysicalstore'] = $isphysicalstore;  
			$productinfos[$index]['isassistant'] = $isassistant;  
			
			$newmemberrate = memberrate($supplierid,$shop_price,$memberrate); 
			$anticipatedincome =  number_format(floatval($newmemberrate)*floatval($shop_price)/100, 2, ".", "");
			$productinfos[$index]['finishedmemberrate'] = $newmemberrate;  
			$productinfos[$index]['anticipatedincome'] = $anticipatedincome;  
			
			$index = $index + 1; 
		}  
		
		rsort($productinfos); 
		echo '{"code":200,"length":'.count($productinfos).',"data":'.json_encode($productinfos).'}'; 
		die(); 
	}
	else
	{
		echo '{"code":201,"length":0,"data":[]}';
		die(); 
	}
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
$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
	$smarty->assign("profile_info",$profile_info);
}
else
{
	$smarty->assign("profile_info",get_profile_info());
} 

$sysinfo = array();
$sysinfo['action'] = 'promotioncenter'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>