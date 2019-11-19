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
	messagebox('错误','没有店铺ID'); 
	die();  
}
 
$potenitalsuppliers = array();

if (isset($_REQUEST['suppliername']) && $_REQUEST['suppliername'] != '' && 
	isset($_REQUEST['profilename']) && $_REQUEST['profilename'] != '' &&
	isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '' &&
	$_REQUEST['type'] == 'submit')
{
    $suppliername = trim($_REQUEST['suppliername']);
	$name = trim($_REQUEST['profilename']);
	$mobile = trim($_REQUEST['mobile']);
    
    try
    {
		$potenitals = XN_Query::create("Content")->tag("potenitalsuppliers")
								  ->filter('type', 'eic', "potenitalsuppliers") 
								  ->filter("my.profileid", "=", $profileid)
								  ->filter("my.deleted", "=", "0")
								  ->end(1)
								  ->execute();
		if (count($potenitals) == 0)
		{
	        $newcontent = XN_Content::create('potenitalsuppliers','',false);
	        $newcontent->my->deleted = '0';
			$newcontent->my->profileid = $profileid; 
	        $newcontent->my->supplierid = $supplierid;
	        $newcontent->my->suppliername = $suppliername;
	        $newcontent->my->name = $name;
	        $newcontent->my->mobile = $mobile; 
			$newcontent->my->potenitalsuppliersstatus = 'JustCreated';  
			$newcontent->save('potenitalsuppliers');
			$potenitalsuppliers['suppliername'] = $suppliername;
			$potenitalsuppliers['name'] = $name;
			$potenitalsuppliers['mobile'] = $mobile;
			$potenitalsuppliers['type'] = 'edit';
		}
		else
		{
			$potenitalsupplier_info = $potenitals[0]; 
	        $potenitalsupplier_info->my->suppliername = $suppliername;
	        $potenitalsupplier_info->my->name = $name;
	        $potenitalsupplier_info->my->mobile = $mobile; 
			$potenitalsupplier_info->save('potenitalsuppliers');
			$potenitalsuppliers['suppliername'] = $suppliername;
			$potenitalsuppliers['name'] = $name;
			$potenitalsuppliers['mobile'] = $mobile;
			$potenitalsuppliers['type'] = 'edit'; 
		}
         
    }
    catch (XN_Exception $e)
    {
        
    }
}

if (count($potenitalsuppliers) == 0)
{
	$potenitals = XN_Query::create("Content")->tag("potenitalsuppliers")
							  ->filter('type', 'eic', "potenitalsuppliers") 
							  ->filter("my.profileid", "=", $profileid)
							  ->filter("my.deleted", "=", "0")
							  ->end(1)
							  ->execute();
	if (count($potenitals) > 0)
	{
		$potenitalsupplier_info = $potenitals[0]; 
		$potenitalsuppliers['suppliername'] = $potenitalsupplier_info->my->suppliername;
		$potenitalsuppliers['name'] = $potenitalsupplier_info->my->name;
		$potenitalsuppliers['mobile'] = $potenitalsupplier_info->my->mobile;
		$published = $potenitalsupplier_info->published;  
		$diff = date_diff(date_create($published),date_create("now"));
		 
	    if (intval($diff->format("%a")) > 1) 
		{
			$potenitalsuppliers['type'] = 'noedit';  
		}
		else
		{
			$potenitalsuppliers['type'] = 'edit';  
		}
	}
	else
	{ 
		$potenitalsuppliers['suppliername'] = '';
		$potenitalsuppliers['name'] = '';
		$potenitalsuppliers['mobile'] = '';
		$potenitalsuppliers['type'] = 'add'; 
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

$smarty->assign("share_info",$recommend_info); 
$smarty->assign("supplier_info",get_supplier_info()); 
 
 
$profile_info = get_supplier_profile_info(); 
$smarty->assign("profile_info",$profile_info);

$smarty->assign("potenitalsuppliers",$potenitalsuppliers);
 

 
$smarty->display($action.'.tpl'); 



?>