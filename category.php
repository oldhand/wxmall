<?php 
 

session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");

 
global $loginprofileid;
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

if (isset($_REQUEST['vendorid']) && $_REQUEST['vendorid'] != "")
{
	$vendorid = $_REQUEST['vendorid'];
 	$_SESSION['vendorid'] = $vendorid; 
}
else
{
	if (isset($_REQUEST['record']) && $_REQUEST['record'] != "")
	{
		
	}
	else
	{
		unset($_SESSION['vendorid']);

	}

}


if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	messagebox('错误',"没有店铺ID!"); 
	die();  
}




function  mall_products($page,$supplierid,$category) 
{ 
	$begin = ($page - 1) * 10;
	$end = $page * 10;
	$supplier_info = get_supplier_info();
	$uniquesales = array();
	if ($supplier_info['showuniquesale'] == '1')
	{
		global $loginprofileid;
		$mall_uniquesales = XN_Query::create ( 'Content' )->tag('mall_uniquesales_'.$loginprofileid)
					->filter ( 'type', 'eic', 'mall_uniquesales') 
					->filter ( 'my.deleted', '=', '0')  
					->filter ( 'my.profileid', '=', $loginprofileid)  
					->end(-1)
					->execute ();  
		foreach($mall_uniquesales as $mall_uniquesale_info)
		{
			$uniquesales[] = $mall_uniquesale_info->my->productid;
		}
	}
	if(isset($_SESSION['vendorid']) && $_SESSION['vendorid'] !='')
	{
		$vendorid = $_SESSION['vendorid']; 
		if (count($uniquesales) > 0)
		{
			$query = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
						->filter ( 'type', 'eic', 'mall_products') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.hitshelf', '=', 'on')
						->filter ( 'my.supplierid', '=', $supplierid)  
						->filter ( 'my.vendorid', '=', $vendorid)  
						->filter ( 'id', '!in', $uniquesales)
						->order("published",XN_Order::DESC)
						->begin($begin)
						->end($end);
		}
		else
		{
			$query = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
						->filter ( 'type', 'eic', 'mall_products') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.hitshelf', '=', 'on')
						->filter ( 'my.supplierid', '=', $supplierid)  
						->filter ( 'my.vendorid', '=', $vendorid)  
						->order("published",XN_Order::DESC)
						->begin($begin)
						->end($end);
		}
		
	} 
	else
	{
		if (count($uniquesales) > 0)
		{
			$query = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
						->filter ( 'type', 'eic', 'mall_products') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.hitshelf', '=', 'on')
						->filter ( 'my.supplierid', '=', $supplierid)  
						->filter ( 'id', '!in', $uniquesales)
						->order("published",XN_Order::DESC)
						->begin($begin)
						->end($end);
		}
		else
		{
			$query = XN_Query::create ( 'Content' )->tag('mall_products_'.$supplierid)
						->filter ( 'type', 'eic', 'mall_products') 
						->filter ( 'my.deleted', '=', '0') 
						->filter ( 'my.hitshelf', '=', 'on')
						->filter ( 'my.supplierid', '=', $supplierid)  
						->order("published",XN_Order::DESC)
						->begin($begin)
						->end($end);
		}
		
	}
	   
    
	$mall_categorys = XN_Query::create ( 'Content' )->tag('mall_categorys')
				->filter ( 'type', 'eic', 'mall_categorys')  
				->filter ( 'my.supplierid', '=', $supplierid) 
				->filter ( 'my.pid', '=', $category) 
				->begin()
				->end(-1)
				->execute (); 
    if (count($mall_categorys) > 0)
	{
		$categorys = array();
		$categorys[] = $category;
		foreach($mall_categorys as $mall_category_info)
		{
			$categorys[] = $mall_category_info->id;
		}
		$query->filter ( 'my.categorys', 'in', $categorys);
	} 
	else
	{
		$query->filter ( 'my.categorys', '=', $category);
	}
	
	$mall_products = $query->execute (); 
	
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
	
	$praise_productids = array();
	foreach($newproductids as $productid)
	{	
		$praise_productids[] = "praises_".$productid;
	}
	$praisesconfig = XN_MemCache::getmany($praise_productids); 

	$productinfos = array();
	$vendors = array();
	foreach($mall_products as $product_info)
	{    				 
				global $APISERVERADDRESS;
				$productlogo = $product_info->my->productlogo;
				if (isset($productlogo) && $productlogo != "")
				{ 
					$productlogo = $APISERVERADDRESS.$productlogo."?width=160";
				}
				 
				$productthumbnail = $product_info->my->productthumbnail;
				if (isset($productlogo) && $productlogo != "")
				{ 
					$productthumbnail = $APISERVERADDRESS.$productthumbnail."?width=160";
				}
				
				$productid = $product_info->id;
				$brandid = $product_info->my->brand; 
				$productinfos[$productid]['productid'] = $productid;  
				$productinfos[$productid]['productlogo'] = $productlogo; 
				$productinfos[$productid]['productthumbnail'] = $productthumbnail;
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
				if (in_array($productid, $product_propertys))
				{
					$productinfos[$productid]['hasproperty'] = '1';
				}
				else
				{
					$productinfos[$productid]['hasproperty'] = '0';
				}
				$vendorid = $product_info->my->vendorid;
				if (isset($vendorid) && $vendorid != "" && $vendorid != "0")
				{
					if (isset($vendors[$vendorid]) && $vendors[$vendorid] != "")
					{ 
						$productinfo[$productid]['vendorid'] = $vendorid;
						$productinfos[$productid]['vendorname'] = $vendors[$vendorid];
					}
					else
					{
						$vendor_info = XN_Content::load($vendorid,"mall_vendors_".$supplierid);
						$productinfos[$productid]['vendorid'] = $vendorid;
						$productinfos[$productid]['vendorname'] = $vendor_info->my->vendorname;
						$vendors[$vendorid] = $vendor_info->my->vendorname;
					}
				
				}
				else
				{ 
					$productinfos[$productid]['vendorname'] = "";
				}
				
	}  
	 
    return $productinfos; 
}

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='' )
{
    $record = $_REQUEST['record'];
	$categorylevel = $_REQUEST['categorylevel']; 
	try 
	{
		if ($categorylevel == '1')
		{
			$page = $_REQUEST['page'];
			$products = mall_products($page,$supplierid,$record);
			echo '{"code":200,"data":'.json_encode($products).'}';
		    die();
		}
		else
		{
			 global $APISERVERADDRESS;
			$mall_main_category = XN_Content::load($record,'mall_categorys');
			$childcategory = array();
			$categorylist = XN_Query::create ( 'Content' )->tag('mall_categorys')
				->filter ( 'type', 'eic', 'mall_categorys')
				->filter("my.deleted","=","0")
				->filter("my.supplierid","=",$supplierid)
				->filter("my.pid","=",$record)
				->order("my.sequence",XN_Order::ASC_NUMBER)
				->end(-1)
				->execute(); 
			foreach($categorylist as $info){
				    $categoryicon = $info->my->categoryicon;
					if (isset($categoryicon) && $categoryicon != "")
					{
						$categoryicon = $APISERVERADDRESS.$categoryicon."?width=60";
					}
					else
					{
						$categoryicon = 'images/fenlei.png';
					}
				    $childcategory[] = array(
									'name'=>$info->my->categoryname,
									'picurl'=>$categoryicon,
									'pid'=>$info->my->pid,
									'id'=>$info->id);
			        }  
			$local_categoryicon = $mall_main_category->my->categoryicon;
			if (isset($local_categoryicon) && $local_categoryicon != "")
			{
				$local_categoryicon = $APISERVERADDRESS.$local_categoryicon."?width=60";
			}
			else
			{
				$local_categoryicon = 'images/fenlei.png';
			}
			$all_category =  array('name'=>'查看全部',
								'picurl'=>$local_categoryicon,
								'pid'=>'0',
								'id'=>$mall_main_category->id); 
			$childcategory[] = $all_category;
			echo '{"code":200,"data":'.json_encode($childcategory).'}';
		    die();
		} 
	}
	catch ( XN_Exception $e ) 
	{ 
		echo '{"code":202,"data":[]}';  
		die();
	} 
}

