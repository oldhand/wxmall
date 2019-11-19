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
   $profileid = "anonymous";
}


$deliveraddressinfo = array(); 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='' )
{
	 $record = $_REQUEST['record'];  
	 try
	 {
	     $deliveraddress_info = XN_Content::load($record,'deliveraddress_'.$profileid,4);  
	 	 $deliveraddressinfo['recordid'] = $record;
	     $deliveraddressinfo['consignee'] = $deliveraddress_info->my->consignee;
	 	 $deliveraddressinfo['province'] = $deliveraddress_info->my->province;
	 	 $deliveraddressinfo['city'] = $deliveraddress_info->my->city;
	 	 $deliveraddressinfo['district'] = $deliveraddress_info->my->district;
	 	 $deliveraddressinfo['address'] = $deliveraddress_info->my->address; 
	 	 $deliveraddressinfo['shortaddress'] = $deliveraddress_info->my->shortaddress; 
	 	 $deliveraddressinfo['zipcode'] = $deliveraddress_info->my->zipcode; 
	 	 $deliveraddressinfo['mobile'] = $deliveraddress_info->my->mobile; 
	 	 $deliveraddressinfo['selected'] = $deliveraddress_info->my->selected;  
	 }
	 catch ( XN_Exception $e ) 
	 {
	 
	 } 
} 
 

require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;
 
$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
	$islogined = true;
} 
$smarty->assign("islogined",$islogined); 

$action = strtolower(basename(__FILE__,".php")); 

$recommend_info = checkrecommend();   
$smarty->assign("share_info",$recommend_info); 
$smarty->assign("supplier_info",get_supplier_info()); 
$smarty->assign("profile_info",get_supplier_profile_info()); 
$smarty->assign("orderid",$_REQUEST["orderid"]);
$smarty->assign("deliveraddressinfo",$deliveraddressinfo); 

 
 

$sysinfo = array();
$sysinfo['action'] = 'shoppingcart'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>