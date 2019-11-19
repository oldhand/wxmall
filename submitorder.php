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

	function productquantity($contents, $productid){
		$pct = 0;
		foreach($contents as $info){
			if($info->my->productid == $productid){
				$pct += intval($info->my->quantity);
			}
		}
		return $pct;
	}


	session_start();

	require_once(dirname(__FILE__)."/config.inc.php");
	require_once(dirname(__FILE__)."/config.common.php");
	require_once(dirname(__FILE__)."/util.php");
	require_once(dirname(__FILE__)."/config.postage.php");

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
		messagebox('错误', '请从微信公众号“特赞商城”或朋友圈中朋友分享链接进入本平台，如您确实采用上述方式仍然出现本信息，请与系统管理员联系。');
		die();
	}

	if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
	{
		$supplierid = $_SESSION['supplierid'];
	}
	else
	{
		echo '{"code":201,"msg":"没有店铺ID!"}';
		die();
	}

	if(isset($_REQUEST['record']) && $_REQUEST['record'] !='' )
	{
		$shoppingcart = '';
		$token = '';
	}
	else
	{
		if(isset($_REQUEST['shoppingcart']) && $_REQUEST['shoppingcart'] !='' )
		{
			$shoppingcart = $_REQUEST['shoppingcart'];
			$_SESSION['shoppingcart'] = $shoppingcart;
		}
		else
		{
			if(isset($_SESSION['shoppingcart']) && $_SESSION['shoppingcart'] !='')
			{
				$shoppingcart = $_SESSION['shoppingcart'];
			}
			else
			{
				messagebox('错误','您的购物车提交数据异常，请与管理者联系!','shoppingcart.php',10);
				die();
			}
		}

		if(isset($_REQUEST['token']) && $_REQUEST['token'] !='' )
		{
			$token = $_REQUEST['token'];
			$_SESSION['token'] = $token;
		}
		else
		{
			if(isset($_SESSION['token']) && $_SESSION['token'] !='')
			{
				$token = $_SESSION['token'];
			}
			else
			{
				messagebox('提示','token数据异常!');
				die();
			}

		}
	}
	$deliveraddressinfo = array ();
	/**
	 * 获取地址信息
	 */
	if (isset($_REQUEST['deliveraddressid']) && $_REQUEST['deliveraddressid'] != '')
	{
		$deliveraddressid = $_REQUEST['deliveraddressid'];
		try
		{
			$deliveContent                      = array ();
			$deliveraddress_info                = XN_Content::load($deliveraddressid, 'deliveraddress_'.$profileid, 4);
			$deliveraddressinfo['recordid']     = $deliveraddressid;
			$deliveraddressinfo['consignee']    = $deliveraddress_info->my->consignee;
			$deliveraddressinfo['province']     = $deliveraddress_info->my->province;
			$deliveraddressinfo['city']         = $deliveraddress_info->my->city;
			$deliveraddressinfo['district']     = $deliveraddress_info->my->district;
			$deliveraddressinfo['address']      = $deliveraddress_info->my->address;
			$deliveraddressinfo['shortaddress'] = $deliveraddress_info->my->shortaddress;
			$deliveraddressinfo['zipcode']      = $deliveraddress_info->my->zipcode;
			$deliveraddressinfo['mobile']       = $deliveraddress_info->my->mobile;
			$deliveraddressinfo['selected']     = $deliveraddress_info->my->selected;
			if ($deliveraddress_info->my->selected != '1')
			{
				$deliveraddress_info->my->selected = '1';
				setBatchsave($deliveContent, $deliveraddress_info);
				$deliveraddress = XN_Query::create('MainContent')->tag('deliveraddress_'.$profileid)
										  ->filter('type', 'eic', 'deliveraddress')
										  ->filter('my.profileid', '=', $profileid)
										  ->filter('id', '!=', $deliveraddressid)
										  ->execute();
				foreach ($deliveraddress as $deliveraddress_info)
				{
					$deliveraddress_info->my->selected = '0';
					setBatchsave($deliveContent, $deliveraddress_info);
				}
			}
			if (count($deliveContent) > 0)
			{
				XN_Content::batchsave($deliveContent, "deliveraddress,deliveraddress_".$profileid);
			}
		}
		catch (XN_Exception $e)
		{
			messagebox('提示', '读取收货地址数据异常!');
			die();
		}
	}
	else
	{
		$deliveraddress = XN_Query::create('MainContent')->tag('deliveraddress_'.$profileid)
								  ->filter('type', 'eic', 'deliveraddress')
								  ->filter('my.profileid', '=', $profileid)
								  ->filter('my.selected', '=', '1')
								  ->execute();
		if (count($deliveraddress) > 0)
		{
			$deliveraddress_info                = $deliveraddress[0];
			$deliveraddressinfo['recordid']     = $deliveraddress_info->id;
			$deliveraddressinfo['consignee']    = $deliveraddress_info->my->consignee;
			$deliveraddressinfo['province']     = $deliveraddress_info->my->province;
			$deliveraddressinfo['city']         = $deliveraddress_info->my->city;
			$deliveraddressinfo['district']     = $deliveraddress_info->my->district;
			$deliveraddressinfo['address']      = $deliveraddress_info->my->address;
			$deliveraddressinfo['shortaddress'] = $deliveraddress_info->my->shortaddress;
			$deliveraddressinfo['zipcode']      = $deliveraddress_info->my->zipcode;
			$deliveraddressinfo['mobile']       = $deliveraddress_info->my->mobile;
			$deliveraddressinfo['selected']     = $deliveraddress_info->my->selected;
		}
	}

	/**
	 * 获取发票信息
	 */
	if (isset($_REQUEST['fapiao']) && $_REQUEST['fapiao'] != '')
	{
		$fapiao  = $_REQUEST['fapiao'];
		$fapiaos = XN_Query::create('MainContent')->tag('fapiao_'.$profileid)
						   ->filter('type', 'eic', 'fapiao')
						   ->filter('my.profileid', '=', $profileid)
						   ->end(1)
						   ->execute();
		if (count($fapiaos) > 0)
		{
			$fapiao_info = $fapiaos[0];
			$fapiaoname  = $fapiao_info->my->fapiao;
			if ($fapiao != $fapiaoname)
			{
				$fapiao_info->my->fapiao = $fapiao;
				$fapiao_info->save('fapiao,fapiao_'.$profileid);
			}
		}
		else
		{
			$newcontent                = XN_Content::create('fapiao', '', false);
			$newcontent->my->deleted   = '0';
			$newcontent->my->profileid = $profileid;
			$newcontent->my->fapiao    = $fapiao;
			$newcontent->save("fapiao,fapiao_".$profileid);
		}
	}
	else
	{
		$fapiao = '';
	}

	$returnbackatcion = "/shoppingcart.php";
	$supplier_info = get_supplier_info();
	try
	{
		$tradestatus = '';
		$distance    = '';
		if (isset($_REQUEST['record']) && $_REQUEST['record'] != '')
		{
			$returnbackatcion        = "/orders_pendingpayment.php";
			$orderid                 = $_REQUEST['record'];
			$mall_order_info         = XN_Content::load($orderid, 'mall_orders_'.$profileid, 7);
			$total_money             = $mall_order_info->my->orderstotal;
			$sumorderstotal          = $mall_order_info->my->sumorderstotal;
			$total_quantity          = $mall_order_info->my->productcount;
			$expectedconsumptiontime = $mall_order_info->my->expectedconsumptiontime;
			$customersmsg            = $mall_order_info->my->customersmsg;
			$tradestatus             = $mall_order_info->my->tradestatus;
			$paymentmode             = $mall_order_info->my->paymentmode;
			$paymentway              = $mall_order_info->my->paymentway;
			$delivermode             = $mall_order_info->my->delivermode;
			$distance                = $mall_order_info->my->distance;
			$fapiao                  = $mall_order_info->my->fapiaoname;
			$orderpostage            = $mall_order_info->my->postage;
			$orderaddpostage         = $mall_order_info->my->addpostage;
			$orderdeliveraddressid   = $mall_order_info->my->deliveraddressid;
			if ($mall_order_info->my->tradestatus == "trade")
			{
				header("Location: orderdetail.php?record=".$mall_order_info->id);
				die();
			}

			if (!isset($_REQUEST['deliveraddressid']) || $_REQUEST['deliveraddressid'] == '')
			{
				$deliveraddressinfo['recordid']     = $mall_order_info->my->deliveraddressid;
				$deliveraddressinfo['consignee']    = $mall_order_info->my->consignee;
				$deliveraddressinfo['province']     = $mall_order_info->my->province;
				$deliveraddressinfo['city']         = $mall_order_info->my->city;
				$deliveraddressinfo['district']     = $mall_order_info->my->district;
				$deliveraddressinfo['address']      = $mall_order_info->my->address;
				$deliveraddressinfo['shortaddress'] = $mall_order_info->my->shortaddress;
				$deliveraddressinfo['zipcode']      = $mall_order_info->my->zipcode;
				$deliveraddressinfo['mobile']       = $mall_order_info->my->mobile;
			}
			$shoppingcart = array ();

			$orders_products = XN_Query::create('YearContent')->tag('mall_orders_products_'.$profileid)
									   ->filter('type', 'eic', 'mall_orders_products')
									   ->filter('my.orderid', '=', $orderid)
									   ->filter('my.deleted', '=', '0')
									   ->end(-1)
									   ->execute();
			/**
			 * 获取商品最新价
			 */
			$productids        = array ();
			$propertyids       = array ();
			$products          = array ();
			$product_propertys = array ();
			foreach ($orders_products as $orders_product_info)
			{
				$productid           = $orders_product_info->my->productid;
				$productids[]        = $productid;
				$product_property_id = $orders_product_info->my->product_property_id;
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
			foreach ($orders_products as $orders_product_info)
			{
				$productid = $orders_product_info->my->productid;
				if (in_array($productid, $productids))
				{
					$shop_quantity             = $orders_product_info->my->quantity;
					$quantity                  = $shop_quantity;
					$product_property_id       = $orders_product_info->my->product_property_id;
					$salesactivityid           = $orders_product_info->my->salesactivityid;//活动ID
					$salesactivitys_product_id = $orders_product_info->my->salesactivitys_product_id;//活动商品ID
					$shop_mergepostage         = $orders_product_info->my->mergepostage;
					$shop_postage              = $orders_product_info->my->postage;
					$shop_includepost          = $orders_product_info->my->includepost;
					$shop_zhekou               = $orders_product_info->my->zhekou;

					if (isset($product_property_id) && $product_property_id != "")
					{
						if (isset($product_propertys[$product_property_id]['shop_price']) && $product_propertys[$product_property_id]['shop_price'] != "")
						{
							$product_price  = $product_propertys[$product_property_id]['shop_price'];//商品最新的属性价格
							$product_market = $product_propertys[$product_property_id]['market_price'];//商品最新市场价格
						}
						else
						{
							messagebox('错误', '您的购物订单属性价格异常，请与管理者联系!', 'orders_pendingpayment',10);
							die();
						}
					}
					elseif (isset($products[$productid]['shop_price']) && $products[$productid]['shop_price'] != "")
					{
						$product_price  = $products[$productid]['shop_price'];//商品最新价格
						$product_market = $products[$productid]['market_price'];//商品最新市场价格
					}
					else
					{
						messagebox('错误', '您的购物订单商品价格异常，请与管理者联系!', 'orders_pendingpayment',10);
						die();
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
						messagebox('错误', '您的购物订单商品已售完，下次下手得快点哦!', 'orders_pendingpayment',10);
						die();
					}
					else
					{
						$inventory_info = $inventorys[0];
						$inventory      = intval($inventory_info->my->inventory);
						if ($inventory <= 0)
						{
							messagebox('错误', '您的购物订单商品已售完，下次下手得快点哦!', 'orders_pendingpayment',10);
							die();
						}
						elseif ($inventory < intval($shop_quantity))
						{
							$orders_product_info->my->quantity = $inventory;
							$quantity                          = $inventory;
							setBatchsave($updateContent, $orders_product_info);
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
								$total_price                                        = number_format(floatval($product_price) * intval($quantity), 2, ".", "");
								$orders_product_info->my->old_shop_price            = $product_price;
								$orders_product_info->my->shop_price                = $product_price;
								$orders_product_info->my->total_price               = $total_price;
								$orders_product_info->my->market_price              = $product_market;
								$orders_product_info->my->zhekou                    = "";
								$orders_product_info->my->zhekoulabel               = "";
								$orders_product_info->my->salesactivityid           = "";
								$orders_product_info->my->salesactivitys_product_id = "";
								setBatchsave($updateContent, $orders_product_info);
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
								$shop_price                  = $orders_product_info->my->shop_price;
								$shop_total                  = $orders_product_info->my->total_price;
								$zhekoulabel                 = $salesactivitys_product_info->my->label;
								$shop_market                 = $orders_product_info->my->market_price;
								$new_price                   = number_format(floatval($product_price) * floatval($zhekou) / 10, 2, ".", "");
								$shop_bargains               = intval($orders_product_info->my->bargains_count);
								$shop_activitymode           = intval($orders_product_info->my->activitymode);
								$shop_bargainrequirednumber  = intval($orders_product_info->my->bargainrequirednumber);

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
									$orders_product_info->my->zhekou         = $zhekou;
									$orders_product_info->my->shop_price     = $new_price;
									$orders_product_info->my->zhekoulabel    = $zhekoulabel;
									$orders_product_info->my->old_shop_price = $product_price;
									setBatchsave($updateContent, $orders_product_info);
								}
								if ($shop_total != $total_price){
									$orders_product_info->my->total_price    = $total_price;
									setBatchsave($updateContent, $orders_product_info);
								}
								if ($shop_market != $product_market)
								{
									$orders_product_info->my->market_price = $product_market;
									setBatchsave($updateContent, $orders_product_info);
								}
								if($shop_activitymode != $activitymode){
									$orders_product_info->my->activitymode = $activitymode;
									setBatchsave($updateContent, $orders_product_info);
								}
								if($shop_bargains != $bargains_count){
									$orders_product_info->my->bargains_count = $bargains_count;
									setBatchsave($updateContent, $orders_product_info);
								}
								if($shop_bargainrequirednumber != $bargainrequirednumber){
									$orders_product_info->my->bargainrequirednumber = $bargainrequirednumber;
									setBatchsave($updateContent, $orders_product_info);
								}
							}
							else
							{
								/**
								 * 活动商品信息异常处理
								 */
								$total_price                                        = number_format(floatval($product_price) * intval($quantity), 2, ".", "");
								$orders_product_info->my->old_shop_price            = $product_price;
								$orders_product_info->my->shop_price                = $product_price;
								$orders_product_info->my->total_price               = $total_price;
								$orders_product_info->my->market_price              = $product_market;
								$orders_product_info->my->zhekou                    = "";
								$orders_product_info->my->zhekoulabel               = "";
								$orders_product_info->my->salesactivityid           = "";
								$orders_product_info->my->salesactivitys_product_id = "";
								setBatchsave($updateContent, $orders_product_info);
							}
						}
						else
						{
							$shop_price  = $orders_product_info->my->shop_price;
							$shop_market = $orders_product_info->my->market_price;
							$shop_total  = $orders_product_info->my->total_price;
							$total_price = number_format(floatval($product_price) * intval($quantity), 2, ".", "");
							if (floatval($shop_price) != floatval($product_price) || intval($shop_quantity) != intval($quantity))
							{
								$total_price                          = number_format(intval($quantity) * $product_price, 2, ".", "");
								$orders_product_info->my->shop_price  = $product_price;
								$orders_product_info->my->total_price = $total_price;
								setBatchsave($updateContent, $orders_product_info);
							}
							if (floatval($shop_market) != floatval($product_market))
							{
								$orders_product_info->my->market_price = $product_market;
								setBatchsave($updateContent, $orders_product_info);
							}
							if (floatval($shop_total) != floatval($total_price))
							{
								$orders_product_info->my->total_price = $total_price;
								setBatchsave($updateContent, $orders_product_info);
							}
						}
						/**
						 * 计算邮费
						 */
						$postage     = $products[$productid]["postage"];
						$includepost = $products[$productid]["includepost"];
						if (floatval($shop_postage) != floatval($postage))
						{
							$orders_product_info->my->postage = number_format(floatval($postage), 2, ".", "");
							setBatchsave($updateContent, $orders_product_info);
						}
						if (intval($shop_mergepostage) != intval($products[$productid]["mergepostage"]))
						{
							$orders_product_info->my->mergepostage = $products[$productid]["mergepostage"];
							setBatchsave($updateContent, $orders_product_info);
						}
						if (intval($shop_includepost) != intval($includepost))
						{
							$orders_product_info->my->includepost = intval($includepost);
							setBatchsave($updateContent, $orders_product_info);
						}
					}
				}
				else
				{
					messagebox('错误', '您的购物订单商品已停售，下次下手得快点哦!', 'orders_pendingpayment',10);
					die();
				}
			}
			if (count($updateContent) > 0)
			{
				XN_Content::batchsave($updateContent, 'mall_orders_products,mall_orders_products_'.$profileid);
				$orders_products = XN_Query::create('YearContent')->tag('mall_orders_products_'.$profileid)
										   ->filter('type', 'eic', 'mall_orders_products')
										   ->filter('my.orderid', '=', $orderid)
										   ->filter('my.deleted', '=', '0')
										   ->end(-1)
										   ->execute();
			}

			$verify_total_money    = 0;
			$verify_alltotal_money = 0;
			$verify_total_quantity = 0;
			$allpostage            = 0;
			$allmergepostage       = 0;
			$addinfo               = array ();

			$totalpricefreeshipping = 0;
			$totalquantityfreeshipping = 0;
			foreach ($orders_products as $orders_product_info){
				$totalpricefreeshipping += floatval($orders_product_info->my->shop_price) * intval($orders_product_info->my->quantity);
				$totalquantityfreeshipping += intval($orders_product_info->my->quantity);
			}

			foreach ($orders_products as $orders_product_info)
			{
				$quantity              = $orders_product_info->my->quantity;
				$shop_price            = $orders_product_info->my->shop_price;
				$total_price           = $orders_product_info->my->total_price;
				$shop_postage          = $orders_product_info->my->postage;
				$mergepostage          = $orders_product_info->my->mergepostage;
				$includepost           = $orders_product_info->my->includepost;
				$bargains_count        = intval($orders_product_info->my->bargains_count);
				$activitymode          = intval($orders_product_info->my->activitymode);
				$bargainrequirednumber = intval($orders_product_info->my->bargainrequirednumber);

				$tmppostage      = 0;
				if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
				   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
				{
					$productallcount = productquantity($orders_products, $orders_product_info->my->productid);
					if ($includepost <= 0 || $includepost > $productallcount)
					{
						$tmppostage = $shop_postage;
						if (intval($mergepostage) != 1)
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
					'id'                        => $orders_product_info->id,
					'productid'                 => $orders_product_info->my->productid,
					'productallcount'			=> $productallcount,
					'productname'               => $orders_product_info->my->productname,
					'productthumbnail'          => $orders_product_info->my->productthumbnail,
					'quantity'                  => $quantity,
					'shop_price'                => number_format($shop_price, 2, ".", ""),
					'market_price'              => number_format($orders_product_info->my->market_price, 2, ".", ""),
					'total_price'               => number_format(floatval($total_price) + floatval($tmppostage), 2, ".", ""),
					'product_property_id'       => $orders_product_info->my->product_property_id,
					'propertydesc'              => $orders_product_info->my->propertydesc,
					'old_shop_price'            => number_format($orders_product_info->my->old_shop_price, 2, ".", ""),
					'zhekou'                    => $orders_product_info->my->zhekou,
					'salesactivityid'           => $orders_product_info->my->salesactivityid,
					'salesactivitys_product_id' => $orders_product_info->my->salesactivitys_product_id,
					'zhekoulabel'               => $orders_product_info->my->zhekoulabel,
					'mergepostage'              => $mergepostage,
					'postage'                   => number_format($shop_postage, 2, ".", ""),
					'includepost'               => $includepost,
					'bargains_count'            => $bargains_count,
					'activitymode'              => $activitymode,
					'bargainrequirednumber'     => $bargainrequirednumber,
				);
				$addinfo[]      = array ("merge" => $product_info["mergepostage"], "quantity" => $product_info["quantity"]);
				$shoppingcart[] = $product_info;
				$verify_total_money += $total_price;
				$verify_total_quantity += $quantity;

			}
			if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
			   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
			{
				$addpostage = getPostage($supplierid, $deliveraddressinfo["province"], $addinfo);
			}else{
				$addpostage = 0;
			}
			$verify_alltotal_money = $verify_total_money + $allmergepostage + $allpostage + $addpostage;
			$orderContent          = array ();
			if (round($verify_total_money, 2) != round($total_money, 2) ||
				$total_quantity != $verify_total_quantity ||
				round($sumorderstotal, 2) != round($verify_alltotal_money, 2) ||
				round($orderpostage, 2) != round($allpostage + $allmergepostage, 2) ||
				round($orderaddpostage, 2) != round($addpostage, 2)
			)
			{
				$mall_order_info                     = XN_Content::load($orderid, 'mall_orders_'.$profileid, 7);
				$mall_order_info->my->sumorderstotal = number_format($verify_alltotal_money, 2, ".", "");
				$mall_order_info->my->amountpayable  = number_format($verify_alltotal_money, 2, ".", "");
				$mall_order_info->my->paymentamount  = "0.00";
				$mall_order_info->my->orderstotal    = number_format($verify_total_money, 2, ".", "");
				$mall_order_info->my->postage        = number_format($allpostage + $allmergepostage, 2, ".", "");
				$mall_order_info->my->addpostage     = number_format($addpostage, 2, ".", "");
				$mall_order_info->my->productcount   = $verify_total_quantity;
				setBatchsave($orderContent, $mall_order_info);
			}
			if ($orderdeliveraddressid != $deliveraddressinfo['recordid'])
			{
				$mall_order_info->my->deliveraddressid = $deliveraddressinfo['recordid'];
				$mall_order_info->my->consignee        = $deliveraddressinfo['consignee'];
				$mall_order_info->my->province         = $deliveraddressinfo['province'];
				$mall_order_info->my->city             = $deliveraddressinfo['city'];
				$mall_order_info->my->district         = $deliveraddressinfo['district'];
				$mall_order_info->my->address          = $deliveraddressinfo['address'];
				$mall_order_info->my->shortaddress     = $deliveraddressinfo['shortaddress'];
				$mall_order_info->my->zipcode          = $deliveraddressinfo['zipcode'];
				$mall_order_info->my->mobile           = $deliveraddressinfo['mobile'];
				setBatchsave($orderContent, $mall_order_info);
			}
			if((floatval($supplier_info['totalpricefreeshipping'] > 0) && floatval($supplier_info['totalpricefreeshipping']) <= $totalpricefreeshipping) || (intval($supplier_info['totalquantityfreeshipping']) > 0 && intval($supplier_info['totalquantityfreeshipping']) <= $totalquantityfreeshipping))
			{
				if (number_format(floatval($mall_order_info->my->totalpricefreeshipping), 2, ".", "") != number_format(floatval($supplier_info['totalpricefreeshipping']), 2, ".", ""))
				{
					$mall_order_info->my->totalpricefreeshipping = $supplier_info['totalpricefreeshipping'];
					setBatchsave($orderContent, $mall_order_info);
				}
				if (intval($mall_order_info->my->totalquantityfreeshipping) != intval($supplier_info['totalquantityfreeshipping']))
				{
					$mall_order_info->my->totalquantityfreeshipping = $supplier_info['totalquantityfreeshipping'];
					setBatchsave($orderContent, $mall_order_info);
				}
			}else{
				$mall_order_info->my->totalpricefreeshipping = '0.00';
				$mall_order_info->my->totalquantityfreeshipping = '0';
				setBatchsave($orderContent, $mall_order_info);
			}
			if (count($orderContent) > 0)
			{
				XN_Content::batchsave($orderContent, 'mall_orders,mall_orders_'.$profileid.',mall_orders_'.$supplierid);
			}
			$total_money    = $verify_alltotal_money;
			$total_quantity = $verify_total_quantity;
		}
		elseif(isset($token) && !empty($token))
		{
			$returnbackatcion = "/orders_pendingpayment.php";
			$mall_orders      = XN_Query::create('YearContent')->tag('mall_orders_'.$profileid)
										->filter('type', 'eic', 'mall_orders')
										->filter('my.token', '=', $token)
										->filter('my.deleted', '=', '0')
										->end(-1)
										->execute();
			if (count($mall_orders) > 0)
			{
				$mall_order_info         = $mall_orders[0];
				$orderid                 = $mall_order_info->id;
				$total_money             = $mall_order_info->my->orderstotal;
				$total_quantity          = $mall_order_info->my->productcount;
				$expectedconsumptiontime = $mall_order_info->my->expectedconsumptiontime;
				$customersmsg            = $mall_order_info->my->customersmsg;
				$sumorderstotal          = $mall_order_info->my->sumorderstotal;
				$orderpostage            = $mall_order_info->my->postage;
				$orderaddpostage         = $mall_order_info->my->addpostage;
				$orderdeliveraddressid   = $mall_order_info->my->deliveraddressid;
				if ($mall_order_info->my->tradestatus == "trade")
				{
					header("Location: orderdetail.php?record=".$orderid);
					die();
				}

				$shoppingcart          = array ();
				$verify_alltotal_money = 0;
				$verify_total_quantity = 0;
				$allpostage            = 0;
				$allmergepostage       = 0;
				$addinfo               = array ();
				$orders_products       = XN_Query::create('YearContent')->tag('mall_orders_products_'.$profileid)
												 ->filter('type', 'eic', 'mall_orders_products')
												 ->filter('my.orderid', '=', $orderid)
												 ->filter('my.deleted', '=', '0')
												 ->end(-1)
												 ->execute();
				$totalpricefreeshipping = 0;
				$totalquantityfreeshipping = 0;
				foreach ($orders_products as $orders_product_info){
					$totalpricefreeshipping += floatval($orders_product_info->my->shop_price) * intval($orders_product_info->my->quantity);
					$totalquantityfreeshipping += intval($orders_product_info->my->quantity);
				}
				foreach ($orders_products as $orders_product_info)
				{
					$quantity              = $orders_product_info->my->quantity;
					$shop_price            = $orders_product_info->my->shop_price;
					$total_price           = $orders_product_info->my->total_price;
					$shop_postage          = $orders_product_info->my->postage;
					$mergepostage          = $orders_product_info->my->mergepostage;
					$includepost           = $orders_product_info->my->includepost;
					$bargains_count        = intval($orders_product_info->my->bargains_count);
					$activitymode          = intval($orders_product_info->my->activitymode);
					$bargainrequirednumber = intval($orders_product_info->my->bargainrequirednumber);

					$tmppostage            = 0;
					if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
					   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
					{
						$productallcount = productquantity($orders_products, $orders_product_info->my->productid);
						if ($includepost <= 0 || $includepost > $productallcount)
						{
							$tmppostage = $shop_postage;
							if (intval($mergepostage) != 1)
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
					$product_info   = array (
						'id'                        => $orders_product_info->id,
						'productid'                 => $orders_product_info->my->productid,
						'productallcount'			=> $productallcount,
						'productname'               => $orders_product_info->my->productname,
						'productthumbnail'          => $orders_product_info->my->productthumbnail,
						'quantity'                  => $quantity,
						'shop_price'                => number_format($shop_price, 2, ".", ""),
						'market_price'              => number_format($orders_product_info->my->market_price, 2, ".", ""),
						'total_price'               => number_format(floatval($total_price) + floatval($tmppostage), 2, ".", ""),
						'product_property_id'       => $orders_product_info->my->product_property_id,
						'propertydesc'              => $orders_product_info->my->propertydesc,
						'old_shop_price'            => number_format($orders_product_info->my->old_shop_price, 2, ".", ""),
						'zhekou'                    => $orders_product_info->my->zhekou,
						'salesactivityid'           => $orders_product_info->my->salesactivityid,
						'salesactivitys_product_id' => $orders_product_info->my->salesactivitys_product_id,
						'zhekoulabel'               => $orders_product_info->my->zhekoulabel,
						'mergepostage'              => $mergepostage,
						'postage'                   => number_format($shop_postage, 2, ".", ""),
						'includepost'               => $includepost,
						'bargains_count'            => $bargains_count,
						'activitymode'              => $activitymode,
						'bargainrequirednumber'     => $bargainrequirednumber,
					);
					$addinfo[]      = array ("merge" => $product_info["mergepostage"], "quantity" => $product_info["quantity"]);
					$shoppingcart[] = $product_info;
					$verify_total_money += $total_price;
					$verify_total_quantity += $quantity;
				}
				if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
				   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
				{
					$addpostage = getPostage($supplierid, $deliveraddressinfo["province"], $addinfo);
				}else{
					$addpostage = 0;
				}
				$verify_alltotal_money = $verify_total_money + $allmergepostage + $allpostage + $addpostage;
				$orderContent          = array ();
				if (round($verify_total_money, 2) != round($total_money, 2) ||
					$total_quantity != $verify_total_quantity ||
					round($sumorderstotal, 2) != round($verify_alltotal_money, 2) ||
					round($orderpostage, 2) != round($allpostage + $allmergepostage, 2) ||
					round($orderaddpostage, 2) != round($addpostage, 2)
				)
				{
					$mall_order_info->my->sumorderstotal = number_format($verify_alltotal_money, 2, ".", "");
					$mall_order_info->my->amountpayable  = number_format($verify_alltotal_money, 2, ".", "");
					$mall_order_info->my->paymentamount  = "0.00";
					$mall_order_info->my->orderstotal    = number_format($verify_total_money, 2, ".", "");
					$mall_order_info->my->postage        = number_format($allpostage + $allmergepostage, 2, ".", "");
					$mall_order_info->my->addpostage     = number_format($addpostage, 2, ".", "");
					$mall_order_info->my->productcount   = $verify_total_quantity;
					setBatchsave($orderContent, $mall_order_info);
				}
				if ($orderdeliveraddressid != $deliveraddressinfo['recordid'])
				{
					$mall_order_info->my->deliveraddressid = $deliveraddressinfo['recordid'];
					$mall_order_info->my->consignee        = $deliveraddressinfo['consignee'];
					$mall_order_info->my->province         = $deliveraddressinfo['province'];
					$mall_order_info->my->city             = $deliveraddressinfo['city'];
					$mall_order_info->my->district         = $deliveraddressinfo['district'];
					$mall_order_info->my->address          = $deliveraddressinfo['address'];
					$mall_order_info->my->shortaddress     = $deliveraddressinfo['shortaddress'];
					$mall_order_info->my->zipcode          = $deliveraddressinfo['zipcode'];
					$mall_order_info->my->mobile           = $deliveraddressinfo['mobile'];
					setBatchsave($orderContent, $mall_order_info);
				}
				if((floatval($supplier_info['totalpricefreeshipping'] > 0) && floatval($supplier_info['totalpricefreeshipping']) <= $totalpricefreeshipping) || (intval($supplier_info['totalquantityfreeshipping']) > 0 && intval($supplier_info['totalquantityfreeshipping']) <= $totalquantityfreeshipping))
				{
					if (number_format(floatval($mall_order_info->my->totalpricefreeshipping), 2, ".", "") != number_format(floatval($supplier_info['totalpricefreeshipping']), 2, ".", ""))
					{
						$mall_order_info->my->totalpricefreeshipping = $supplier_info['totalpricefreeshipping'];
						setBatchsave($orderContent, $mall_order_info);
					}
					if (intval($mall_order_info->my->totalquantityfreeshipping) != intval($supplier_info['totalquantityfreeshipping']))
					{
						$mall_order_info->my->totalquantityfreeshipping = $supplier_info['totalquantityfreeshipping'];
						setBatchsave($orderContent, $mall_order_info);
					}
				}else{
					$mall_order_info->my->totalpricefreeshipping = '0.00';
					$mall_order_info->my->totalquantityfreeshipping = '0';
					setBatchsave($orderContent, $mall_order_info);
				}
				if (count($orderContent) > 0)
				{
					XN_Content::batchsave($orderContent, 'mall_orders,mall_orders_'.$profileid.',mall_orders_'.$supplierid);
				}
				$total_quantity = $verify_total_quantity;
				$total_money    = $verify_alltotal_money;
			}
			else
			{
				/**
				 * 购物车确认订单
				 */
				$returnbackatcion = "/shoppingcart.php";
				$orderid       = "";
				$shoppingcarts = XN_Content::load($shoppingcart, 'mall_shoppingcarts_'.$profileid, 7);

				$shoppingcart            = array ();
				$total_money             = 0;
				$total_quantity          = 0;
				$allpostage              = 0;
				$allmergepostage         = 0;
				$expectedconsumptiontime = "";
				$customersmsg            = "";
				$addinfo                 = array ();
				$totalpricefreeshipping = 0;
				$totalquantityfreeshipping = 0;
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
					$bargains_count        = intval($shoppingcart_info->my->bargains_count);
					$activitymode          = intval($shoppingcart_info->my->activitymode);
					$bargainrequirednumber = intval($shoppingcart_info->my->bargainrequirednumber);

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
					);
					$shoppingcart[] = $product_info;
					$total_money += $total_price;
					$total_quantity += $quantity;
					$addinfo[] = array ("merge" => $product_info["mergepostage"], "quantity" => $product_info["quantity"]);
				}

				if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
				   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
				{
					$addpostage = getPostage($supplierid, $deliveraddressinfo["province"], $addinfo);
				}else{
					$addpostage = 0;
				}
				$total_money = floatval($total_money) + $addpostage + $allpostage + $allmergepostage;
			}
		}
		else{
			/**
			 * 购物车确认订单
			 */
			$orderid       = "";
			$shoppingcarts = XN_Content::load($shoppingcart, 'mall_shoppingcarts_'.$profileid, 7);

			$shoppingcart            = array ();
			$total_money             = 0;
			$total_quantity          = 0;
			$allpostage              = 0;
			$allmergepostage         = 0;
			$expectedconsumptiontime = "";
			$customersmsg            = "";
			$addinfo                 = array ();
			$totalpricefreeshipping = 0;
			$totalquantityfreeshipping = 0;
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
				$bargains_count        = intval($shoppingcart_info->my->bargains_count);
				$activitymode          = intval($shoppingcart_info->my->activitymode);
				$bargainrequirednumber = intval($shoppingcart_info->my->bargainrequirednumber);

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
					'productallcount' 		=> $productallcount,
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
				);
				$shoppingcart[] = $product_info;
				$total_money += $total_price;
				$total_quantity += $quantity;
				$addinfo[] = array ("merge" => $product_info["mergepostage"], "quantity" => $product_info["quantity"]);
			}
			if((floatval($supplier_info['totalpricefreeshipping']) <= 0 || floatval($supplier_info['totalpricefreeshipping']) > $totalpricefreeshipping) &&
			   (intval($supplier_info['totalquantityfreeshipping']) <= 0 || intval($supplier_info['totalquantityfreeshipping']) > $totalquantityfreeshipping))
			{
				$addpostage = getPostage($supplierid, $deliveraddressinfo["province"], $addinfo);
			}else{
				$addpostage = 0;
			}
			$total_money = floatval($total_money) + $addpostage + $allpostage + $allmergepostage;
		}

	}
	catch (XN_Exception $e)
	{
		messagebox('错误', $e->getMessage());
		die();
	}

	require_once('Smarty_setup.php');

	$smarty = new vtigerCRM_Smarty;

	$islogined = false;
	if ($_SESSION['u'] == $_SESSION['profileid'])
	{
		$islogined = true;
	}
	$smarty->assign("islogined", $islogined);

	$action = strtolower(basename(__FILE__, ".php"));

	$recommend_info = checkrecommend();
	$smarty->assign("share_info", $recommend_info);
	$smarty->assign("supplier_info", $supplier_info);
	$profile_info = get_supplier_profile_info();
	$smarty->assign("profile_info", $profile_info);
	
	
	
	
	$rankdeductionmoney = 0;
	if ($supplier_info['rankcost'] == '0' && $profile_info['rank'] > 0)
	{
		$rankdeductionmoney =  $profile_info['rank'] * floatval($supplier_info['rankcostrate']) / 100; 
		$allowmoney = $total_money;
		if ($rankdeductionmoney < $allowmoney)
		{
			$smarty->assign("rankdeductionmoney", number_format($rankdeductionmoney, 2, ".", ""));
		}
		else
		{
			$smarty->assign("rankdeductionmoney", number_format($allowmoney, 2, ".", ""));
		} 
	}
	else
	{
		$smarty->assign("rankdeductionmoney", '0');
	}
	
	
	if ($supplier_info['vipauthentication'] == '1')
	{
		$query = XN_Query::create('Content')->tag('supplier_authenticationprofiles_' . $supplierid)
	        ->filter('type', 'eic', 'supplier_authenticationprofiles')
	        ->filter('my.deleted', '=', '0')
	        ->filter('my.supplierid', '=', $supplierid)
	        ->filter('my.profileid', '=', $profileid)
	        ->end(1); 
		if (isset($supplierinfo['repurchase']) && $supplierinfo['repurchase'] == '0')
		{  
			$query->filter('my.repurchasestatus', '=', '0');
		}
		$authenticationprofiles = $query->execute();
		if (count($authenticationprofiles) > 0)
		{
			$authenticationprofile_info = $authenticationprofiles[0];
			$rankid = $authenticationprofile_info->my->rankid;  
			$rankdiscount = $authenticationprofile_info->my->rankdiscount; 
			if (isset($rankid) && $rankid != "")
			{
				$rank_info = XN_Content::load($rankid,"supplier_profileranks");
				$rankname = $rank_info->my->rankname;
				$smarty->assign("authenticationid", $authenticationprofile_info->id);
				$smarty->assign("rankid", $rankid);
				$smarty->assign("rankname", $rankname);
				$smarty->assign("rankdiscount", $rankdiscount);
			}
			else
			{
				$smarty->assign("authenticationid", "");
			}
			 
		}
		else
		{
			$smarty->assign("authenticationid", "");
		}
	}
	else
	{
		$smarty->assign("authenticationid", "");
	}
	 
	

	$smarty->assign("shoppingcarts", $shoppingcart);
	$smarty->assign("deliveraddress", $deliveraddressinfo);

	$smarty->assign("orderid", $orderid);
	$smarty->assign("fapiao", $fapiao);
	$smarty->assign("tradestatus", $tradestatus);
	$smarty->assign("paymentmode", $paymentmode);
	$smarty->assign("paymentway", $paymentway);
	$smarty->assign("delivermode", $delivermode);
	$smarty->assign("distance", $distance);

	$smarty->assign("total_money", number_format($total_money, 2, ".", ""));
	$smarty->assign("total_quantity", $total_quantity);
	$smarty->assign("addpostage", number_format($addpostage, 2, ".", ""));

	$smarty->assign("expectedconsumptiontime", $expectedconsumptiontime);
	$smarty->assign("customersmsg", $customersmsg);

	$smarty->assign("token", $_REQUEST['token']);
	$smarty->assign("returnbackatcion", $returnbackatcion);

	$sysinfo                    = array ();
	$sysinfo['action']          = 'shoppingcart';
	$sysinfo['date']            = date("md");
	$sysinfo['api']             = $APISERVERADDRESS;
	$sysinfo['http_user_agent'] = check_http_user_agent();
	$sysinfo['domain']          = $WX_DOMAIN;
	$sysinfo['width']           = $_SESSION['width'];
	$smarty->assign("sysinfo", $sysinfo);
	
	
	$realnameauthentication = $supplier_info['realnameauthentication'];
	$authenticationpayment = $supplier_info['authenticationpayment'];
	
	if ($realnameauthentication == '0' && $authenticationpayment == '1')
	{
		$authenticationprofile = $profile_info['authenticationprofile'];
		if ($authenticationprofile == '1')
		{
			$smarty->assign("allowpayment", 'true');
		}
		else
		{
			$smarty->assign("allowpayment", 'false');
		}
	}
	else
	{
		$smarty->assign("allowpayment", 'true');
	}
	
	unset($_SESSION['return']);

	$smarty->display($action.'.tpl');

?>