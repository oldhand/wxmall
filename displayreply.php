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
require_once (dirname(__FILE__) . "/util.php");


XN_Application::$CURRENT_URL = "admin";

if( isset($_GET['type']) && $_GET['type'] =='o2o')
{
	if( isset($_GET['id']) && $_GET['id'] !='')
	{ 
		try
		{  
			$wxrole = XN_Content::load($_GET['id'],"supplier_wxroles"); 
			$reply = $wxrole->my->reply;
			$replytitle = $wxrole->my->replytitle;
			$supplierid = $wxsetting->my->supplierid;
 
		}
	    catch ( XN_Exception $e ) 
	    { 
	    	$replytitle = "错误";
	    	$reply = $e->getMessage ();
	    }
	}
	elseif( isset($_GET['wxid']) && $_GET['wxid'] !='')
	{ 
		try
		{
			$wxsetting = XN_Content::load($_GET['wxid'],"supplier_wxsettings"); 
			$reply = $wxsetting->my->welcomewords;
			$replytitle = $wxsetting->my->welcometitle;
			$supplierid = $wxsetting->my->supplierid;
 
		}
	    catch ( XN_Exception $e ) 
	    { 
	    	$replytitle = "错误";
	    	$reply = $e->getMessage ();
	    }
	}	 
	else
	{
		$replytitle = "错误";
		$reply = '链接地址有误！';
	}
}
else
{
	if( isset($_GET['id']) && $_GET['id'] !='')
	{ 
		try
		{  
			$wxrole = XN_Content::load($_GET['id'],"wxroles"); 
			$reply = $wxrole->my->reply;
			$replytitle = $wxrole->my->replytitle;
			$supplierid = $wxsetting->my->supplierid;
 
		}
	    catch ( XN_Exception $e ) 
	    { 
	    	$replytitle = "错误";
	    	$reply = $e->getMessage ();
	    }
	}
	elseif( isset($_GET['wxid']) && $_GET['wxid'] !='')
	{ 
		try
		{
			$wxsetting = XN_Content::load($_GET['wxid'],"wxsettings"); 
			$reply = $wxsetting->my->welcomewords;
			$replytitle = $wxsetting->my->welcometitle;
			$supplierid = $wxsetting->my->supplierid;
 
		}
	    catch ( XN_Exception $e ) 
	    { 
	    	$replytitle = "错误";
	    	$reply = $e->getMessage ();
	    }
	}	 
	else
	{
		$replytitle = "错误";
		$reply = '链接地址有误！';
	}
}



if($_REQUEST['title']){
    $replytitle=$_REQUEST['title'];
}

require_once('util.php'); 
require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;


$smarty->assign("id",$_GET['id']);  
$smarty->assign("replytitle",$replytitle); 

 
$reply = str_replace("http://www.ttwz168.com","", $reply);
$reply = preg_replace('#<img(.*?)src="(.*?)"(.*?)>#','<img$1 class="img-responsive" src="'.$APISERVERADDRESS.'$2"$3>', $reply);
$reply = str_replace("<p>","", $reply);
$reply = str_replace("</p>","", $reply);


$smarty->assign("reply",$reply);  

$smarty->assign("supplier_info",get_supplier_info($supplierid));  
 
$smarty->display('reply.tpl');  
 




?>