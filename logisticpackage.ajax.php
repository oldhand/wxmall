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

$mall_logistictrips = XN_Query::create('YearContent')->tag('mall_logistictrips_' . $supplierid)
    ->filter('type', 'eic', 'mall_logistictrips')
    ->filter('my.deleted', '=', '0')
    ->filter('my.supplierid', '=', $supplierid)
    ->filter('my.profileid', '=', $profileid) 
    ->filter('my.logistictripstatus', 'in', array('0','1','2'))
    ->end(1)
    ->execute();
if (count($mall_logistictrips) > 0)
{
	$mall_logistictrip_info = $mall_logistictrips[0]; 
	$logistictripid = $mall_logistictrip_info->id; 
	$current_logisticpackageid = $mall_logistictrip_info->my->current_logisticpackageid;
} 
else
{
	echo '{"code":201,"msg":"没有车次信息!"}';
	die();  
}

try{
	if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'scan' &&
	   isset($_REQUEST['scan_type']) && $_REQUEST['scan_type'] != '' &&
	   isset($_REQUEST['scan_result']) && $_REQUEST['scan_result'] != '')
	{
		$scan_type = $_REQUEST['scan_type'];  
		$scan_result = $_REQUEST['scan_result']; 
		$result = explode(",",trim($scan_result,','));
        $barcode =  $result[1];
		
		$mall_logisticpackages = XN_Query::create('Content')->tag('mall_logisticpackages_' . $supplierid)
		    ->filter('type', 'eic', 'mall_logisticpackages')
		    ->filter('my.deleted', '=', '0')
		    ->filter('my.approvalstatus', '=', '2')
		    ->filter('my.status', '=', '0')
		    ->filter('my.supplierid', '=', $supplierid)  
		    ->filter('my.mall_logisticpackages_no', '=', $barcode)
		    ->end(1)
		    ->execute();
		if (count($mall_logisticpackages) > 0)
		{
			$mall_logisticpackage_info = $mall_logisticpackages[0];
			$serialname = $mall_logisticpackage_info->my->serialname;
			$logisticpackageid = $mall_logisticpackage_info->id;
			 
					
			$logisticpackageid = $mall_logisticpackage_info->id;
			$mall_logistictrip_packages = XN_Query::create('YearContent')->tag('mall_logistictrip_packages_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logistictrip_packages')
			    ->filter('my.deleted', '=', '0')
			    ->filter('my.status', '=', '0')
			    ->filter('my.supplierid', '=', $supplierid)  
			    ->filter('my.logistictripid', '=', $logistictripid)
			    ->filter('my.logisticpackageid', '=', $logisticpackageid)
			    ->end(1)
			    ->execute();
			if (count($mall_logistictrip_packages) > 0)
			{
				$mall_logistictrip_info->my->current_logisticpackageid = $logisticpackageid;
			    $mall_logistictrip_info->save('mall_logistictrips,mall_logistictrips_' . $supplierid);
				echo '{"code":202,"logisticpackageid":"'.$logisticpackageid.'","msg":"箱包【'.$serialname.'】已经加入了当前车次!"}';  							die();  
			} 
			else
			{
				$mall_logistictrip_packages = XN_Query::create('YearContent')->tag('mall_logistictrip_packages_' . $supplierid)
				    ->filter('type', 'eic', 'mall_logistictrip_packages')
				    ->filter('my.deleted', '=', '0')
				    ->filter('my.status', '=', '0')
				    ->filter('my.supplierid', '=', $supplierid)   
				    ->filter('my.logisticpackageid', '=', $logisticpackageid)
				    ->end(1)
				    ->execute();
				if (count($mall_logistictrip_packages) > 0)
				{
					echo '{"code":201,"msg":"箱包【'.$serialname.'】已经使用中!"}';
					die();  
				} 
				else
				{
					    $logistictrip_package_info = XN_Content::create('mall_logistictrip_packages', '', false, 8);
			            $logistictrip_package_info->my->deleted = '0';
			            $logistictrip_package_info->my->supplierid = $supplierid;
			            $logistictrip_package_info->my->profileid = $profileid;
			            $logistictrip_package_info->my->status = '0';
			            $logistictrip_package_info->my->receivestatus = '0';
			            $logistictrip_package_info->my->logistictripid = $logistictripid;
			            $logistictrip_package_info->my->logisticpackageid = $logisticpackageid;
			            $logistictrip_package_info->save('mall_logistictrip_packages,mall_logistictrip_packages_' . $supplierid);
			            
			            
						 
						$mall_logistictrip_info->my->logistictripstatus = '1';
						$mall_logistictripsstatus = $mall_logistictrip_info->my->mall_logistictripsstatus; 
						if ($mall_logistictripsstatus == "JustCreated")
						{
							$mall_logistictrip_info->my->mall_logistictripsstatus = "Packing";
						} 	
			            $mall_logistictrip_info->my->current_logisticpackageid = $logisticpackageid;
			            $mall_logistictrip_info->save('mall_logistictrips,mall_logistictrips_' . $supplierid);
			            
			            flash_logistictrip($logistictripid, $supplierid);
			            
			            echo '{"code":200,"logisticpackageid":"'.$logisticpackageid.'","serialname":"'.$serialname.'"}';
					    die();  
				}
			} 			 
		}
		else
		{
			

			if (isset($current_logisticpackageid) && $current_logisticpackageid != "")
			{
				$logisticdrivers = XN_Query::create('Content')->tag('mall_logisticdrivers_' . $supplierid)
			        ->filter('type', 'eic', 'mall_logisticdrivers')
			        ->filter('my.deleted', '=', '0')
			        ->filter('my.supplierid', '=', $supplierid)
			        ->filter('my.profileid', '=', $profileid)
			        ->filter('my.approvalstatus', '=', '2')
			        ->filter('my.status', '=', '0')
			        ->end(1)
			        ->execute();
				if (count($logisticdrivers) > 0)
				{
					$logisticdriver_info = $logisticdrivers[0]; 
					$logisticdriverid = $logisticdriver_info->id;
					$drivername = $logisticdriver_info->my->drivername;
				}
				else
				{
					echo '{"code":201,"msg":"当前用户不是司机身份!"}';
					die();
				}
				$mall_logisticpackage_info = XN_Content::load($current_logisticpackageid,'mall_logisticpackages_' . $supplierid);
				$serialname = $mall_logisticpackage_info->my->serialname;
				
				$mall_logisticbills = XN_Query::create('YearContent')->tag('mall_logisticbills_' . $supplierid)
				    ->filter('type', 'eic', 'mall_logisticbills')
				    ->filter('my.deleted', '=', '0')
				    ->filter('my.supplierid', '=', $supplierid)  
				    ->filter('my.mall_logisticbills_no', '=', $barcode)
				    ->end(1)
				    ->execute();
				 
				if (count($mall_logisticbills) > 0)
				{
					$mall_logisticbill_info = $mall_logisticbills[0];
					$logisticbills_no = $mall_logisticbill_info->my->logisticbills_no;
					$bill_logistictripid = $mall_logisticbill_info->my->logistictripid;
					$logisticpackageid = $mall_logisticbill_info->my->logisticpackageid; 
					 
					$mall_logisticbill_info->my->logistictripid = $logistictripid; 
					$mall_logisticbill_info->my->logisticpackageid = $current_logisticpackageid; 
					$mall_logisticbill_info->my->logisticdriverid = $logisticdriverid;
					$mall_logisticbill_info->my->mall_logisticbillsstatus = "Packing";
					$mall_logisticbill_info->save('mall_logisticbills,mall_logisticbills_' . $supplierid);
					
					$logisticroute_info = XN_Content::create('mall_logisticroutes','',false,7);
		            $logisticroute_info->my->deleted = '0';
					$logisticroute_info->my->supplierid = $supplierid;  
					$logisticroute_info->my->logisticbillid = $mall_logisticbill_info->id; 
					$logisticroute_info->my->route = "司机".$drivername."装箱【".$serialname."】完毕。";
		            $logisticroute_info->save('mall_logisticroutes,mall_logisticroutes_'.$supplierid);
		            
		            flash_logistictrip($logistictripid, $supplierid);
	            
		            echo '{"code":300,"logisticpackageid":"'.$current_logisticpackageid.'","logisticbills_no":"'.$logisticbills_no.'"}';
				    die();  
				}
			}
			else
			{
				    echo '{"code":201,"msg":"请先扫码一个箱包条形码，才能扫码包裹条形码!"}';
					die();  
			}
		}		
			
	} 
}
catch(XN_Exception $e)
{
	 
} 
echo '{"code":201,"msg":"系统没有找到匹配的条码!"}';
die();  
  
  
function flash_logistictrip($logistictripid, $supplierid)
{
    $logistictrip_info = XN_Content::load($logistictripid,'mall_logistictrips_' . $supplierid,7);     
	
	$query = XN_Query::create('YearContent_count')->tag('mall_logistictrip_packages_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logistictrip_packages')
			    ->filter('my.deleted', '=', '0') 
			    ->filter('my.supplierid', '=', $supplierid)  
			    ->filter('my.logistictripid', '=', $logistictripid) 
			    ->rollup()
			    ->end(-1);   
    $query->execute();		
	$packagecount = $query->getTotalCount();   
	
	$query = XN_Query::create('YearContent_count')->tag('mall_logisticbills_' . $supplierid)
			    ->filter('type', 'eic', 'mall_logisticbills')
			    ->filter('my.deleted', '=', '0') 
			    ->filter('my.supplierid', '=', $supplierid)  
			    ->filter('my.logistictripid', '=', $logistictripid) 
			    ->rollup()
			    ->end(-1);   
    $query->execute();		
	$billcount = $query->getTotalCount(); 
	
	if ($logistictrip_info->my->packagecount != $packagecount || $logistictrip_info->my->billcount != $billcount)
	{
		$logistictrip_info->my->packagecount = $packagecount;
		$logistictrip_info->my->billcount = $billcount;
		$logistictrip_info->save('mall_logistictrips,mall_logistictrips_' . $supplierid);
	}
} 
?>