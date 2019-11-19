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

if(isset($_REQUEST['page']) && $_REQUEST['page'] !='')
{
	$page = $_REQUEST['page'];  
}
else
{
	echo '{"code":201,"data":[]}';
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
	$profiles = supplier_profile($supplierid,$page);
	if (count($profiles) > 0)
	{
		echo '{"code":200,"data":'.json_encode($profiles).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":202,"data":[]}'; 
die();  
 

function  supplier_profile($supplierid,$page)
{ 
	if ($page > 5) return array();
	$query = XN_Query::create ( 'MainContent' ) ->tag("supplier_profile_".$supplierid)	 
				->filter('type','eic','supplier_profile')
				->filter ('my.supplierid', '=', $supplierid) 
				->order("my.accumulatedmoney",XN_Order::DESC_NUMBER) 
				->begin(($page-1)*10)
				->end($page*10);
	$supplier_profiles = $query->execute();
	$noofrows = $query->getTotalCount();  
	$profilelist = array();
	if (count($supplier_profiles) > 0)
	{  
		$key = ($page-1)*10+1;
		foreach($supplier_profiles as $supplier_profile_info)
		{ 
			$accumulatedmoney = $supplier_profile_info->my->accumulatedmoney;
			$rank = $supplier_profile_info->my->rank; 
			$profilelist[$key]['profileid'] = $supplier_profile_info->my->profileid; 
			$profilelist[$key]['mobile'] = $supplier_profile_info->my->mobile;   
			$profilelist[$key]['givenname'] = $supplier_profile_info->my->givenname; 
			$profilelist[$key]['rank'] = $rank; 
			$profilelist[$key]['rankname'] = getProfileRank($rank);
			$profilelist[$key]['accumulatedmoney'] = number_format($accumulatedmoney,2,".","");  
			$profilelist[$key]['fubusi'] = $key; 
			$key ++; 
		     
		} 
	}  
    return $profilelist; 
}
 
?>