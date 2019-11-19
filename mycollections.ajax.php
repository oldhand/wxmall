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
 
 

try{
	$products = mall_products($page,$supplierid,$loginprofileid);
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
 

function  mall_products($page,$supplierid,$profileid) {
    global $APISERVERADDRESS; 
	$begin = ($page - 1) * 10;
	$end = $page * 10;
	
	$mycollections = XN_Query::create ( 'Content' )
	        ->tag ( "mall_mycollections_".$profileid)
	        ->filter ( 'type', 'eic', 'mall_mycollections' )
	        ->filter ( 'my.deleted', '=', '0' )
	        ->filter ( 'my.profileid', '=', $profileid )
	        ->filter ( 'my.supplierid', '=', $supplierid) 
	        ->filter ( 'my.status', '=', '1' )
	        ->begin($begin)
			->end($end)
	        ->execute();
    $productids = array(); 
    foreach($mycollections as $mycollection_info)
	{
		$productid = $mycollection_info->my->productid;  
		$productids[] = $productid;
	} 
	if (count($productids) == 0)
	{
		return array();
	}
	$mall_products =  XN_Content::loadMany($productids,"mall_products_".$profileid);  
	
	 
	if (count($mall_products) == 0)
	{
		return array();
	}
  
	
	$brandids=array();
	foreach($mall_products as $product_info)
	{ 
		$brandids[] = $product_info->my->brand;; 
	}
	
	$brands=array(); 
	if(count($brandids) > 0)
	{
		$brand_contents = XN_Content::loadMany(array_unique($brandids),"mall_brands"); 
		foreach($brand_contents as $brand_info)
		{
			$brandid = $brand_info->id; 
			$brands[$brandid]['brand_logo'] = $brand_info->my->brand_logo; 
			$brands[$brandid]['brand_name'] = $brand_info->my->brand_name;  
		} 
	}   
	$praise_productids = array();
	foreach($newproductids as $productid)
	{	
		$praise_productids[] = "praises_".$productid;
	}
	$praisesconfig = XN_MemCache::getmany($praise_productids); 

	$productinfos = array();
	
	foreach($mall_products as $product_info)
	{  
			if($product_info->my->hitshelf=='on' && $product_info->my->deleted=='0')
			{ 
				$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=".$_SESSION['width'];
				$productid = $product_info->id;
				$brandid = $product_info->my->brand; 
				$productinfos[$productid]['productid'] = $productid;  
				$productinfos[$productid]['productlogo'] = $productlogo; 
				$productinfos[$productid]['keywords'] = $product_info->my->keywords; 
				$productinfos[$productid]['market_price'] = number_format($product_info->my->market_price,2,".",""); 
				$productinfos[$productid]['shop_price'] = number_format($product_info->my->shop_price,2,".","");   
				$productinfos[$productid]['productname'] = $product_info->my->productname; 
				$productinfos[$productid]['description'] = $product_info->my->description; 
				$productinfos[$productid]['simple_desc'] = $product_info->my->simple_desc; 
				$productinfos[$productid]['product_weight'] = $product_info->my->product_weight; 
				$productinfos[$productid]['weight_unit'] = $product_info->my->weight_unit; 
				$productinfos[$productid]['brand'] = $product_info->my->brand; 
				$productinfos[$productid]['categorys'] = $product_info->my->categorys; 
				$productinfos[$productid]['supplierid'] = $product_info->my->supplierid;   
				$praise = intval($product_info->my->praise);
				if ($praise < 1) $praise = 1;
				$productinfos[$productid]['praise'] = intval($product_info->my->praise);
				if (is_array($brands[$brandid])) 
				{
					$productinfos[$productid]['brand_logo'] = $brands[$brandid]['brand_logo'];
					$productinfos[$productid]['brand_name'] = $brands[$brandid]['brand_name'];
				}
				else
				{
					$productinfos[$productid]['brand_logo'] = "/images/brand_logo.png"; 
					$productinfos[$productid]['brand_name'] = "";
				}
				if (isset($praisesconfig["praises_".$productid]))
				{
					$productinfos[$productid]['praise'] = $praisesconfig["praises_".$productid];
				}
			} 
	}  
	 
    return $productinfos; 
}
 
?>