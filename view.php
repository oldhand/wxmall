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

if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	messagebox('错误','没有店铺ID。');
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
 
if(isset($_REQUEST['id']) && $_REQUEST['id'] !='')
{
	$record = $_REQUEST['id'];  
}
else
{
	messagebox('错误','参数错误！');  
	die(); 
}

	
try{   
 
	$article_info = array();
	$loadcontent = XN_Content::load($record,'local_news_'.$supplierid); 
	
	$description = $loadcontent->my->description; 
	$articletext = $loadcontent->my->articletext;
	$articletext = str_replace("http://www.ttwz168.com","", $articletext);
	$articletext = str_replace("http://www.tezan.cn","", $articletext); 
	
	$width = "320";
	$articletext = preg_replace('#<img(.*?)src="(.*?)"(.*?)>#','<img$1 class="img-responsive lazy" src="images/lazyload.png" data-original="'.$APISERVERADDRESS.'$2?width='.$width.'"$3>', $articletext);
	
 
    $article_info['description'] = $description;   
	$article_info['image'] = $loadcontent->my->image;  
	$article_info['articleauthor'] = $loadcontent->my->articleauthor;  
	$article_info['articletext'] = $articletext;  
	$article_info['articletitle'] = $loadcontent->my->articletitle;
	$article_info['articletype'] = $loadcontent->my->articletype;
	$article_info['description'] = $description;
    $article_info['published'] =  date("Y-m-d H:i",strtotime($loadcontent->published)); 
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

$share_info = checkrecommend();    

$query_string = base64_encode($_SERVER["REQUEST_URI"]);
 
$shareurl = 'http://'.$WX_DOMAIN.'/index.php?u='.$profileid.'&sid='.$supplierid.'&uri='.$query_string; 

$share_info['share_url'] = $shareurl;
$articletitle = str_replace('"','',$article_info['articletitle']);
$share_info['share_title'] = $articletitle;

$smarty->assign("share_info",$share_info); 
$smarty->assign("supplier_info",get_supplier_info()); 
$profileinfo = get_supplier_profile_info();
$smarty->assign("profile_info",$profileinfo); 

$smarty->assign("article_info",$article_info); 


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