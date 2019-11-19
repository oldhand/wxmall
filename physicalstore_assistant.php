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

if (isset($_SESSION['supplierid']) && $_SESSION['supplierid'] != '')
{
	$supplierid = $_SESSION['supplierid'];
}
else
{
	messagebox('错误', '没有店铺ID。');
	die();
}

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

function getProfileInfoArrByids($ids)
{
    if (count($ids) == 0) return array();  
    $profiles = XN_Profile::loadMany($ids, "id", "profile");
    $profileinfos = array();
    foreach ($profiles as $profile_info)
    {
		$profileid = $profile_info->profileid;
		$headimgurl = $profile_info->link;
		$givenname = strip_tags($profile_info->givenname);
		if ($headimgurl == "")
		{
			$headimgurl = 'images/ranks/new.png';
		}     
		$profileinfos[$profileid]['profileid'] = $profile_id;  
		$profileinfos[$profileid]['mobile'] = get_hidden_mobile($profile_info->mobile);
		$profileinfos[$profileid]['identitycard'] = $profile_info->identitycard;  
		$profileinfos[$profileid]['birthdate'] = $profile_info->birthdate; 
		$profileinfos[$profileid]['gender'] = $profile_info->gender; 
		$profileinfos[$profileid]['headimgurl'] = $headimgurl; 
		$profileinfos[$profileid]['givenname'] = $givenname; 
		$profileinfos[$profileid]['invitationcode'] = $profile_info->invitationcode;
		$profileinfos[$profileid]['sourcer'] = $profile_info->sourcer;
		$profileinfos[$profileid]['province'] = $profile_info->province; 
		$profileinfos[$profileid]['city'] = $profile_info->city;  
    }
    return $profileinfos;
}

function  mall_profiles($supplierid,$profileid,$page)
{ 
	$profilelist = array();
    $supplier_physicalstores = XN_Query::create('Content')->tag('supplier_physicalstores_' . $supplierid)
        ->filter('type', 'eic', 'supplier_physicalstores')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid) 
        ->filter('my.profileid','=',$profileid)  
		->end(1)
		->execute();
	if (count($supplier_physicalstores) == 0)
	{
		return $profilelist;
	}
	$supplier_physicalstore_info = $supplier_physicalstores[0];
	$physicalstoreid = $supplier_physicalstore_info->id;
    $query = XN_Query::create('Content')->tag('supplier_physicalstoreassistants_' . $supplierid)
        ->filter('type', 'eic', 'supplier_physicalstoreassistants')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid) 
        ->filter('my.physicalstoreid','=',$physicalstoreid) 
		->order("published",XN_Order::DESC) 
		->begin(($page-1)*5)
		->end($page*5);  
	$physicalstoreassistants = $query->execute();
	$noofrows = $query->getTotalCount();  
	
	if (count($physicalstoreassistants) > 0)
	{  
		$profileids = array();
		foreach($physicalstoreassistants as $physicalstoreassistants_info)
		{
			$profileids[] = $physicalstoreassistants_info->my->profileid;
		}
		$profilelist = getProfileInfoArrByids($profileids);
		foreach($physicalstoreassistants as $physicalstoreassistants_info)
		{
			$assistantprofileid = $physicalstoreassistants_info->my->profileid;
			$published = date("Y-m-d H:i",strtotime($physicalstoreassistants_info->published)); 
			$profilelist[$assistantprofileid]['published'] = $published;
		}
		 
	}  
    return $profilelist; 
}

if(isset($_REQUEST['type']) && $_REQUEST['type'] =='ajax')
{
	if(isset($_REQUEST['page']) && $_REQUEST['page'] !='')
	{
		$page = $_REQUEST['page'];  
	}
	else
	{
		echo '{"code":201,"data":[]}';
		die(); 
	} 
	
	try{
		$profiles = mall_profiles($supplierid,$profileid,$page);
		if (count($profiles) > 0)
		{
			echo '{"code":200,"data":'.json_encode($profiles).'}';
			die();
		}
	}
	catch(XN_Exception $e)
	{
	 
	} 
	echo '{"code":200,"data":[]}'; 
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
$smarty->assign("supplier_info",get_supplier_info()); 
$profileinfo = get_supplier_profile_info();
$smarty->assign("profile_info",$profileinfo); 


$sysinfo = array();
$sysinfo['action'] = 'promotioncenter'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>