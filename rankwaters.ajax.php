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
 
 
function  get_rankwatertype_info($rankwatertype)
{
	$info = array(
		'addrank' => array('info'=>'增加积分','flag'=>'+'),
		'decrank' => array('info'=>'扣除积分','flag'=>'-'),
		'productrank' => array('info'=>'购买商品增加积分','flag'=>'+'),
		'costrank' => array('info'=>'消费扣除积分','flag'=>'-'),
		'vipdepositrank' => array('info'=>'交纳保证金产生积分','flag'=>'+'), 
		'dailybonus' => array('info'=>'签到产生积分','flag'=>'+'), 
	);
	if (isset($info[$rankwatertype]) && count($info[$rankwatertype]) > 0)
	{
		return $info[$rankwatertype];
	}
	else
	{
		return   array('info'=>'未知','flag'=>'');
	}
}  


function  mall_billwaters($profileid,$supplierid,$page) 
{ 
	$mall_rankwaters = XN_Query::create ( 'MainYearContent' )->tag('mall_rankwaters_'.$profileid)
				->filter ( 'type', 'eic', 'mall_rankwaters') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*10)
				->end($page*10)
				->execute ();

	$list = array();
	if (count($mall_rankwaters) > 0)
	{    
		foreach($mall_rankwaters as $mall_rankwaters_info)
		{
			$id = $mall_rankwaters_info->id;
			$amount = $mall_rankwaters_info->my->amount;  
			$list[$id]['orderid'] = $id; 
		    $list[$id]['amount'] = abs(floatval($amount)); 
		    $list[$id]['rank'] = round(floatval($mall_rankwaters_info->my->rank));
			$published = $mall_rankwaters_info->published; 
			$rankwatertype = $mall_rankwaters_info->my->rankwatertype; 
			$rankwatertype_info = get_rankwatertype_info($rankwatertype);
		    $list[$id]['published'] =  date("Y-m-d H:i",strtotime($published)); 
		    $list[$id]['rankwatertype'] = $rankwatertype;  
			$list[$id]['rankwatertypeinfo'] = $rankwatertype_info['info']; 
			$list[$id]['flag'] = $rankwatertype_info['flag']; 
		} 
	}
	rsort($list);
    return $list; 
}
 
?>