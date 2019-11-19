<?php


session_start(); 
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/util.php");


global $supplierid;
if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	echo '{"code":201,"msg":"没有店铺ID!"}';
	die();  
} 	
	
if(isset($_SESSION['profileid']) && $_SESSION['profileid'] != '' &&
	isset($_REQUEST['sharepage']) && $_REQUEST['sharepage'] != '' ) 
{
	$profileid = $_SESSION['profileid'];
	$sharedate = date("Y-m-d");
	$sharepage = $_REQUEST['sharepage'];
 
	share($profileid,$sharedate,$sharepage);
						
}


function share($profileid,$sharedate,$sharepage) 
{   
		 global $supplierid;
		
		$shares = XN_Query::create ( 'YearContent' )->tag('mall_shares_'.$profileid)
				->filter ( 'type', 'eic', 'mall_shares' )
				
				->filter (  'my.deleted', '=', '0' )
				->filter (  'my.profileid', '=', $profileid )
				->filter (  'my.supplierid', '=', $supplierid )
				->filter (  'my.sharedate', '=', $sharedate )
				->filter (  'my.sharepage', '=', $sharepage )
				->execute ();
		 
		if(count($shares) > 0) return; 
	 
		if ($sharepage == 'index')
		{
			$profile_info = get_supplier_profile_info(); 
			$supplierinfo = get_supplier_info(); 
			$sharefund = $supplierinfo['sharefund'];
			if (floatval($sharefund) > 0)
			{
				$newcontent = XN_Content::create('mall_shares','',false,7);					  
				$newcontent->my->deleted = '0';  
				$newcontent->my->profileid = $profileid;
				$newcontent->my->supplierid = $supplierid; 
			    $newcontent->my->sharedate = $sharedate; 
				$newcontent->my->sharefund = $supplierinfo['sharefund']; 
				$newcontent->my->amount = $sharefund;  
				$newcontent->my->province = $profile_info['province'];
				$newcontent->my->city =  $profile_info['city'];
				$newcontent->my->profilerank = $profile_info['rank'];
				$newcontent->my->sharepage = $sharepage;  
				$newcontent->my->ip = $_SERVER['REMOTE_ADDR'];
				$newcontent->my->browser = getsharebrowser();
				$newcontent->my->system = getsharesystem(); 
				$newcontent->save('mall_shares,mall_shares_'.$profileid);  
				
				$money = $profile_info['money'];
				$new_money = floatval($money) + floatval($sharefund);
				$accumulatedmoney = $profile_info['accumulatedmoney'];
				$new_accumulatedmoney = floatval($accumulatedmoney) + floatval($sharefund);
				   
			 	 
				$billwater_info = XN_Content::create('mall_billwaters','',false,8);					  
				$billwater_info->my->deleted = '0';    
				$billwater_info->my->supplierid = $supplierid;  
				$billwater_info->my->profileid = $profileid; 
				$billwater_info->my->billwatertype = 'share';
				$billwater_info->my->sharedate = $sharedate;  
				$billwater_info->my->orderid = '';
				$billwater_info->my->shareid = $newcontent->id;
				$billwater_info->my->amount = '+'.number_format($sharefund,2,".",""); 
				$billwater_info->my->money = number_format($new_money,2,".","");
				$billwater_info->save('mall_billwaters,mall_billwaters_'.$profileid);  
			 
				
				$profile_info['money'] = $new_money; 
				$profile_info['accumulatedmoney'] = $new_accumulatedmoney;

				$takecashitem = $supplierinfo['takecashitem'];
				if (in_array('2',$takecashitem))
				{
					$maxtakecash = $profile_info['maxtakecash'];
					$new_maxtakecash = floatval($maxtakecash) + floatval($sharefund);
					$profile_info['maxtakecash'] = $new_maxtakecash;
				}

				update_supplier_profile_info($profile_info);
				
				
			}
			else
			{
				$newcontent = XN_Content::create('mall_shares','',false,7);					  
				$newcontent->my->deleted = '0';  
				$newcontent->my->profileid = $profileid;
				$newcontent->my->supplierid = $supplierid; 
			    $newcontent->my->sharedate = $sharedate; 
				$newcontent->my->sharefund = $profile_info['sharefund']; 
				$newcontent->my->amount = "0.00";  
				$newcontent->my->province = $profile_info['province'];
				$newcontent->my->city =  $profile_info['city'];
				$newcontent->my->profilerank = $profile_info['rank'];
				$newcontent->my->sharepage = $sharepage;  
				$newcontent->my->ip = $_SERVER['REMOTE_ADDR'];
				$newcontent->my->browser = getsharebrowser();
				$newcontent->my->system = getsharesystem(); 
				$newcontent->save('mall_shares,mall_shares_'.$profileid); 
			} 
		}
		else
		{
			$profile_info = get_supplier_profile_info(); 
			$newcontent = XN_Content::create('mall_shares','',false,7);					  
			$newcontent->my->deleted = '0';  
			$newcontent->my->profileid = $profileid;
			$newcontent->my->supplierid = $supplierid; 
		    $newcontent->my->sharedate = $sharedate; 
			$newcontent->my->sharefund = $profile_info['sharefund']; 
			$newcontent->my->amount = "0.00";  
			$newcontent->my->province = $profile_info['province'];
			$newcontent->my->city =  $profile_info['city'];
			$newcontent->my->profilerank = $profile_info['rank'];
			$newcontent->my->sharepage = $sharepage;  
			$newcontent->my->ip = $_SERVER['REMOTE_ADDR'];
			$newcontent->my->browser = getsharebrowser();
			$newcontent->my->system = getsharesystem(); 
			$newcontent->save('mall_shares,mall_shares_'.$profileid); 
		}
		
		
		
		 
}

	
?>