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
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
}
 
try{   
	$mall_salesactivitys = XN_Query::create ( 'Content' )->tag('mall_salesactivitys')
				->filter ( 'type', 'eic', 'mall_salesactivitys') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.approvalstatus', '=', '2') 
				->filter ( 'my.status', '=', '0')  
				->filter ( 'my.begindate', '<=', date("Y-m-d")) 
				->filter ( 'my.enddate', '>=', date("Y-m-d"))  
				->order("my.sequence",XN_Order::DESC_NUMBER)  
				->end(-1)
				->execute ();

	$salesactivitylist = array();
	if (count($mall_salesactivitys) > 0)
	{    
		foreach($mall_salesactivitys as $mall_salesactivity_info)
		{
			$id = $mall_salesactivity_info->id;  
			$published = $mall_salesactivity_info->published;    
			$salesactivitylist[$id]['id'] = $id;    
		    $salesactivitylist[$id]['published'] =  date("Y-m-d H:i",strtotime($published));   
			$salesactivitylist[$id]['activityname'] = $mall_salesactivity_info->my->activityname;  
			$salesactivitylist[$id]['display_type'] = $mall_salesactivity_info->my->display_type;  
			$salesactivitylist[$id]['status'] = $mall_salesactivity_info->my->status;  
			$salesactivitylist[$id]['sequence'] = $mall_salesactivity_info->my->sequence;  
			$salesactivitylist[$id]['activitylogo'] = $mall_salesactivity_info->my->activitylogo;  
			$salesactivitylist[$id]['homepage'] = $mall_salesactivity_info->my->homepage;  
			$salesactivitylist[$id]['activity_desc'] = $mall_salesactivity_info->my->activity_desc; 
			$salesactivitylist[$id]['begindate'] = date("Y-m-d",strtotime($mall_salesactivity_info->my->begindate)); 
			$salesactivitylist[$id]['enddate'] = date("Y-m-d",strtotime($mall_salesactivity_info->my->enddate));   
			$salesactivitylist[$id]['mall_salesactivitysstatus'] = $mall_salesactivity_info->my->mall_salesactivitysstatus;  
			  
		} 
	}
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage();	
	messagebox('错误',$msg);
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

$smarty->assign("salesactivitylist",$salesactivitylist); 

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