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
	$orders = mall_billwaters($profileid,$supplierid,$page);
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
 
 
function  get_depositwatertype_info($depositwatertype)
{ 
	$info = array(
		'newmoney' => array('info'=>'新交消费承诺金','flag'=>'+'),
		'addmoney' => array('info'=>'增加消费承诺金','flag'=>'+'),
		'returnmoney' => array('info'=>'退回消费承诺金','flag'=>'-'), 
	);
	if (isset($info[$depositwatertype]) && count($info[$depositwatertype]) > 0)
	{
		return $info[$depositwatertype];
	}
	else
	{
		return   array('info'=>'未知','flag'=>'');
	}
}  


function  mall_billwaters($profileid,$supplierid,$page) 
{ 
	$mall_depositwaters = XN_Query::create ( 'MainYearContent' )->tag('mall_depositwaters')
				->filter ( 'type', 'eic', 'mall_depositwaters') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*10)
				->end($page*10)
				->execute ();

	$list = array();
	if (count($mall_depositwaters) > 0)
	{    
		foreach($mall_depositwaters as $mall_depositwater_info)
		{
			$id = $mall_depositwater_info->id;
			$amount = $mall_depositwater_info->my->amount;   
		    $list[$id]['amount'] = number_format(abs(floatval($amount)),2,".",""); 
		    $list[$id]['remainmoney'] = $mall_depositwater_info->my->remainmoney;  
			$list[$id]['returncount'] = $mall_depositwater_info->my->returncount;  
			$published = $mall_depositwater_info->published; 
			$depositwatertype = $mall_depositwater_info->my->depositwatertype; 
			$depositwatertype_info = get_depositwatertype_info($depositwatertype);
		    $list[$id]['published'] =  date("Y-m-d H:i",strtotime($published)); 
		    $list[$id]['depositwatertype'] = $mall_depositwater_info->my->depositwatertype; 
			$list[$id]['flag'] = $depositwatertype_info['flag'];  
			$list[$id]['depositwatertypeeinfo'] = $depositwatertype_info['info']; 
		} 
	}
	rsort($list);
    return $list; 
}
 
?>