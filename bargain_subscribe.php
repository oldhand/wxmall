<?php
/*+**********************************************************************************
 * The contents of this file are subject to the 361CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  361CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/


session_start();
 
require_once(dirname(__FILE__) . "/config.inc.php");
require_once(dirname(__FILE__) . "/config.common.php");
require_once(dirname(__FILE__) . "/util.php");

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
    $supplierid = $_SESSION['supplierid'];
}
else
{
    messagebox('错误', "没有店铺ID!");
    die();
}

if (isset($_SESSION['u']) && $_SESSION['u'] != '')
{
    $share_profileid = $_SESSION['u'];
}
else
{
    messagebox('错误', "只能在朋友圈里点开!");
    die();
} 

if (isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] != '')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
    messagebox('错误', "只能在朋友圈里点开!");
    die();
}

if (isset($_REQUEST['salesactivityid']) && $_REQUEST['salesactivityid'] != '')
{
    $salesactivityid = $_REQUEST['salesactivityid'];
}
else
{
    messagebox('错误', "没有活动ID!");
    die();
}

require_once('Smarty_setup.php');

$smarty = new vtigerCRM_Smarty;


if (isset($_REQUEST['productid']) && $_REQUEST['productid'] != '')
{
    $productid = $_REQUEST['productid'];
    $smarty->assign('productid', $productid);
}
else
{
    messagebox('错误', '无法获得productid');
    die();
}
if (isset($_REQUEST['bargain']) && $_REQUEST['bargain'] != '')
{
    $bargain = $_REQUEST['bargain']; 
} 
else
{
    messagebox('错误', '必备的砍价选项！');
    die();
}
try
{   
	if ($_REQUEST['bargain'] == 'join')
	{
	    $bargain = '3'; 
	} 
	else
	{
		$mall_bargains = XN_Query::create("YearContent")->tag("mall_bargains_".$share_profileid)
                                 ->filter("type", "eic", "mall_bargains")
                                 ->filter("my.salesactivityid", "=", $salesactivityid)
                                 ->filter("my.productid", "=", $productid)
                                 ->filter("my.supplierid", "=", $supplierid)
                                 ->filter("my.profileid", "=", $share_profileid)
							     ->filter("my.bargainer", "=", $profileid)
                                 ->end(1)
                                 ->execute(); 
		if (count($mall_bargains) == 0)
		{
			$bargain = "1";
			if ($_REQUEST['bargain'] == 'refuse')
			{
			    $bargain = '2'; 
			} 
	        $newcontent = XN_Content::create('mall_bargains','',false,7);
	        $newcontent->my->deleted = '0';
	        $newcontent->my->salesactivityid = $salesactivityid; 
	        $newcontent->my->productid = $productid;
	        $newcontent->my->supplierid = $supplierid;
	        $newcontent->my->profileid = $share_profileid;
	        $newcontent->my->bargainer = $profileid; 
			$newcontent->my->bargain = $bargain; 
	        $newcontent->save('mall_bargains,mall_bargains_'.$share_profileid.',mall_bargains_'.$supplierid);
	       
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
	                $profile_info = get_profile_info($profileid);
	                 if ($bargain == '1')
					 {
		                if (count($profile_info) > 0)
		                {
			                 $givenname = $profile_info['givenname'];
			                 $message = $givenname.'帮你砍价了！快去感谢他吧！';
		                }
		                else
		                {
			                 $message = '一位好友帮你砍价了！快去感谢他吧！';
		                } 
		            }
		            else
		            {
			            if (count($profile_info) > 0)
		                {
			                 $givenname = $profile_info['givenname'];
			                 $message = $givenname.'拒绝帮你砍价了！去鄙视他吧！';
		                }
		                else
		                {
			                 $message = '某位好友拒绝帮你砍价！';
		                } 
			        }
	               
	                XN_Message::sendmessage($share_profileid, $message, $appid);
	            } 
	         
		}
		else
		{
			$mall_bargain_info = $mall_bargains[0];
			$bargain = $mall_bargain_info->my->bargain;
		}
	}
    
     
}
catch (XN_Exception $e)
{  
}
  
  
$panel = strtolower(basename(__FILE__, ".php"));
$smarty->assign("actionname", $panel); 
 
$smarty->assign("supplier_info", get_supplier_info()); 

$smarty->assign("bargain", $bargain); 
 
$smarty->display('bargain_subscribe.tpl'); 
 

?>