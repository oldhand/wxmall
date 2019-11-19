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


 
try{    
    $commissions_query = XN_Query::create('YearContent_Count')->tag('mall_commissions_'.$profileid)
	   		->filter('type','eic','mall_commissions')
	   		->filter('my.deleted','=','0') 
            ->filter('my.supplierid','=',$supplierid) 
   		    ->filter('my.profileid','=',$profileid) 						
   		    ->rollup('my.amount')
            ->group('my.commissiontype')
	   		->begin(0)
	   		->end(-1)
	   		->execute();
   $commission = array('0'=>0,'1'=>0,'2'=>0,);
   foreach($commissions_query as $commission_info)
   {
       $amount = $commission_info->my->amount;
	   $commissiontype = $commission_info->my->commissiontype;
	   $data = $commission[$commissiontype];
       $commission[$commissiontype] = $data + floatval($amount);
   }
   
   $billwaters_query = XN_Query::create('MainYearContent_Count')->tag('mall_billwaters_'.$profileid)
   		    ->filter('type','eic','mall_billwaters')
   		    ->filter('my.deleted','=','0') 
            ->filter('my.supplierid','=',$supplierid) 
  		    ->filter('my.profileid','=',$profileid) 	
		    ->filter('my.billwatertype','in',array("share","popularize")) 					
  		    ->rollup('my.amount')
            ->group('my.billwatertype')
   		->begin(0)
   		->end(-1)
   		->execute();
  $billwater = array('share'=>0,'popularize'=>0);
  foreach($billwaters_query as $billwater_info)
  {
      $amount = $billwater_info->my->amount;
      $billwatertype = $billwater_info->my->billwatertype;
      $data = $billwater[$commissiontype];
      $billwater[$billwatertype] = $data + floatval($amount);
  }
  	$authenticationprofiles = XN_Query::create('Content')->tag('supplier_authenticationprofiles_' . $supplierid)
        ->filter('type', 'eic', 'supplier_authenticationprofiles')
        ->filter('my.deleted', '=', '0')
        ->filter('my.supplierid', '=', $supplierid)
        ->filter('my.profileid', '=', $profileid)
        ->end(1)
        ->execute();
    $authentication_remainmoney = 0;
	if (count($authenticationprofiles) > 0)
	{
		$authenticationprofile_info = $authenticationprofiles[0];		 
		$authentication_remainmoney = $authenticationprofile_info->my->remainmoney;
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
$profile_info = get_supplier_profile_info();
if (count($profile_info) > 0)
{
	$smarty->assign("profile_info",$profile_info);
}
else
{
	$smarty->assign("profile_info",get_profile_info());
}
$profileinfo = get_supplier_profile_info(); 
$accumulatedmoney = $profileinfo['accumulatedmoney'];
$money = $profileinfo['money'];
$profileinfo['money'] = number_format($money,2,".","");	
$profileinfo['accumulatedmoney'] = number_format($accumulatedmoney,2,".","");	
$smarty->assign("profile_info",$profileinfo); 

$totalcommission = $commission['0']+$commission['1']+$commission['2'];
$frozencommission = $commission['0'];
$smarty->assign("frozencommission",number_format($frozencommission,2,".",""));
$smarty->assign("totalcommission",number_format($totalcommission,2,".",""));

$smarty->assign("authentication_remainmoney",number_format($authentication_remainmoney,2,".",""));



$share = $billwater['share'];
$popularize = $billwater['popularize']; 
$smarty->assign("share",number_format($share,2,".",""));
$smarty->assign("popularize",number_format($popularize,2,".",""));

$total = $popularize + $share + $totalcommission;
$smarty->assign("total",number_format($total,2,".",""));

$smarty->assign("supplier_info",get_supplier_info());  
 
 
$smarty->display($action.'.tpl'); 



?>