try 
{ 
	$recommend_info = checkrecommend();  
	global $APISERVERADDRESS;
	//大类信息---------------------------------------------------------
	$categorylist = XN_Query::create ( 'Content' )->tag('mall_categorys')
		->filter ( 'type', 'eic', 'mall_categorys')
		->filter("my.deleted","=","0")
		->filter("my.supplierid","=",$supplierid)
		->filter("my.pid","=","0")
		->order("my.sequence",XN_Order::ASC_NUMBER)
		->end(-1)
		->execute();
	$category = array();
	foreach($categorylist as $info){
	    $categoryicon = $info->my->categoryicon;
		if (isset($categoryicon) && $categoryicon != "")
		{
			$categoryicon = $APISERVERADDRESS.$categoryicon."?width=60";
		}
		else
		{
			$categoryicon = 'images/fenlei.png';
		}
	    $category[] = array('name'=>$info->my->categoryname,
							'picurl'=>$categoryicon,
							'pid'=>$info->my->pid,
							'id'=>$info->id);
	} 
	//初始化小类信息-----------------------------------------------------
	$childcategory = array();
	if (count($category) > 0)
	{
		$childpid = $category[0]['id']; 
		$categorylist = XN_Query::create ( 'Content' )->tag('mall_categorys')
			->filter ( 'type', 'eic', 'mall_categorys')
			->filter("my.deleted","=","0")
			->filter("my.supplierid","=",$supplierid)
			->filter("my.pid","=",$childpid)
			->order("my.sequence",XN_Order::ASC_NUMBER)
			->end(-1)
			->execute();
	     
		foreach($categorylist as $info){
			    $categoryicon = $info->my->categoryicon;
				if (isset($categoryicon) && $categoryicon != "")
				{
					$categoryicon = $APISERVERADDRESS.$categoryicon."?width=60";
				}
				else
				{
					$categoryicon = 'images/fenlei.png';
				}
			    $childcategory[] = array(
								'name'=>$info->my->categoryname,
								'picurl'=>$categoryicon,
								'pid'=>$info->my->pid,
								'id'=>$info->id);
		        }  
		$all_category = $category[0];
		$all_category['name'] = '查看全部';
		$childcategory[] = $all_category;
	}
	//小类信息---------------------------------------------------------
	
}
catch ( XN_Exception $e ) 
{ 
	errorprint('错误',$e->getMessage());	  
	die();
} 

require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;
 
$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
	$islogined = true;
} 
$smarty->assign("islogined",$islogined);   
$smarty->assign("share_info",$recommend_info); 
$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
	$smarty->assign("profile_info",$profile_info);
}
else
{
	$smarty->assign("profile_info",get_profile_info());
}
$supplier_info = get_supplier_info();
$smarty->assign("supplier_info",$supplier_info); 
$action = strtolower(basename(__FILE__,".php"));
$smarty->assign("actionname",$action); 
  
$smarty->assign("categorys",$category);  
$smarty->assign("categoryid",$category[0]['id']);  
$smarty->assign("childcategorys",$childcategory);    
  
 
 
  
$sysinfo = array();
$sysinfo['action'] = $action; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS;  
$sysinfo['http_user_agent'] = check_http_user_agent(); 
$sysinfo['webpath'] = $WEB_PATH; 
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo);  
 
$categoryslevel = $supplier_info['categoryslevel']; 

if ($categoryslevel == '1')
{
	if ($supplierid == "7962")
	{
		$smarty->display('category_hxhuahui_onelevel.tpl');  
	}
	else
	{
		 $smarty->display('category_onelevel.tpl'); 
	}
	
}
else
{
	$smarty->display('category.tpl'); 
} 

 
 
?>