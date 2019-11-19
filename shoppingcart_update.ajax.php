<?php
	session_start();
	require_once(dirname(__FILE__)."/config.inc.php");
	require_once(dirname(__FILE__)."/config.common.php");
	require_once(dirname(__FILE__)."/util.php");
	if (isset($_SESSION['profileid']) && $_SESSION['profileid'] != '')
	{
		$loginprofileid = $_SESSION['profileid'];
	}
	elseif (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
	{
		$loginprofileid = $_SESSION['accessprofileid'];
	}
	else
	{
		$loginprofileid = "anonymous";
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
	if (isset($_REQUEST['record']) && $_REQUEST['record'] != '' &&
		isset($_REQUEST['qty_item']) && $_REQUEST['qty_item'] != ''
	)
	{
		$shoppingcartid = $_REQUEST['record'];
		$qty_item       = $_REQUEST['qty_item'];
	}
	else
	{
		die();
	}
	try
	{
		$shoppingcart_info                  = XN_Content::load($shoppingcartid, "mall_shoppingcarts", 7);
		$shop_price                         = $shoppingcart_info->my->shop_price;
		$total_price                        = floatval($shop_price) * intval($qty_item);
		$shoppingcart_info->my->quantity    = $qty_item;
		$shoppingcart_info->my->total_price = number_format($total_price,2,".","");
		$shoppingcart_info->save('mall_shoppingcarts,mall_shoppingcarts_'.$loginprofileid.',mall_shoppingcarts_'.$supplierid);
		echo 'success';
		die();
	}
	catch (XN_Exception $e)
	{
		$msg = $e->getMessage();
		echo '{"code":202,"msg":"'.$msg.'"}';
		die();
	}
