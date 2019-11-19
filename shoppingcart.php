<?php
	/**
	 * @param $updateContent
	 * @param $Content
	 */
	function setBatchsave(&$updateContent, $Content)
	{
		if (!in_array($Content, $updateContent))
		{
			$updateContent[] = $Content;
		}
	}

	/**
	 * @param $profileid
	 * @return array
	 */
	function getShoppingcartConntent($supplierid, $profileid)
	{
		$shoppingcarts = XN_Query::create('YearContent')->tag('mall_shoppingcarts_'.$profileid)
								 ->filter('type', 'eic', 'mall_shoppingcarts')
								 ->filter('my.supplierid', '=', $supplierid)
								 ->filter('my.deleted', '=', '0')
								 ->end(-1);
		if ($profileid == "anonymous")
		{
			$shoppingcarts->filter('my.sessionid', '=', session_id());
		}
		else
		{
			$shoppingcarts->filter('my.profileid', '=', $profileid);
		}
		return $shoppingcarts->execute();
	}

	session_start();
	require_once(dirname(__FILE__)."/config.inc.php");
	require_once(dirname(__FILE__)."/config.common.php");
	require_once(dirname(__FILE__)."/util.php");
	

if (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '' &&
	isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $profileid = $_SESSION['accessprofileid'];
	$supplierid = $_SESSION['supplierid'];  
	 
    $supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
        ->filter('type', 'eic', 'supplier_wxsettings')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->end(1)
        ->execute();
    if (count($supplier_wxsettings) > 0)
    {
        $supplier_wxsetting_info = $supplier_wxsettings[0];
        $appid = $supplier_wxsetting_info->my->appid; 
        $secret = $supplier_wxsetting_info->my->secret;  
	    $wxopenids = XN_Query::create('MainContent')->tag("profile_" . $profileid)
	        ->filter('type', 'eic', 'wxopenids')
	        ->filter('my.profileid', '=', $profileid)
	        ->filter('my.appid', '=', $appid)
	        ->end(1)
	        ->execute();
	    if (count($wxopenids) == 0)
	    {
			require_once dirname(__FILE__).'/wxoauth2.php';
			WxOauth2::$APPID = $appid;
			WxOauth2::$APPSECRET = $secret;
			$wxopenid = WxOauth2::GetOpenid();
			if (isset($wxopenid) && $wxopenid != '')
			{
			    try
			    {
		            $newcontent = XN_Content::create('wxopenids', '', false);
		            $newcontent->my->profileid = $profileid;
		            $newcontent->my->unionid = '';
		            $newcontent->my->wxopenid = $wxopenid;
		            $newcontent->my->appid = $appid;
		            $newcontent->save("wxopenids,wxopenids_" . $wxopenid . ",wxopenids_" . $profileid.",profile_" . $profileid.",profile_" . $wxopenid); 
 			    }
			    catch (XN_Exception $e)
			    {

			    }
			}
		}
	} 
}
	
	if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
	{
		$profileid = $_SESSION['profileid'];
	}
	elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
	{
		$profileid = $_SESSION['accessprofileid'];
	}
	else
	{
		$profileid = "anonymous";
	}
	if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
	{
		$supplierid = $_SESSION['supplierid'];
	}
	else
	{
		messagebox('提示', "没有店铺ID!");
		die();
	}

	$errorMsg = array ();
	try
	{
		$shoppingcarts     = getShoppingcartConntent($supplierid, $profileid);
		$productids        = array ();
		$propertyids       = array ();
		$products          = array ();
		$product_propertys = array ();
		foreach ($shoppingcarts as $shoppingcart_info)
		{
			$productid           = $shoppingcart_info->my->productid;
			$productids[]        = $productid;
			$product_property_id = $shoppingcart_info->my->product_property_id;
			if (isset($product_property_id) && $product_property_id != "")
			{
				$propertyids[] = $product_property_id;
			}
		}
		if (count($productids) > 0)
		{
			$product_contents = XN_Content::loadMany(array_unique($productids), "mall_products");
			foreach ($product_contents as $product_info)
			{
				$productid = $product_info->id;
				if ($product_info->my->deleted == 0 && $product_info->my->hitshelf == "on")
				{
					$products[$productid]['shop_price']   = $product_info->my->shop_price;
					$products[$productid]['market_price'] = $product_info->my->market_price;
					$products[$productid]['mergepostage'] = $product_info->my->mergepostage;
					$products[$productid]['postage']      = $product_info->my->postage;
					$products[$productid]["includepost"]  = $product_info->my->include_post_count;
				}
				elseif (in_array($productid, $productids))
				{
					unset($productids[$productid]);
				}
			}
			$productids = array_unique($productids);
		}
		if (count($propertyids) > 0)
		{
			$product_property_contents = XN_Content::loadMany($propertyids, "mall_product_property");
			foreach ($product_property_contents as $product_property_info)
			{
				$productid  = $product_property_info->my->productid;
				$propertyid = $product_property_info->id;
				if (in_array($productid, $productids))
				{
					$product_propertys[$propertyid]['shop_price']   = $product_property_info->my->shop;
					$product_propertys[$propertyid]['market_price'] = $product_property_info->my->market;
				}
				elseif (in_array($propertyid, $propertyids))
				{
					unset($propertyids[$propertyid]);
				}
			}
			$propertyids = array_unique($propertyids);
		}
		$updateContent = array ();
		foreach ($shoppingcarts as $shoppingcart_info)
		{
			$productid = $shoppingcart_info->my->productid;
			if (in_array($productid, $productids))
			{
				$shop_quantity             = $shoppingcart_info->my->quantity;
				$quantity                  = $shop_quantity;
				$product_property_id       = $shoppingcart_info->my->product_property_id;
				$salesactivityid           = $shoppingcart_info->my->salesactivityid;//活动ID
				$salesactivitys_product_id = $shoppingcart_info->my->salesactivitys_product_id;//活动商品ID
				$shop_mergepostage         = $shoppingcart_info->my->mergepostage;
				$shop_postage              = $shoppingcart_info->my->postage;
				$shop_includepost          = $shoppingcart_info->my->includepost;
				$shop_zhekou               = $shoppingcart_info->my->zhekou;
				/**
				 * 检查商品价格是否正常
				 */
				if (isset($product_property_id) && $product_property_id != "")
				{
					if (!isset($product_propertys[$product_property_id]['shop_price']) || $product_propertys[$product_property_id]['shop_price'] == "")
					{
						$shoppingcart_info->my->deleted = "1";
						setBatchsave($updateContent, $shoppingcart_info);
						$errorMsg[] = '商品【'.$shoppingcart_info->my->productname.'】的属性数据异常,已经清理!';
						continue;
					}
					else
					{
						$product_price  = $product_propertys[$product_property_id]['shop_price'];//商品最新的属性价格
						$product_market = $product_propertys[$product_property_id]['market_price'];//商品最新市场价格
					}
				}
				elseif (isset($products[$productid]['shop_price']) && $products[$productid]['shop_price'] != "")
				{
					$product_price  = $products[$productid]['shop_price'];//商品最新价格
					$product_market = $products[$productid]['market_price'];//商品最新市场价格
				}
				else
				{
					$shoppingcart_info->my->deleted = "1";
					setBatchsave($updateContent, $shoppingcart_info);
					$errorMsg[] = '商品【'.$shoppingcart_info->my->productname.'】的价格数据异常,已经清理!';
					continue;
				}
				/**
				 * 处理库存
				 */
				$inventorys = XN_Query::create('Content')->tag('mall_inventorys_' . $supplierid)
									  ->filter('type', 'eic', 'mall_inventorys')
									  ->filter('my.productid', '=', $productid)
									  ->filter("my.deleted", "=", '0')
									  ->end(1);
				if ($product_property_id != "" && $product_property_id != "0")
				{
					$inventorys->filter('my.propertytypeid', '=', $product_property_id);
				}
				$inventorys = $inventorys->execute();
				if (count($inventorys) == 0)
				{
					$shoppingcart_info->my->deleted = "1";
					setBatchsave($updateContent, $shoppingcart_info);
					$errorMsg[] = '商品【'.$shoppingcart_info->my->productname.'】已售空,已经清理!';
					continue;
				}
				else
				{
					$inventory_info = $inventorys[0];
					$inventory      = intval($inventory_info->my->inventory);
					if ($inventory <= 0)
					{
						$shoppingcart_info->my->deleted = "1";
						setBatchsave($updateContent, $shoppingcart_info);
						$errorMsg[] = '商品【'.$shoppingcart_info->my->productname.'】已售空,已经清理!';
						continue;
					}
					elseif ($inventory < intval($shop_quantity))
					{
						$shoppingcart_info->my->quantity = $inventory;
						$quantity                        = $inventory;
						setBatchsave($updateContent, $shoppingcart_info);
					}
					/**
					 * 处理价格
					 */
					if (isset($salesactivityid) && $salesactivityid != "" && $salesactivityid != "0")
					{
						$activity_info = XN_Content::load($salesactivityid, "mall_salesactivitys");
						if ($activity_info->my->deleted == '1' || $activity_info->my->status == '1' || $activity_info->my->begindate > date("Y-m-d") || $activity_info->my->enddate < date("Y-m-d"))
						{
							/**
							 * 活动到期,恢复商品原价格
							 */
							$total_price                                      = number_format(floatval($product_price) * intval($quantity), 2, ".", "");
							$shoppingcart_info->my->old_shop_price            = $product_price;
							$shoppingcart_info->my->shop_price                = $product_price;
							$shoppingcart_info->my->total_price               = $total_price;
							$shoppingcart_info->my->market_price              = $product_market;
							$shoppingcart_info->my->zhekou                    = "";
							$shoppingcart_info->my->zhekoulabel               = "";
							$shoppingcart_info->my->salesactivityid           = "";
							$shoppingcart_info->my->salesactivitys_product_id = "";
							setBatchsave($updateContent, $shoppingcart_info);
						}
						elseif (isset($salesactivitys_product_id) && $salesactivitys_product_id != "" && $salesactivitys_product_id != "0")
						{
							$bargains_count = 0;
							$activitymode = intval($activity_info->my->activitymode);
							$bargainrequirednumber = intval($activity_info->my->bargainrequirednumber);
							if(intval($activitymode) === 1){
								$bargains_products = XN_Query::create("YearContent_Count")->tag("mall_bargains")
															 ->filter("type", "eic", "mall_bargains")
															 ->filter("my.salesactivityid", "=", $salesactivityid)
															 ->filter("my.productid", "=", $productid)
															 ->filter("my.supplierid", "=", $supplierid)
															 ->filter("my.profileid", "=", $profileid)
															 ->filter("my.bargain", "=", '1')
															 ->rollup()
															 ->end(-1);
								$bargains_products->execute();
								$bargains_count = intval($bargains_products->getTotalCount());
								if($bargains_count > $bargainrequirednumber){
									$bargains_count = $bargainrequirednumber;
								}
							}

							$salesactivitys_product_info = XN_Content::load($salesactivitys_product_id, "mall_salesactivitys_products");
							$zhekou                      = $salesactivitys_product_info->my->zhekou;
							$zhekoulabel                 = $salesactivitys_product_info->my->label;
							$shop_market                 = $shoppingcart_info->my->market_price;
							$shop_price                  = $shoppingcart_info->my->shop_price;
							$shop_total                  = $shoppingcart_info->my->total_price;
							$shop_bargains               = intval($shoppingcart_info->my->bargains_count);
							$shop_activitymode           = intval($shoppingcart_info->my->activitymode);
							$shop_bargainrequirednumber  = intval($shoppingcart_info->my->bargainrequirednumber);
//							$bargains_count = 2;
							$new_price        = number_format(floatval($product_price) * floatval($zhekou) / 10, 2, ".", "");
							if (intval($activitymode) === 1)
							{
								$promotionalprice = floatval($product_price) - floatval($product_price) * (10 - floatval($zhekou)) / 10 / $bargainrequirednumber * $bargains_count;
								$total_price      = intval($quantity) * $promotionalprice;
							}
							else
							{
								$promotionalprice = number_format(floatval($product_price) * floatval($zhekou) / 10, 2, ".", "");
								$total_price      = number_format(intval($quantity) * $promotionalprice, 2, ".", "");
							}
							if ($zhekou != $shop_zhekou || $shop_quantity != $quantity || $shop_price != $new_price)
							{
								$shoppingcart_info->my->zhekou         = $zhekou;
								$shoppingcart_info->my->shop_price     = $new_price;
								$shoppingcart_info->my->zhekoulabel    = $zhekoulabel;
								$shoppingcart_info->my->total_price    = $total_price;
								$shoppingcart_info->my->old_shop_price = $product_price;
								setBatchsave($updateContent, $shoppingcart_info);
							}
							elseif ($shop_total != $total_price)
							{
								$shoppingcart_info->my->total_price = $total_price;
								setBatchsave($updateContent, $shoppingcart_info);
							}
							if ($shop_market != $product_market)
							{
								$shoppingcart_info->my->market_price = $product_market;
								setBatchsave($updateContent, $shoppingcart_info);
							}
							if($shop_activitymode != $activitymode){
								$shoppingcart_info->my->activitymode = $activitymode;
								setBatchsave($updateContent, $shoppingcart_info);
							}
							if($shop_bargains != $bargains_count){
								$shoppingcart_info->my->bargains_count = $bargains_count;
								setBatchsave($updateContent, $shoppingcart_info);
							}
							if($shop_bargainrequirednumber != $bargainrequirednumber){
								$shoppingcart_info->my->bargainrequirednumber = $bargainrequirednumber;
								setBatchsave($updateContent, $shoppingcart_info);
							}
						}
						else
						{
							/**
							 * 活动商品信息错误,直接删除
							 */
							$shoppingcart_info->my->deleted = "1";
							setBatchsave($updateContent, $shoppingcart_info);
							$errorMsg[] = '商品【'.$shoppingcart_info->my->productname.'】活动数据异常,已经清理!';
							continue;
						}
					}
					else
					{
						$shop_price  = $shoppingcart_info->my->shop_price;
						$shop_market = $shoppingcart_info->my->market_price;
						$shop_total  = $shoppingcart_info->my->total_price;
						$total_price = number_format(floatval($product_price) * intval($quantity), 2, ".", "");
						if ($shop_price != $product_price || $shop_quantity != $quantity)
						{
							$total_price                        = number_format(intval($quantity) * $product_price, 2, ".", "");
							$shoppingcart_info->my->shop_price  = number_format($product_price, 2, ".", "");
							$shoppingcart_info->my->total_price = number_format($total_price, 2, ".", "");
							setBatchsave($updateContent, $shoppingcart_info);
						}
						if ($shop_market != $product_market)
						{
							$shoppingcart_info->my->market_price = number_format($product_market, 2, ".", "");
							setBatchsave($updateContent, $shoppingcart_info);
						}
						if ($shop_total != $total_price)
						{
							$shoppingcart_info->my->total_price = number_format($total_price, 2, ".", "");
							setBatchsave($updateContent, $shoppingcart_info);
						}
					}
					/**
					 * 计算邮费
					 */
					$postage     = $products[$productid]["postage"];
					$includepost = $products[$productid]["includepost"];
					if ($shop_postage != $postage)
					{
						$shoppingcart_info->my->postage = number_format(floatval($postage), 2, ".", "");
						setBatchsave($updateContent, $shoppingcart_info);
					}
					if ($shop_mergepostage != $products[$productid]["mergepostage"])
					{
						$shoppingcart_info->my->mergepostage = $products[$productid]["mergepostage"];
						setBatchsave($updateContent, $shoppingcart_info);
					}
					if ($shop_includepost != $includepost)
					{
						$shoppingcart_info->my->includepost = intval($includepost);
						setBatchsave($updateContent, $shoppingcart_info);
					}
				}
			}
			else
			{
				$shoppingcart_info->my->deleted = "1";
				setBatchsave($updateContent, $shoppingcart_info);
				$errorMsg[] = '商品【'.$shoppingcart_info->my->productname.'】已下架停售,已经清理!';
			}
		}
		if (count($updateContent) > 0)
		{
			XN_Content::batchsave($updateContent, 'mall_shoppingcarts,mall_shoppingcarts_'.$profileid);
			$shoppingcarts = getShoppingcartConntent($supplierid, $profileid);
		}
		$shoppingcart    = array ();
		$idlists         = array ();
		$total_money     = 0;
		$total_quantity  = 0;
		$allpostage      = 0;
		$allmergepostage = 0;

		function productquantity($shoppingcarts, $productid){
			$pct = 0;
			foreach($shoppingcarts as $info){
				if($info->my->productid == $productid){
					$pct += intval($info->my->quantity);
				}
			}
			return $pct;
		}
		$totalpricefreeshipping = 0;
		$totalquantityfreeshipping = 0;
		$supplier_info = get_supplier_info();
		foreach ($shoppingcarts as $shoppingcart_info){
			$totalpricefreeshipping += floatval($shoppingcart_info->my->shop_price) * intval($shoppingcart_info->my->quantity);
			$totalquantityfreeshipping += intval($shoppingcart_info->my->quantity);
		}

		foreach ($shoppingcarts as $shoppingcart_info)
		{
			$shoppingcartid        = $shoppingcart_info->id;
			$productid             = $shoppingcart_info->my->productid;
			$productname           = $shoppingcart_info->my->productname;
			$productthumbnail      = $shoppingcart_info->my->productthumbnail;
			$quantity              = $shoppingcart_info->my->quantity;
			$propertydesc          = $shoppingcart_info->my->propertydesc;
			$product_property_id   = $shoppingcart_info->my->product_property_id;
			$zhekou                = $shoppingcart_info->my->zhekou;
			$zhekoulabel           = $shoppingcart_info->my->label;
			$shop_mergepostage     = $shoppingcart_info->my->mergepostage;
			$shop_postage          = $shoppingcart_info->my->postage;
			$shop_price            = $shoppingcart_info->my->shop_price;
			$old_shop_price        = $shoppingcart_info->my->old_shop_price;
			$total_price           = $shoppingcart_info->my->total_price;
			$market_price          = $shoppingcart_info->my->market_price;
			$includepost           = $shoppingcart_info->my->includepost;
			$uniquesale           = $shoppingcart_info->my->uniquesale;
			$bargains_count        = intval($shoppingcart_info->my->bargains_count);
			$activitymode          = intval($shoppingcart_info->my->activitymode);
			$bargainrequirednumber = intval($shoppingcart_info->my->bargainrequirednumber);
			
			global $APISERVERADDRESS,$width;
			if (isset($productthumbnail) && $productthumbnail != "")
			{
				$width = 320;
				//$productlogo = $APISERVERADDRESS.$product_info->my->productlogo."?width=".$width;
				$productthumbnail = $APISERVERADDRESS.$productthumbnail;
			}
			
			$vendorid = $shoppingcart_info->my->vendorid;
			if (isset($vendorid) && $vendorid != "" && $vendorid != "0")
			{
				$vendor_info = XN_Content::load($vendorid,"mall_vendors_".$supplierid); 
				$vendorname = $vendor_info->my->vendorname;
			}
			else
			{
				$vendorname = "";
			}

			$tmppostage      = 0;
			if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
			   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
			{
				$productallcount = productquantity($shoppingcarts, $productid);
				if ($includepost <= 0 || $includepost > $productallcount)
				{
					$tmppostage = $shop_postage;
					if (intval($shop_mergepostage) != 1)
					{
						$tmppostage = $shop_postage * $quantity;
						$allmergepostage += $tmppostage;
					}
					elseif ($allpostage < $shop_postage)
					{
						$allpostage = $shop_postage;
					}
				}
			}
			$product_info = array (
				'id'                    => $shoppingcartid,
				'productid'             => $productid,
				'productallcount'		=> $productallcount,
				'productname'           => $productname,
				'productthumbnail'      => $productthumbnail,
				'quantity'              => $quantity,
				'zhekou'                => $zhekou,
				'zhekoulabel'           => $zhekoulabel,
				'shop_price'            => number_format($shop_price, 2, ".", ""),
				'old_shop_price'        => number_format($old_shop_price, 2, ".", ""),
				'market_price'          => number_format($market_price, 2, ".", ""),
				'total_price'           => number_format($total_price + $tmppostage, 2, ".", ""),
				'product_property_id'   => $product_property_id,
				'propertydesc'          => $propertydesc,
				'mergepostage'          => $shop_mergepostage,
				'postage'               => number_format($shop_postage, 2, ".", ""),
				'includepost'           => $includepost,
				'bargains_count'        => $bargains_count,
				'activitymode'          => $activitymode,
				'bargainrequirednumber' => $bargainrequirednumber,
				'vendorid'              => $vendorid,
				'vendorname'            => $vendorname,
				'uniquesale'            => $uniquesale,
				
			);
			$shoppingcart[] = $product_info;
			$total_money += $total_price;
			$total_quantity += $quantity;
			$idlists[] = $shoppingcartid;
		}
		$total_money += $allpostage + $allmergepostage;
	}
	catch (XN_Exception $e)
	{
		$shoppingcarts = getShoppingcartConntent($supplierid, $profileid);
		$updateContent = array ();
		foreach ($shoppingcarts as $shoppingcart_info)
		{
			$shoppingcart_info->my->deleted = "1";
			setBatchsave($updateContent, $shoppingcart_info);
		}
		if (count($updateContent) > 0)
		{
			XN_Content::batchsave($updateContent, 'mall_shoppingcarts,mall_shoppingcarts_'.$profileid);
			messagebox('提示', '因商品下架等原因，您的购物车数据异常，系统自动清除购物车，麻烦您重新选择商品，对您造成的不便敬请谅解!', 'shoppingcart.php', 10);
		}
		die();
	}

	/**
	 * 处理完数据,输出
	 */
	require_once('Smarty_setup.php');
	$smarty    = new vtigerCRM_Smarty;
	$islogined = false;
	if ($_SESSION['u'] == $_SESSION['profileid'])
	{
		$islogined = true;
	}
	$action         = strtolower(basename(__FILE__, ".php"));
	$recommend_info = checkrecommend();
	$smarty->assign("islogined", $islogined);
	$smarty->assign("share_info", $recommend_info);
	$smarty->assign("supplier_info", $supplier_info);
	$profile_info = get_supplier_profile_info();
	if (count($profile_info) > 0)
	{
		$smarty->assign("profile_info", $profile_info);
	}
	else
	{
		$smarty->assign("profile_info", get_profile_info());
	}
	$smarty->assign("shoppingcarts", $shoppingcart);
	$smarty->assign("idlists", json_encode($idlists));
	$smarty->assign("total_money", number_format($total_money, 2, ".", ""));
	$smarty->assign("total_quantity", $total_quantity);
	$smarty->assign("errorMsg", implode("<br>", $errorMsg));
	$guid = guid();
	$smarty->assign("token", $guid);
	$sysinfo                    = array ();
	$sysinfo['action']          = $action;
	$sysinfo['date']            = date("md");
	$sysinfo['api']             = $APISERVERADDRESS;
	$sysinfo['http_user_agent'] = check_http_user_agent();
	$sysinfo['domain']          = $WX_DOMAIN;
	$sysinfo['width']           = $_SESSION['width'];
	$smarty->assign("sysinfo", $sysinfo);
	
	
	$time = strtotime("now");
	//$time = strtotime('-1 hours',$time);
	$time = strtotime('-1 months',$time);
	$supplier_wxsettings = XN_Query::create('MainContent')->tag('supplier_wxsettings')
                ->filter('type', 'eic', 'supplier_wxsettings')
                ->filter('my.deleted', '=', '0')
                ->filter('my.supplierid', '=', $supplierid)
                ->end(1)
                ->execute();
    if (count($supplier_wxsettings) > 0)
    {
        $supplier_wxsetting_info = $supplier_wxsettings[0];
        $appid = $supplier_wxsetting_info->my->appid;
        require_once(XN_INCLUDE_PREFIX . "/XN/Message.php");
	    $supplier_profile = XN_Query::create('MainContent')->tag("supplier_profile")
	        ->filter('type', 'eic', 'supplier_profile') 
			->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.repurchasestatus', '=', '0')
			->filter('my.purchasedate', '<', date("Y-m-d H:i:s",$time))
	        ->filter('my.deleted', '=', '0') 
	        ->end(100)
	        ->execute();
	    if (count($supplier_profile) > 0) 
		{ 
			foreach($supplier_profile as $supplier_profile_info)
			{
				$profileid = $supplier_profile_info->my->profileid; 
				$wxopenid =  $supplier_profile_info->my->wxopenid;
				$supplier_profile_info->my->repurchasestatus = '1';  
			    $supplier_profile_info->save("supplier_profile,supplier_profile_".$profileid.",supplier_profile_".$supplierid.",supplier_profile_".$wxopenid);
				XN_Message::sendmessage($profileid, '您的分佣资格已经过期，请及时复购!', $appid);
			}
		}
		
    }
    
		
		
	$smarty->display($action.'.tpl');
