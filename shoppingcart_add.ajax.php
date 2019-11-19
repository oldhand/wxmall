<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
	$loginprofileid = $_SESSION['profileid']; 
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
	$loginprofileid = $_SESSION['accessprofileid']; 
}
else
{
	$loginprofileid = "anonymous";
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
 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$productid = $_REQUEST['record'];  
}
else
{
	echo '{"code":201,"msg":"参数错误!"}';
	die(); 
}

if(isset($_REQUEST['shoppingcart']) && $_REQUEST['shoppingcart'] !='')
{
	$shoppingcart = $_REQUEST['shoppingcart'];  
}
else
{
	$shoppingcart = '0'; 
}
 
	
$salesactivityid = '';
$salesactivitys_product_id= '';
$activitymode = 0;
$bargainrequirednumber = 0;
$bargains_count = 0;
if(isset($_REQUEST['salesactivityid']) && $_REQUEST['salesactivityid'] !='')
{
	$salesactivityid = $_REQUEST['salesactivityid'];
	$salesactivitys_info =  XN_Content::load($salesactivityid,"mall_salesactivitys");
	$activitymode = intval($salesactivitys_info->my->activitymode);
	$bargainrequirednumber = intval($salesactivitys_info->my->bargainrequirednumber);
	if(intval($activitymode) === 1){
		$order_products = XN_Query::create("YearContent")->tag("mall_orders_products")
								  ->filter('type', 'eic', "mall_orders_products")
								  ->filter("my.salesactivityid", "=", $salesactivityid)
								  ->filter("my.productid", "=", $productid)
								  ->filter("my.supplierid", "=", $supplierid)
								  ->filter("my.profileid", "=", $loginprofileid)
								  ->filter("my.deleted", "=", "0")
								  ->end(-1)
								  ->execute();
		foreach($order_products as $order_product_info){
			$orderid = $order_product_info->my->orderid;
			$orders = XN_Content::load($orderid,"mall_orders_".$loginprofileid,7);
			if($orders->my->deleted == "0"){
				$tradestatus = $orders->my->tradestatus;
				if($tradestatus == "notrade"){
					echo '{"code":203,"msg":"宝贝限购一件!<br>宝贝在待付款等你带回家呢."}';
				}else{
					$deliverystatus = $orders->my->deliverystatus;
					if($deliverystatus == "nosend"){
						echo '{"code":204,"msg":"宝贝限购一件!<br>宝贝正在等候去你家的航班呢."}';
					}else{
						$confirmreceipt = $orders->my->confirmreceipt;
						if($confirmreceipt != "receipt")
						{
							echo '{"code":205,"msg":"宝贝限购一件!<br>宝贝在去你家的路上了."}';
						}else{
							echo '{"code":206,"msg":"宝贝限购一件!<br>宝贝已经在你家了."}';
						}
					}
				}
				die();
			}
		}
		$bargains_products = XN_Query::create("YearContent_Count")->tag("mall_bargains")
									 ->filter("type", "eic", "mall_bargains")
									 ->filter("my.salesactivityid", "=", $salesactivityid)
									 ->filter("my.productid", "=", $productid)
									 ->filter("my.supplierid", "=", $supplierid)
									 ->filter("my.profileid", "=", $loginprofileid)
									 ->filter("my.bargain", "=", '1')
									 ->rollup()
									 ->end(-1);
		$bargains_products->execute();
		$bargains_count = intval($bargains_products->getTotalCount());
		if($bargains_count > $bargainrequirednumber){
			$bargains_count = $bargainrequirednumber;
		}
	}
} 
if(isset($_REQUEST['salesactivitys_product_id']) && $_REQUEST['salesactivitys_product_id'] !='')
{
	$salesactivitys_product_id = $_REQUEST['salesactivitys_product_id'];  
	$salesactivitys_product_info =  XN_Content::load($salesactivitys_product_id,"mall_salesactivitys_products");
    $zhekou = $salesactivitys_product_info->my->zhekou;
	$zhekoulabel = $salesactivitys_product_info->my->label; 
} 
else
{
    $zhekou = '';
	$zhekoulabel = '';
}


