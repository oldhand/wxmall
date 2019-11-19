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



if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'confirmpayment' )
{
	if (isset($_REQUEST['authenticationid']) && $_REQUEST['authenticationid'] != '' &&
		isset($_REQUEST['paymentway']) && $_REQUEST['paymentway'] != '')
	{
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
			echo '{"code":201,"msg":"用户ID!"}';
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

		if(isset($_REQUEST['authenticationid']) && $_REQUEST['authenticationid'] !='')
		{
			$authenticationid = $_REQUEST['authenticationid']; 
			$paymentway = $_REQUEST['paymentway'];
			$supplier_profilerank_info = XN_Content::load($authenticationid,'supplier_profileranks');
			$rankname = $supplier_profilerank_info->my->rankname;
			$rankdiscount = $supplier_profilerank_info->my->rankdiscount;
			$depositmoney = $supplier_profilerank_info->my->depositmoney;
			$needmoney = $supplier_profilerank_info->my->depositmoney; 
			$sequence = $supplier_profilerank_info->my->sequence;
			$rankdiscount = $supplier_profilerank_info->my->rankdiscount;
	        try
	        {
	            $profileranks = XN_MemCache::get("profileranks_" . $supplierid.'_'.$profileid); 
				$needmoney = $profileranks[$authenticationid]['needmoney'];
	        }
	        catch (XN_Exception $e)
	        {
			    $msg = $e->getMessage(); 
				echo '{"code":201,"msg":"'.$msg.'"}';
			    die();
	        }
			 
			try{   
				
			    $newcontent = XN_Content::create('supplier_authenticationorders', '', false);
	            $newcontent->my->deleted = '0';
	            $newcontent->my->profileid = $profileid;
	            $newcontent->my->supplierid = $supplierid;
	            $newcontent->my->profilerankid = $authenticationid;
				$newcontent->my->sequence = $sequence;
				$newcontent->my->rankname = $rankname; 
				$newcontent->my->amount = number_format($needmoney,2,".",""); 
				$newcontent->my->paymentstatus = "0";
				$newcontent->my->rankdiscount = $rankdiscount;
				$newcontent->my->supplier_authenticationordersstatus = "JustCreated";
	            $newcontent->save("supplier_authenticationorders,supplier_authenticationorders_".$profileid.",supplier_authenticationorders_".$supplierid);
			
				$ordername = '认证'.$rankname;
				$order_no = 'AUTHORD_'.$newcontent->id;
				$orderid = $newcontent->id;
				$amount = $needmoney*100; 
				require_once (dirname(__FILE__) . "/payment.func.php");
				if ($supplierid == "71352" || $supplierid == "12434") // 特赞测试账号
				{
					$jsApiParameters = weixin_jsapi($profileid,$ordername,'1',$order_no,$orderid);
				}
				else
				{
					$jsApiParameters = weixin_jsapi($profileid,$ordername,$amount,$order_no,$orderid);
				} 
				
				echo '{"code":200,"paymentway":"weixin","orderid":'.$newcontent->id.',"json":',$jsApiParameters,'}';
				die();
			}
			catch(XN_Exception $e)
			{
				$msg = $e->getMessage();	
				echo '{"code":202,"msg":"'.$msg.'"}'; 
				die(); 
			}  
		} 
		else
		{
			echo '{"code":201,"msg":"没有订单ID!"}';
			die();  
		}
	}
	echo '{"code":201,"msg":"错误的参数!"}';
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
	if(isset($_REQUEST['authenticationid']) && $_REQUEST['authenticationid'] !='')
	{
		$authenticationid = $_REQUEST['authenticationid']; 
		$supplier_profilerank_info = XN_Content::load($authenticationid,'supplier_profileranks');
		$profilerank = array();
		$profilerank['authenticationid'] = $authenticationid;
		$profilerank['rankname'] = $supplier_profilerank_info->my->rankname;
		$profilerank['rankdiscount'] = $supplier_profilerank_info->my->rankdiscount;
		$profilerank['depositmoney'] = $supplier_profilerank_info->my->depositmoney;
		$profilerank['needmoney'] = $supplier_profilerank_info->my->depositmoney;
		$profilerank['csskey'] = $supplier_profilerank_info->my->csskey;
        try
        {
            $profileranks = XN_MemCache::get("profileranks_" . $supplierid.'_'.$profileid);  
			$profilerank['needmoney'] = $profileranks[$authenticationid]['needmoney'];
        }
        catch (XN_Exception $e)
        {
		    $msg = $e->getMessage();  
			messagebox('错误',$msg); 
		    die();
        } 
	} 
	else
	{
		messagebox('错误',"没有authenticationid!"); 
		die();  
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
  
$profile_info = get_supplier_profile_info();  
 
$smarty->assign("profile_info",$profile_info);

$smarty->assign("profilerank",$profilerank);
	
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