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
	$logistictrips = mall_logistictrips($profileid,$supplierid,$page);
	if (count($logistictrips) > 0)
	{
		echo '{"code":200,"data":'.json_encode($logistictrips).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":200,"data":[]}';
die();  
 

function  mall_logistictrips($profileid,$supplierid,$page) 
{
	 
	$logistictrips = XN_Query::create ( 'YearContent' )->tag('mall_logistictrips_'.$supplierid)
				->filter ( 'type', 'eic', 'mall_logistictrips') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*5)
				->end($page*5)
				->execute ();
 
	

	$orderlist = array();
	if (count($logistictrips) > 0)
	{   
	    $pos = 1;
		foreach($logistictrips as $logistictrip_info)
		{ 
			$logistictripid = $logistictrip_info->id;
			$orderlist[$pos]['id'] = $logistictrip_info->id;
		    $orderlist[$pos]['mall_logistictrips_no'] = $logistictrip_info->my->mall_logistictrips_no; 
		    $orderlist[$pos]['driverid'] = $logistictrip_info->my->driverid; 
		    $orderlist[$pos]['enddate'] = $logistictrip_info->my->enddate; 
		    $orderlist[$pos]['startdate'] = $logistictrip_info->my->startdate; 
		    $orderlist[$pos]['swaptime'] = $logistictrip_info->my->swaptime; 
		    $orderlist[$pos]['billcount'] = $logistictrip_info->my->billcount;   
			$orderlist[$pos]['published'] = $logistictrip_info->published;   
			$mall_logistictripsstatus = $logistictrip_info->my->mall_logistictripsstatus; 			 
			if ($mall_logistictripsstatus == "JustCreated")
			{
				$orderlist[$pos]['mall_logistictripsstatus'] = "刚刚创建";
			} 
			else if ($mall_logistictripsstatus == "Packing")
			{
				$$orderlist[$pos]['mall_logistictripsstatus'] = "正在装箱";
			} 
			else if ($mall_logistictripsstatus == "Start")
			{
				$$orderlist[$pos]['mall_logistictripsstatus'] = "在途";
			} 
			else if ($mall_logistictripsstatus == "End")
			{
				$orderlist[$pos]['mall_logistictripsstatus'] = "已经到达";
			} 
			$logisticpackageid  = $logistictrip_info->my->logisticpackageid;
			$orderlist[$pos]['logisticpackageid'] = $logisticpackageid;
			if (isset($logisticpackageid) && $logisticpackageid != "")
			{
				$logisticpackage_info = XN_Content::load($logisticpackageid,"mall_logisticpackages_".$supplierid);
				$orderlist[$pos]['serialname'] = $logisticpackage_info->my->serialname;
			}
			else
			{
				$orderlist[$pos]['serialname'] = "";
			} 
			$mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logisticbills')
			    ->filter('my.deleted', '=', '0') 
			    ->filter('my.supplierid', '=', $supplierid)    
				->filter('my.logistictripid', '=', $logistictripid)    
			    ->filter('my.logisticpackageid', '=', $logisticpackageid) 
			    ->end(-1)
			    ->execute(); 
			 
			$bills = array();
			foreach($mall_logisticbills as $mall_logisticbill_info)
			{   
				$bills[] = $mall_logisticbill_info->my->logisticbills_no;  
			}  
			$orderlist[$pos]['bills'] = $bills;
			$pos++; 
		} 
	}
	rsort($orderlist);
    return $orderlist; 
}
 
?>