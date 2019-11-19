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


function getGivenName($profileid)
{
	global $givennames; 
	if (!isset($givennames))
	{
		$givennames = array();
	} 
	 
	if (isset($givennames[$profileid]) && $givennames[$profileid] != "")
	{
		return $givennames[$profileid];
	}
	else
	{
        try
        {
			$profile_info      = XN_Profile::load($profileid,"profile");
			$givenname = $profile_info->givenname;
			if ($givenname == "")
			{
				$fullName = $profile_info->fullName;
				if (preg_match('.[#].', $fullName))
				{
					$fullNames = explode('#', $fullName);
					$fullName  = $fullNames[0];
				}
				$givenname = $fullName;
			}
			$givenname .= strip_tags($givenname).',';
			$givennames[$profileid] = $givenname;
			return $givennames[$profileid];
		}
        catch (XN_Exception $e)
        {
			
        }
	}  
	return "";
}
//获得产品名称
function get_product_name($productid)
{
	global $cache_productinfos; 
	if (!isset($cache_productinfos))
	{
		$cache_productinfos = array();
	}  
	if (isset($cache_productinfos[$productid]) && $cache_productinfos[$productid] != "")
	{
		return $cache_productinfos[$productid];
	}  
    try
    {
        $product_info = XN_Content::load($productid,'mall_products');
		$productname = $product_info->my->productname;
		$cache_productinfos[$productid] = $productname;
        return $productname;
    }
    catch (XN_Exception $e)
    {
    } 
	return "";
}

if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'ajax')
{
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != '')
	{
		$page = $_REQUEST['page'];  
		
		$mall_commissions = XN_Query::create ( 'YearContent' )->tag('mall_commissions_'.$profileid)
					->filter ( 'type', 'eic', 'mall_commissions') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
			  	    ->filter( 'my.profileid','=',$profileid) 	  
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
		$commissionlist = array();
		if (count($mall_commissions) > 0)
		{  
			foreach($mall_commissions as $commission_info)
			{
				$orderid = $commission_info->my->orderid; 
				$order_info = XN_Content::load($orderid,'mall_orders_'.$supplierid,7);
	   			$id = $commission_info->id;
	   			$commissionlist[$id]['orderid'] = $id; 
	   		    $commissionlist[$id]['address'] = $order_info->my->address; 
	   		    $commissionlist[$id]['city'] = $order_info->my->city; 
	   		    $commissionlist[$id]['consignee'] = $order_info->my->consignee; 
	   		    $commissionlist[$id]['mall_orders_no'] = $order_info->my->mall_orders_no; 
	   		    $commissionlist[$id]['province'] = $order_info->my->province; 
	   		    $commissionlist[$id]['ordername'] = $order_info->my->ordername; 
	   			$commissionlist[$id]['sumorderstotal'] = $order_info->my->sumorderstotal;
	   			$commissionlist[$id]['productcount'] = $order_info->my->productcount;  
	   			$commissionlist[$id]['tradestatus'] = $order_info->my->tradestatus; 
	   			$commissionlist[$id]['order_status'] = $order_info->my->order_status;  
	   			$commissionlist[$id]['deliverystatus'] = $order_info->my->deliverystatus; 
	   			$commissionlist[$id]['confirmreceipt'] = $order_info->my->confirmreceipt; 
	   			$commissionlist[$id]['appraisestatus'] = $order_info->my->appraisestatus;
	   			$commissionlist[$id]['aftersaleservicestatus'] = $order_info->my->aftersaleservicestatus;
	   			$commissionlist[$id]['returnedgoodsapply'] = $order_info->my->returnedgoodsapply; 
	   			$commissionlist[$id]['returnedgoodsstatus'] = $order_info->my->returnedgoodsstatus;   
	   			$commissionlist[$id]['delivery_status'] = $order_info->my->delivery_status; 
	   			$commissionlist[$id]['deliverytime'] = $order_info->my->deliverytime; 
				$buyer = $order_info->my->profileid; 
				$commissionlist[$id]['profileid'] = $buyer; 
				$profile_info = get_profile_info($buyer); 
				$commissionlist[$id]['profileid_givenname'] = $profile_info['givenname']; 
				$commissionlist[$id]['profileid_mobile'] = $profile_info['mobile']; 
				$commissionlist[$id]['profileid_headimgurl'] = $profile_info['headimgurl'];   
		 
				$commissionlist[$id]['middleman'] = $commission_info->my->middleman;
				$commissionlist[$id]['consumer'] = $commission_info->my->consumer;
				$commissionlist[$id]['middleman'] = $commission_info->my->middleman;
				$commissionlist[$id]['distributionmode'] = $commission_info->my->distributionmode;
			    $commissionlist[$id]['amount'] = $commission_info->my->amount;
				$commissionlist[$id]['quantity'] = $commission_info->my->quantity;
				$commissionlist[$id]['totalprice'] = $commission_info->my->totalprice;
				$commissionlist[$id]['royaltyrate'] = $commission_info->my->royaltyrate;
				$productid = $commission_info->my->productid;
				$commissionlist[$id]['productid'] = $productid;
				$commissionlist[$id]['productname'] = get_product_name($productid);
				$commissionsource = $commission_info->my->commissionsource; 
				switch($commissionsource)
				{
					case "0":
						$commissionlist[$id]['commissionsource'] = "会员";
					break;
					case "1":
						$commissionlist[$id]['commissionsource'] = "店铺";
					break;
					case "2":
						$commissionlist[$id]['commissionsource'] = "店员";
					break;
				}  
				$commissiontype = $commission_info->my->commissiontype; 
				switch($commissiontype)
				{
					case "0":
						$commissionlist[$id]['commissiontype'] = "冻结";
					break;
					case "1":
						$commissionlist[$id]['commissiontype'] = "已结算";
					break;
					case "2":
						$commissionlist[$id]['commissiontype'] = "已退货";
					break;
				}   
				 
			}
			
		}
		
		
		rsort($commissionlist); 
		echo '{"code":200,"length":'.count($commissionlist).',"data":'.json_encode($commissionlist).'}'; 
		die(); 
	}
	else
	{
		echo '{"code":201,"data":[]}';
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