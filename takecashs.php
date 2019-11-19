<?php
/*+**********************************************************************************
 * The contents of this file are subject to the 361CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  361CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/


session_start(); 

require_once (dirname(__FILE__) . "/config.inc.php");
require_once (dirname(__FILE__) . "/config.common.php");
require_once (dirname(__FILE__) . "/util.php");

$needverifycode = "no";

$unlimited_suppliers = array("352307","71352","496790");

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
	messagebox('错误','请从微信公众号“特赞商城”或朋友圈中朋友分享链接进入本平台，如您确实采用上述方式仍然出现本信息，请与系统管理员联系。');
	die();
}

if(isset($_SESSION['supplierid']) && $_SESSION['supplierid'] !='')
{
	$supplierid = $_SESSION['supplierid']; 
} 
else
{
	messagebox('错误','没有店铺ID!');
	die();  
} 
 if (isset($_REQUEST['mobile']) && $_REQUEST['mobile'] != '' && $_REQUEST['type'] == 'send')
{
    $mobile = $_REQUEST['mobile'];
    $mobile = trim($mobile);

    try
    { 

        $checkcode = randomkeys(6);
        XN_MemCache::put($checkcode, "verifycode_" . $profileid, "3600");
 
        global $copyrights; 
	    $access_key_id = $copyrights['sms_access_key_id'];
	    $access_key_secret = $copyrights['sms_access_key_secret'];
	    $signname =  $copyrights['sms_signname'];
	    $templatecode = $copyrights['sms_tixian_templatecode'];
		
		if (isset($access_key_id) && $access_key_id !="" &&
			isset($access_key_secret) && $access_key_secret !="" &&
			isset($signname) && $signname !="" &&
			isset($templatecode) && $templatecode !="" )
        { 
	         
		   XN_Content::create('sendmobile', '', false, 2)
			            ->my->add('status', 'waiting') 
			            ->my->add('to_mobile', $mobile)
			            ->my->add('verifycode', $checkcode)
				        ->my->add('access_key_id', $access_key_id)
			            ->my->add('access_key_secret', $access_key_secret)
			            ->my->add('signname', urlencode($signname))
			            ->my->add('codename', 'code')
			            ->my->add('templatecode', $templatecode)	
			            ->save("sendmobile");
		 	            
            echo '{"code":200,"msg":"success"}';
			die();
        }
        else
        {
	        echo '{"code":202,"msg":"系统没有配置短信网关参数!"}';
            die(); 
        } 

    }
    catch (XN_Exception $e)
    {
        echo '{"code":201,"msg":"' . $e->getMessage() . '"}';
        die();
    }
}

if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'submit')
{ 
	try{   
		if(isset($_REQUEST['token']) && $_REQUEST['token'] != '' &&
		   isset($_REQUEST['bank']) && $_REQUEST['bank'] != '' &&
		   isset($_REQUEST['account']) && $_REQUEST['account'] != '' &&
		   isset($_REQUEST['realname']) && $_REQUEST['realname'] != '' &&
		   isset($_REQUEST['amount']) && $_REQUEST['amount'] != '')
		{ 
			$token = $_REQUEST['token'];  
			$bank = $_REQUEST['bank']; 
		    $account = $_REQUEST['account']; 
			$realname = $_REQUEST['realname']; 
			$amount = $_REQUEST['amount']; 
			$idcard = '';  
			$verifycode = trim($_REQUEST['verifycode']);
			
			$amount = intval($amount);
			
			if ($needverifycode == "yes")
			{
				try
				{ 
					   $takecash_token = XN_MemCache::get("takecash_".$profileid);
					   XN_MemCache::put("","takecash_".$profileid,"120"); 
					   if ($takecash_token != $token)
					   {
		   					echo '{"code":201,"msg":"token验证失败!"}';
		   					die();
					   }
				}
				catch (XN_Exception $e) 
				{ 
					echo '{"code":201,"msg":"token过期!"}';
					die();
				     
				}   
				
				try
	            {
	                $memcache_verifycode = XN_MemCache::get("verifycode_" . $profileid);
	            }
	            catch (XN_Exception $e)
	            {
	                echo '{"code":201,"msg":"验证码已经过期！"}';
	                die();
	            }
	            
	            if ($memcache_verifycode != $verifycode)
		        {
			         echo '{"code":201,"msg":"验证码错误！"}';
	                 die();
		        }
			}
			
			
			
			$takecashs = get_takecashs($supplierid,$profileid);  
			
			$allowtakecash = $takecashs['allowtakecash']; 
			$takecashlimit = $takecashs['takecashlimit'];
			
			if ($amount > $allowtakecash )
			{
				echo '{"code":201,"msg":"提现金额有误！"}';
				die();
			}
			if ($amount < $takecashlimit )
			{
				echo '{"code":201,"msg":"提现金额必须大于或等于'.$takecashlimit.'元！"}';
				die();
			}
		   
		    $profile_info = get_supplier_profile_info($profileid,$supplierid);	
			
			
			
			if (count($profile_info) > 0 )	 
			{  
				$money = $profile_info['money'];
				$maxtakecash = $profile_info['maxtakecash'];
			}
			else
			{
				throw new XN_Exception("您根本没有余额可付!");  
				return false;  
			}
			$new_money = floatval($money) - $amount;
			$new_maxtakecash = floatval($maxtakecash) - $amount;
			
			$profile_info['money'] = $new_money;
			$profile_info['maxtakecash'] = $new_maxtakecash;

			update_supplier_profile_info($profile_info);
	
			$newcontent = XN_Content::create('supplier_takecashs','',false,7); 
			$newcontent->my->supplierid = $supplierid;  
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->token = $token;
			$newcontent->my->bank = $bank;
			$newcontent->my->account = $account;
			$newcontent->my->realname = $realname;
			$newcontent->my->amount = number_format($amount,2,".",""); 
			$newcontent->my->idcard = $idcard;
			$newcontent->my->supplier_takecashsstatus = "待处理";
			$newcontent->my->execute = '';
			$newcontent->my->executedatetime = '';
			$newcontent->my->newmoney = number_format($new_money,2,".",""); 
			$newcontent->my->money = number_format($money,2,".",""); 
			$newcontent->my->deleted = '0'; 
			$newcontent->my->tradestatus = '';
			$newcontent->save("supplier_takecashs,supplier_takecashs_".$supplierid.",supplier_takecashs_".$profileid);
			
			$newcontent = XN_Content::create('mall_billwaters','',false,7);					  
			$newcontent->my->deleted = '0';  
			$newcontent->my->supplierid = $supplierid;  
			$newcontent->my->profileid = $profileid; 
			$newcontent->my->billwatertype = 'takecash';
			$newcontent->my->sharedate = '-';  
			$newcontent->my->orderid = '';
			$newcontent->my->amount = '-'.number_format($amount,2,".",""); 
			$newcontent->my->money = number_format($new_money,2,".","");
			$newcontent->save('mall_billwaters,mall_billwaters_'.$profileid.',mall_billwaters_'.$supplierid); 
			
			echo '{"code":200,"msg":"ok"}';
			die();
		}	
		else
		{
			echo '{"code":201,"msg":"参数错误!"}';
			die(); 
		}	
			 
	}
	catch(XN_Exception $e)
	{
		$msg = $e->getMessage();	
		echo '{"code":201,"msg":"'.$msg.'"}';
		die(); 
	} 
	
}

 

 
$takecashs = get_takecashs($supplierid,$profileid); 

$allowtakecash = $takecashs['allowtakecash'];
$checktakecash = $takecashs['checktakecash'];

$supplier_takecashs = XN_Query::create ( 'YearContent' )->tag("supplier_takecashs_".$profileid)
					->filter ( 'type', 'eic', 'supplier_takecashs')  
			        ->filter (  'my.supplierid', '=', $supplierid) 
					->filter (  'my.profileid', '=', $profileid) 
					->filter (  'my.tradestatus', '=', '' )
			        ->filter (  'my.deleted', '=', '0' )
					->end(2)
					->execute ();
if(count($supplier_takecashs) > 1)
{
	messagebox('错误','提现异常，请与客服人员联系!');
	die();
}
else if(count($supplier_takecashs) > 0)
{
	$supplier_takecash_info = $supplier_takecashs[0];
	$amount = $supplier_takecash_info->my->amount;
	$published = $supplier_takecash_info->published;
	$msg =  '您在'.date("Y-m-d",strtotime($published)).'成功提现￥'.$amount.'<br>请等待财务人员完成转账操作！';
	$takecashs['msg'] = $msg;
	$takecashs['takecash'] = 'close';
}
else
{
	$supplier_takecashs = XN_Query::create ( 'YearContent' )->tag("supplier_takecashs_".$profileid)
						->filter ( 'type', 'eic', 'supplier_takecashs')  
				        ->filter (  'my.supplierid', '=', $supplierid) 
						->filter (  'my.profileid', '=', $profileid) 
						->filter (  'my.tradestatus', '=', 'trade' )
				        ->filter (  'my.deleted', '=', '0' )
						->order("published",XN_Order::DESC)
						->end(1)
						->execute ();
	if(count($supplier_takecashs) > 0)
	{
		$supplier_takecash_info = $supplier_takecashs[0];
		$amount = $supplier_takecash_info->my->amount;
		$published = $supplier_takecash_info->published; 
		if (in_array($supplierid,$unlimited_suppliers))
		{ 
			if ($allowtakecash == 0)
			{
				$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!';
				$takecashs['msg'] = $msg;
				$takecashs['takecash'] = 'close';
			}
			else if ($checktakecash == 'close')
			{
				$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!';
				$takecashs['msg'] = $msg;
				$takecashs['takecash'] = 'close';
			}
			else{
				$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!<br>现在您可以继续提现.';
				$takecashs['msg'] = $msg;
				$takecashs['takecash'] = 'open';
			}  
		}
		else
		{
			$newtixian = strtotime('+30 days',strtotime($published)); 
			$now = date_create("now");
			$diff = date_diff(date_create($published),$now);
		    if (intval($diff->format("%a")) < 30) 
			{
				$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!<br>您的下次允许提现日期为'.date("m月d日",$newtixian).'之后.';
				$takecashs['msg'] = $msg;
				$takecashs['takecash'] = 'close';
			} 
			else
			{ 
				if ($allowtakecash == 0)
				{
					$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!';
					$takecashs['msg'] = $msg;
					$takecashs['takecash'] = 'close';
				}
				else if ($checktakecash == 'close')
				{
					$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!';
					$takecashs['msg'] = $msg;
					$takecashs['takecash'] = 'close';
				}
				else{
					$msg = '您'.date("m月d日",strtotime($published)).'的提现已完成,请查收!<br>现在您可以继续提现.';
					$takecashs['msg'] = $msg;
					$takecashs['takecash'] = 'open';
				} 
			}
		}
        
	}
	else
	{
		$supplier_takecashs = XN_Query::create ( 'YearContent' )->tag("supplier_takecashs_".$profileid)
							->filter ( 'type', 'eic', 'supplier_takecashs')  
					        ->filter (  'my.supplierid', '=', $supplierid) 
							->filter (  'my.profileid', '=', $profileid) 
							->filter (  'my.tradestatus', '=', 'notrade' )
					        ->filter (  'my.deleted', '=', '0' )
							->order("published",XN_Order::DESC)
							->end(1)
							->execute ();
		if(count($supplier_takecashs) > 0)
		{
			$supplier_takecash_info = $supplier_takecashs[0];
			$amount = $supplier_takecash_info->my->amount;
			$published = $supplier_takecash_info->published; 
			$msg = '您'.date("m月d日",strtotime($published)).'的提现已经驳回,资金已经返回,请查收!<br>请检查您的资料是否正确！';
			$takecashs['msg'] = $msg;
			$takecashs['takecash'] = 'open';
			 
		}
		else
		{
			if ($allowtakecash == 0)
			{
				$takecashs['msg'] = '先去挣点收益吧，再来考虑提现的问题！';
				$takecashs['takecash'] = 'close';
			}
			else if ($checktakecash == 'close')
			{
				$takecashs['msg'] = '您还需要增加一些可提现的收益，才能提现！';
				$takecashs['takecash'] = 'close';
			}
			else{
				$takecashs['msg'] = '首次提现';
				$takecashs['takecash'] = 'open';
			} 
		}
		
	}
}

try
{ 
	  $takecash_token = XN_MemCache::get("takecash_".$profileid);
}
catch (XN_Exception $e) 
{ 
	 $takecash_token = guid();
     XN_MemCache::put($takecash_token,"takecash_".$profileid,"600");  
}  

$takecashs['token'] = $takecash_token;

require_once('Smarty_setup.php'); 

$smarty = new vtigerCRM_Smarty;
 
$islogined = false;
if ($_SESSION['u'] == $_SESSION['profileid'])
{
	$islogined = true;
} 
$smarty->assign("islogined",$islogined); 

$action = strtolower(basename(__FILE__,".php")); 

$recommend_info = checkrecommend();   
$smarty->assign("share_info",$recommend_info); 
$smarty->assign("supplier_info",get_supplier_info());

$supplier_frozenlists = XN_Query::create ( 'Content' )->tag("supplier_frozenlists_".$profileid)
	->filter ( 'type', 'eic', 'supplier_frozenlists')
	->filter (  'my.supplierid', '=', $supplierid)
	->filter (  'my.profileid', '=', $profileid)
	->filter (  'my.frozenliststatus', '=', 'Frozen' )
	->filter (  'my.deleted', '=', '0' )
	->end(1)
	->execute ();
if(count($supplier_frozenlists) > 0)
{
	$takecashs['msg'] = '账号异常冻结，禁止提现！请联系客服!';
	$takecashs['takecash'] = 'close';
}
$profile_info = get_supplier_profile_info();  

if (count($profile_info) > 0)
{  
	if ($profile_info['mobile'] == '') 	 
	{
		$takecashs['msg'] = '请先在个人资料中完善手机信息！';
		$takecashs['takecash'] = 'close';
	}
}
$smarty->assign("profile_info",$profile_info);
$smarty->assign("takecashs",$takecashs);  
 
$smarty->assign("needverifycode",$needverifycode); 
 
$smarty->display($action.'.tpl'); 

die();

function get_takecashs($supplierid,$profileid)
{ 
	try{   
			$supplier_settings = XN_Query::create ( 'MainContent' )->tag("supplier_settings")
								->filter ( 'type', 'eic', 'supplier_settings')
						        ->filter (  'my.supplierid', '=', $supplierid)
						        ->filter (  'my.deleted', '=', '0' )
								->end(1)
								->execute ();
			$takecashs = array();
			if (count($supplier_settings) > 0 )
			{
				$supplier_setting_info = $supplier_settings[0];
				$takecashs['allowtakecash'] = $supplier_setting_info->my->allowtakecash;
				$takecashlimit = $supplier_setting_info->my->takecashlimit;
				$takecashlimit = intval($takecashlimit);
				$takecashs['takecashlimit'] = $takecashlimit;
				$takecashitem = $supplier_setting_info->my->takecashitem;
				$takecashitem = (array)$takecashitem;
				$takecashs['takecashitem'] = $takecashitem;
			    //  '提成金'  => '0','推广金' => '1','分享金' => '2'
			}
			else
			{
				messagebox('错误','没有找到店铺的运营设置！');
				die();
			}


		   $billwaters_query = XN_Query::create('MainYearContent_Count')->tag('mall_billwaters_'.$profileid)
		   		    ->filter('type','eic','mall_billwaters')
		   		    ->filter('my.deleted','=','0') 
		            ->filter('my.supplierid','=',$supplierid) 
		  		    ->filter('my.profileid','=',$profileid) 	
				    ->filter('my.billwatertype','in',array("share","popularize","commission")) 					
		  		    ->rollup('my.amount')
		            ->group('my.billwatertype')
		   		->begin(0)
		   		->end(-1)
		   		->execute();
		  $billwater = array('share'=>0,'popularize'=>0,'commission'=>0);
		  foreach($billwaters_query as $billwater_info)
		  {
		      $amount = $billwater_info->my->amount;
		      $billwatertype = $billwater_info->my->billwatertype;
		      $billwater[$billwatertype] = floatval($amount);
		  }
  
		  $profileinfo = get_supplier_profile_info(); 
		  $accumulatedmoney = $profileinfo['accumulatedmoney'];
		  $money = $profileinfo['money'];
		  $takecashs['money'] = number_format($money,2,".","");	
		  $takecashs['accumulatedmoney'] = number_format($accumulatedmoney,2,".","");
 
 
		  $share = $billwater['share'];
		  $popularize = $billwater['popularize']; 
		  $commission = $billwater['commission']; 
  
		  $takecashs['share'] = number_format($share,2,".","");
		  $takecashs['popularize'] = number_format($popularize,2,".",""); 
		  $takecashs['commission'] = number_format($commission,2,".",""); 
		  $total = $popularize + $share + $commission;
		  $takecashs['total'] = number_format($total,2,".",""); 
		  $allowtakecash = intval($profileinfo['maxtakecash']);



			if (in_array('0',$takecashitem))
			{
				$takecashs['allow_commission'] = '1';
			}
			else
			{
				$takecashs['allow_commission'] = '0';
			}
			if (in_array('1',$takecashitem))
			{
				$takecashs['allow_popularize'] = '1';
			}
			else
			{
				$takecashs['allow_popularize'] = '0';
			}
			if (in_array('2',$takecashitem))
			{
				$takecashs['allow_share'] = '1';
			}
			else
			{
				$takecashs['allow_share'] = '0';
			}


		$supplier_takecashs = XN_Query::create('MainYearContent_Count')->tag('supplier_takecashs_'.$profileid)
	   		    ->filter('type','eic','supplier_takecashs')
	   		    ->filter('my.deleted','=','0') 
	            ->filter('my.supplierid','=',$supplierid) 
	  		    ->filter('my.profileid','=',$profileid) 
				->filter('my.tradestatus', '=', 'trade' )	  					
	  		    ->rollup('my.amount') 
		   		->begin(0)
		   		->end(-1)
		   		->execute();
		  if (count($supplier_takecashs) > 0)
		  {
		  		$supplier_takecash_info = $supplier_takecashs[0];
				$amount = $supplier_takecash_info->my->amount;
				$historytakecash = intval($amount);
		  }
		  else
		  {
		  	     $historytakecash = 0;
		  }
		  //$allowtakecash = 300;
		  $allowtakecash = $money;
		  //$historytakecash = 200;
		  $takecashs['historytakecash'] = number_format($historytakecash,2,".",""); 
		  
		  if (round($allowtakecash,2) == 0) 
		  {
			   $takecashs['allowtakecash'] = intval($allowtakecash);  
			   $takecashs['checktakecash'] = 'close';
		  }
		  else if (round($allowtakecash,2) > round($money,2))  
		  {
		  	   $takecashs['allowtakecash'] = intval($money); 
			   if (round($money,2) >= round($takecashlimit,2)) 
			   {
			   	     $takecashs['checktakecash'] = 'open';
			   } 
			   else
			   {
			   		 $takecashs['checktakecash'] = 'close';
			   }
		  }
		  else
		  {
		  	   $takecashs['allowtakecash'] = intval($allowtakecash); 
			   if (round($allowtakecash,2) >= round($takecashlimit,2)) 
			   {
			   	     $takecashs['checktakecash'] = 'open';
			   } 
			   else
			   {
			   		 $takecashs['checktakecash'] = 'close';
			   }
		  } 
		  
		  if ($takecashs['checktakecash'] == 'open')
		  {
	  			$supplier_profilebankcards = XN_Query::create('MainContent')->tag('supplier_profilebankcards')
	  						  ->filter('type', 'eic', 'supplier_profilebankcards')
	  						  ->filter('my.supplierid', '=', $supplierid)
	  						  ->filter('my.profileid', '=', $profileid) 
	  						  ->filter('my.selected', '=', '1')  
							  ->end(1)
	  						  ->execute();
	  			if (count($supplier_profilebankcards) > 0)
	  			{
	  				$supplier_profilebankcards_info = $supplier_profilebankcards[0];
	  				$takecashs['bank'] = $supplier_profilebankcards_info->my->bank; 
					$takecashs['account'] = $supplier_profilebankcards_info->my->account; 
					$takecashs['realname'] = $supplier_profilebankcards_info->my->realname;  
	  			}
		  } 
	   	  return $takecashs;  
	}
	catch(XN_Exception $e)
	{
		$msg = $e->getMessage();	
		messagebox('错误',$msg);
		die(); 
	} 
} 

?>