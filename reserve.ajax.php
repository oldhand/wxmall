<?php 
 

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
	$profileid = "anonymous";
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
	$orders = mall_reserves($profileid,$supplierid,$page);
	if (count($orders) > 0)
	{
		echo '{"code":200,"data":'.json_encode($orders).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":202,"data":[]}'; 
die();  
 
  


function  mall_reserves($profileid,$supplierid,$page) 
{ 
	$mall_reserves = XN_Query::create ( 'YearContent' )->tag('mall_reserves_'.$profileid)
				->filter ( 'type', 'eic', 'mall_reserves') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*10)
				->end($page*10)
				->execute ();

	$reservelist = array();
	if (count($mall_reserves) > 0)
	{   
		 
		foreach($mall_reserves as $mall_reserve_info)
		{
			$id = $mall_reserve_info->id;  
			$published = $mall_reserve_info->published;  
			$place = $mall_reserve_info->my->reserveplace;
			$mall_reservesstatus = $mall_reserve_info->my->mall_reservesstatus;
			$reservelist[$id]['id'] = $id;    
		    $reservelist[$id]['published'] =  date("Y-m-d H:i",strtotime($published));   
			$reservelist[$id]['consignee'] = $mall_reserve_info->my->consignee;  
			$reservelist[$id]['mobile'] = $mall_reserve_info->my->mobile;  
			$reservelist[$id]['reservetime'] = $mall_reserve_info->my->reservetime;  
			$reservelist[$id]['numberofpeople'] = $mall_reserve_info->my->numberofpeople;  
			$reservelist[$id]['deskofpeople'] = $mall_reserve_info->my->deskofpeople;  
			if ($place == "room")
			{
				$reservelist[$id]['place'] = '包房'; 
			}
			else if ($place == "hall")
			{
				$reservelist[$id]['place'] = '大厅'; 
			}
			  
			$reservelist[$id]['memo'] = $mall_reserve_info->my->memo;  
			if ($mall_reservesstatus == "JustCreated")
			{
				$reservelist[$id]['mall_reservesstatus'] = '待处理'; 
			} 
			else
			{
				$reservelist[$id]['mall_reservesstatus'] = $mall_reserve_info->my->mall_reservesstatus; 
			}
			  
		} 
	}
	rsort($reservelist);
    return $reservelist; 
}
 
 
?>