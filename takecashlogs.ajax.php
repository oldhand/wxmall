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
	echo '{"code":201,"data":[]}';
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
	global $noofrows;
	$noofrows = 0;
	$takecashlogs = takecashlogs($supplierid,$profileid,$page);
	if (count($takecashlogs) > 0)
	{
		echo '{"code":200,"length":'.$noofrows.',"data":'.json_encode($takecashlogs).'}';
		die();
	}
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":200,"length":0,"data":[]}'; 
die();  
 

function  takecashlogs($supplierid,$profileid,$page)
{  
	$query = XN_Query::create ( 'YearContent' )->tag("supplier_takecashs_".$profileid)
						->filter ( 'type', 'eic', 'supplier_takecashs')  
				        ->filter (  'my.supplierid', '=', $supplierid) 
						->filter (  'my.profileid', '=', $profileid)  
				        ->filter (  'my.deleted', '=', '0' )
						->order("published",XN_Order::DESC) 
						->begin(($page-1)*5)
						->end($page*5);
	$supplier_takecashs = $query->execute();
	$noofrows = $query->getTotalCount();  
	$takecashlist = array();
	if (count($supplier_takecashs) > 0)
	{  
		$pos = 0;
		foreach($supplier_takecashs as $supplier_takecash_info)
		{ 
			$bank = $supplier_takecash_info->my->bank;
			$account = $supplier_takecash_info->my->account;
			$realname = $supplier_takecash_info->my->realname;
			$amount = $supplier_takecash_info->my->amount; 
			$idcard = $supplier_takecash_info->my->idcard;
			$takecashsstatus = $supplier_takecash_info->my->supplier_takecashsstatus;
			$execute = $supplier_takecash_info->my->execute;
			$executedatetime = $supplier_takecash_info->my->executedatetime;
			$newmoney = $supplier_takecash_info->my->newmoney; 
			$money = $supplier_takecash_info->my->money; 
			$tradestatus = $supplier_takecash_info->my->tradestatus;
			$rejectreason = $supplier_takecash_info->my->rejectreason;
			$published = $supplier_takecash_info->published;
			
			
			
			$takecashlist[$pos]['bank'] = $bank;  
			$takecashlist[$pos]['account'] = $account;
			$takecashlist[$pos]['realname'] = $realname;  
			$takecashlist[$pos]['amount'] = $amount; 
			$takecashlist[$pos]['idcard'] = $idcard; 
			$takecashlist[$pos]['takecashsstatus'] = $takecashsstatus; 
			$takecashlist[$pos]['execute'] = $execute; 
			$takecashlist[$pos]['executedatetime'] = date("Y-m-d H:i",strtotime($executedatetime));
			$takecashlist[$pos]['newmoney'] = $newmoney;
			$takecashlist[$pos]['money'] = $money; 
			$takecashlist[$pos]['tradestatus'] = $tradestatus;
			$takecashlist[$pos]['rejectreason'] = $rejectreason;
			$takecashlist[$pos]['published'] = date("Y-m-d H:i",strtotime($published));
			$pos = $pos + 1; 
		     
		} 
	}  
    return $takecashlist; 
}
 
?>