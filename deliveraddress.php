<?php
	session_start();

	require_once(dirname(__FILE__)."/config.inc.php");
	require_once(dirname(__FILE__)."/config.common.php");
	require_once(dirname(__FILE__)."/util.php");

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
	if (isset($_REQUEST['result']) && $_REQUEST['result'] != '')
	{
		$weixin_result = $_REQUEST['result'];
		
		try
		{	 
			$weixin_deliveraddress = json_decode($weixin_result, true);
			 
			if (isset($weixin_deliveraddress['errMsg']) && $weixin_deliveraddress['errMsg'] == "openAddress:ok")
			{
				$province  = $weixin_deliveraddress['provinceName'];
				$city      = $weixin_deliveraddress['cityName'];
				$district  = $weixin_deliveraddress['countryName']; 
				$address   = $weixin_deliveraddress['detailInfo'];
				$mobile    = $weixin_deliveraddress['telNumber'];
				$consignee = $weixin_deliveraddress['userName'];
				$zipcode   = $weixin_deliveraddress['postalCode'];

				$hash = md5($province.$city.$district.$address.$consignee.$mobile);

				$deliveraddress = XN_Query::create('MainContent')->tag('deliveraddress_'.$profileid)
										  ->filter('type', 'eic', 'deliveraddress')
										  ->filter('my.profileid', '=', $profileid)
										  ->filter('my.hash', '=', $hash)
										  ->end(1)
										  ->execute();
				if (count($deliveraddress) == 0)
				{
					$newcontent                   = XN_Content::create('deliveraddress', '', false, 4);
					$newcontent->my->deleted      = '0';
					$newcontent->my->profileid    = $profileid;
					$newcontent->my->hash         = $hash;
					$newcontent->my->consignee    = $consignee;
					$newcontent->my->mobile       = $mobile;
					$newcontent->my->province     = $province;
					$newcontent->my->city         = $city;
					$newcontent->my->district     = $district;
					$newcontent->my->shortaddress = $address;
					$newcontent->my->zipcode      = $zipcode;
					$newcontent->my->address      = $province.$city.$district." ".$address;
					$newcontent->save("deliveraddress,deliveraddress_".$profileid);
				}
			}

		}
		catch (XN_Exception $e)
		{

		}
	}
	if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'delete' &&
		isset($_REQUEST['record']) && $_REQUEST['record'] != ''
	)
	{
		$record = $_REQUEST['record'];
		try
		{
			$loadcontent = XN_Content::load($record, 'deliveraddress_'.$profileid, 4);
			XN_Content::delete($loadcontent, "deliveraddress,deliveraddress_".$profileid, 4);
		}
		catch (XN_Exception $e)
		{

		}
	}
	if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'submit')
	{
		if (isset($_REQUEST['consignee']) && $_REQUEST['consignee'] != '' &&
			isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '' &&
			isset($_REQUEST['province']) && $_REQUEST['province'] != '' &&
			isset($_REQUEST['city']) && $_REQUEST['city'] != '' &&
			isset($_REQUEST['address']) && $_REQUEST['address'] != ''
		)
		{
			$consignee = $_REQUEST['consignee'];
			$mobile    = $_REQUEST['mobile'];
			$province  = $_REQUEST['province'];
			$city      = $_REQUEST['city'];
			$district  = $_REQUEST['district'];
			$address   = $_REQUEST['address'];
			$zipcode   = $_REQUEST['zipcode'];
			if (isset($_REQUEST['record']) && $_REQUEST['record'] != '')
			{
				$record = $_REQUEST['record'];
				try
				{
					$newcontent                   = XN_Content::load($record, 'deliveraddress_'.$profileid, 4);
					$newcontent->my->deleted      = '0';
					$newcontent->my->profileid    = $profileid;
					$newcontent->my->consignee    = $consignee;
					$newcontent->my->mobile       = $mobile;
					$newcontent->my->province     = $province;
					$newcontent->my->city         = $city;
					$newcontent->my->district     = $district;
					$newcontent->my->shortaddress = $address;
					$newcontent->my->zipcode      = $zipcode;
					$newcontent->my->address      = $province.$city.$district." ".$address;
					$newcontent->save("deliveraddress,deliveraddress_".$profileid);
				}
				catch (XN_Exception $e)
				{

				}
			}
			else
			{
				$newcontent                   = XN_Content::create('deliveraddress', '', false, 4);
				$newcontent->my->deleted      = '0';
				$newcontent->my->profileid    = $profileid;
				$newcontent->my->consignee    = $consignee;
				$newcontent->my->mobile       = $mobile;
				$newcontent->my->province     = $province;
				$newcontent->my->city         = $city;
				$newcontent->my->district     = $district;
				$newcontent->my->shortaddress = $address;
				$newcontent->my->zipcode      = $zipcode;
				$newcontent->my->address      = $province.$city.$district." ".$address;
				$newcontent->save("deliveraddress,deliveraddress_".$profileid);
			}
		}
	}

	$deliveraddressinfo = array ();

	try
	{

		$deliveraddress = XN_Query::create('MainContent')->tag('deliveraddress_'.$profileid)
								  ->filter('type', 'eic', 'deliveraddress')
								  ->filter('my.profileid', '=', $profileid)
								  ->execute();
		if (count($deliveraddress) > 0)
		{
			foreach ($deliveraddress as $deliveraddress_info)
			{
				$id                                      = $deliveraddress_info->id;
				$deliveraddressinfo[$id]['consignee']    = $deliveraddress_info->my->consignee;
				$deliveraddressinfo[$id]['province']     = $deliveraddress_info->my->province;
				$deliveraddressinfo[$id]['city']         = $deliveraddress_info->my->city;
				$deliveraddressinfo[$id]['district']     = $deliveraddress_info->my->district;
				$deliveraddressinfo[$id]['address']      = $deliveraddress_info->my->address;
				$deliveraddressinfo[$id]['shortaddress'] = $deliveraddress_info->my->shortaddress;
				$deliveraddressinfo[$id]['zipcode']      = $deliveraddress_info->my->zipcode;
				$deliveraddressinfo[$id]['mobile']       = $deliveraddress_info->my->mobile;
				$deliveraddressinfo[$id]['selected']     = $deliveraddress_info->my->selected;

			}
		}
	}
	catch (XN_Exception $e)
	{

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
	$smarty->assign("supplier_info", get_supplier_info());
	$smarty->assign("profile_info", get_supplier_profile_info());
	$smarty->assign("orderid",$_REQUEST["orderid"]);
	$smarty->assign("deliveraddress", $deliveraddressinfo);

	$sysinfo                    = array ();
	$sysinfo['action']          = 'shoppingcart';
	$sysinfo['date']            = date("md");
	$sysinfo['api']             = $APISERVERADDRESS;
	$sysinfo['http_user_agent'] = check_http_user_agent();
	$sysinfo['domain']          = $WX_DOMAIN;
	$sysinfo['width']           = $_SESSION['width'];
	$smarty->assign("sysinfo", $sysinfo);

	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['DOCUMENT_URI'];

	$query_string = base64_encode($url);

	$weixin_deliveraddress_url = 'http://'.$WX_MAIN_DOMAIN.'/deliveraddress_weixin.php?uri='.$query_string;

	$smarty->assign("weixin_deliveraddress_url", $weixin_deliveraddress_url);
	
	if (isset($_REQUEST['f']) && $_REQUEST['f'] != '')
	{
		$_SESSION['return'] = $_REQUEST['f'];
		$smarty->assign("return", $_REQUEST['f']);
	}
	else if (isset($_SESSION['return']) && $_SESSION['return'] != '')
	{
		$smarty->assign("return", $_SESSION['return']);
	}
	else
	{ 
		$smarty->assign("return", '');
	}

	$smarty->display($action.'.tpl');
