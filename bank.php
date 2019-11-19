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
	if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
	{
		$supplierid = $_SESSION['supplierid']; 
	} 
	else
	{ 
		die();  
	} 
	 
	if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'delete' &&
		isset($_REQUEST['record']) && $_REQUEST['record'] != ''
	)
	{
		$record = $_REQUEST['record'];
		try
		{ 
			XN_Content::delete($record, "supplier_profilebankcards,supplier_profilebankcards_".$supplierid);
		}
		catch (XN_Exception $e)
		{

		}
	}
	if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'selected')
	{
		if (isset($_REQUEST['record']) && $_REQUEST['record'] != '')
		{
			$record = $_REQUEST['record'];
			$newcontent                   = XN_Content::load($record, 'supplier_profilebankcards');
			$newcontent->my->selected   = '1';
			$newcontent->save("supplier_profilebankcards,supplier_profilebankcards_".$supplierid);
			
			$supplier_profilebankcards = XN_Query::create('MainContent')->tag('supplier_profilebankcards')
						  ->filter('type', 'eic', 'supplier_profilebankcards')
						  ->filter('my.supplierid', '=', $supplierid)
						  ->filter('my.profileid', '=', $profileid) 
						  ->filter('my.selected', '=', '1') 
						  ->filter('id', '!=', $record)  
						  ->execute();
			if (count($supplier_profilebankcards) > 0)
			{
				foreach ($supplier_profilebankcards as $supplier_profilebankcards_info)
				{
					$supplier_profilebankcards_info->my->selected   = '0';
					$supplier_profilebankcards_info->save("supplier_profilebankcards,supplier_profilebankcards_".$supplierid);
				}
			}
		}
        header("Location: takecashs.php"); 
		die();
	}
	if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'submit')
	{
		if (isset($_REQUEST['bank']) && $_REQUEST['bank'] != '' &&
			isset($_REQUEST['account']) && $_REQUEST['account'] != '' &&
			isset($_REQUEST['realname']) && $_REQUEST['realname'] != '' )
		{
			$bank = $_REQUEST['bank'];
			$account    = $_REQUEST['account'];
			$realname  = $_REQUEST['realname']; 
			if (isset($_REQUEST['record']) && $_REQUEST['record'] != '')
			{
				$record = $_REQUEST['record'];
				try
				{
					$newcontent                   = XN_Content::load($record, 'supplier_profilebankcards');
					$newcontent->my->deleted      = '0';
					$newcontent->my->bank    = $bank;
					$newcontent->my->account    = $account;
					$newcontent->my->realname       = $realname;  
					$newcontent->my->authenticationstatus   = '0';
					$newcontent->save("supplier_profilebankcards,supplier_profilebankcards_".$supplierid);
				}
				catch (XN_Exception $e)
				{

				}
			}
			else
			{
				$supplier_profilebankcards = XN_Query::create('MainContent')->tag('supplier_profilebankcards')
							  ->filter('type', 'eic', 'supplier_profilebankcards')
							  ->filter('my.supplierid', '=', $supplierid)
							  ->filter('my.profileid', '=', $profileid) 
							  ->filter('my.account', '=', $account)  
							  ->execute();
				if (count($supplier_profilebankcards) == 0)
				{
					$newcontent                   = XN_Content::create('supplier_profilebankcards', '', false);
					$newcontent->my->deleted      = '0';
					$newcontent->my->supplierid    = $supplierid;
					$newcontent->my->profileid    = $profileid;
					$newcontent->my->bank    	  = $bank;
					$newcontent->my->account      = $account;
					$newcontent->my->realname     = $realname; 
					$newcontent->my->authenticationstatus   = '0';
					$newcontent->my->selected   = '0';
					$newcontent->my->supplier_profilebankcardsstatus     = 'JustCreated'; 
					$newcontent->save("supplier_profilebankcards,supplier_profilebankcards_".$supplierid);
				} 
			}
		}
	}

	$banks = array ();

	try
	{

		$supplier_profilebankcards = XN_Query::create('MainContent')->tag('supplier_profilebankcards')
								  ->filter('type', 'eic', 'supplier_profilebankcards')
								  ->filter('my.supplierid', '=', $supplierid)
								  ->filter('my.profileid', '=', $profileid)  
								  ->order("published",XN_Order::DESC)
								  ->execute();
		if (count($supplier_profilebankcards) > 0)
		{
			foreach ($supplier_profilebankcards as $supplier_profilebankcards_info)
			{
				$id                                      = $supplier_profilebankcards_info->id;
				$banks[$id]['bank']    = $supplier_profilebankcards_info->my->bank;
				$banks[$id]['account']     = $supplier_profilebankcards_info->my->account;
				$banks[$id]['realname']         = $supplier_profilebankcards_info->my->realname;
				$banks[$id]['authenticationstatus']     = $supplier_profilebankcards_info->my->authenticationstatus;
				$banks[$id]['selected']     = $supplier_profilebankcards_info->my->selected; 
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
	$smarty->assign("banks", $banks);

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
