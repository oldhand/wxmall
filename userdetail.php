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
	messagebox('错误','请从微信公众号“特赞商城”或朋友圈中朋友分享链接进入本平台，如您确实采用上述方式仍然出现本信息，请与系统管理员联系。');
	die();
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
$supplier_info = get_supplier_info(); 
$smarty->assign("supplier_info",$supplier_info);  
 
$profile_info = get_supplier_profile_info();  
if (isset($supplier_info['allowphysicalstore']) && $supplier_info['allowphysicalstore'] == '0')
{
	$supplier_physicalstoreprofiles = XN_Query::create ( 'Content' ) 
	    ->filter ( 'type', 'eic', 'supplier_physicalstoreprofiles') 
		->filter ( 'my.supplierid', '=',$supplierid)
		->filter ( 'my.profileid', '=',$profileid)
	    ->filter ( 'my.deleted', '=', '0' )
		->end(1)
	    ->execute ();
	if (count($supplier_physicalstoreprofiles) > 0)
	{
		$supplier_physicalstoreprofile_info = $supplier_physicalstoreprofiles[0];
		$physicalstoreid = $supplier_physicalstoreprofile_info->my->physicalstoreid;
		$assistantprofileid = $supplier_physicalstoreprofile_info->my->assistantprofileid;
		$physicalstore_info = XN_Content::load($physicalstoreid,"supplier_physicalstores");
		$profile_info['physicalstorename'] = $physicalstore_info->my->storename;
		$assistantprofile_info = get_profile_info($assistantprofileid);
		$profile_info['assistantprofile'] = $assistantprofile_info['givenname']; 
	}
}
 

if (count($profile_info) > 0)
{
	//$profile_info['mobile'] = '';
	//$profile_info['sourcer'] = '';
	$sourcer = $profile_info['onelevelsourcer'];
	if (isset($sourcer) && $sourcer != '')
	{
		$profile_info['sourcergivename'] = get_profile_givenname($sourcer);
	}
	else
	{
		$profile_info['sourcergivename'] = '';
	}
}
$smarty->assign("profile_info",$profile_info);

	
$sysinfo = array();
$sysinfo['action'] = 'index'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
$smarty->assign("needverifycode",'no'); 
 
$smarty->display($action.'.tpl'); 



?>