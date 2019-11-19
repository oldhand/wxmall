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
 
if(isset($_REQUEST['type']) && $_REQUEST['type'] !='')
{
	$type = $_REQUEST['type'];  
} 

try{
	$orders = mall_profits($profileid,$supplierid,$page,$type);
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
  

function  mall_profits($profileid,$supplierid,$page,$type) 
{

	$profits = array();
	if ($type == 'share')
	{
		$mall_billwaters = XN_Query::create ( 'MainYearContent' )->tag('mall_billwaters_'.$profileid)
					->filter ( 'type', 'eic', 'mall_billwaters') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid)  
					->filter ( 'my.billwatertype', '=', 'share')  
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*10)
					->end($page*10)
					->execute (); 
		if (count($mall_billwaters) > 0)
		{     
			foreach($mall_billwaters as $mall_billwater_info)
			{
				$id = $mall_billwater_info->id;
				$amount = $mall_billwater_info->my->amount;  
				$profits[$id]['profitid'] = $id; 
			    $profits[$id]['amount'] = number_format(abs(floatval($amount)),2,".","");  
				$published = $mall_billwater_info->published;  
			    $profits[$id]['published'] =  date("Y-m-d",strtotime($published));  
			} 
		}
	}
	else if ($type == 'popularize')
	{
		$mall_billwaters = XN_Query::create ( 'MainYearContent' )->tag('mall_billwaters_'.$profileid)
					->filter ( 'type', 'eic', 'mall_billwaters') 
					->filter ( 'my.deleted', '=', '0') 
					->filter ( 'my.supplierid', '=', $supplierid)
					->filter ( 'my.profileid', '=', $profileid)  
					->filter ( 'my.billwatertype', '=', 'popularize')  
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*10)
					->end($page*10)
					->execute (); 
		if (count($mall_billwaters) > 0)
		{   
			$inviteprofileids = array();
			foreach($mall_billwaters as $mall_billwater_info)
			{ 
				$inviteprofileid = $mall_billwater_info->my->inviteprofileid;
				if (isset($inviteprofileid) && $inviteprofileid != "")
				    $inviteprofileids[] = $mall_billwater_info->my->inviteprofileid;
			} 
			$profiles = getGivenNameArrByids($inviteprofileids);
			 			
			foreach($mall_billwaters as $mall_billwater_info)
			{
				$id = $mall_billwater_info->id;
				$amount = $mall_billwater_info->my->amount;  
				$profits[$id]['billwaterid'] = $id; 
			    $profits[$id]['amount'] = number_format(abs(floatval($amount)),2,".","");  
				$published = $mall_billwater_info->published;  
			    $profits[$id]['published'] =  date("Y-m-d",strtotime($published)); 
				$inviteprofileid = $mall_billwater_info->my->inviteprofileid; 
				if (isset($profiles[$inviteprofileid]) && $profiles[$inviteprofileid] != "")
				{
					$profits[$id]['inviteprofilename'] =  $profiles[$inviteprofileid]; 
				}
				else
				{
					$profits[$id]['inviteprofilename'] =  ""; 
				}
				
			} 
		}
	}
	else if ($type == 'commission')
	{
		$mall_commissions = XN_Query::create('YearContent')->tag('mall_commissions_'.$profileid)
			   		->filter('type','eic','mall_commissions')
			   		->filter('my.deleted','=','0') 
		            ->filter('my.supplierid','=',$supplierid) 
		   		    ->filter('my.profileid','=',$profileid) 		  
					->order("published",XN_Order::DESC) 
					->begin(($page-1)*10)
					->end($page*10)
					->execute (); 
		if (count($mall_commissions) > 0)
		{   
		 
			$orderids = array();
			foreach($mall_commissions as $mall_commission_info)
			{  
				$orderids[] = $mall_commission_info->my->orderid;
			}  
			$orders = array();
			$order_contents = XN_Content::loadMany(array_unique($orderids),"mall_orders",7); 
			foreach($order_contents as $order_content_info)
			{
				$orderid = $order_content_info->id; 
				$orders_no= $order_content_info->my->mall_orders_no; 
				$orders[$orderid] = $orders_no;  
			} 
			
			foreach($mall_commissions as $mall_commission_info)
			{
				$id = $mall_commission_info->id;
				$amount = $mall_commission_info->my->amount;  
				$profits[$id]['profitid'] = $id; 
			    $profits[$id]['amount'] = number_format(abs(floatval($amount)),2,".","");  
				$published = $mall_commission_info->published;  
			    $profits[$id]['published'] =  date("Y-m-d",strtotime($published)); 
				$orderid = $mall_commission_info->my->orderid; 
				$profits[$id]['orders_no'] =  $orders[$orderid]; 
				$commissiontype =  $mall_commission_info->my->commissiontype; 
				$profits[$id]['commissiontype'] = $commissiontype; 
				if ($commissiontype == '0')
				{
					$profits[$id]['status'] = '冻结';
				}
				else if ($commissiontype == '1')
				{
					$profits[$id]['status'] = '已结算';
				}
				else if ($commissiontype == '2')
				{
					$profits[$id]['status'] = '已退货';
				}
			} 
		}
	}
	
	rsort($profits);
    return $profits; 
}
function getGivenNameArrByids($ids){      
	if (count($ids) == 0) return array();
    $infos=XN_Profile::loadMany($ids,"id","profile");
    $givenNames=array();
    foreach($infos as $info){
        $givenname = $info->givenname;

        if ($givenname == "")
        {
            $fullName = $info->fullName;

            if(preg_match('.[#].', $fullName))
            {
                $fullNames = explode('#', $fullName);
                $fullName = $fullNames[0];
            }
            $givenname = $fullName;
        }
        $givenNames[$info->profileid]=$givenname;
    }

    return $givenNames;
}

 
 
?>