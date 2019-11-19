<?php 
 

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
	$profileid = "anonymous";
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

 
	
if(isset($_REQUEST['page']) && $_REQUEST['page'] !='')
{
	$page = $_REQUEST['page'];  
}
else
{
	echo '{"code":201,"data":[]}';
	die(); 
}
 
 

try{
	$orders = mall_usages_details($profileid,$supplierid,$page);
	if (count($orders) > 0)
	{
		echo '{"code":200,"data":'.json_encode($orders).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":202,"data":[]}'; 
die();  
 
  


function  mall_usages_details($profileid,$supplierid,$page) 
{ 
	$mall_usages_details = XN_Query::create ( 'YearContent' )->tag('mall_usages_details_'.$profileid)
				->filter ( 'type', 'eic', 'mall_usages_details') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*10)
				->end($page*10)
				->execute ();

	$usagesdetaillist = array();
	if (count($mall_usages_details) > 0)
	{   
		$orderids = array(); 
		$vipcardids = array(); 
		foreach($mall_usages_details as $mall_usages_detail_info)
		{ 
			$orderids[] = $mall_usages_detail_info->my->orderid;
			$vipcardids[] = $mall_usages_detail_info->my->vipcardid;
		}
		$orders = XN_Content::load($orderids,"mall_orders_".$profileid,7);
		$orderinfos = array(); 
		foreach($orders as $order_info)
		{
			$orderinfos[$order_info->id] = $order_info->my->mall_orders_no;
		}
		$vipcards = XN_Content::load($vipcardids,"mall_vipcards_".$profileid);
		$vipcardinfos = array(); 
		foreach($vipcards as $vipcard_info)
		{
			$vipcardinfos[$vipcard_info->id] = $vipcard_info->my->vipcardname;
		}
		foreach($mall_usages_details as $mall_usages_detail_info)
		{ 
			$id = $mall_usages_detail_info->id;
			$orderid = $mall_usages_detail_info->my->orderid;
			$vipcardid = $mall_usages_detail_info->my->vipcardid;
			$discount = $mall_usages_detail_info->my->discount;  
			$sumorderstotal =  $mall_usages_detail_info->my->sumorderstotal;
			$published = $mall_usages_detail_info->published;   
			$usagesdetaillist[$id]['order_no'] = $orderinfos[$orderid]; 
			$usagesdetaillist[$id]['vipcardname'] = $vipcardinfos[$vipcardid];
		    $usagesdetaillist[$id]['discount'] = number_format(floatval($discount),2,".",""); 
		    $usagesdetaillist[$id]['sumorderstotal'] = number_format(floatval($sumorderstotal),2,".","");  
		    $usagesdetaillist[$id]['published'] =  date("Y-m-d H:i",strtotime($published));  
		} 
	}
	rsort($usagesdetaillist);
    return $usagesdetaillist; 
}
 
?>