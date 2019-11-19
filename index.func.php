<?php  
 

function  mall_newproducts($supplierid,$count=8) {
    global $APISERVERADDRESS;   
	 
	$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
				->filter ( 'type', 'eic', 'mall_products') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.hitshelf', '=', 'on')
				->filter ( 'my.supplierid', '=', $supplierid) 
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

function  mall_salevolume_products($supplierid,$count=8) {
    global $APISERVERADDRESS;  
	
	$supplier_info = get_supplier_info();
	if ($supplier_info['showuniquesale'] == '1')
	{
		global $loginprofileid;
		$mall_uniquesales = XN_Query::create ( 'Content' )->tag('mall_uniquesales_'.$loginprofileid)
					->filter ( 'type', 'eic', 'mall_uniquesales') 
					->filter ( 'my.deleted', '=', '0')  
					->filter ( 'my.profileid', '=', $loginprofileid)  
					->filter ( 'my.supplierid', '=', $supplierid) 
					->end(-1)
					->execute (); 
		$uniquesales = array();
		foreach($mall_uniquesales as $mall_uniquesale_info)
		{
			$uniquesales[] = $mall_uniquesale_info->my->productid;
		}
		if (count($uniquesales) > 0)
		{ 
			$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
						->filter ( 'type', 'eic', 'mall_products') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.hitshelf', '=', 'on')
						->filter ( 'my.supplierid', '=', $supplierid) 
						->filter ( 'id', '!in', $uniquesales)
						->order("my.salevolume",XN_Order::DESC_NUMBER) 
						->end($count)
						->execute ();  
		}
		else
		{ 
				$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
							->filter ( 'type', 'eic', 'mall_products') 
							->filter ( 'my.deleted', '=', '0') 
							->filter ( 'my.hitshelf', '=', 'on')
							->filter ( 'my.supplierid', '=', $supplierid)  
							->order("my.salevolume",XN_Order::DESC_NUMBER)
							->end($count)
							->execute ();  
		}
		
	}
	else
	{
		$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
					->filter ( 'type', 'eic', 'mall_products') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.hitshelf', '=', 'on')
					->filter ( 'my.supplierid', '=', $supplierid) 
					->order("my.salevolume",XN_Order::DESC_NUMBER)
					->end($count)
					->execute ();  
	} 

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

function  mall_products($page,$supplierid,$pagesize=12) {
    global $APISERVERADDRESS; 
	$begin = ($page - 1) * $pagesize;
	$end = $page * $pagesize;
	
	$supplier_info = get_supplier_info();
	if ($supplier_info['showuniquesale'] == '1')
	{
		global $loginprofileid;
		$mall_uniquesales = XN_Query::create ( 'Content' )->tag('mall_uniquesales_'.$loginprofileid)
					->filter ( 'type', 'eic', 'mall_uniquesales') 
					->filter ( 'my.deleted', '=', '0')  
					->filter ( 'my.supplierid', '=', $supplierid) 
					->filter ( 'my.profileid', '=', $loginprofileid)  
					->end(-1)
					->execute (); 
		$uniquesales = array();
		foreach($mall_uniquesales as $mall_uniquesale_info)
		{
			$uniquesales[] = $mall_uniquesale_info->my->productid;
		}
		if (count($uniquesales) > 0)
		{ 
			$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
						->filter ( 'type', 'eic', 'mall_products') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.hitshelf', '=', 'on')
						->filter ( 'my.supplierid', '=', $supplierid) 
						->filter ( 'id', '!in', $uniquesales)
						->order("my.sequence",XN_Order::ASC_NUMBER)
						->begin($begin)
						->end($end)
						->execute ();  
		}
		else
		{ 
				$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
							->filter ( 'type', 'eic', 'mall_products') 
							->filter ( 'my.deleted', '=', '0') 
							->filter ( 'my.hitshelf', '=', 'on')
							->filter ( 'my.supplierid', '=', $supplierid)  
							->order("my.sequence",XN_Order::ASC_NUMBER)
							->begin($begin)
							->end($end)
							->execute ();  
		}
		
	}
	else
	{
		$mall_products = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
					->filter ( 'type', 'eic', 'mall_products') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.hitshelf', '=', 'on')
					->filter ( 'my.supplierid', '=', $supplierid) 
					->order("my.sequence",XN_Order::ASC_NUMBER)
					->begin($begin)
					->end($end)
					->execute ();  
	}
	
	

	$productids = array();  
	if (count($mall_products) == 0)
	{
		return array();
	}
    foreach($mall_products as $product_info)
	{ 
		$productids[] = $product_info->id; 
	}
     
	$mall_product_propertys = XN_Query::create ( 'Content' )->tag('mall_product_property_'.$supplierid)
		->filter ( 'type', 'eic', 'mall_product_property' )
		->filter ( 'my.productid', 'in', $productids )
        ->filter('my.deleted','=','0') 
		->end(-1)	
		->execute ();
		
	$product_propertys = array();
	 
	foreach($mall_product_propertys as $mall_product_property_info)
	{
		$productid = $mall_product_property_info->my->productid;
		if (!in_array($productid, $product_propertys))
		{
			$product_propertys[] = $productid;
		}
	}
	
	$brandids=array();
	foreach($mall_products as $product_info)
	{ 
		$brandids[] = $product_info->my->brand;; 
	}
	
	$brands=array(); 
	if(count($brandids) > 0)
	{
		$brand_contents = XN_Content::loadMany(array_unique($brandids),"mall_brands_".$supplierid); 
		foreach($brand_contents as $brand_info)
		{
			$brandid = $brand_info->id; 
			$brands[$brandid]['brand_logo'] = $brand_info->my->brand_logo; 
			$brands[$brandid]['brand_name'] = $brand_info->my->brand_name;  
		} 
	}   
	$praise_productids = array();
	foreach($productids as $productid)
	{	
		$praise_productids[] = "praises_".$productid;
	}
	
	
	$praisesconfig = XN_MemCache::getmany($praise_productids); 

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
				if (is_array($brands[$brandid])) 
				{
					$productinfos[$key]['brand_logo'] = $brands[$brandid]['brand_logo'];
					$productinfos[$key]['brand_name'] = $brands[$brandid]['brand_name'];
				}
				else
				{
					$productinfos[$key]['brand_logo'] = "/images/brand_logo.png"; 
					$productinfos[$key]['brand_name'] = "";
				}
				if (isset($praisesconfig["praises_".$productid]))
				{
					$productinfos[$key]['praise'] = $praisesconfig["praises_".$productid];
				}
				if (in_array($productid, $product_propertys))
				{
					$productinfos[$key]['hasproperty'] = '1';
				}
				else
				{
					$productinfos[$key]['hasproperty'] = '0';
				}

				$key++;
			} 
	}  
	 
    return $productinfos; 
}
 
?>