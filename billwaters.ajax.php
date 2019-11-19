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
 
 
function  get_billwatertype_info($billwatertype)
{
	$info = array(
		'share' => array('info'=>'分享收益','flag'=>'+'),
		'commission' => array('info'=>'提成收益','flag'=>'+'),
		'ad' => array('info'=>'广告收益','flag'=>'+'),
		'popularize' => array('info'=>'推广收益','flag'=>'+'),
		'addprofile' => array('info'=>'会员管理','flag'=>'+'), 
		'reimburse' => array('info'=>'余额退款','flag'=>'+'), 
		'consumption' => array('info'=>'消费支出','flag'=>'-'),
		'decprofile' => array('info'=>'会员管理','flag'=>'-'),
		'deductpercentage' => array('info'=>'退货减提成','flag'=>'-'),
		'donation' => array('info'=>'微捐','flag'=>'-'),
		'deductpopularize' => array('info'=>'扣除推广收益','flag'=>'-'),
		'takecash' => array('info'=>'提现申请','flag'=>'-'),
		'rejecttakecash' => array('info'=>'驳回提现','flag'=>'+'),
		'transferfailure' => array('info'=>'转账失败','flag'=>'+'),
		'newprofilebenefit' => array('info'=>'新会员福利','flag'=>'+'),  
	);
	if (isset($info[$billwatertype]) && count($info[$billwatertype]) > 0)
	{
		return $info[$billwatertype];
	}
	else
	{
		return   array('info'=>'未知','flag'=>'');
	}
}  


function  mall_billwaters($profileid,$supplierid,$page) 
{ 
	$mall_billwaters = XN_Query::create ( 'MainYearContent' )->tag('mall_billwaters_'.$profileid)
				->filter ( 'type', 'eic', 'mall_billwaters') 
				->filter ( 'my.deleted', '=', '0') 
				->filter ( 'my.supplierid', '=', $supplierid)
				->filter ( 'my.profileid', '=', $profileid)  
				->order("published",XN_Order::DESC) 
				->begin(($page-1)*10)
				->end($page*10)
				->execute ();

	$billwaterlist = array();
	if (count($mall_billwaters) > 0)
	{   
		if ($page == 1)
		{
			$mall_billwater_info = $mall_billwaters[0];
			if ($mall_billwater_info->my->badge != 'yes')
			{
				$mall_billwater_info->my->badge = 'yes';
				$mall_billwater_info->save('mall_billwaters,mall_billwaters_'.$profileid);  
				XN_MemCache::delete("mall_badges_".$supplierid."_".$profileid);
			}
		}
		foreach($mall_billwaters as $mall_billwater_info)
		{
			$id = $mall_billwater_info->id;
			$amount = $mall_billwater_info->my->amount;  
			$billwaterlist[$id]['orderid'] = $id; 
		    $billwaterlist[$id]['amount'] = number_format(abs(floatval($amount)),2,".",""); 
		    $billwaterlist[$id]['money'] = $mall_billwater_info->my->money;  
			$published = $mall_billwater_info->published; 
			$billwatertype = $mall_billwater_info->my->billwatertype; 
			$billwatertype_info = get_billwatertype_info($billwatertype);
		    $billwaterlist[$id]['published'] =  date("Y-m-d H:i",strtotime($published)); 
		    $billwaterlist[$id]['billwatertype'] = $mall_billwater_info->my->billwatertype; 
			$billwaterlist[$id]['flag'] = $billwatertype_info['flag'];  
			$billwaterlist[$id]['billwatertypeinfo'] = $billwatertype_info['info']; 
		} 
	}
	rsort($billwaterlist);
    return $billwaterlist; 
}
 
?>