try{   
	//data: 'type=detail&record=' + productid + '&quantity=' + qty_item + '&propertyid=' + product_property_id,
	if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'detail')
	{
		if(isset($_REQUEST['propertyid']) && $_REQUEST['propertyid'] != '' &&
		   isset($_REQUEST['quantity']) && $_REQUEST['quantity'] != '' )
		{
			 $propertyid = $_REQUEST['propertyid']; 
			 $quantity = $_REQUEST['quantity'];
			 
			 
			 $product_info =  XN_Content::load($productid,"mall_products");
			
			 
		 	if ($loginprofileid == "anonymous")
		 	{ 
		 	    $shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts_'.$loginprofileid )
		 	        ->filter ( 'type', 'eic', 'mall_shoppingcarts' )
					  	
		 	        ->filter (  'my.sessionid', '=',session_id())
		 			->filter (  'my.supplierid', '=',$supplierid )  
		 	        ->filter (  'my.productid', '=',$productid) 
		 	        ->filter (  'my.deleted', '=','0');
				if(isset($salesactivityid) && !empty($salesactivityid))
				{
					if(intval($activitymode) === 1)
					{
						$shoppingcarts->filter('my.salesactivityid', '=', $salesactivityid);
					}else{
						$shoppingcarts->filter('my.salesactivityid', '=', $salesactivityid);
						$shoppingcarts->filter('my.product_property_id', '=', $propertyid);
					}
				}else{
					$shoppingcarts->filter('my.salesactivityid', '=', '');
					$shoppingcarts->filter('my.product_property_id', '=', $propertyid);
				}
				$shoppingcarts = $shoppingcarts->execute();
		 	}
		 	else
		 	{
		 	    $shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts_'.$loginprofileid )
		 	        ->filter ( 'type', 'eic', 'mall_shoppingcarts' )
					  	
		 	        ->filter (  'my.profileid', '=',$loginprofileid)
		 			->filter (  'my.supplierid', '=',$supplierid )
		 	        ->filter (  'my.productid', '=',$productid) 
		 	        ->filter (  'my.deleted', '=','0');
				if(isset($salesactivityid) && !empty($salesactivityid))
				{
					if(intval($activitymode) === 1)
					{
						$shoppingcarts->filter('my.salesactivityid', '=', $salesactivityid);
					}else{
						$shoppingcarts->filter('my.salesactivityid', '=', $salesactivityid);
						$shoppingcarts->filter('my.product_property_id', '=', $propertyid);
					}
				}else{
					$shoppingcarts->filter('my.salesactivityid', '=', '');
					$shoppingcarts->filter('my.product_property_id', '=', $propertyid);
				}
				$shoppingcarts = $shoppingcarts->execute();
		 	}
			 $product_property_info =  XN_Content::load($propertyid,"mall_product_property");
			 
			 $propertydesc = $product_property_info->my->propertydesc;
			 
		     if (count($shoppingcarts) == 0)
		     {
		         //如果购物车里面没有这种商品的话，新增   
		         $newcontent = XN_Content::create('mall_shoppingcarts','',false,7);
		         $newcontent->my->deleted = '0';
		 		if ($profileid == "anonymous")
		 		{
		 			$newcontent->my->sessionid = session_id();
		 			$newcontent->my->profileid = "";
		 		}
		 		else
		 		{
		 			$newcontent->my->profileid = $loginprofileid;
					$newcontent->my->sessionid = session_id();
		 		} 
				
		         $newcontent->my->productid = $productid;
		         $newcontent->my->productname = $product_info->my->productname;
		         $newcontent->my->productthumbnail = $product_info->my->productthumbnail;
		         $newcontent->my->quantity = $quantity;
				 if (isset($zhekou) && $zhekou != '')
				 {
					 $shop_price = $product_property_info->my->shop;
					 $newcontent->my->old_shop_price = $shop_price;
					 $newcontent->my->zhekou         = $zhekou;
					 $newcontent->my->zhekoulabel    = $zhekoulabel;
					 $newcontent->my->activitymode    = intval($activitymode);
					 $newcontent->my->bargains_count    = intval($bargains_count);
					 $newcontent->my->bargainrequirednumber    = intval($bargainrequirednumber);
					 if(intval($activitymode) === 1){
						 $promotionalprice               = floatval($shop_price) - floatval($shop_price) * (10 - floatval($zhekou)) / 10 / $bargainrequirednumber * $bargains_count ;
						 $newcontent->my->shop_price     = $promotionalprice;
						 $total_price                    = intval($quantity) * $promotionalprice;
						 $newcontent->my->total_price    = $total_price;
					 }else
					 {
						 $promotionalprice               = floatval($shop_price) * floatval($zhekou) / 10;
						 $newcontent->my->shop_price     = $promotionalprice;
						 $total_price                    = intval($quantity) * $promotionalprice;
						 $newcontent->my->total_price    = $total_price;
					 }
					 $newcontent->my->salesactivityid           = $salesactivityid;
					 $newcontent->my->salesactivitys_product_id = $salesactivitys_product_id;
				 }
				 else
				 {
					 $shop_price                      = $product_property_info->my->shop;
					 $total_price                     = intval($quantity) * $shop_price;
					 $newcontent->my->shop_price      = $shop_price;
					 $newcontent->my->total_price     = $total_price;
					 $newcontent->my->salesactivityid = '';
				 } 
		         $newcontent->my->market_price = $product_info->my->market_price;
				 $newcontent->my->vendorid = $product_info->my->vendorid;
				 $newcontent->my->vendor_price = $product_property_info->my->vendor_price;
		         $newcontent->my->product_property_id = $propertyid;
		         $newcontent->my->propertydesc = $propertydesc;
		         $newcontent->my->supplierid = $supplierid;
		         $newcontent->my->categorys = $product_info->my->categorys; 
		         $newcontent->save('mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid.',mall_shoppingcarts_'.$supplierid);
		     }
		     else
		     {
		         //购物车已经有这种商品的话，数量叠加
				 if(intval($activitymode) === 1){
					 echo '{"code":202,"msg":"宝贝限购一件!<br>宝贝在购物车等你带回家呢."}';
					 die();
				 }elseif ($shoppingcart == '0')
				 {
			         $shoppingcart_info = $shoppingcarts[0];
			         $oldqty_item = $shoppingcart_info->my->quantity;
			         $qty_item = intval($oldqty_item) + intval($quantity);
			         $shoppingcart_info->my->quantity = $qty_item;
					 if (isset($zhekou) && $zhekou != '')
					 {
						 $shop_price                                       = $product_info->my->shop_price;
						 $promotionalprice                                 = floatval($shop_price) * floatval($zhekou) / 10;
						 $shoppingcart_info->my->old_shop_price            = $shop_price;
						 $shoppingcart_info->my->shop_price                = $promotionalprice;
						 $total_price                                      = $qty_item * $promotionalprice;
						 $shoppingcart_info->my->total_price               = $total_price;
						 $shoppingcart_info->my->zhekou                    = $zhekou;
						 $shoppingcart_info->my->zhekoulabel               = $zhekoulabel;
						 $shoppingcart_info->my->salesactivityid           = $salesactivityid;
						 $shoppingcart_info->my->salesactivitys_product_id = $salesactivitys_product_id;
					 }
					 else
					 {
						 $shop_price                             = $product_info->my->shop_price;
						 $total_price                            = $qty_item * $shop_price;
						 $shoppingcart_info->my->shop_price      = $shop_price;
						 $shoppingcart_info->my->total_price     = $total_price;
						 $shoppingcart_info->my->salesactivityid = '';
					 }
					 $shoppingcart_info->my->vendorid = $product_info->my->vendorid;
					 $shoppingcart_info->my->vendor_price = $product_property_info->my->vendor_price;
			         $shoppingcart_info->my->market_price = $product_info->my->market_price;
			         $shoppingcart_info->save('mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid.',mall_shoppingcarts_'.$supplierid);
				 	
				 }
		     }
			 $msg = '【'. $propertydesc.' '.$product_info->my->productname."】已经添加到购物车！";
		}
		else
		{
			throw new XN_Exception("没有属性参数!");
		}
	}
	else
	{
		if(isset($_REQUEST['quantity']) && $_REQUEST['quantity'] != '' )
		{
			 $quantity = $_REQUEST['quantity'];
		}
		else
		{
			 $quantity = '1';
		}
		 
		$mall_product_propertys = XN_Query::create ( 'Content' )->tag('mall_product_property')
			->filter ( 'type', 'eic', 'mall_product_property' )
			->filter ( 'my.productid', '=',$productid )
	        ->filter('my.deleted','=','0') 
			->end(1)	
			->execute ();
		if (count($mall_product_propertys) > 0)
		{
			echo '{"code":201,"msg":"此'.$productprefix.'有多个属性，请点击进入'.$productprefix.'详情，选择属性后购买!"}';
			die();  
		}
		
		$product_info =  XN_Content::load($productid,"mall_products");

		if ($loginprofileid == "anonymous")
		{
			$shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts_'.$loginprofileid )
									 ->filter ( 'type', 'eic', 'mall_shoppingcarts' )
									 
									 ->filter (  'my.sessionid', '=',session_id())
									 ->filter (  'my.supplierid', '=',$supplierid )
									 ->filter (  'my.productid', '=',$productid)
									 ->filter (  'my.deleted', '=','0');
			if(isset($salesactivityid) && !empty($salesactivityid))
			{
				$shoppingcarts->filter('my.salesactivityid', '=', $salesactivityid);
			}else{
				$shoppingcarts->filter('my.salesactivityid', '=', '');
			}
			$shoppingcarts = $shoppingcarts->execute();
		}
		else
		{
			$shoppingcarts = XN_Query::create ( 'YearContent' )->tag('mall_shoppingcarts_'.$loginprofileid )
									 ->filter ( 'type', 'eic', 'mall_shoppingcarts' )
									 
									 ->filter (  'my.profileid', '=',$loginprofileid)
									 ->filter (  'my.supplierid', '=',$supplierid )
									 ->filter (  'my.productid', '=',$productid)
									 ->filter (  'my.deleted', '=','0');
			if(isset($salesactivityid) && !empty($salesactivityid))
			{
				$shoppingcarts->filter('my.salesactivityid', '=', $salesactivityid);
			}else{
				$shoppingcarts->filter('my.salesactivityid', '=', '');
			}
			$shoppingcarts = $shoppingcarts->execute();
		}

	    if (count($shoppingcarts) == 0)
	    {
		     //如果是资格产品，需要验证是否已经买过 
		    if ($product_info->my->uniquesale == '1')
		    {
			    if ($profileid == "anonymous")
			    {
				    echo '{"code":202,"msg":"匿名用户不能认证资格产品!"}';
					die();
			    }
			    else
			    {
				     $mall_uniquesales = XN_Query::create('Content')->tag('mall_uniquesales_' . $supplierid)
				        ->filter('type', 'eic', 'mall_uniquesales')
				        ->filter('my.deleted', '=', '0')
				        ->filter('my.supplierid', '=', $supplierid)
				        ->filter('my.profileid', '=', $loginprofileid)
						->filter('my.productid', '=', $productid)  
				        ->end(-1)
				        ->execute();
				     if (count($mall_uniquesales) > 0)
				     {
					     echo '{"code":202,"msg":"您已经认证了'.$product_info->my->productname.'，无需再次认证！!"}';
						 die();
				     }
			    }
			   
		    }
	        //如果购物车里面没有这种商品的话，新增 
	        $newcontent = XN_Content::create('mall_shoppingcarts','',false,7);
	        $newcontent->my->deleted = '0';
			if ($profileid == "anonymous")
			{
				$newcontent->my->sessionid = session_id();
				$newcontent->my->profileid = "";
			}
			else
			{
				$newcontent->my->profileid = $loginprofileid;
				$newcontent->my->sessionid = session_id();
			} 
	        $newcontent->my->productid = $productid;
	        $newcontent->my->productname = $product_info->my->productname;
	        $newcontent->my->productthumbnail = $product_info->my->productthumbnail;
	        $newcontent->my->quantity = $quantity;  
			 if (isset($zhekou) && $zhekou != '')
			 {
				 $shop_price                            = $product_property_info->my->shop;
				 $newcontent->my->old_shop_price        = $shop_price;
				 $newcontent->my->zhekou                = $zhekou;
				 $newcontent->my->zhekoulabel           = $zhekoulabel;
				 $newcontent->my->activitymode          = intval($activitymode);
				 $newcontent->my->bargains_count        = intval($bargains_count);
				 $newcontent->my->bargainrequirednumber = intval($bargainrequirednumber);
				 if(intval($activitymode) === 1){
					 $promotionalprice               = floatval($shop_price) - floatval($shop_price) * (10 - floatval($zhekou)) / 10 / $bargainrequirednumber * $bargains_count ;
					 $newcontent->my->shop_price     = $promotionalprice;
					 $total_price                    = intval($quantity) * $promotionalprice;
					 $newcontent->my->total_price    = $total_price;
				 }else
				 {
					 $promotionalprice               = floatval($shop_price) * floatval($zhekou) / 10;
					 $newcontent->my->shop_price     = $promotionalprice;
					 $total_price                    = intval($quantity) * $promotionalprice;
					 $newcontent->my->total_price    = $total_price;
				 }
				 $newcontent->my->salesactivityid           = $salesactivityid;
				 $newcontent->my->salesactivitys_product_id = $salesactivitys_product_id;
			 }
			 else
			 {
				 $shop_price                      = $product_info->my->shop_price;
				 $total_price                     = intval($quantity) * $shop_price;
				 $newcontent->my->shop_price      = $shop_price;
				 $newcontent->my->total_price     = $total_price;
				 $newcontent->my->salesactivityid = '';
			 }
			$newcontent->my->vendorid = $product_info->my->vendorid;
			$newcontent->my->vendor_price = $product_info->my->vendor_price;
			$newcontent->my->market_price = $product_info->my->market_price;
			$newcontent->my->uniquesale = $product_info->my->uniquesale;
	        $newcontent->my->product_property_id = '';
	        $newcontent->my->propertydesc = '';
	        $newcontent->my->supplierid = $supplierid;
	        $newcontent->my->categorys = $product_info->my->categorys; 
	        $newcontent->save('mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid.',mall_shoppingcarts_'.$supplierid);
	    }
	    else
	    {
	        //购物车已经有这种商品的话，数量叠加
			if(intval($activitymode) === 1){
				echo '{"code":202,"msg":"宝贝限购一件!<br>宝贝在购物车等你带回家呢."}';
				die();
			}elseif ($shoppingcart == '0')
			{
		        $shoppingcart_info = $shoppingcarts[0];
		        if ($product_info->my->uniquesale == '1')
		        {
			        echo '{"code":202,"msg":"资格商品限认证一次!"}';
					die();
		        }
		        $oldqty_item = $shoppingcart_info->my->quantity;
		        $qty_item = intval($oldqty_item) + intval($quantity);
		        $shoppingcart_info->my->quantity = $qty_item;
				 if (isset($zhekou) && $zhekou != '')
				 {
					 $shop_price                                       = $product_info->my->shop_price;
					 $promotionalprice                                 = floatval($shop_price) * floatval($zhekou) / 10;
					 $shoppingcart_info->my->old_shop_price            = $shop_price;
					 $shoppingcart_info->my->shop_price                = $promotionalprice;
					 $total_price                                      = $qty_item * $promotionalprice;
					 $shoppingcart_info->my->total_price               = $total_price;
					 $shoppingcart_info->my->zhekou                    = $zhekou;
					 $shoppingcart_info->my->zhekoulabel               = $zhekoulabel;
					 $shoppingcart_info->my->salesactivityid           = $salesactivityid;
					 $shoppingcart_info->my->salesactivitys_product_id = $salesactivitys_product_id;
				 }
				 else
				 {
			          $shop_price = $product_info->my->shop_price;
			          $total_price = $qty_item * $shop_price;
			          $shoppingcart_info->my->shop_price = $shop_price;
					  $shoppingcart_info->my->total_price = $total_price;
				 }
				$shoppingcart_info->my->vendorid = $product_info->my->vendorid;
				$shoppingcart_info->my->vendor_price = $product_info->my->vendor_price;
		        $shoppingcart_info->my->market_price = $product_info->my->market_price; 
		        $shoppingcart_info->save('mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid.',mall_shoppingcarts_'.$supplierid);
				
			}
	    }
		$msg = '【'.$product_info->my->productname."】已经添加到购物车！";
	}
	
	
	$shoppingcart = 0; 
	if ($loginprofileid == "anonymous")
	{
		$shoppingcarts = XN_Query::create ( 'YearContent_Count' )->tag('mall_shoppingcarts_'.$loginprofileid )
						->filter ( 'type', 'eic', 'mall_shoppingcarts' )
						  	
						->filter (  'my.supplierid', '=',$supplierid )
						->filter (  'my.sessionid', '=',session_id())  
						->filter (  'my.deleted', '=','0')
						->rollup('my.quantity')
						->end(-1)
						->execute ();
	}
	else
	{
		$shoppingcarts = XN_Query::create ( 'YearContent_Count' )->tag('mall_shoppingcarts_'.$loginprofileid )
						->filter ( 'type', 'eic', 'mall_shoppingcarts' )
						  	
						->filter (  'my.supplierid', '=',$supplierid )	
						->filter (  'my.profileid', '=',$loginprofileid)  
						->filter (  'my.deleted', '=','0')
						->rollup('my.quantity')
						->end(-1)
						->execute ();
	} 
	if (count($shoppingcarts) > 0)
	{
		$shoppingcart_info = $shoppingcarts[0];
		$shoppingcart = $shoppingcart_info->my->quantity;
		if ($shoppingcart > 99) $shoppingcart = 99;
	}  
	
	echo '{"code":200,"msg":"'.$msg.'","shoppingcart":"'.$shoppingcart.'"}';
	die(); 
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	echo '{"code":202,"msg":"'.$msg.'"}'; 
	die();  
}