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
if(isset($_REQUEST['type']) && $_REQUEST['type'] !='')
{
	$type = $_REQUEST['type'];  
}
else
{
	echo '{"code":201,"data":[]}';
	die(); 
}
 
 

try{
	$orders = mall_orders($profileid,$supplierid,$page,$type);
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
 

function  mall_orders($profileid,$supplierid,$page,$type='trade') 
{
	if ($type == 'trade')
	{
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid) 
					->filter ( 'my.tradestatus', '=', 'trade')
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
	}
	else if ($type == 'pendingpayment')
	{
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid) 
					->filter ( 'my.tradestatus', '=', 'notrade')
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
	} 
	else if ($type == 'sendout')
	{
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid) 
					->filter ( 'my.tradestatus', '=', 'trade')
					->filter ( 'my.order_status', '=', '已付款') 
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
	}
	else if ($type == 'receipt')
	{
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid) 
					->filter ( 'my.tradestatus', '=', 'trade')
					->filter ( 'my.confirmreceipt', '!=', 'receipt') 
					->filter ( 'my.deliverystatus', '=', 'sendout')
					->filter ( 'my.aftersaleservicestatus', '!=', 'yes') 
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
	}
	else if ($type == 'appraise')
	{
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid) 
					->filter ( 'my.tradestatus', '=', 'trade') 
					->filter ( 'my.confirmreceipt', '=', 'receipt')   
					->filter ( 'my.appraisestatus', '=', 'no') 
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
	}
	else if ($type == 'aftersaleservice')
	{
		$orders = XN_Query::create ( 'YearContent' )->tag('mall_orders_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid)  
					->filter ( 'my.tradestatus', '=', 'trade')   
					->filter ( 'my.aftersaleservicestatus', '=', 'yes') 
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*5)
					->end($page*5)
					->execute ();
	}
	else
	{
		return array();
	}
	

	$orderlist = array();
	if (count($orders) > 0)
	{  
		$orderids = array();
		foreach($orders as $order_info)
		{ 
			$orderids[] = $order_info->id;
		}

		$orders_products = XN_Query::create ( 'YearContent' )->tag('mall_orders_products_'.$profileid)
					->filter ( 'type', 'eic', 'mall_orders_products') 
					->filter ( 'my.orderid', 'in', $orderids) 
					->end(-1)
					->execute (); 
	
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
			$courierid = $order_info->my->courierid; 
			if (isset($courierid) && $courierid != "")
			{
		        $courier_info = XN_Content::load($courierid,"mall_couriers_".$supplierid); 
				$orderlist[$id]['courier'] = $courier_info->my->couriername."【".$courier_info->my->mobile."】"; 
			}
			else
			{ 
				$orderlist[$id]['courier'] = ""; 
			}  
			
			$deliveryer = $order_info->my->deliveryer;
			if (isset($deliveryer) && $deliveryer != "")
			{
				$orderlist[$id]['deliveryer'] = get_profile_givenname($deliveryer);
			}
			else
			{ 
				$orderlist[$id]['deliveryer'] = ''; 
			}
			
			$orderlist[$id]['products'] = array(); 
			
	 
			foreach($orders_products as $orders_product_info)
			{ 
				$orders_product_id = $orders_product_info->id;
				$productid = $orders_product_info->my->productid;  
				$productthumbnail = $orders_product_info->my->productthumbnail;  
				$loop_orderid = $orders_product_info->my->orderid; 
				if (!isset($orderlist[$id]['thumbnail']) && $loop_orderid == $id) 
				{
					$orderlist[$id]['thumbnail'] = $productthumbnail;
				}
				$orderlist[$id]['products'][$orders_product_id]['productid'] = $productid; 
				$orderlist[$id]['products'][$orders_product_id]['productname'] = $orders_product_info->my->productname; 
				$orderlist[$id]['products'][$orders_product_id]['productthumbnail'] = $orders_product_info->my->productthumbnail;
				$orderlist[$id]['products'][$orders_product_id]['quantity'] = $orders_product_info->my->quantity; 
				$orderlist[$id]['products'][$orders_product_id]['shop_price'] = $orders_product_info->my->shop_price; 
				$orderlist[$id]['products'][$orders_product_id]['market_price'] = $orders_product_info->my->market_price; 
				$orderlist[$id]['products'][$orders_product_id]['propertydesc'] = $orders_product_info->my->propertydesc; 
				$orderlist[$id]['products'][$orders_product_id]['product_property_id'] = $orders_product_info->my->product_property_id; 
				$orderlist[$id]['products'][$orders_product_id]['total_price'] = $orders_product_info->my->total_price; 
  				 
			}
		} 
	}
	rsort($orderlist);
    return $orderlist; 
}
 
?>