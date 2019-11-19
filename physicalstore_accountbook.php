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
		
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
			  	    ->filter( 'my.profileid','!=',$profileid) 	
					->filter( 'my.orderssources','=',$profileid) 
					->filter ( 'my.tradestatus', '=', 'trade')
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
		$orderlist = array();
		if (count($orders) > 0)
		{  
			$orderids = array();
			foreach($orders as $order_info)
			{ 
				$orderids[] = $order_info->id;
			}
		    $mall_commissions = XN_Query::create('YearContent')->tag('mall_commissions_'.$profileid)
			   		->filter('type','eic','mall_commissions')
			   		->filter('my.deleted','=','0') 
		            ->filter('my.supplierid','=',$supplierid) 
					->filter('my.profileid','=',$profileid) 	
		   		    ->filter('my.orderid','in',$orderids) 	 
			   		->end(-1)
			   		->execute();
			
			
		   foreach($orders as $order_info)
		   {
	   			$id = $order_info->id;
	   			$orderlist[$id]['orderid'] = $id; 
	   		    $orderlist[$id]['address'] = $order_info->my->address; 
	   		    $orderlist[$id]['city'] = $order_info->my->city; 
	   		    $orderlist[$id]['consignee'] = $order_info->my->consignee; 
	   		    $orderlist[$id]['mall_orders_no'] = $order_info->my->mall_orders_no; 
	   		    $orderlist[$id]['province'] = $order_info->my->province; 
	   		    $orderlist[$id]['ordername'] = $order_info->my->ordername; 
	   			$orderlist[$id]['sumorderstotal'] = $order_info->my->sumorderstotal;
	   			$orderlist[$id]['productcount'] = $order_info->my->productcount;  
	   			$orderlist[$id]['tradestatus'] = $order_info->my->tradestatus; 
	   			$orderlist[$id]['order_status'] = $order_info->my->order_status;  
	   			$orderlist[$id]['deliverystatus'] = $order_info->my->deliverystatus; 
	   			$orderlist[$id]['confirmreceipt'] = $order_info->my->confirmreceipt; 
	   			$orderlist[$id]['appraisestatus'] = $order_info->my->appraisestatus;
	   			$orderlist[$id]['aftersaleservicestatus'] = $order_info->my->aftersaleservicestatus;
	   			$orderlist[$id]['returnedgoodsapply'] = $order_info->my->returnedgoodsapply; 
	   			$orderlist[$id]['returnedgoodsstatus'] = $order_info->my->returnedgoodsstatus;  
			
	   			$orderlist[$id]['delivery_status'] = $order_info->my->delivery_status; 
	   			$orderlist[$id]['deliverytime'] = $order_info->my->deliverytime; 
				$buyer = $order_info->my->profileid; 
				$orderlist[$id]['profileid'] = $buyer; 
				$profile_info = get_profile_info($buyer); 
				$orderlist[$id]['profileid_givenname'] = $profile_info['givenname']; 
				$orderlist[$id]['profileid_mobile'] = $profile_info['mobile']; 
				$orderlist[$id]['profileid_headimgurl'] = $profile_info['headimgurl']; 
				
				
				$commissions = array();
				foreach($mall_commissions as $commission_info )
				{
					if ($commission_info->my->orderid == $id)
					{
						$commissionid = $commission_info->id;
						$commissions[$commissionid]['middleman'] = $commission_info->my->middleman;
						$commissions[$commissionid]['consumer'] = $commission_info->my->consumer;
						$commissions[$commissionid]['middleman'] = $commission_info->my->middleman;
						$commissions[$commissionid]['distributionmode'] = $commission_info->my->distributionmode;
						$commissions[$commissionid]['amount'] = $commission_info->my->amount;
						$commissions[$commissionid]['quantity'] = $commission_info->my->quantity;
						$commissions[$commissionid]['totalprice'] = $commission_info->my->totalprice;
						$commissions[$commissionid]['royaltyrate'] = $commission_info->my->royaltyrate;
						$productid = $commission_info->my->productid;
						$commissions[$commissionid]['productid'] = $productid;
						$commissions[$commissionid]['productname'] = get_product_name($productid);
						$commissionsource = $commission_info->my->commissionsource; 
						switch($commissionsource)
						{
							case "0":
								$commissions[$commissionid]['commissionsource'] = "会员";
							break;
							case "1":
								$commissions[$commissionid]['commissionsource'] = "店铺";
							break;
							case "2":
								$commissions[$commissionid]['commissionsource'] = "店员";
							break;
						}  
						$commissiontype = $commission_info->my->commissiontype; 
						switch($commissiontype)
						{
							case "0":
								$commissions[$commissionid]['commissiontype'] = "冻结";
							break;
							case "1":
								$commissions[$commissionid]['commissiontype'] = "已结算";
							break;
							case "2":
								$commissions[$commissionid]['commissiontype'] = "已退货";
							break;
						}  
					}
				}
				$orderlist[$id]['commissions'] = $commissions; 
				
				
		   } 
		}
		
		
		rsort($orderlist); 
		echo '{"code":200,"length":'.count($orderlist).',"data":'.json_encode($orderlist).'}'; 
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