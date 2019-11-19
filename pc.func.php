<?php  
 

function  product_historys($supplierid,$profileid,$productid,$count=8) {
    global $APISERVERADDRESS;  
	
	if ($profileid == "anonymous") return array();
	
	$mall_product_historys = XN_Query::create("YearContent")->tag("mall_product_historys_".$profileid)
	    ->filter("type", "eic", "mall_product_historys")
	    ->filter("my.supplierid", "=", $supplierid) 
		->filter("my.profileid", "=", $profileid) 
		->filter("my.productid", "!=", $productid) 
		->order("my.accesstime",XN_Order::DESC) 
	    ->end($count)
	    ->execute();
	
	$productids = array();  
	if (count($mall_product_historys) == 0)
	{
		return array();
	}
    foreach($mall_product_historys as $mall_product_history_info)
	{ 
		$productids[] = $mall_product_history_info->my->productid; 
	} 
	$mall_products = XN_Content::loadMany($productids,'mall_products_'.$supplierid);   

	$productinfos = array();
	$key = 1;
	foreach($mall_products as $product_info)
	{  
			if($product_info->my->hitshelf=='on' && $product_info->my->deleted=='0')
			{ 
				//$productlogo = $APISERVERADDRESS.$product_info->my->productlogo;
				$productlogo = $product_info->my->productlogo;
				
				global $APISERVERADDRESS,$width;
				if (isset($productlogo) && $productlogo != "")
				{
					$width = 320;
					//$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=".$width;
					$productlogo = $APISERVERADDRESS.$product_info->my->productlogo;
				}
				
				$productid = $product_info->id;
				$brandid = $product_info->my->brand; 
				$productinfos[$key]['productid'] = $productid;  
				$productinfos[$key]['productlogo'] = $productlogo; 
				$productinfos[$key]['keywords'] = $product_info->my->keywords; 
				$productinfos[$key]['market_price'] = number_format($product_info->my->market_price,2,".","");   
				$productinfos[$key]['shop_price'] = number_format($product_info->my->shop_price,2,".","");    
				$productinfos[$key]['productname'] = $product_info->my->productname;  
				$productinfos[$key]['product_weight'] = $product_info->my->product_weight; 
				$productinfos[$key]['weight_unit'] = $product_info->my->weight_unit; 
				$productinfos[$key]['brand'] = $product_info->my->brand; 
				$productinfos[$key]['categorys'] = $product_info->my->categorys; 
				$productinfos[$key]['supplierid'] = $product_info->my->supplierid;  
				$productinfos[$key]['salevolume'] = $product_info->my->salevolume;   
				$praise = intval($product_info->my->praise);
				if ($praise < 1) $praise = 1;
				$productinfos[$key]['praise'] = intval($product_info->my->praise); 
				$key++;
			} 
	}  
	 
    return $productinfos; 
}
 
 
function  samecategory_products($supplierid,$categoryid,$productid,$count=4) {
    global $APISERVERADDRESS;    
	$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
					->filter ( 'type', 'eic', 'mall_products') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.hitshelf', '=', 'on')
					->filter ( 'my.supplierid', '=', $supplierid) 
					->filter ( 'my.categorys', '=', $categoryid) 
					->filter ( 'id', '!=', $productid) 
					->order("published",XN_Order::DESC) 
					->end($count)
					->execute ();   

	$productinfos = array();
	$key = 1;
	foreach($mall_products as $product_info)
	{  
			if($product_info->my->hitshelf=='on' && $product_info->my->deleted=='0')
			{ 
				//$productlogo = $APISERVERADDRESS.$product_info->my->productlogo;
				$productlogo = $product_info->my->productlogo;
				
				global $APISERVERADDRESS,$width;
				if (isset($productlogo) && $productlogo != "")
				{
					$width = 320;
					//$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=".$width;
					$productlogo = $APISERVERADDRESS.$product_info->my->productlogo;
				}
				
				$productid = $product_info->id;
				$brandid = $product_info->my->brand; 
				$productinfos[$key]['productid'] = $productid;  
				$productinfos[$key]['productlogo'] = $productlogo; 
				$productinfos[$key]['keywords'] = $product_info->my->keywords; 
				$productinfos[$key]['market_price'] = number_format($product_info->my->market_price,2,".","");   
				$productinfos[$key]['shop_price'] = number_format($product_info->my->shop_price,2,".","");    
				$productinfos[$key]['productname'] = $product_info->my->productname;  
				$productinfos[$key]['product_weight'] = $product_info->my->product_weight; 
				$productinfos[$key]['weight_unit'] = $product_info->my->weight_unit; 
				$productinfos[$key]['brand'] = $product_info->my->brand; 
				$productinfos[$key]['categorys'] = $product_info->my->categorys; 
				$productinfos[$key]['supplierid'] = $product_info->my->supplierid;  
				$productinfos[$key]['salevolume'] = $product_info->my->salevolume;   
				$praise = intval($product_info->my->praise);
				if ($praise < 1) $praise = 1;
				$productinfos[$key]['praise'] = intval($product_info->my->praise); 

				$key++;
			} 
	}  
	 
    return $productinfos; 
}
 
?>