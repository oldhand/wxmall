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
function bubble_sort($array) 
{
     for($i = 0; $i < count($array) - 1; $i++) {    //$i为已经排过序的元素个数
         for($j = 0; $j < count($array) - 1 - $i; $j++) {    //$j为需要排序的元素个数,用总长减去$i
             if($array[$j]['distance'] > $array[$j + 1]['distance']) {    //按升序排序
                 $temp = $array[$j];
                 $array[$j] = $array[$j + 1];
                 $array[$j + 1] = $temp;
             }
        }
     }
    return $array;
}

 
if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'ajax')
{
	if (isset($_REQUEST['longitude']) && $_REQUEST['longitude'] != '' &&
		isset($_REQUEST['latitude']) && $_REQUEST['latitude'] != '' )
	{
	   	 $latitude = $_REQUEST['latitude'];
	   	 $longitude = $_REQUEST['longitude'];  
	 
	   	 if ($latitude != '0' && $longitude != '0')
	   	 {
	   		 require_once (XN_INCLUDE_PREFIX."/XN/Wx.php");
	   		 require_once (XN_INCLUDE_PREFIX."/XN/Earth.php"); 
	 
	   		 $newlocation = XN_Earth::weixin_to_baidu($latitude, $longitude);
	 
	   		 $newlatitude = $newlocation['lat'];
	   		 $newlongitude = $newlocation['lng'];  
			 try{
			 	$supplier_physicalstores = XN_Query::create ( 'Content' )->tag('supplier_physicalstores_'.$supplierid)
			 	    ->filter ( 'type', 'eic', 'supplier_physicalstores')
			 	    ->filter ( 'my.deleted', '=', '0') 
			 		->filter ( 'my.supplierid', '=', $supplierid) 
			 		->begin(0)
			 		->end(-1)
			 		->execute();
			 	$data = array();
			 	foreach($supplier_physicalstores as $physicalstore_info){
			 		$image = $physicalstore_info->my->image;
			 		if (!isset($image) || $image == "")
			 		{
				 		$image = "/images/noimage.jpg";
			 		}
					$lat = $physicalstore_info->my->latitude;
					$lng = $physicalstore_info->my->longitude;
					$distance = XN_Earth::Distance($lng,$lat,$newlongitude,$newlatitude);  
			 		$data[] = array('id'=>$physicalstore_info->id,
			 	                       'image'=>$image,
			 						   'storename'=>$physicalstore_info->my->storename,
			 						   'street'=>$physicalstore_info->my->street,
			 						   'district'=>$physicalstore_info->my->district,
			 						   'address'=>$physicalstore_info->my->address, 
									   'latitude'=>$lat,
									   'longitude'=>$lng, 
									   'distance'=>$distance,
									   'distanceinfo'=>XN_Earth::DistanceInfo($distance),
			 					   );
			 	}
			
			 	if (count($data) > 0)
			 	{
					$newdata = bubble_sort($data);
			 		echo '{"code":200,"length":'.count($data).',"data":'.json_encode($newdata).'}';
					die();
			 	} 
			 }
			 catch(XN_Exception $e)
			 {
		 	 
			 } 
		}
	 	else
	 	{
	 	 	$supplier_physicalstores = XN_Query::create ( 'Content' )->tag('supplier_physicalstores_'.$supplierid)
	 	 	    ->filter ( 'type', 'eic', 'supplier_physicalstores')
	 	 	    ->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.approvalstatus', '=', '2')
	 	 		->filter ( 'my.supplierid', '=', $supplierid)  
	 	 		->begin(0)
	 	 		->end(-1)
	 	 		->execute();
	 	 	$data = array();
	 	 	foreach($supplier_physicalstores as $physicalstore_info){
	 	 		$image = $physicalstore_info->my->image;
 	 			if (!isset($image) || $image == "")
		 		{
			 		$image = "/images/noimage.jpg";
		 		}
	 			$lat = $physicalstore_info->my->latitude;
	 			$lng = $physicalstore_info->my->longitude; 
	 	 		$data[] = array('id'=>$physicalstore_info->id,
	 	 	                       'image'=>$image,
	 	 						   'storename'=>$physicalstore_info->my->storename,
	 	 						   'street'=>$physicalstore_info->my->street,
	 	 						   'district'=>$physicalstore_info->my->district,
	 	 						   'address'=>$physicalstore_info->my->address, 
	 							   'latitude'=>$lat,
	 							   'longitude'=>$lng, 
	 							   'distance'=> "",
	 							   'distanceinfo'=>"没有获得您的定位信息。",
	 	 					   );
	 	 	}  
	 	 	if (count($data) > 0)
	 	 	{ 
	 	 		echo '{"code":200,"length":'.count($data).',"data":'.json_encode($data).'}';
	 			die();
	 	 	} 
	 	}
		
	}
	
    echo '{"code":200,"length":0,"data":[]}';
	die();
}

try{   
 
	
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