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

$salesactivityinfo = array();

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$salesactivityid = $_REQUEST['record'];  
	$mall_salesactivity_info = XN_Content::load($salesactivityid,'mall_salesactivitys');
	 
	$id = $mall_salesactivity_info->id;  
	$published = $mall_salesactivity_info->published;    
	$salesactivityinfo['id'] = $id;    
    $salesactivityinfo['published'] =  date("Y-m-d H:i",strtotime($published));   
	$salesactivityinfo['activityname'] = $mall_salesactivity_info->my->activityname;  
	$salesactivityinfo['display_type'] = $mall_salesactivity_info->my->display_type;  
	$salesactivityinfo['status'] = $mall_salesactivity_info->my->status;  
	$salesactivityinfo['sequence'] = $mall_salesactivity_info->my->sequence;  
	$salesactivityinfo['activitylogo'] = $mall_salesactivity_info->my->activitylogo;  
	$salesactivityinfo['homepage'] = $mall_salesactivity_info->my->homepage;  
	$salesactivityinfo['activity_desc'] = $mall_salesactivity_info->my->activity_desc;  
	$salesactivityinfo['activitymode'] = $mall_salesactivity_info->my->activitymode;
	$salesactivityinfo['bargainrequirednumber'] = $mall_salesactivity_info->my->bargainrequirednumber;
	$salesactivityinfo['begindate'] = date("Y-m-d",strtotime($mall_salesactivity_info->my->begindate)); 
	$salesactivityinfo['enddate'] = date("Y-m-d",strtotime($mall_salesactivity_info->my->enddate));   
	$salesactivityinfo['mall_salesactivitysstatus'] = $mall_salesactivity_info->my->mall_salesactivitysstatus;  
	 
}
else
{
	messagebox('错误','参数错误。');
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

$smarty->assign("salesactivityinfo",$salesactivityinfo);  

$sysinfo = array();
$sysinfo['action'] = 'index'; 
$sysinfo['date'] = date("md"); 
$sysinfo['api'] = $APISERVERADDRESS; 
$sysinfo['http_user_agent'] = check_http_user_agent();  
$sysinfo['domain'] = $WX_DOMAIN; 
$sysinfo['width'] = $_SESSION['width'];  
$smarty->assign("sysinfo",$sysinfo); 
 
 
$smarty->display($action.'.tpl'); 



?>