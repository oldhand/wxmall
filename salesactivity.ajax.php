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


if(isset($_REQUEST['page']) && $_REQUEST['page'] !='')
{
	$page = $_REQUEST['page'];  
}
else
{
	echo '{"code":201,"data":[]}';
	die(); 
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort'] !='')
{
	$sort = $_REQUEST['sort'];  
} 
if(isset($_REQUEST['order']) && $_REQUEST['order'] !='')
{
	$order = $_REQUEST['order'];  
} 

if(isset($_REQUEST['salesactivityid']) && $_REQUEST['salesactivityid'] !='')
{
	$salesactivityid = $_REQUEST['salesactivityid'];  
}  
 

try{
	$products = mall_products($page,$supplierid,$order,$sort,$salesactivityid);
	if (count($products) > 0)
	{
		echo '{"code":200,"data":'.json_encode($products).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":202,"data":[]}'; 
die();  
 

function  mall_products($page,$supplierid,$order,$sort,$salesactivityid) {
	
    $activitys_products = XN_Query::create("Content")->tag("mall_salesactivitys_products")
				        ->filter("type","eic","mall_salesactivitys_products")
				        ->filter("my.salesactivityid","=",$salesactivityid) 
						->filter("my.status","=",'0')
				        ->end(-1)
				        ->execute();
	$productids = array();
	$salesactivitys_products_info = array();
    foreach($activitys_products as $activitys_product_info){
        $productid = $activitys_product_info->my->productid;
		$productids[] = $productid;
		$salesactivitys_products_info[$productid]['salesactivityid'] = $salesactivityid;
		$salesactivitys_products_info[$productid]['salesactivitys_product_id'] = $activitys_product_info->id;
        $salesactivitys_products_info[$productid]['zhekou'] = $activitys_product_info->my->zhekou;
		$salesactivitys_products_info[$productid]['label'] = $activitys_product_info->my->label; 
    }
	
	if (count($productids) == 0)
	{
		return array();
	}
		
		
    global $APISERVERADDRESS; 
	$begin = ($page - 1) * 10;
	$end = $page * 10;
	$query = XN_Query::create ( 'Content' )->tag('mall_products')
				->filter ( 'type', 'eic', 'mall_products') 
				->filter ( 'my.hitshelf', '=', 'on') 
				->filter ( 'my.deleted', '=', '0')
				->filter ( 'my.supplierid', '=', $supplierid)  
				->filter ( 'id', 'in', $productids)  
				->begin($begin)
				->end($end); 
	if ($order == "published")
	{
		$query->order("published",XN_Order::DESC);
	}
	else if ($order == "price")
	{
		if ($sort == "desc")
		{
			$query->order("my.shop_price",XN_Order::DESC_NUMBER);
		}
		else
		{
			$query->order("my.shop_price",XN_Order::ASC_NUMBER);
		} 
	}
	else if ($order == "salevalue")
	{
		if ($sort == "desc")
		{
			$query->order("my.salevolume",XN_Order::DESC_NUMBER);
		}
		else
		{
			$query->order("my.salevolume",XN_Order::ASC_NUMBER);
		} 
	}
	else if ($order == "praise")
	{
		if ($sort == "desc")
		{
			$query->order("my.praise",XN_Order::DESC_NUMBER);
		}
		else
		{
			$query->order("my.praise",XN_Order::ASC_NUMBER);
		} 
	}
	
	$mall_products	= $query->execute ();  

	$productids = array();  
	if (count($mall_products) == 0)
	{
		return array();
	}
	
    $mall_salesactivity_info = XN_Content::load($salesactivityid,'mall_salesactivitys');

	$productinfos = array();
	
	$index = 0;
	foreach($mall_products as $product_info)
	{  
			if($product_info->my->hitshelf=='on' && $product_info->my->deleted=='0')
			{ 
				$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=".$_SESSION['width'];
				$productid = $product_info->id;
				$brandid = $product_info->my->brand; 
				$productinfos[$index]['productid'] = $productid;  
				$productinfos[$index]['productlogo'] = $productlogo; 
				$productinfos[$index]['keywords'] = $product_info->my->keywords; 
				$productinfos[$index]['market_price'] = number_format($product_info->my->market_price,2,".",""); 
				$productinfos[$index]['shop_price'] = number_format($product_info->my->shop_price,2,".","");   
				$productinfos[$index]['productname'] = $product_info->my->productname; 
				$productinfos[$index]['description'] = $product_info->my->description; 
				$productinfos[$index]['simple_desc'] = $product_info->my->simple_desc; 
				$productinfos[$index]['product_weight'] = $product_info->my->product_weight; 
				$productinfos[$index]['weight_unit'] = $product_info->my->weight_unit; 
				$productinfos[$index]['brand'] = $product_info->my->brand; 
				$productinfos[$index]['categorys'] = $product_info->my->categorys; 
				$productinfos[$index]['supplierid'] = $product_info->my->supplierid; 
				$productinfos[$index]['salevolume'] = $product_info->my->salevolume;    
				 
				
		        $zhekou =  $salesactivitys_products_info[$productid]['zhekou'];
				$label = $salesactivitys_products_info[$productid]['label']; 
				$salesactivityid = $salesactivitys_products_info[$productid]['salesactivityid'];
				$salesactivitys_product_id = $salesactivitys_products_info[$productid]['salesactivitys_product_id'];
				$productinfos[$index]['zhekou'] = $zhekou;  
				$productinfos[$index]['label'] = $label; 
				$productinfos[$index]['salesactivityid'] = $salesactivityid; 
				$productinfos[$index]['salesactivitys_product_id'] = $salesactivitys_product_id; 
				$productinfos[$index]['activitymode'] = $mall_salesactivity_info->my->activitymode;
				$productinfos[$index]['bargainrequirednumber'] = $mall_salesactivity_info->my->bargainrequirednumber;
				
				$shop_price = $product_info->my->shop_price;
				$promotionalprice = floatval($shop_price) * floatval($zhekou) / 10;
				$productinfos[$index]['promotional_price'] = number_format($promotionalprice,2,".",""); 
				$index = $index + 1;
			} 
	}  
	 
    return $productinfos; 
}
 
?>