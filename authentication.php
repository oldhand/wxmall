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
 

try
{ 
    $profileranks = array();
	$supplier_profileranks = XN_Query::create('Content')->tag('supplier_profileranks_' . $supplierid)
        ->filter('type', 'eic', 'supplier_profileranks')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.status', '=', '0')
		->filter('my.approvalstatus', '=', '2')
		->order("my.sequence",XN_Order::ASC_NUMBER)
        ->end(-1)
        ->execute();
	if (count($supplier_profileranks) > 0)
	{
		$selected = false;
		foreach($supplier_profileranks as $supplier_profilerank_info)
		{
			$rankid = $supplier_profilerank_info->id; 
			$profileranks[$rankid]['rankname'] = $supplier_profilerank_info->my->rankname;
			$profileranks[$rankid]['rankdiscount'] = $supplier_profilerank_info->my->rankdiscount;
			$profileranks[$rankid]['depositmoney'] = $supplier_profilerank_info->my->depositmoney;
			$profileranks[$rankid]['needmoney'] = $supplier_profilerank_info->my->depositmoney;
			$profileranks[$rankid]['sequence'] = $supplier_profilerank_info->my->sequence;
			$profileranks[$rankid]['csskey'] = $supplier_profilerank_info->my->csskey;
			$profileranks[$rankid]['description'] = $supplier_profilerank_info->my->description;
			
			$profileranks[$rankid]['allow'] = '1';
			if (!$selected)
			{
				$profileranks[$rankid]['selected'] = '1';
				$selected = true;
			}
			else
			{
				$profileranks[$rankid]['selected'] = '0';
			}
			 
		} 
	} 
	 
	 
    $authentication = array();
	$authenticationprofiles = XN_Query::create('Content')->tag('supplier_authenticationprofiles_' . $supplierid)
        ->filter('type', 'eic', 'supplier_authenticationprofiles')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.profileid', '=', $profileid)
        ->end(1)
        ->execute();
	if (count($authenticationprofiles) > 0)
	{
		$authenticationprofile_info = $authenticationprofiles[0];
		$rankid = $authenticationprofile_info->my->rankid; 
		$depositmoney = $authenticationprofile_info->my->depositmoney; 
		$remainmoney = $authenticationprofile_info->my->remainmoney;
		$index = $authenticationprofile_info->my->index; 
		$returncount = $authenticationprofile_info->my->returncount; 
		$returnmoney = $authenticationprofile_info->my->returnmoney; 
	    $authenticationstatus = $authenticationprofile_info->my->authenticationstatus;
		$sequence = $authenticationprofile_info->my->sequence;  
		$rankdiscount = $authenticationprofile_info->my->rankdiscount;  
		
		$authentication['rankid'] = $rankid;
	 	$authentication['rankname'] = $profileranks[$rankid]['rankname'];
		$authentication['published'] = $authenticationprofile_info->published;
		$authentication['depositmoney'] = $depositmoney;
		$authentication['remainmoney'] = $remainmoney;
		$authentication['index'] = $index;
		$authentication['returncount'] = $returncount;
		$authentication['returnmoney'] = $returnmoney; 
		$authentication['rankdiscount'] = $rankdiscount; 
		$authentication['authenticationstatus'] = '2'; 
		$newprofileranks = array();
		$selected = false;
		foreach($profileranks as $rankid => $profilerank_info)
		{
			$needmoney = $profilerank_info['needmoney'];
			if (intval($profilerank_info['sequence']) > intval($sequence))
			{
				$profilerank_info['needmoney'] =  floatval($needmoney) - floatval($depositmoney);
				$authentication['authenticationstatus'] = '1'; 
				if (!$selected)
				{
					$profilerank_info['selected'] = '1';
					$selected = true;
				}
				else
				{
					$profilerank_info['selected'] = '0';
				}
			} 
			else
			{
				$profilerank_info['allow'] = '0';
				$profilerank_info['selected'] = '0';
			}
			$newprofileranks[$rankid] = $profilerank_info;
		}
		$profileranks = $newprofileranks;
		XN_MemCache::put($profileranks, "profileranks_" . $supplierid.'_'.$profileid);
	}
	else 
	{ 
		if (count($profileranks) > 0)
		{
			$authentication['authenticationstatus'] = 0;
			XN_MemCache::put($profileranks, "profileranks_" . $supplierid.'_'.$profileid);
		}
		else
		{
			$authentication['authenticationstatus'] = -1;
		}
	    
	}    
}
catch (XN_Exception $e)
{
    $msg = $e->getMessage();
    messagebox('错误', $msg);
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
 
$smarty->assign("profileranks",$profileranks);  

$smarty->assign("authentication",$authentication);  
 
$profile_info = get_supplier_profile_info();  
 
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