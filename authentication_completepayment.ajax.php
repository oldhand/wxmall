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

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");


if(isset($_SESSION['profileid']) && $_SESSION['profileid'] !='')
{
    $profileid = $_SESSION['profileid'];
}
elseif(isset($_SESSION['accessprofileid']) && $_SESSION['accessprofileid'] !='')
{
    $profileid = $_SESSION['accessprofileid'];
}
else
{
	echo '{"code":201}';
	die();
}
 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$record = $_REQUEST['record'];  
	$authenticationorder_info = XN_Content::load($record,'supplier_authenticationorders');

	$rankname = $authenticationorder_info->my->rankname; 
	$amount = $authenticationorder_info->my->amount; 
	$profileid = $authenticationorder_info->my->profileid; 
	$profilerankid = $authenticationorder_info->my->profilerankid; 
	$supplierid = $authenticationorder_info->my->supplierid;  
	$sequence = $authenticationorder_info->my->sequence;
	$rankdiscount = $authenticationorder_info->my->rankdiscount; 	
	$tradestatus = $authenticationorder_info->my->tradestatus;
	if ($tradestatus == "trade")
	{
		echo '{"code":200,"msg":"trade"}';
		die();
	}
	else
	{
		echo '{"code":201,"msg":"waiting"}';
		die();
	}  	 	
} 
echo '{"code":201,"msg":"waiting"}';
die(); 


?>