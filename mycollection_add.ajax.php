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
if(isset($_REQUEST['status']) && $_REQUEST['status'] !='')
{
	$status = $_REQUEST['status'];  
}  
 
 
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$productid = $_REQUEST['record'];  
}
 
 


try{   
    $mycollections = XN_Query::create ( 'Content' )
        ->tag ( "mall_mycollections_".$profileid)
        ->filter ( 'type', 'eic', 'mall_mycollections' )
        ->filter ( 'my.deleted', '=', '0' )
        ->filter ( 'my.profileid', '=', $profileid )
        ->filter ( 'my.productid', '=', $productid )
        ->end(1)
        ->execute();

    if(count($mycollections) == 0)
	{
		$newcontent = XN_Content::create('mall_mycollections','',false);					  
		$newcontent->my->deleted = '0';    
		$newcontent->my->supplierid = $supplierid;  
		$newcontent->my->profileid = $profileid; 
		$newcontent->my->productid = $productid; 
		$newcontent->my->status = $status;
		$newcontent->save('mall_mycollections,mall_mycollections_'.$profileid); 
	}
	else
	{
		$mycollection_info = $mycollections[0];
		if ($mycollection_info->my->status != $status)
		{
			$mycollection_info->my->status = $status;
			$mycollection_info->save('mall_mycollections,mall_mycollections_'.$profileid.',mall_mycollections_'.$businesseid); 
		} 
	} 
}
catch(XN_Exception $e)
{
	$msg = $e->getMessage(); 
} 

 
 
 
?>